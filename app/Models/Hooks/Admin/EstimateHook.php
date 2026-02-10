<?php

namespace App\Models\Hooks\Admin;

use App\Models\{CompanyUser, Estimate, EstimateItem, Client, Contract, Invoice,EstimateInstallment};
use Auth;
use DB;
use Illuminate\Foundation\Console\ObserverMakeCommand;
use App\Observers\EstimateObserver;
use App\Http\Controllers\Portal\EstimateController;
use App\Models\User;
use Crypt;

class EstimateHook
{
    private $_model;

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
    public function hook_query_index(&$query, $request, $slug = NULL)
    {
        //Your code here
        $getCompany = CompanyUser::getCompany(Auth::user()->id);
        $query->select('user_estimate.*', 'organizations.name as organization_name','organizations.address_one as organization_address_one','organizations.email as organization_email','organizations.phone as organization_phone','organizations.deleted_at as organization_deleted_at');
        $query->join('organizations', 'user_estimate.organization_id', '=', 'organizations.id');

        if (Auth::user()->user_type == 'client') {
            $query->where('user_estimate.client_id', Auth::user()->id);
            $query->where('user_estimate.status', '!=', 'draft');
        } else {
           // $query->where('user_estimate.company_id', $getCompany->id);
            $query->where('user_estimate.auth_code', Auth::user()->auth_code);
        }


        if (!empty($request['keyword'])) {
            $keyword = $request['keyword'];
            $query->where(function ($where) use ($keyword) {
                $where->orWhere('organizations.name', 'like', "$keyword%");
                $where->orWhere('user_estimate.slug', 'like', "$keyword%");
                $where->orWhere('user_estimate.issue_date', 'like', "$keyword%");
            });
        }

        if (!empty($request['start_date']) && !empty($request['end_date'])) {
            $query->whereBetween('user_estimate.created_at', [
                $request['start_date'] . ' 00:00:00',
                $request['end_date'] . ' 23:59:59'
            ]);
        }

        if (!empty($request['status']) && $request['status'] != 'all') {
            $status = ($request['status'] == 'new') ? 'sent' : $request['status'];
            $query->where('user_estimate.status', $status);
        }
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate data input before add data is execute
    | ----------------------------------------------------------------------
    | @arr
    |
    */
    public function hook_before_add($request, &$postdata)
    {
        if (!empty($postdata['contract_slug'])) {
            $postdata['contract_id'] = Contract::where('slug', $postdata['contract_slug'])->value('id');
        }
        $getCompany = CompanyUser::getCompany(Auth::user()->id);
        $slug = $this->_model::generateUniqueSlug();
        $postdata['company_id'] = $getCompany->id;
        $postdata['slug'] = $slug;
        $postdata['auth_code'] = Auth::user()->auth_code;
        $postdata['estimate_number'] = $slug;
        $postdata['created_by'] = Auth::user()->id;
        $postdata['issue_date'] = $postdata['estimate_date'];
        $organization_id = Client::where('client_id', $postdata['client_id'])->where('auth_code', Auth::user()->auth_code)->value('organization_id');
        $postdata['organization_id'] = $organization_id;
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after add public static function called
    | ----------------------------------------------------------------------
    | @record
    |
    */
    public function hook_after_add($request, $record)
    {
           $newData = [
            'estimate' => $record->fresh()->toArray(),
            'items' => EstimateItem::where('user_estimate_id', $record->id)->get()->toArray(),
            'taxes' => DB::table('user_estimate_taxes')->where('estimate_id', $record->id)->get()->toArray(),
            'discounts' => DB::table('user_estimate_discounts')->where('estimate_id', $record->id)->get()->toArray(),
        ];
    

        $record->logActivity(
           'This estimate was created by ' . Auth::user()->name,
            [],
            $newData
        );
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
        $estimate = Estimate::where('slug', $slug)->first();
        $postData['issue_date'] = $postData['estimate_date'];
        $postData['valid_until'] = $postData['expiration_date'];
        $postData['status'] = ($estimate->status == 'draft') ? "draft" : 'revised';
        EstimateItem::where('user_estimate_id', $estimate->id)->delete();
        DB::table('user_estimate_taxes')->where('estimate_id', $estimate->id)->delete();
        DB::table('user_estimate_discounts')->where('estimate_id', $estimate->id)->delete();

          if(!empty($request->installments)){
            $postData['is_installment'] = '1';
          }else{
            $postData['is_installment'] = '0';
          }

        if (!empty($request->products)) {
            foreach ($request->products as $product) {
                // dd($product);
                if (!empty($product['name']) && !empty($product['quantity']) && !empty($product['price'])) {
                    EstimateItem::create([
                        'user_estimate_id' => $estimate->id,
                        'name' => $product['name'],
                        'quantity' => $product['quantity'],
                        'price' => $product['price'],
                        'product_price'=> $product['product_total_price'],
                        'tax'=> $product['tax'],
                        'gratuity'=> $product['gratuity'],
                        'total_price' => ($product['product_total_price'] * $product['quantity']),
                        'unit' => 'each',
                    ]);
                }
            }
        }

        if(!empty($request->installments)){
            EstimateInstallment::where('estimate_id', $estimate->id)->forceDelete();
            foreach ($request->installments as $installment) {
                EstimateInstallment::create([
                    'estimate_id' => $estimate->id,
                    'amount' => $installment['amount'],
                    'installment_date' => $installment['date'],
                ]);
            }
        }

        if (!empty($request->taxes)) {
            foreach ($request->taxes as $tax) {
                DB::table('user_estimate_taxes')->insert([
                    'estimate_id' => $estimate->id,
                    'name' => $tax['name'],
                    'percent' => $tax['percent'],
                ]);
            }
        }

        if (!empty($request->discounts)) {
            foreach ($request->discounts as $discount) {
                DB::table('user_estimate_discounts')->insert([
                    'estimate_id' => $estimate->id,
                    'name' => $discount['name'],
                    'value' => $discount['value'],
                    'type' => $discount['type'],
                ]);
            }
        }
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after edit public static function called
    | ----------------------------------------------------------------------
    | @request  = Http request objects
    | @$slug    = $slug
    |
    */
    public function hook_after_edit($request, $slug)
    {
        $record = Estimate::where('slug', $slug)->first();

        if (Auth::user()->user_type != 'client') {

            if (!empty($request->adjust) && $request->adjust == '1') {
                $estimate = Estimate::where('slug', $slug)->first();
                $estimate->status = 'approved';
                $estimate->is_adjusted = 1;
                $estimate->save();

                $request->merge(['slug' => $slug]);

                $contract = Contract::find($estimate->contract_id);
                Invoice::invoiceRevision($request, $estimate, $contract);
            }
        }

        if(Auth::user()->user_type != 'client') {
               $newData = [
                    'estimate' => $record->fresh()->toArray(),
                    'items' => EstimateItem::where('user_estimate_id', $record->id)->get()->toArray(),
                    'taxes' => DB::table('user_estimate_taxes')->where('estimate_id', $record->id)->get()->toArray(),
                    'discounts' => DB::table('user_estimate_discounts')->where('estimate_id', $record->id)->get()->toArray(),
                ];

                $record->logActivity(
                    'Estimate Edited by ' . Auth::user()->name,
                    [],
                    $newData
                );
        }

        if(Auth::user()->user_type != 'client') {
            if(!empty($request->mail_send) && $request->mail_send == '1') {
                    $getEstimate = Estimate::where('slug', $slug)->first();
                    $getClientEmail = Client::where('client_id', $getEstimate->client_id)->where('company_id', $getEstimate->company_id)->first();
                    $user = User::where('email', $getClientEmail->email)->first();
                    $getCompany = CompanyUser::getCompany(Auth::user()->id);
                    if ($user) {
                        $mail_params['company_name'] = $getCompany->name;
                        $mail_params['username'] = $getClientEmail->first_name . ' ' . $getClientEmail->last_name;
                        $mail_params['link']     = ($user->password == null) ? route('admin.create-password', ['any' => Crypt::encrypt($user->email)]) : env('APP_URL');
                        $mail_params['message'] = ($getEstimate->status == 'draft') ? 'You have a new estimate from ' . "$getCompany->name" : 'company review estimate from ' . "$getCompany->name";
                        $subject = $getEstimate->status == 'draft' ? "New Draft from " . $getCompany->name : "New Estimate from " . $getCompany->name;
                       
                        sendMail(
                            $user->email,
                            'estimate',
                            'New Estimate',
                            $mail_params
                        );
                    }
                    // dd($mail_params['link']);

                    Estimate::where('slug', $slug)->update([
                        'status' => 'sent'
                    ]);
            }   
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
    public function hook_before_delete($request, $slug)
    {
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
    public function hook_after_delete($request, $records)
    {
        //Your code here
    }

    public function create_cache_signature($request)
    {
        $cache_params = $request->except(['user', 'api_token']);
    }
}
