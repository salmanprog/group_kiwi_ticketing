<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected
        $__admin_dir_path  = 'portal',
        $__is_paginate     = true, // to control pagination object
        $__is_collection   = true, // for item detail response
        $__is_error        = false, // for item detail response
        $__collection      = true,// to control general response
        $__apiResourcePath = '\App\Http\Resources\\', // set api resource path
        $__apiResource;

    protected function __validateRequestParams($input_params, $param_rules, $custom_message = [])
    {
        $this->__params = $input_params;
        $validator = \Validator::make($input_params, $param_rules, $custom_message);

        $errors = [];

        if($validator->fails()){
            foreach ($param_rules as $field => $value){
                $message = $validator->errors()->first($field);
                if(!empty($message)) {
                    $errors[$field] = $message;
                }
            }
            $this->__is_error = true;

            return $this->__sendError( __('app.validation_error'), $errors);
        }
    }

    /**
     * @param $data
     * @param $response_code
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function __sendResponse($data, $response_code, $message)
    {
        $response_data = [];
        $paginate    = $this->_paginate($data);
        $apiResource = $this->__apiResourcePath . $this->__apiResource;

        if( !empty($data) ){
            if( $this->__collection ){
                if( !$this->__is_collection ){
                    $response_data = new $apiResource($data);
                }else{
                    $response_data = $apiResource::collection($data);
                }
            }else{
                $response_data = $this->__is_paginate ? $data->items() : $data;
            }
        }

        $response = [
            'code'       => $response_code,
            'data'       => $response_data,
            'message'    => $message,
            'pagination' => $paginate,
        ];

        return response()->json($response, $response_code);
    }

    /**
     * @param $data
     * @return array
     */
    private function _paginate($obj_model)
    {
        if(!$this->__is_paginate){
            $response['links'] = [
                "first" => null,
                "last" => null,
                "prev" =>  null,
                "next" =>  null
            ];

            $response['meta'] = [
                "current_page" =>  1,
                "from" =>  1,
                "last_page" =>  0,
                "to" =>  0,
                "total" =>  is_object($obj_model) ? 1 : (!empty($obj_model) ? count($obj_model) : 0)
            ];

            return $response;
        }

        $obj_model_arr = $obj_model->toArray();
        $response['links'] = [
            "first" => !empty($obj_model_arr['first_page_url']) ? $obj_model_arr['first_page_url'] : null,
            "last"  => !empty($obj_model_arr['next_page_url']) ? $obj_model_arr['next_page_url'] : null,
            "prev"  => !empty($obj_model_arr['prev_page_url']) ? $obj_model_arr['prev_page_url'] : null,
            "next"  => !empty($obj_model_arr['next_page_url']) ? $obj_model_arr['next_page_url'] : null
        ];

        $response['meta'] = [
            "current_page" =>  !empty($obj_model_arr['current_page']) ? $obj_model_arr['current_page'] : null,
            "from"         =>  !empty($obj_model_arr['from']) ? $obj_model_arr['from'] : null,
            "last_page"    =>  !empty($obj_model_arr['current_page']) ? ($obj_model_arr['current_page'] + 1) : null,
            "to"           =>  !empty($obj_model_arr['to']) ? $obj_model_arr['to'] : null,
            "total"         => !empty($obj_model_arr['total']) ? $obj_model_arr['total'] : null
        ];

        return $response;

    }

    /**
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function __sendError($error, $errorMessages = [], $code = 400)
    {
        $response = [
            'code'    => $code,
            'message' => $error,
            'data'    => $errorMessages
        ];

        return response()->json($response, $code);
    }

    protected function __cbAdminView($view_name,$data = [])
    {
        return view($this->__admin_dir_path . '.'.$view_name,$data);
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $params
     * @param string $user_token
     * @return @json
     */
    protected function __internalCall($url,$method,$params = [], $user_token = '')
    {
        $server  = $_SERVER;
        $server['HTTP_HOST'] = \URL::to('/');
        $request = Request::create(\URL::to($url), $method, $params,[],[],$server);
        $request->headers->set('token', encryptData(config('constants.CLIENT_ID')));
        $request->headers->set('user-token', encryptData($user_token));
        $response     = app()->handle($request);
        $responseBody = $response->getContent();
        return json_decode($responseBody);
    }

    protected function __httpApiPostRequest($uri,$params=[],$attachments=[],$headers=[])
    {
        $api_url = env('API_URL') . $uri;
        $headers['token'] = encryptData(config('constants.CLIENT_ID'));
        if( !empty(session('user')) ){
            $headers['user-token'] = encryptData(session('user')->api_token);
        }
        $response = Http::withHeaders($headers);
        if( count($attachments) ){
            foreach( $attachments as $attachment ){
                $response->attach($attachment['key'],$attachment['file'],$attachment['name']);
            }
        }
        $response = $response->post($api_url,$params);
        $response = json_decode($response->getBody()->getContents());
        if( !\Request::ajax() && $response->code == 401 ){
            Auth::logout();
            session()->flush();
            return redirect()->route('login')->with('error',$response->data->auth);
        }
        return $response;
    }

    protected function __httpApiDeleteRequest($uri,$params=[],$headers=[])
    {
        $api_url = env('API_URL') . $uri;
        $headers['token'] = encryptData(config('constants.CLIENT_ID'));
        if( !empty(session('user')) ){
            $headers['user-token'] = encryptData(session('user')->api_token);
        }
        $response = Http::withHeaders($headers);
        $response = $response->delete($api_url,$params);
        $response = json_decode($response->getBody()->getContents());
        if( !\Request::ajax() && $response->code == 401 ){
            Auth::logout();
            session()->flush();
            return redirect()->route('login')->with('error',$response->data->auth);
        }
        return $response;
    }

    protected function __httpApiGetRequest($uri,$params=[],$headers=[])
    {
        $api_url = env('API_URL') . $uri;
        $headers['token'] = encryptData(config('constants.CLIENT_ID'));
        if( !empty(session('user')) ){
            $headers['user-token'] = encryptData(session('user')->api_token);
        }
        $response = Http::withHeaders($headers)->get($api_url,$params);
        $response = json_decode($response->getBody()->getContents());
        if( !\Request::ajax() && $response->code == 401 ){
            Auth::logout();
            session()->flush();
            return redirect()->route('login')->with('error',$response->data->auth);
        }
        return $response;
    }
}
