<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\Payment\Payment;
use App\Models\UserConnectAccount;
use App\Models\UserExternalAccount;
use URL;

class PayoutController extends Controller
{
    protected $__request;
    private $_gateway;

    public function __construct(Request $request)
    {
        $this->__request      = $request;
        $this->__apiResource  = 'ConnectAccount';
        $this->_gateway       = Payment::init();
    }

    public function savePersonalInfo()
    {
        $request = $this->__request;
        $param_rule['first_name']  = ['min:2','max:50','regex:/^([A-Za-z0-9\s])+$/'];
        $param_rule['last_name']   = ['min:2','max:50','regex:/^([A-Za-z0-9\s])+$/'];
        $param_rule['dob']         = 'date';
        $param_rule['ssn']         = 'min:9|max:9';
        $param_rule['id_front']    = 'image|max:5120';
        $param_rule['id_back']     = 'image|max:5120';
        $param_rule['city']        = 'max:100';
        $param_rule['state']       = 'max:100';
        $param_rule['street']      = 'max:100';
        $param_rule['postal_code'] = 'max:100';
        $param_rule['phone']       = 'max:100';

        $response = $this->__validateRequestParams($request->all(),$param_rule);
        if( $this->__is_error )
            return $response;

        if( !empty($request['dob']) ){
            $dob                   = explode('-',$request['dob']);
            $request['day']        = $dob[2];
            $request['month']      = $dob[1];
            $request['year']       = $dob[0];
        }
        if( !empty($request['ssn']) ){
            $last4ssn = substr($request['ssn'], -4);
            $request['ssn_last_4'] = $last4ssn;
        }

        $request['email'] = $request['user']->email;

        if( !empty($request['id_front']) && !empty($request['id_back']) ){
            $frontCardResponse = $this->_gateway->uploadFile($request['id_front']->path());
            if( $frontCardResponse['code'] != 200 ){
                return $this->__sendError('Gatewat Error',['message' => $frontCardResponse['message'] ],400);
            }
            $backCardResponse = $this->_gateway->uploadFile($request['id_back']->path());
            if( $backCardResponse['code'] != 200 ){
                return $this->__sendError('Gatewat Error',['message' => $backCardResponse['message'] ],400);
            }
            $request['file_upload_front_id'] = $frontCardResponse['data']['id'];
            $request['file_upload_back_id']  = $backCardResponse['data']['id'];
        }

        $updateConnectAccount = $this->_gateway->updateConnectAccount($request['user']->gateway_connect_id,$request->all());
        if( $updateConnectAccount['code'] != 200 ){
            return $this->__sendError('Gateway Error',['message' => $updateConnectAccount['message']],400);
        }
        $request['due_fields'] = $updateConnectAccount['data']['connect_account']->requirements->eventually_due;
        $record = UserConnectAccount::saveConnectAccount($request->all());

        $this->__is_paginate   = false;
        $this->__is_collection = false;

        return $this->__sendResponse($record,200,'Account has been updated successfully');
    }

    public function checkAccountStatus()
    {
        $request = $this->__request;

        $account = $this->_gateway->getConnectAccount($request['user']->gateway_connect_id);
        $data['fields_due'] = $account['data']->requirements->currently_due;
        $data['errors']     = $account['data']->requirements->errors;

        //updated connect account status
        if( !empty($account['data']->requirements->currently_due) ){
            $update_status['due_fields'] = json_encode($account['data']->requirements->currently_due);
            $update_status['status']     = 'pending';
        } else {
            $update_status['due_fields'] = null;
            $update_status['status']     = 'verified';
        }
        UserConnectAccount::where('user_id',$request['user']->id)->update($update_status);

        $personal_info = UserConnectAccount::getConnectAccount($request['user']->id);
        if( !empty($personal_info->id) ){
            $personal_info->id_front = !empty($personal_info->id_front) ? URL::to('gateway/'.$personal_info->id_front) : null;
            $personal_info->id_back  = !empty($personal_info->id_back) ? URL::to('gateway/'.$personal_info->id_back) : null;
        }

        $data['personal_info'] = $personal_info;
        $data['bank_info']     = UserExternalAccount::getExternalAccountByUserID($request['user']->id);

        $this->__is_paginate = false;
        $this->__collection  = false;

        return $this->__sendResponse($data,200,'Account has been retrieved successfully');
    }

    public function getPersonalInfo()
    {
        $request = $this->__request;
        $account = $this->_gateway->getConnectAccount($request['user']->gateway_connect_id);

        $data['fields_due'] = $account['data']->requirements->currently_due;
        $data['errors']     = $account['data']->requirements->errors;

        //updated connect account status
        if( !empty($account['data']->requirements->currently_due) ){
            $update_status['due_fields'] = json_encode($account['data']->requirements->currently_due);
        } else {
            $update_status['due_fields'] = null;
            $update_status['status']     = 'verified';
        }
        UserConnectAccount::where('user_id',$request['user']->id)->update($update_status);

        $record  =  UserConnectAccount::getConnectAccount($request['user']->id);

        $this->__is_paginate   = false;
        $this->__is_collection = false;

        return $this->__sendResponse($record,200,'Account retrieved successfully');
    }

    public function addExternalAccount()
    {
        $request = $this->__request;

        $currentExternalAccount  = UserExternalAccount::getExternalAccountByUserID($request['user']->id);
        //update new external account
        $externalAccount = $this->_gateway->createExternalAccount($request['user']->gateway_connect_id,$request['bank_token'],true);
        if( $externalAccount['code'] != 200 ){
            return $this->__sendError('Gateway Error',['message' => $externalAccount['message']],400);
        }
        //delete current external account
        if( isset($currentExternalAccount->id) ){
            $this->_gateway->deleteExternalAccount($request['user']->gateway_connect_id,$currentExternalAccount->gateway_external_account_id);
        }
        $record = UserExternalAccount::createExtAccount($request->all(),$externalAccount['data']);

        $this->__is_paginate = false;
        $this->__collection  = false;

        return $this->__sendResponse($record,200,'Bank account added successfully');
    }

    public function getExternalAccount()
    {
        $request = $this->__request;
        $record  = UserExternalAccount::getExternalAccountByUserID($request['user']->id);

        $this->__is_paginate = false;
        $this->__collection  = false;

        return $this->__sendResponse($record,200,'Bank account added successfully');
    }

    public function connectOnBoard()
    {
        $request = $this->__request;
        $gateway_response = $this->_gateway->createConnectOnBoardLink($request['user']->gateway_connect_id);
        if( $gateway_response['code'] != 200 ){
            return $this->__sendError('Gateway Error',['message' => $gateway_response['message']],400);
        }
        $data['account_link'] = $gateway_response['data']['account_link'];

        $this->__is_paginate = false;
        $this->__collection  = false;

        return $this->__sendResponse($data,200,'Account link has been generated successfully');
    }
}
