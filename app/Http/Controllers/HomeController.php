<?php
namespace App\Http\Controllers;

use App\Models\ContentManagement;
use App\Libraries\Payment\Payment;

class HomeController
{
    private $_gateway;

    public function __construct()
    {
        $this->_gateway = Payment::init();
    }

    public function braintreeDropIn($customer_id)
    {
        $clientToken = $this->_gateway->clientToken($customer_id);
        if( $clientToken['code'] != 200){
            return $clientToken['message'];
        }
        return view('braintree-dropin',['client_token' =>  $clientToken['data']['client_token'] ]);
    }

    public function getContent($slug)
    {
        $data['content'] = ContentManagement::getBySlug($slug);
        if( !isset($data['content']->id) )
            return redirect('/');

        return view('content', $data);
    }

    public function customTableSort()
    {
        return view('custom-table-sort');
    }

    public function deepLinking()
    {
        return view('deep-link');
    }
}
