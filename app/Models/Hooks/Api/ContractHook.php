<?php

namespace App\Models\Hooks\Api;

use App\Helpers\CustomHelper;
use App\Models\UserApiToken;
use App\Models\Estimate;
use App\Models\Contract;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ContractHook
{
    private $_model,
            $except_update_params = [
                'auth_code',
                'slug',
                'terms_and_condition',
                'contract_number',
                'client_id',
                'company_id',
                'organization_id',
                'event_date',
                'total',
                'terms',
                'notes',
                'is_accept',
                'status'
            ];

    public function __construct($model)
    {
        $this->_model = $model;
    }

    /*
   | ----------------------------------------------------------------------
   | Hook for manipulate query of index result
   | ----------------------------------------------------------------------
   | @query   = current sql query
   | @request = laravel http request class
   |
   */
    public function hook_query_index(&$query,$request, $slug=NULL) {
        //Your code here
        $query->with([
                        'organization',
                        'company',
                        'client',
                        'estimates.items.itemTaxes',   // nested: items -> itemTaxes
                        'estimates.taxes',             // estimate -> taxes
                        'estimates.discounts',          // estimate -> discounts
                        'estimates.installments',      // estimate -> installments
                        'invoices.installmentPlan.payments',
                        'invoices.creditNotes',
                        'items',
                        'taxes'
                    ])->where('client_id',$request['user']->id)->where('is_accept','accepted');
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate data input before add data is execute
    | ----------------------------------------------------------------------
    | @arr
    |
    */
    public function hook_before_add($request,&$postdata)
    {
       
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after add public static function called
    | ----------------------------------------------------------------------
    | @record
    |
    */
    public function hook_after_add($request,$record)
    {

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate data input before update data is execute
    | ----------------------------------------------------------------------
    | @request  = http request object
    | @postdata = input post data
    | @id       = current id
    |
    */
    public function hook_before_edit($request, $slug, &$postData)
    {
        foreach( $postData as $key => $value ){
            if( in_array($key,$this->except_update_params) )
                unset($postData[$key]);
        }
        $params = $request->all();
        if( !empty($postData['image_url']) ){
            $postData['image_url'] = CustomHelper::uploadMedia('users',$postData['image_url']);
        }
        
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after edit public static function called
    | ----------------------------------------------------------------------
    | @request  = Http request object
    | @$slug    = $slug
    |
    */
    public function hook_after_edit($request, $slug) {
        //Your code here
        $params = $request->all();
        $estimate = Estimate::with('items.itemTaxes')->with('taxes')->with('discounts')->with('installments')->where('slug', $slug)->firstOrFail();
        $subtotal = $estimate->items->sum(fn($item) => $item->total_price);
        $taxTotal = $estimate->items->sum(fn($item) => 
            $item->itemTaxes->sum(fn($tax) => round($item->total_price * ($tax->percentage / 100), 2))
        );
        $discountPercent = $estimate->discounts->sum(fn($discount) => $discount->value);
        $total = ($subtotal + $taxTotal) * (1 - ($discountPercent / 100));
        $discountAmount = ($subtotal + $taxTotal) * ($discountPercent / 100);

        if($params['status'] == 'approved'){
        
        $estimate->update(['subtotal' => $subtotal,'total' => $total,'discount_total' => $discountAmount,'tax_total' => $taxTotal,'status' => 'approved']);
               
        $contract = Contract::find($estimate->contract_id);

        if (!$contract) {
            $contractSlug = Contract::generateUniqueSlug();
            $contract = new Contract([
                'slug' => $contractSlug,
                'auth_code' => $request['user']->auth_code,
                'contract_number' => $contractSlug,
                'client_id' => $estimate->client_id,
                'company_id' => $estimate->company_id,
                'organization_id' => $estimate->organization_id,
                'status' => 'active',
                'event_date' => $estimate->event_date,
                'total' => $estimate->total,
                'terms' => $estimate->terms,
                'is_accept' => 1,
                'notes' => $estimate->note,
                'terms_and_condition' => $estimate->terms_and_condition,
            ]);
        } else {
            $contract->total += $estimate->total;
        }

        $contract->save();

        $estimate->update(['contract_id' => $contract->id]);

        $invoice = Invoice::generateInvoice($request, $estimate, $contract,$request['user']->auth_code); 
        }elseif($params['status'] == 'rejected'){
            $estimate->update(['subtotal' => $subtotal,'total' => $total,'discount_total' => $discountAmount,'tax_total' => $taxTotal,'status' => 'rejected']);

            \App\Models\ActivityLog::create([
                'module' => 'estimate',
                'module_id' => $estimate->id,
                'description' => 'Estimate Rejected by ' . $request['user']->name,
                'user_id' => $request['user']->id,
                'old_data' => json_encode($estimate->toArray()),
                'new_data' => json_encode($estimate->toArray()),
            ]);
        }
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command before delete public static function called
    | ----------------------------------------------------------------------
    | @request  = Http request object
    | @$id      = record id = int / array
    |
    */
    public function hook_before_delete($request, $slug) {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after delete public static function called
    | ----------------------------------------------------------------------
    | @$request       = Http request object
    | @records        = deleted records
    |
    */
    public function hook_after_delete($request,$records) {
        //Your code here

    }

    public function create_cache_signature($request)
    {
        $cache_params = $request->isMethod('post') ? [] : $request->except(['user','api_token']);
        return 'users_api_' . md5(implode('',$cache_params));
    }
}
