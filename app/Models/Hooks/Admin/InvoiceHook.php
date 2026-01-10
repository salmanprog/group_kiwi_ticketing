<?php

namespace App\Models\Hooks\Admin;
use App\Models\{CompanyUser,Estimate,EstimateItem,Client};
use Auth;
use DB;

class InvoiceHook
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
    public function hook_query_index(&$query,$request, $slug=NULL) {
        //Your code here
        $getCompany = CompanyUser::getCompany(Auth::user()->id); 
        $query->select('user_invoices.*');
        if(Auth::user()->user_type == 'client'){
            $query->where('user_invoices.client_id', Auth::user()->id);
            $query->where('user_invoices.status', '!=', 'draft');
        }else{
            $query->where('user_invoices.company_id', $getCompany->id);
        }

         if( !empty($request['keyword']) ){
            $keyword = $request['keyword'];
            $query->where(function($where) use ($keyword){
                $where->orWhere('user_invoices.slug','like',"$keyword%");
                $where->orWhere('user_invoices.issue_date','like',"$keyword%");
            });
        }

        if (!empty($request['start_date']) && !empty($request['end_date'])) {
                $query->whereBetween('user_invoices.created_at', [
                    $request['start_date'] . ' 00:00:00',
                    $request['end_date'] . ' 23:59:59'
                ]);
            }

        if( !empty($request['status']) && $request['status'] != 'all' ){
            $status = $request['status'];
            $query->where('user_invoices.status', $status);
        }
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
        $getCompany = CompanyUser::getCompany(Auth::user()->id); 
        $slug = $this->_model::generateUniqueSlug();
        $postdata['company_id'] = $getCompany->id;
        $postdata['slug'] = $slug;
        $postdata['estimate_number'] = $slug;
        $postdata['created_by'] = Auth::user()->id;
        $postdata['issue_date'] = $postdata['estimate_date'];
        $organization_id = Client::where('client_id',$postdata['client_id'])->where('company_id',$getCompany->id)->value('organization_id');
        $postdata['organization_id'] = $organization_id;
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
        //Your code here
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
        // dd($request->all());
        $estimate = Estimate::where('slug',$slug)->first();
        $postData['issue_date'] = $postData['estimate_date'];
        $postData['valid_until'] = $postData['expiration_date'];
        $postData['status'] = ($estimate->status == 'draft') ? "draft" : 'revised';
        EstimateItem::where('user_estimate_id',$estimate->id)->delete();
        DB::table('user_estimate_taxes')->where('estimate_id',$estimate->id)->delete();
        DB::table('user_estimate_discounts')->where('estimate_id',$estimate->id)->delete();
        if(!empty($request->products)){
             foreach($request->products as $product)
             {
                    EstimateItem::create([
                                    'user_estimate_id'=>$estimate->id,
                                    'name'=>$product['name'],
                                    'quantity'=>$product['quantity'],
                                    'price'=>$product['price'],
                                    'total_price'=>($product['price'] * $product['quantity']),
                                    'unit'=>'each',
                                ]);
             }          
        }

         if(!empty($request->taxes)){
             foreach($request->taxes as $tax)
             {
                    DB::table('user_estimate_taxes')->insert([
                                    'estimate_id'=>$estimate->id,
                                    'name'=>$tax['name'],
                                    'percent'=>$tax['percent'],
                                ]);
             }          
        }

         if(!empty($request->discounts)){
             foreach($request->discounts as $discount)
             {
                    DB::table('user_estimate_discounts')->insert([
                                    'estimate_id'=>$estimate->id,
                                    'name'=>$discount['name'],
                                    'value'=>$discount['value'],
                                    'type'=>$discount['type'],
                                ]);
             }          
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
        $cache_params = $request->except(['user','api_token']);
    }
}
