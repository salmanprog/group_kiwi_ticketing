<?php

namespace App\Http\Controllers\Api;

use App\Libraries\Sms\Sms;
use App\Models\User;
use App\Models\Estimate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\RestController;

class UserEstimateController extends RestController
{
    private $_gateway;

    public function __construct(Request $request)
    {
        $this->middleware('custom_auth:api')->only(['index','show','update']);
        parent::__construct('Estimate');
        $this->__request     = $request;
        $this->__apiResource = 'Estimate';
    }

    /**
     * This function is used to validate restfull routes
     * @param $action
     * @param string $slug
     * @return array
     */
    public function validation($action,$slug=NULL)
    {
        $validator = [];
        $custom_messages = [
            'password.regex' => __('app.password_regex')
        ];
        switch ($action){
            case 'POST':
                $validator = Validator::make($this->__request->all(), [],$custom_messages);
                break;
            case 'PUT':
                $custom_messages = [
                    'slug.exists' => __('app.invalid_user')
                ];
                $this->__request->merge(['slug' => $slug]);
                $validator = Validator::make($this->__request->all(), [],$custom_messages);
                break;
        }
        return $validator;
    }

    /**
     * GET Request Hook
     * This function is run before a model load
     * @param $request
     */
    public function beforeIndexLoadModel($request)
    {
        $this->__apiResource = 'Estimate';
    }

    /**
     * @param $request
     * @param $record
     */
    public function afterIndexLoadModel($request,$record)
    {

    }

    /**
     * POST Request Hook
     * This function is run before a model load
     * @param $request
     */
    public function beforeStoreLoadModel($request)
    {
        
    }

    /**
     * @param $request
     * @param $record
     */
    public function afterStoreLoadModel($request,$record)
    {

    }

    /**
     * Get Single Record hook
     * This function is run before a model load
     * @param {object} $request
     * @param {string} $slug
     */
    public function beforeShowLoadModel($request,$slug)
    {
        
    }

    /**
     * @param $request
     * @param $record
     */
    public function afterShowLoadModel($request,$record)
    {

    }

    /**
     * Update Request Hook
     * This function is run before a model load
     * @param {object} $request
     * @param {string} $slug
     */
    public function beforeUpdateLoadModel($request,$slug)
    {
        
    }

    /**
     * @param $request
     * @param $record
     */
    public function afterUpdateLoadModel($request,$record)
    {

    }

    /**
     * Delete Request Hook
     * This function is run before a model load
     * @param {object} $request
     * @param {string} $slug
     */
    public function beforeDestroyLoadModel($request,$slug)
    {

    }

    /**
     * @param $request
     * @param $slug
     */
    public function afterDestroyLoadModel($request,$slug)
    {

    }

}
