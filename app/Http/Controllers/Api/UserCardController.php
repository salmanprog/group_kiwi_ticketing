<?php

namespace App\Http\Controllers\Api;

use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\RestController;
use App\Libraries\Payment\Payment;
use App\Models\UserCard;

class UserCardController extends RestController
{
    private $_gateway;

    public function __construct(Request $request)
    {
        parent::__construct('UserCard');
        $this->__request     = $request;
        $this->__apiResource = 'UserCard';
        $this->_gateway = Payment::init();
    }

    /**
     * This function is used for validate restfull request
     * @param $action
     * @param string $slug
     * @return array
     */
    public function validation($action,$slug=0)
    {
        $validator = [];
        switch ($action){
            case 'POST':
                $validator = Validator::make($this->__request->all(), [
                    'card_token' => 'required',
                ]);
                break;
            case 'PUT':
                $validator = Validator::make($this->__request->all(), [
                    'is_default' => 'required|in:1,0',
                ]);
                break;
        }
        return $validator;
    }

    /**
     * @param $request
     */
    public function beforeIndexLoadModel($request)
    {

    }

    /**
     * @param $request
     * @param $record
     */
    public function afterIndexLoadModel($request,$record)
    {

    }

    /**
     * @param $request
     */
    public function beforeStoreLoadModel($request)
    {
        $cardResponse = $this->_gateway->createCustomerCard(
            $request['user']->gateway_customer_id,
            $request['card_token']
        );
        if( $cardResponse['code'] != 200 ){
            $this->__is_error = true;
            return $this->__sendError('Gateway Error',['message' => $cardResponse['message']],400);
        }
        $countRecord = UserCard::countRecord($request['user']->id);
        $request['card_id'] = $cardResponse['data']['card_id'];
        $request['last_4_digit'] = $cardResponse['gateway_response']->last4;
        $request['brand'] = $cardResponse['gateway_response']->brand;
    }

    /**
     * @param $request
     * @param $record
     */
    public function afterStoreLoadModel($request,$record)
    {

    }

    /**
     * @param $request
     * @param $slug
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
     * @param $request
     * @param $slug
     */
    public function beforeUpdateLoadModel($request,$slug)
    {
        $record = UserCard::getCardBySlug($slug);
        $cardResponse = $this->_gateway->makeDefaultCard(
            $request['user']->gateway_customer_id,
            $record->card_id
        );
        if( $cardResponse['code'] != 200 ){
            $this->__is_error = true;
            return $this->__sendError('Gateway Error',['message' => $cardResponse['message']],400);
        }
    }

    /**
     * @param $request
     * @param $record
     */
    public function afterUpdateLoadModel($request,$record)
    {

    }

    /**
     * @param $request
     * @param $slug
     */
    public function beforeDestroyLoadModel($request,$slug)
    {
        $record = UserCard::getCardBySlug($slug);
        if( $record->is_default == 1 ){
            $this->__is_error = true;
            return $this->__sendError('Validation Error',['message' => 'You can not delete the default card'],400);
        }
        //delete stripe card
        $cardResponse = $this->_gateway->deleteCustomerCard(
                        $request['user']->gateway_customer_id,
                        $record->card_id
                    );
        if( $cardResponse['code'] != 200 ){
            $this->__is_error = true;
            return $this->__sendError('Gateway Error',['message' => $cardResponse['message']],400);
        }
    }

    /**
     * @param $request
     * @param $slug
     */
    public function afterDestroyLoadModel($request,$slug)
    {

    }
}
