<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{Organization,Company,CompanyUser,Client, Contract, Invoice, CreditNote, Estimate, EstimateItem, EstimateTax, UserEstimateItemTax, EstimateInstallment, InstallmentPlan, AccountActivityLog,InvoiceItem, ContractItem,InvoiceTax,ContractTaxes, Product};
use Auth;
use DB;
use App\Services\ThirdPartyApiService;
use App\Services\UserMailer;
use App\Models\{ContractModified,ContractModifiedItem,ContractModifiedTax,ContractModifiedDiscount,ContractModifiedInstallment,UserHoldTickets};
use App\Models\{ActivityLog,UserOrders};


class ContractController extends CRUDCrontroller
{
    protected $apiService;

    public function __construct(Request $request, ThirdPartyApiService $apiService)
    {
        parent::__construct('Contract');
        $this->__request = $request;
        $this->__data['page_title'] = 'Contract';
        $this->__indexView = 'contract.index';
        // $this->__createView = 'contractss.edit';
        $this->__detailView = 'contract.detail';
        $this->apiService = $apiService;
    }

    /**
     * This function is used for validate data
     * @param string $action
     * @param string $slug
     * @return array|\Illuminate\Contracts\Validation\Validator
     */
    public function validation(string $action, string $slug = NULL)
    {
        $validator = [];
        $custom_messages = [
            'organization_id.required' => 'Please select organization',
        ];
        switch ($action) {
            case 'POST':
                $validator = Validator::make($this->__request->all(), [
                    'first_name' => 'required|min:2|max:50',
                    'last_name' => 'required|min:2|max:50',
                    'email' => 'required|email|max:100',
                    'mobile_no' => 'required',
                    'position' => 'required',
                    'organization_id' => 'required',
                ], $custom_messages);

                break;
            case 'PUT':
                $validator = Validator::make($this->__request->all(), [
                    '_method' => 'required|in:PUT',
                    'status' => 'required|in:0,1',
                ]);
                break;
        }
        return $validator;
    }

    /**
     * This function is used for before the index view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     */
    public function beforeRenderIndexView()
    {

    }

    /**
     * This function is used to add data in datatable
     * @param object $record
     * @return array
     */
    public function dataTableRecords($record)
    {

        $options = '<a href="' . route('contract.show', ['contract' => $record->slug]) . '" title="View" class="btn btn-xs btn-success"><i class="fa fa-eye"></i></a>';
        return [
            $record->contract_number,
            $record->company_name,
            $record->total,
            ($record->is_accept == 'pending') ? '<span class="btn btn-xs btn-warning">Pending</span>' : (($record->is_accept == 'accepted') ? '<span class="btn btn-xs btn-success">Accepted</span>' : '<span class="btn btn-xs btn-danger">Rejected</span>'),
            date(config("constants.ADMIN_DATE_FORMAT"), strtotime($record->created_at)),
            $options
        ];
    }

    /**
     * This function is used for before the create view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     */
    public function beforeRenderCreateView()
    {
        $this->__data['organizations'] = Organization::where('status', 1)->where('company_id', CompanyUser::getCompany(Auth::user()->id)->id)->where('client_id', 0)->get();
    }

    /**
     * This function is called before a model load
     */
    public function beforeStoreLoadModel()
    {
    }

    /**
     * This function is used for before the edit view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     * @param string @slug
     */
    public function beforeRenderEditView($slug)
    {

    }

    /**
     * This function is called before a model load
     */
    public function beforeUpdateLoadModel()
    {

    }

    /**
     * This function is called before a model load
     */
    public function beforeRenderDetailView()
    {

    }

    /**
     * This function is called before a model load
     */
    public function beforeDeleteLoadModel()
    {

    }

     public function show($slug)
    {
        $this->__data['record'] = Contract::with([
                                    'organization',
                                    'company',
                                    'client',
                                    'invoices.installmentPlan.payments',
                                    'invoices.creditNotes',
                                    'items',
                                    'taxes',
                                    'estimates',
                                    'contractModified'
                                ])
                                ->where('slug', $slug)
                                ->first();

        // dd($this->__data['record']);
        $this->__data['estimate_user'] = Client::where('client_id', $this->__data['record']->client_id)->first();

        if (Auth::user()->user_type == 'client') {
            $this->__data['products'] = [];
        } else {
            $this->__data['products'] = \App\Models\Product::where('auth_code', Auth::user()->auth_code)->get();

        }

        if (Auth::user()->user_type == 'client') {
            $this->__data['logs'] = [];
        } else {
            $this->__data['logs'] = \App\Models\ActivityLog::where('module_id', $this->__data['record']->id)->where('module', 'contract')->orderBy('id', 'desc')->get();

        }
        $this->__data['activityLog'] = AccountActivityLog::with('createdBy')->where('module','contracts')->where('module_id',$this->__data['record']->id)->get();

        return $this->__cbAdminView($this->__detailView, $this->__data);
    }

    // public function show($slug)
    // {
    //     $this->__data['record'] = Contract::with([
    //                                 'organization',
    //                                 'company',
    //                                 'client',
    //                                 'invoices.installmentPlan.payments',
    //                                 'invoices.creditNotes',
    //                                 'items',
    //                                 'taxes',

    //                                 'estimates' => function ($estimateQuery) {
    //                                     $estimateQuery->with([
    //                                         'items' => function ($itemQuery) {
    //                                             $itemQuery->where('is_modify', 0)
    //                                                     ->with('itemTaxes');
    //                                         },
    //                                         'taxes' => function ($taxQuery) {
    //                                             $taxQuery->where('is_modify', 0);
    //                                         },
    //                                         'discounts',
    //                                         'installments',
    //                                     ]);
    //                                 } 

    //                             ])
    //                             ->where('slug', $slug)
    //                             ->first();
    //     $this->__data['estimate_user'] = Client::where('client_id', $this->__data['record']->client_id)->first();

    //     if (Auth::user()->user_type == 'client') {
    //         $this->__data['products'] = [];
    //     } else {
    //         $this->__data['products'] = \App\Models\Product::where('auth_code', Auth::user()->auth_code)->get();

    //     }

    //     if (Auth::user()->user_type == 'client') {
    //         $this->__data['logs'] = [];
    //     } else {
    //         $this->__data['logs'] = \App\Models\ActivityLog::where('module_id', $this->__data['record']->id)->where('module', 'contract')->orderBy('id', 'desc')->get();

    //     }

    //     // dd($this->__data['record']->company);

    //     $this->__data['activityLog'] = AccountActivityLog::with('createdBy')->where('module','contracts')->where('module_id',$this->__data['record']->id)->get();

    //     return $this->__cbAdminView($this->__detailView, $this->__data);
    // }

    public function acceptContract($slug)
    {
        $record = Contract::where('slug', $slug)->first();
        $record->is_accept = 'accepted';
        $record->save();
        return redirect()->back()->with('success', 'Contract accepted successfully');
    }

    public function rejectContract($slug)
    {
        $record = Contract::where('slug', $slug)->first();
        $record->is_accept = 'rejected';
        $record->save();
        return redirect()->back()->with('success', 'Contract accepted successfully');
    }

    public function updateContract(Request $request, $slug)
    {
        $record = Contract::where('slug', $slug)->first();
        $record->event_date = $request->event_date;
        $record->terms_and_condition = $request->terms;
        $record->notes = $request->notes;
        $record->save();
        return redirect()->back()->with('success', 'Contract accepted successfully');
    }

    public function eventCalander()
    {

        // $this->__data['contracts'] = Contract::with('organization', 'userestimates', 'userestimates.items')->where('company_id', CompanyUser::getCompany(Auth::user()->id)->id)->get();
        // $this->__data['estimates'] = Estimate::with('client')->where('company_id', CompanyUser::getCompany(Auth::user()->id)->id)->get();
        $this->__data['contracts'] = Contract::with('organization', 'userestimates', 'userestimates.items')->where('auth_code', Auth::user()->auth_code)->get();
        $this->__data['estimates'] = Estimate::with('client')->where('auth_code', Auth::user()->auth_code)->get();
        $this->__data['cabana'] = DB::table('contract_items')
                                                ->join('company_products', 'contract_items.product_id', '=', 'company_products.id')
                                                ->join('contracts', 'contract_items.contract_id', '=', 'contracts.id')
                                                ->where('company_products.hasSeats', 1)
                                                ->select(
                                                    'contract_items.*',
                                                    'contracts.event_date',
                                                    'contracts.slug as contract_slug',
                                                    'company_products.id as product_id'
                                                )
                                                ->where('contracts.auth_code', Auth::user()->auth_code)
                                                ->get();
            $this->__data['visitors'] = DB::table('contract_items')
                                            ->join('company_products', 'contract_items.product_id', '=', 'company_products.id')
                                            ->join('contracts', 'contract_items.contract_id', '=', 'contracts.id')
                                            ->where('company_products.ticketCategory', '!=', 'Food Addons')
                                            ->select(
                                                'company_products.id as product_id',
                                                'contracts.slug as contract_slug',
                                                'contracts.event_date',
                                                DB::raw('SUM(contract_items.quantity) as total_quantity')
                                            )
                                            ->groupBy(
                                                'company_products.id',
                                                'contracts.slug',
                                                'contracts.event_date'
                                            )
                                            ->get();
        // dd($this->__data['visitors']); 
        // $this->__data['organizations'] = Organization::where('status', 1)->where('company_id', CompanyUser::getCompany(Auth::user()->id)->id)->where('client_id','>', 0)->get();
        return $this->__cbAdminView('contract.event-calander', $this->__data);
    }

    public function addCreditNote($slug)
    {

        $invoice = Invoice::where('slug', $slug)->first();
        $contract = Contract::where('id', $invoice->contract_id)->first();
        $estimate = Estimate::where('id', $invoice->estimate_id)->first();
        $creditNoteExists = CreditNote::where('invoice_id', $invoice->id)->where('contract_id', $contract->id)->where('estimate_id', $estimate->id)->first();
        if ($creditNoteExists) {
            return redirect()->back()->with('error', 'Credit note already exists');
        }

        $this->__data['contract'] = Contract::where('id', $invoice->contract_id)->first();
        $this->__data['invoice'] = $invoice;
        return $this->__cbAdminView('contract.credit-note-add', $this->__data);
    }

    public function saveCreditNote(Request $request)
    {
        $invoice = Invoice::where('slug', $request->invoice_slug)->first();
        if (!$invoice) {
            return redirect()->back()->with('error', 'Invalid invoice');
        }

        if ($request->amount > $invoice->paid_amount) {
            return redirect()->back()->with('error', 'Amount can not be greater than total amount');
        }


        $contract = Contract::where('id', $invoice->contract_id)->first();
        $estimate = Estimate::where('id', $invoice->estimate_id)->first();

        $creditNoteExists = CreditNote::where('invoice_id', $invoice->id)->where('contract_id', $contract->id)->where('estimate_id', $estimate->id)->first();

        if ($creditNoteExists) {
            return redirect()->back()->with('error', 'Credit note already exists');
        }

        $creditNote = new CreditNote();
        $creditNote->contract_id = $contract->id;
        $creditNote->invoice_id = $invoice->id;
        $creditNote->estimate_id = $estimate->id;
        $creditNote->amount = $request->amount;
        $creditNote->reason = $request->reason;
        $creditNote->save();

        return redirect()->route('contract.show', $contract->slug)->with('success', 'Credit note added successfully');
    }

    public function modifyContract(Request $request)
    {
        $contract = Contract::where('id', $request->contract_id)->first();

        $getContractModified = \App\Models\ContractModified::where('contract_id', $contract->id)->count();
        $slug = $contract->slug . '-' . $getContractModified + 1;

        $ContractModified = \App\Models\ContractModified::create([
            'contract_id' => $contract->id,
            'created_by' => Auth::id(),
            'slug' => $slug,
        ]);

        foreach ($request->products as $key => $product) {
            $productget = \App\Models\Product::where('id', $product['product_id'])->first();
            // dd($product);
            \App\Models\ContractModifiedItem::create([
                'contract_modified_id' => $ContractModified->id,
                'contract_id' => $contract->id,
                'created_by' => Auth::id(),
                'name' => $productget->name,
                'quantity' => $product['qty'],
                'unit' => $productget->unit,
                'price' => $product['unit_price'],
                'total_price' => $product['qty'] * $product['unit_price'],
            ]);
        }



        if ($request->confirmed_with_client == 'yes') {

            $invoice = Invoice::generateInvoiceForContract($ContractModified->slug);

        } else {


            dd($contract);
        }

        return redirect()->route('contract.show', $contract->slug)->with('success', 'Contract modified successfully');
    }


    public function modifyContractProducts(Request $request)
    {
        $request->validate([
            'contract_id' => 'required',
            'product' => 'required',
            'product_qty' => 'required|numeric',
            'product_price' => 'required|numeric',
        ]);

        $get_estimate = Estimate::where('contract_id', $request->contract_id)->first();
        // dd($request->all());
        $create_items = EstimateItem::create([
            'user_estimate_id' => $get_estimate->id,
            'product_id' => $request->product,
            'name' => $request->product_name,
            'quantity' => $request->product_qty,
            'unit' => 'pcs',
            'product_price' => $request->product_price,
            'tax' => '0',
            'gratuity' => '0',
            'price' => $request->product_price,
            'total_price' => $request->product_qty * $request->product_price,
            'is_modify' => '1',
        ]);

       $contract = Contract::with([
            'estimates' => function ($estimateQuery) {
                $estimateQuery->with([
                    'items' => function ($itemQuery) {
                        $itemQuery->where('is_modify', 1)
                                ->with('itemTaxes');
                    },
                    'taxes' => function ($taxQuery) {
                        $taxQuery->where('is_modify', 1); // make sure column exists and is integer
                    },
                    'discounts',
                    'installments',
                ]);
            }
        ])->findOrFail($request->contract_id);

        return response()->json($contract);
    }


    public function modifyContractProductsDelete(Request $request)
    {
        $request->validate([
            'contract_id' => 'required',
            'product_id' => 'required',
        ]);
        $get_estimate = Estimate::where('contract_id', $request->contract_id)->first();
        $get_estimate_tax = EstimateTax::where('estimate_id', $get_estimate->id)->first();
        $delete_estimate_item = EstimateItem::where('user_estimate_id', $get_estimate->id)->where('id', $request->product_id)->delete();
        if($get_estimate_tax){
            $delete_estimate_item_tax = UserEstimateItemTax::where('estimate_tax_id', $get_estimate_tax->id)->where('user_estimate_item_id', $request->product_id)->delete();
        }

       $contract = Contract::with([
            'estimates' => function ($estimateQuery) {  
                            $estimateQuery->with([
                                'items' => function ($itemQuery) {
                                    $itemQuery->where('is_modify', 1)
                                            ->with('itemTaxes');
                                },
                                'taxes' => function ($taxQuery) {
                                                $taxQuery->where('is_modify', 1); 
                                            },
                                'discounts',
                                'installments',
                            ]);
                        }
        ])->findOrFail($request->contract_id);

        return response()->json($contract);
    }
    
    public function applyTax(Request $request)
    {
        $request->validate([
            'contract_id' => 'required',
            'tax_name' => 'required',
            'tax_percent' => 'required|numeric',
            'products' => 'required|array'
        ]);
         
        $get_estimate = Estimate::where('contract_id', $request->contract_id)->first();
         $totalTaxAmount = 0;
        foreach ($request->products as $productId) {
           $productDetails = DB::table('user_estimate_items')->where('id', $productId)->first();
        //    $productDetails->total_price = $productDetails->price * $productDetails->quantity;
           $totalTaxAmount += ($productDetails->total_price * $request->tax_percent / 100);
        }
        $insert_tax = EstimateTax::create([
                'estimate_id' => $get_estimate->id,
                'name'  => $request->tax_name,
                'percent'        => $request->tax_percent,
                'amount'        => $totalTaxAmount,
                'is_modify'        => 1,
            ]);

        foreach ($request->products as $productId) {
            
            $insert_item_tax = UserEstimateItemTax::create([
                'estimate_tax_id' => $insert_tax->id,
                'user_estimate_item_id'  => $productId,
                'name'        => $insert_tax->name,
                'percentage'        => $insert_tax->percent,
            ]);
        }

        $contract = Contract::with([
            'estimates' => function ($estimateQuery) {
                            $estimateQuery->with([
                                'items' => function ($itemQuery) {
                                    $itemQuery->where('is_modify', 1)
                                            ->with('itemTaxes');
                                },
                                'taxes' => function ($taxQuery) {
                                                $taxQuery->where('is_modify', 1); 
                                            },
                                'discounts',
                                'installments',
                            ]);
                        }
        ])->findOrFail($request->contract_id);

        return response()->json($contract);
    }


    public function getContractModifyDetails($id)
    {
        $contract = Contract::with([
            'estimates' => function ($estimateQuery) {
                            $estimateQuery->with([
                                'items' => function ($itemQuery) {
                                    $itemQuery->where('is_modify', 1)
                                            ->with('itemTaxes');
                                },
                                'taxes' => function ($taxQuery) {
                                                $taxQuery->where('is_modify', 1); 
                                            },
                                'discounts',
                                'installments' => function ($insQuery) {
                                                $insQuery->where('is_modify', 1); 
                                            },
                            ]);
                        }
        ])->findOrFail($id);

        return response()->json($contract);
    }

    public function getContractTaxModifyDetails($contractId, $taxId)
    {
        $contract = Contract::with([
            'estimates' => function ($estimateQuery) use ($taxId) {
                $estimateQuery->with([
                    'items' => function ($itemQuery) {
                        $itemQuery->where('is_modify', 1)
                                ->with('itemTaxes');
                    },
                    'taxes' => function ($taxQuery) use ($taxId) {
                        $taxQuery->where('is_modify', 1)
                                ->where('id', $taxId); // fetch only the tax we want
                    },
                    'discounts',
                    'installments',
                ]);
            }
        ])->findOrFail($contractId);

        // Fetch the specific tax details
        $tax = EstimateTax::find($taxId);

        return response()->json([
            'contract' => $contract,
            'tax' => $tax, // send tax info separately for pre-fill
            'taxId' => $taxId
        ]);
    }

    public function updateModifyTax(Request $request)
    {
        $request->validate([
            'contract_id' => 'required',
            'tax_id' => 'required',
            'md_edit_tax_name' => 'required',
            'md_edit_tax_percent' => 'required|numeric',
            'selected_products' => 'required|array'
        ]);

        $estimate = Estimate::where('contract_id', $request->contract_id)->firstOrFail();

        $tax = EstimateTax::where('id', $request->tax_id)
                ->where('is_modify', 1)
                ->firstOrFail();
        $tax->update([
            'name' => $request->md_edit_tax_name,
            'percent' => $request->md_edit_tax_percent,
        ]);

        $selectedProducts = $request->selected_products;
        UserEstimateItemTax::where('estimate_tax_id', $tax->id)
            ->whereNotIn('user_estimate_item_id', $selectedProducts)
            ->delete();

        foreach ($selectedProducts as $productId) {

            UserEstimateItemTax::updateOrCreate(
                [
                    'estimate_tax_id' => $tax->id,
                    'user_estimate_item_id' => $productId,
                ],
                [
                    'name' => $tax->name,
                    'percentage' => $tax->percent,
                ]
            );
        }

        $contract = Contract::with([
            'estimates' => function ($estimateQuery) {
                $estimateQuery->with([
                    'items' => function ($itemQuery) {
                        $itemQuery->where('is_modify', 1)
                                ->with('itemTaxes');
                    },
                    'taxes' => function ($taxQuery) {
                        $taxQuery->where('is_modify', 1);
                    },
                    'discounts',
                    'installments',
                ]);
            }
        ])->findOrFail($request->contract_id);

        return response()->json($contract);
    }
    
    public function deleteModifyTax($id)
    {
        $tax = EstimateTax::where('id', $id)
                ->where('is_modify', 1)
                ->firstOrFail();

        UserEstimateItemTax::where('estimate_tax_id', $tax->id)->delete();
        $tax->delete();

        return response()->json(['success' => true]);
    }

    public function saveModifiedContract(Request $request)
    {
        // dd($request->all());

        $estimatecheck = Estimate::where('contract_id', $request->cont_id)->first();

        $estimateItemValidation = EstimateItem::where('user_estimate_id', $estimatecheck->id)->where('is_modify', 1)->first();
        // dd($estimatecheck->id);
        if(empty($estimateItemValidation)){
            return response()->json([
                'status' => false,
                'message' => 'Please add at least one product',
            ]);
        }

        $estimateInstallmentValidation = EstimateInstallment::where('estimate_id', $estimatecheck->id)->where('is_modify', 1)->first();
        if(empty($estimateInstallmentValidation)){
               return response()->json([
                    'status' => false,
                    'message' => 'Please add at least one installment',
                ]);
        }

        // Validate inputs
        $request->validate([
            'cont_id' => 'required',
        ]);


        try {
            $contract = Contract::findOrFail($request->cont_id);

            $contract->confirmed_with_client = $request->confirmed_with_client;
            $contract->save();

            if($request->confirmed_with_client == '0'){
                $get_estimate = Estimate::with([
                                    'items' => function ($q) {
                                        $q->where('is_modify', 1)
                                        ->with(['itemTaxes' ]);
                                    },
                                    'taxes' => function ($q) {
                                        $q->where('is_modify', 1);
                                    },
                                    'installments' => function ($q) {
                                        $q->where('is_modify', 1);
                                    },
                                    'discounts'
                                ])->where('contract_id', $request->cont_id)->first();
                
                if ($get_estimate) {
                    EstimateItem::where('user_estimate_id', $get_estimate->id)
                        ->where('is_modify', '1')
                        ->update(['is_modify' => 0]);
                        
                    EstimateTax::where('estimate_id', $get_estimate->id)
                        ->where('is_modify', '1')
                        ->update(['is_modify' => 0]);
                    
                    EstimateInstallment::where('estimate_id', $get_estimate->id)
                        ->where('is_modify', '1')
                        ->update(['is_modify' => 0]);

                    $get_updated_estimate = Estimate::with('items.itemTaxes')->with('taxes')->with('discounts')->with('installments')->where('contract_id', $request->cont_id)->firstOrFail();

                    $subtotal = $get_updated_estimate->items->sum(fn($item) => $item->total_price);

                    $taxTotal = $get_updated_estimate->items->sum(fn($item) => 
                        $item->itemTaxes->sum(fn($tax) => round($item->total_price * ($tax->percentage / 100), 2))
                    );

                    
                    $discountPercent = $get_updated_estimate->discounts->sum(fn($discount) => $discount->value);
                    $total = ($subtotal + $taxTotal) * (1 - ($discountPercent / 100));
                    $discountAmount = ($subtotal + $taxTotal) * ($discountPercent / 100);

                    //  echo "Subtotal: $subtotal\n";
                    //     echo "Tax: $taxTotal\n";
                    //     echo "Discount: $discountAmount\n";
                    //     echo "Total: $total\n";
                    //     die();
                    $get_updated_estimate->update(['subtotal' => $subtotal,'total' => $total,'discount_total' => $discountAmount,'tax_total' => $taxTotal]);

                    $update_contract = Contract::find($request->cont_id);
                    $update_contract->total += $get_updated_estimate->total;
                    $update_contract->save();

                    Invoice::where('contract_id', $request->cont_id)
                        ->where('subtotal', 1)
                        ->update(['subtotal' => $subtotal,'total' => $total]);

                    $last_updated_estimate = Estimate::where('contract_id', $request->cont_id)->first();
                    $get_previous_instplan = InstallmentPlan::where('estimate_id', $last_updated_estimate->id)->first();
                    $get_update_invoice = Invoice::where('contract_id', $request->cont_id)->first();
                    $total_installments = count($get_estimate->installments);
                    
                    InstallmentPlan::where('estimate_id', $last_updated_estimate->id)
                        ->update(['total_amount' => $last_updated_estimate->total,'installment_count' => $get_previous_instplan->installment_count + $total_installments]);

                    $installments = $get_estimate->installments;

                    foreach ($installments as $index => $installment) {
                        \App\Models\InstallmentPayment::create([
                            'installment_plan_id' => $get_previous_instplan->id,
                            'installment_number' => $index + 1,
                            'invoice_id' => $get_update_invoice->id,
                            'estimate_id' => $get_estimate->id,
                            'contract_id' => $request->cont_id,
                            'due_date' => $installment->installment_date,
                            'amount' => $installment->amount,
                            'is_paid' => false,
                        ]);
                    }

                    foreach ($get_estimate->items as $item) {
                        InvoiceItem::create([
                            'invoice_id' => $get_update_invoice->id,
                            'product' => $item->product_id,
                            'name' => $item->name,
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                            'unit' => $item->unit,
                            'total_price' => $item->total_price,
                        ]);
                        ContractItem::create([
                            'contract_id' => $request->cont_id,
                            'name' => $item->name,
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                            'unit' => $item->unit,
                            'price' => $item->price,
                            'total_price' => $item->total_price,
                            'taxes' => $item->tax,
                            'product_price' => $item->product_price,
                            'gratuity' => $item->gratuity,
                            'accepted_by_client' => '1',
                            'modified' => '1',
                            'invoice_id' => $get_update_invoice->id,
                        ]);
                    }

                    foreach ($get_estimate->taxes as $tax) {
                        InvoiceTax::create([
                            'invoice_id' => $get_update_invoice->id,
                            'name' => $tax->name,
                            'percent' => $tax->percent,
                        ]);

                        ContractTaxes::create([
                            'contract_id' =>$request->cont_id,
                            'name' => $tax->name,
                            'percent' => $tax->percent,
                            'invoice_id' => $get_update_invoice->id,
                            'amount' => $tax->amount,
                        ]);
                    }
                }

                 return response()->json([
                    'status' => true,
                    'message' => 'Contract updated successfully',
                ]);

            }else{
                 return response()->json([
                    'status' => true,
                    'message' => 'Contract updated successfully',
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: '.$e->getMessage(),
            ], 500);
        }
    }


    public function sendOrdersTicket(Request $request, $slug)
    {
        $contract = Contract::where('slug', $slug)->first();
        $estimate = Estimate::where('contract_id',$contract->id)->first();
        // $tickets = DB::table('user_order_tickets')
        //                         ->join('user_orders', 'user_order_tickets.user_order_id', '=', 'user_orders.id')
        //                         ->where('user_orders.order_number', $estimate->slug)
        //                         ->get();
        $status = $request->get('status', 'NotSent');
        if (!$status) {
            $status = 'NotSent'; 
        }
        $tickets = $this->apiService->queryOrderSendList($estimate->slug, $status, $estimate->auth_code);
    //    dd($tickets);
        
        return $this->__cbAdminView('contract.send-orders-ticket', [
            'tickets' => $tickets,
            'slug' => $slug,
        ]);
 
    }

    public function ajaxsendOrdersTicket($slug)
    {
        $contract = Contract::where('slug', $slug)->first();
        $estimate = Estimate::where('contract_id',$contract->id)->first();
        $status = 'NotSent';
        $tickets = $this->apiService->queryOrderSendList($estimate->slug, $status, $estimate->auth_code);

        return response()->json(['tickets' => $tickets]);

    }


   public function sendTicketEmail(Request $request)
    {
        $request->validate([
            'ticket_ids' => 'required|array',
            'ticket_ids.*' => 'string', // assuming visualId is string
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        $ticketIds = $request->ticket_ids;
        $recipientName = $request->name;
        $recipientEmail = $request->email;

        // // 1. Fetch tickets from DB
        $tickets = DB::table('user_order_tickets')
                    ->whereIn('visualId', $ticketIds)
                    ->get();

        if ($tickets->isEmpty()) {
            return response()->json(['message' => 'No valid tickets found.'], 404);
        }

        try {
            $qrCodes = $tickets->pluck('visualId')->toArray();
            $qrCodes = [
                '123lskdfmk',
                '101-240326222343328-187466-688011'
            ];
            $authCode = Auth::user()->auth_code; // or pass as needed
            // dd($qrCodes);
            $apiResponse = $this->apiService->sendTicketToRecipient($qrCodes, $recipientName, $recipientEmail, $authCode);
            // dd($apiResponse->json());
            if($apiResponse['status']['errorCode'] == 2){
                return response()->json(['message' => $apiResponse['status']['errorMessage']], 500);
            }


            if($apiResponse['status']['errorCode'] == 0)
            {
                 
                 $auth_code = Auth::user()->auth_code;
                $toEmails = $recipientEmail;
                $templateIdentifier = 'ticket_email_send';
                // Initialize empty string
                $ticketList = '';

                // Loop through tickets and append HTML for each
                foreach ($tickets as $ticket) {
                    $ticketList .= '
                        <div class="ticket">
                            <h5>Ticket - ' . ($ticket->type ?? 'General') . '</h5>
                            <img src="https://quickchart.io/qr?text=' . $ticket->visualId . '&margin=2&size=100" alt="Ticket QR Code">
                            <p>' . $ticket->visualId . '</p>
                            <p>' . ($ticket->ticketType ?? 'General Admission') . '</p>
                            <p>' . ($ticket->ticketDisplayDate ?? date('m-d-Y')) . '</p>
                        </div>
                    ';
                }


                $companyName = Company::where('auth_code',Auth::user()->auth_code)->first(); 
                $data = [
                    'username' => $recipientName,
                    'company_name' => $companyName->name,
                    'ticketList' => $ticketList
                ];
                
                
                try {
                    UserMailer::sendTemplate($auth_code, $toEmails, $templateIdentifier, $data);
                } catch (\Exception $e) {
                    return false;
                }

            }





            // You can log or handle $apiResponse if needed
        } catch (\Exception $e) {
            \Log::error('DynamicPricing API failed: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to send tickets. ' . $e->getMessage()], 500);
        }

        // 2. Update status to "Sent"
        DB::table('user_order_tickets')
            ->whereIn('visualId', $ticketIds)
            ->update(['status' => 1]);

        // try {
        //     $auth_code = Auth::user()->auth_code;
        //     $templateIdentifier = 'ticket_email';
        //     $data = [
        //         'name' => $recipientName,
        //         'email' => $recipientEmail,
        //         'tickets' => $tickets
        //     ];
        //     UserMailer::sendTemplate($auth_code, $recipientEmail, $templateIdentifier, $data);
        // } catch (\Exception $e) {
        //     // Log the error but don't fail the request
        //     \Log::error('Failed to send ticket email: ' . $e->getMessage());
        // }
    
        return response()->json(['message' => $apiResponse['status']['errorMessage']]);
    }


    public function clientTicketEnable($slug)
    {
        $contract = Contract::with('client')->where('slug', $slug)->first();
        if (!$contract) {
            return response()->json(['message' => 'Contract not found.'], 404);
        }
        
        $contract->ticket_enable = '1';
        $contract->save();


           $companyName = Company::where('auth_code',Auth::user()->auth_code)->first(); 
            $data = [
                'username' => $contract->client->name,
                'company_name' => $companyName->name,
            ];
            
            
            try {
                UserMailer::sendTemplate($contract->auth_code, $contract->client->email, 'enable_ticket_email', $data);
            } catch (\Exception $e) {
                return false;
            }

          \App\Models\ActivityLog::create([
                'module' => 'contract',
                'module_id' => $contract->id,
                'description' => 'Ticket enable updated by ' . auth()->user()->name,
                'user_id' => auth()->user()->id,
                'old_data' => json_encode($contract->toArray()),
                'new_data' => json_encode($contract->toArray()),
            ]);
        
        return response()->json(['message' => 'Tickets delivery enabled via distribution system.'], 200);
    }


    public function clientTicketDisable($slug)
    {
        $contract = Contract::where('slug', $slug)->first();
        if (!$contract) {
            return redirect()->back()->with('error', 'Contract not found.');
        }
        
        $contract->ticket_enable = '0';
        $contract->save();

          \App\Models\ActivityLog::create([
                'module' => 'contract',
                'module_id' => $contract->id,
                'description' => 'Ticket disable updated by ' . auth()->user()->name,
                'user_id' => auth()->user()->id,
                'old_data' => json_encode($contract->toArray()),
                'new_data' => json_encode($contract->toArray()),
            ]);
        
        return redirect()->back()->with('success', 'Ticket disable updated successfully.');
    }


    public function printTicket(Request $request){
        
        $contract = Contract::where('slug',$request->slug)->first();
        if(!$contract){
            return response()->json(['message' => 'Contract not found.'], 404);
        }
        $estimate = Estimate::where('contract_id',$contract->id)->first();
        if(!$estimate){
            return response()->json(['message' => 'estimate not found.'], 404);
        }


        $orderNumber = $estimate->slug;
        $isAllowedForPrinting = 1;

        $apiResponse = $this->apiService->UpdateOrderSettings($orderNumber,$isAllowedForPrinting,Auth::user()->auth_code);
        if($apiResponse->json()['errorCode'] == 1){
            return response()->json(['message' => $apiResponse->json()['errorMessage']]);
        }
        
        $contract->isAllowedForPrinting = '1';
        $contract->save();

         \App\Models\ActivityLog::create([
                'module' => 'contract',
                'module_id' => $contract->id,
                'description' => 'Ticket printed updated by ' . auth()->user()->name,
                'user_id' => auth()->user()->id,
                'old_data' => json_encode($contract->toArray()),
                'new_data' => json_encode($contract->toArray()),
            ]);
        
        
        return response()->json(['message' => ($apiResponse->json()['errorMessage']) ? $apiResponse->json()['errorMessage'] : 'Tickets delivery enabled via Boca printer.'],200);

    }

    public function getContractModifyPage($slug)
    {
        $contract = Contract::where('slug', $slug)->first();
        if(!$contract){
            return response()->json(['message' => 'Contract not found.'], 404);
        }
 
        $ContractModified = ContractModified::where('contract_id', $contract->id)->where('status','pending')->first();
        if(!$ContractModified){
            $ContractModifiedCount = ContractModified::where('contract_id', $contract->id)->count();

            $estimate = Estimate::where('contract_id', $contract->id)->first();
            $newContractModified = ContractModified::create([
                'contract_id' => $contract->id,
                'user_estimate_id' => $estimate->id,
                'status' => 'pending',
                'slug' => $slug . '-' . ($ContractModifiedCount+1),
            ]);

            ActivityLog::create([
                'module' => 'contract',
                'module_id' => $contract->id,
                'description' => "Contract modify  $newContractModified->slug  created by " . auth()->user()->name,
                'user_id' => auth()->user()->id,
            ]);
        }

        $estimate = Estimate::where('contract_id', $contract->id)->first();
        $data = ContractModified::with('items.itemTaxes','taxes','discounts','installments')
                                ->where('contract_id', $contract->id)
                                ->where('status','pending')
                                ->first();
        $user_hold_tickets = UserHoldTickets::with(['user_hold_ticket_items' => function($query)use($data){
                                            $query->where('modified_contract_id', $data->id);
                                        }])
                                            ->where('auth_code',  Auth::user()->auth_code)
                                            ->where('estimate_id', $estimate->id)
                                            ->first();
        // dd($user_hold_tickets);
        $products = Product::where('auth_code',  Auth::user()->auth_code)->get();
        
        return view('portal.contract.modify-edit', compact('data','products','estimate','user_hold_tickets'));
    }


     public function getContractModifyDetailPage($slug)
    {
        $contract = ContractModified::where('slug', $slug)->first();
        if(!$contract){
            return redirect()->back()->with('error', "$slug".' Modify not found.');
        }
       
        $estimate = Estimate::where('contract_id', $contract->contract_id)->first();
        $data = ContractModified::with('items.itemTaxes','taxes','discounts','installments')
                                ->where('slug', $slug)
                                ->first();
        $user_hold_tickets = UserHoldTickets::with(['user_hold_ticket_items' => function($query)use($data){
                                            $query->where('modified_contract_id', $data->id);
                                        }])
                                            ->where('auth_code',  Auth::user()->auth_code)
                                            ->where('estimate_id', $estimate->id)
                                            ->first();
        $products = Product::where('auth_code',  Auth::user()->auth_code)->get();
        
        return view('portal.contract.modify-view', compact('data','products','estimate','user_hold_tickets'));
    }




     public function modifyContractAddProducts(Request $request)
    {
        // Validate the request
        $request->validate([
            'contract_modified_id' => 'required',
            'products' => 'required|array'
        ]);

        $contract_modified_id = $request->input('contract_modified_id');
        $addedItems = [];
        $duplicateProducts = [];

        foreach ($request->products as $productData) {
            $product = Product::find($productData['product_id']);
            if (!$product) {
                continue; // skip if product not found
            }

            // Check if product already exists for this estimate
            $exists = ContractModifiedItem::where('contract_modified_id', $contract_modified_id)
                                ->where('product_id', $product->id)
                                ->first();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => "Product '{$exists['name']}' is already added to this estimate."
                ]);
            }
            $ContractModified = ContractModified::find($contract_modified_id);
            $contract = Contract::find($ContractModified->contract_id);
            $item = ContractModifiedItem::create([
                'contract_id' => $contract->id,
                'contract_modified_id' => $contract_modified_id,
                'product_id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'quantity' => (int) $productData['qty'],
                'unit' => $productData['unit'] ?? 'pcs',
                'price' => (float) $productData['price'],
                'total_price' => (int) $productData['qty'] * (float) $productData['price'],
                'product_price' => (float) $productData['price'],
            ]);

            $addedItems[] = $item;
        }

        if (!empty($duplicateProducts)) {
            return response()->json([
                'status' => false,
                'message' => 'These products are already added: ' . implode(', ', $duplicateProducts),
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Products added successfully',
            'items' => $addedItems
        ]);
    }


     public function modifyContractUpdateProducts(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'item_id' => 'required',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:10',
            'contract_modified_id' => 'required'
        ]);

        $item = ContractModifiedItem::findOrFail($request->item_id);
        $item->quantity = $request->quantity;
        $item->unit = ($request->unit) ? $request->unit : 'pcs';
        $item->price = $request->price;
        $item->total_price = $request->quantity * $request->price;
        $item->save();


        $taxes = DB::table('contract_modified_taxes')
            ->where('contract_modified_id', $item->contract_modified_id)
            ->get();

        if ($taxes->isNotEmpty()) {
            foreach ($taxes as $tax) {
                $singleProductTaxAmount = ($item->total_price * $tax->percent / 100);
                $user_estimate_item_taxes = DB::table('contract_modified_item_taxes')
                    ->where('contract_modified_tax_id', $tax->id)
                    ->where('contract_modified_item_id', $item->id)
                    ->first();

                if ($user_estimate_item_taxes) {
                    DB::table('contract_modified_item_taxes')
                        ->where('contract_modified_tax_id', $tax->id)
                        ->where('contract_modified_item_id', $item->id)
                        ->update(['amount' => $singleProductTaxAmount]);
                }

                DB::table('contract_modified_taxes')
                    ->where('id', $tax->id)
                    ->update([
                        'amount' => $tax->amount - ($user_estimate_item_taxes ? $user_estimate_item_taxes->amount : 0) + $singleProductTaxAmount
                    ]);
            }
        }
 


        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully',
            'item' => $item
        ]);
    }


    public function modifyContractDeleteProduct(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'contract_modified_id' => 'required'
        ]);

        $item = ContractModifiedItem::findOrFail($request->item_id);

          $taxes = DB::table('contract_modified_taxes')
            ->where('contract_modified_id', $item->contract_modified_id)
            ->get();

        if ($taxes->isNotEmpty()) {
            foreach ($taxes as $tax) {
                $user_estimate_item_taxes = DB::table('contract_modified_item_taxes')
                    ->where('contract_modified_tax_id', $tax->id)
                    ->where('contract_modified_item_id', $item->id)
                    ->first();

                if ($user_estimate_item_taxes) {
                    DB::table('contract_modified_item_taxes')
                        ->where('contract_modified_tax_id', $tax->id)
                        ->where('contract_modified_item_id', $item->id)
                        ->delete();

                    DB::table('contract_modified_taxes')
                        ->where('id', $tax->id)
                        ->update([
                            'amount' => $tax->amount - $user_estimate_item_taxes->amount
                        ]);
                }
            }
        }
      
        $item->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ]);
    }
 
     public function getContractModifyProducts(Request $request)
    {
        // dd($request->all());
        $item = ContractModifiedItem::where('contract_modified_id', $request->contract_modified_id)->get();

        return response()->json([
            'status' => true,
            'item' => $item
        ]);
    }

      public function modifyContractAddTax(Request $request)
    {
        // Validate the request
        $request->validate([
            'contract_modified_id' => 'required|integer',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|integer',
            'products.*.tax_name' => 'required|string',
            'products.*.tax_percent' => 'required|numeric|min:0',
            'products.*.price' => 'required|numeric|min:0'
        ]);

        // dd($request->all());

        $contractModifiedId = $request->input('contract_modified_id');
        $products = $request->input('products');
        $taxPercent = $products[0]['tax_percent'];
        $taxName = $products[0]['tax_name'];

        $totalTaxAmount = 0;
        foreach ($products as $product) {
           $productDetails = DB::table('contract_modified_items')->where('id', $product['id'])->first();
        //    $productDetails->total_price = $productDetails->price * $productDetails->quantity;
           $totalTaxAmount += ($productDetails->total_price * $taxPercent / 100);
        }

        try {
            
            $estimateTaxId = \DB::table('contract_modified_taxes')->insertGetId([
                'contract_modified_id' => $contractModifiedId,
                'name' => $taxName,
                'percent' => $taxPercent,
                'amount' => $totalTaxAmount,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Attach selected products to this tax
            foreach ($products as $product) {
                $productDetails = DB::table('contract_modified_items')->where('id', $product['id'])->first();
                $singleProductTaxAmount = ($productDetails->total_price * $taxPercent / 100);

                \DB::table('contract_modified_item_taxes')->insert([
                    'contract_modified_tax_id' => $estimateTaxId,
                    'contract_modified_item_id' => $product['id'],
                    'name' => $taxName,
                    'percentage' => $taxPercent,
                    'amount' => $singleProductTaxAmount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Tax added successfully',
                'tax_id' => $estimateTaxId
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while adding tax',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function productTaxUpdate(Request $request)
    {
        // Validate the request
        $request->validate([
            'tax_id' => 'required|integer', // ID of the existing tax
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|integer',
            'products.*.tax_name' => 'required|string',
            'products.*.tax_percent' => 'required|numeric|min:0',
            'products.*.price' => 'required|numeric|min:0'
        ]);

        $taxId = $request->input('tax_id');
        $products = $request->input('products');

        $taxPercent = $products[0]['tax_percent'];
        $taxName = $products[0]['tax_name'];

        $totalTaxAmount = 0;
        foreach ($products as $product) {
            $productDetails = DB::table('user_estimate_items')->where('id', $product['id'])->first();
            $totalTaxAmount += ($productDetails->total_price * $taxPercent / 100);
        }
            // dd($totalTaxAmount);

        try {
            // Update main tax record
            \DB::table('contract_modified_taxes')
                ->where('id', $taxId)
                ->update([
                    'name' => $taxName,
                    'percent' => $taxPercent,
                    'amount' => $totalTaxAmount,
                    'updated_at' => now(),
                ]);

            // Remove old product-tax relations for this tax
            \DB::table('contract_modified_item_taxes')
                ->where('contract_modified_tax_id', $taxId)
                ->delete();

            // Re-insert updated products
            foreach ($products as $product) {
                \DB::table('contract_modified_item_taxes')->insert([
                    'contract_modified_tax_id' => $taxId,
                    'contract_modified_item_id' => $product['id'],
                    'name' => $taxName,
                    'percentage' => $taxPercent,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Tax updated successfully',
                'tax_id' => $taxId
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while updating tax',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function deleteTax(Request $request, $taxId)
    {
        try {
            // Delete tax and related item_taxes
            \DB::table('contract_modified_item_taxes')->where('contract_modified_tax_id', $taxId)->delete();
            \DB::table('contract_modified_taxes')->where('id', $taxId)->delete();

            return response()->json([
                'status' => true,
                'message' => 'Tax deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ]);
        }
    }

     public function editGetItem(Request $request)
    {
      

        $items = ContractModifiedItem::with('itemTaxes')
            ->where('contract_modified_id', $request->contract_modified_id)
            ->get();

        $tax = ContractModifiedTax::where('id', $request->tax_id)
            ->first();


        return response()->json([
            'status' => true,
            'item' => $items,
            'tax' => $tax
        ]);
    }

     public function productModifyTaxUpdate(Request $request)
    {
        // Validate the request
        $request->validate([
            'tax_id' => 'required|integer', // ID of the existing tax
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|integer',
            'products.*.tax_name' => 'required|string',
            'products.*.tax_percent' => 'required|numeric|min:0',
            'products.*.price' => 'required|numeric|min:0'
        ]);

        $taxId = $request->input('tax_id');
        $products = $request->input('products');

        $taxPercent = $products[0]['tax_percent'];
        $taxName = $products[0]['tax_name'];

        $totalTaxAmount = 0;
        foreach ($products as $product) {
            $productDetails = DB::table('contract_modified_items')->where('id', $product['id'])->first();
            $totalTaxAmount += ($productDetails->total_price * $taxPercent / 100);
        }
            // dd($totalTaxAmount);

        try {
            // Update main tax record
            \DB::table('contract_modified_taxes')
                ->where('id', $taxId)
                ->update([
                    'name' => $taxName,
                    'percent' => $taxPercent,
                    'amount' => $totalTaxAmount,
                    'updated_at' => now(),
                ]);

            // Remove old product-tax relations for this tax
            \DB::table('contract_modified_item_taxes')
                ->where('contract_modified_tax_id', $taxId)
                ->delete();

            // Re-insert updated products
            foreach ($products as $product) {
                $productDetails = DB::table('contract_modified_items')->where('id', $product['id'])->first();
                $singleProductTaxAmount = ($productDetails->total_price * $taxPercent / 100);
 
                \DB::table('contract_modified_item_taxes')->insert([
                    'contract_modified_tax_id' => $taxId,
                    'contract_modified_item_id' => $product['id'],
                    'name' => $taxName,
                    'amount'=>$singleProductTaxAmount,
                    'percentage' => $taxPercent,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Tax updated successfully',
                'tax_id' => $taxId
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while updating tax',
                'error' => $e->getMessage()
            ]);
        }
    }


    public function productDiscountAdd(Request $request)
    {
        // Validate the request
        $request->validate([
            'contract_modified_id' => 'required|integer',
            'products.*.discount_name' => 'required|string',
            'products.*.discount_value' => 'required|numeric|min:0',
        ]);

        $contract_modified_id = $request->input('contract_modified_id');
        $products = $request->input('products');
        $discountType = $request->input('discount_type', 'percent');

        // // Calculate total discount amount 
        $totalDiscount = 0;
        foreach ($products as $product) {
            // if ($product['discount_type'] === 'percent') {
            //     $totalDiscount += ($product['price'] * $product['discount_value'] / 100);
            // } else { // fixed
                $totalDiscount += $product['discount_value'];
            //}
        }

        try {
            // Insert discount record
            $discountId = \DB::table('contract_modified_discounts')->insertGetId([
                'contract_modified_id' => $contract_modified_id,
                'name' => $products[0]['discount_name'],
                'type' => $discountType,
                'value' => $products[0]['discount_value'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

         
            return response()->json([
                'status' => true,
                'message' => 'Discount applied successfully',
                'discount_id' => $discountId
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while adding discount',
                'error' => $e->getMessage()
            ]);
        }
    }

     public function getItem(Request $request)
    {
        $item = ContractModifiedDiscount::where('id', $request->id)->get();
        // dd($item);
        return response()->json([
            'status' => true,
            'item' => $item
        ]);
    }

     public function updateDiscount(Request $request)
    {
        $request->validate([
            'discount_id' => 'required',
            'contract_modified_id' => 'required',
            'name'        => 'required|string|max:255',
            'value'       => 'required|numeric|min:0',
        ]);

        $discount = ContractModifiedDiscount::where('id', $request->discount_id)
            ->where('contract_modified_id', $request->contract_modified_id)
            ->first();

        if (!$discount) {
            return response()->json([
                'status'  => false,
                'message' => 'Discount not found'
            ], 404);
        }

        $discount->update([
            'name'  => $request->name,
            'value' => $request->value,
            'type'  => $request->type,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Discount updated successfully',
            'item'    => $discount
        ]);
    }

    public function deleteDiscount(Request $request, $id)
    {
        $discount = ContractModifiedDiscount::find($id);

        if (!$discount) {
            return response()->json([
                'status' => false,
                'message' => 'Discount not found'
            ], 404);
        }

        $discount->delete();

        return response()->json([
            'status' => true,
            'message' => 'Discount deleted successfully'
        ]);
    }

    public function savePaymentSchedule(Request $request, $ContractModifiedId)
    {
        $estimate = ContractModifiedInstallment::where('contract_modified_id',$ContractModifiedId)->delete();
        // dd('work');
        if(!$request->has('installments')) {
            return response()->json([
                'status' => false,
                'message' => 'No installments provided. Payment not saved'
            ], 400);
        }
        
        $total_amount = 0;
        foreach ($request->installments as $inst) {
            $total_amount += $inst['amount'];
        }

        if($total_amount != $request->total_amount) {
            return response()->json([
                'status' => false,
                'message' => 'Total amount does not match with installments amount. Payment not saved'
            ], 400);
        }

      
        
        $request->validate([
            'installments' => 'required|array',
            'installments.*.amount' => 'required|numeric|min:0.01',
            'installments.*.date' => 'required|date',
        ]);

       // dd($request->all());
        // Remove old installments (soft delete)
        //$estimate->installments()->delete();

        // Use parallel processing to create installments
        $installments = array_map(function($inst) use ($ContractModifiedId) {
            return [
                'contract_modified_id' => $ContractModifiedId,
                'amount' => $inst['amount'],
                'installment_date' => $inst['date'],
            ];
        }, $request->installments);

        collect($installments)->chunk(100)->each(function($chunk) {
            ContractModifiedInstallment::insert($chunk->toArray());
        });

        return response()->json([
            'status' => true,
            'message' => 'Payment schedule saved successfully!'
        ]);
    }

    public function deletePaymentSchedule(Request $request, $ContractModifiedId)
    {
        $request->validate([
            'id' => 'required',
        ]);
        ContractModifiedInstallment::where('id',$request->id)->delete();
        return response()->json([
            'status' => true,
            'message' => 'Payment schedule deleted successfully!'
        ]);
    }

    public function updateItemDescription(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'description' => 'required|string',
        ]);

        $item = ContractModifiedItem::findOrFail($request->id);
        $item->description = $request->description;
        $item->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Description updated successfully'
        ]);
    }

    public function sendToClient(Request $request)
    {
        $request->validate([
            'contract_modified_id' => 'required',
            'confirmed_with_client' => 'required|string',
        ]);

        $products = DB::table('contract_modified_items')->where('contract_modified_id',$request->contract_modified_id)->count();
        if($products == 0) {
            return response()->json([
                'status' => false,
                'message' => 'Please add products first'
            ]);
        }

        $ContractModifiedInstallment = ContractModifiedInstallment::where('contract_modified_id',$request->contract_modified_id)->count();
        if($ContractModifiedInstallment == 0) {
            return response()->json([
                'status' => false,
                'message' => 'Please add payment plan first'
            ]);
        }
    
        $holdTicket = DB::table('user_hold_tickets')
                        ->join('user_hold_ticket_items', 'user_hold_tickets.id', '=', 'user_hold_ticket_items.user_hold_ticket_id')
                        ->where('user_hold_tickets.estimate_id', $request->estimate_id)
                        ->where('user_hold_ticket_items.modified_contract_id',$request->contract_modified_id)
                        ->first();
        // dd($request->all());
        if(!$holdTicket) {
            return response()->json([
                'status' => false,
                'message' => 'Please hold tickets for this in modify contract.'
            ]);
        }

        $status = ($request->confirmed_with_client == '1') ? "sent" : 'accept_by_company';

        $ContractModified = ContractModified::where('id', $request->contract_modified_id)->first();
        $ContractModified->status = $status;
        $ContractModified->save();
       
        if($request->confirmed_with_client == '0') {
            $this->generateUpdateContract($ContractModified->contract_id, $request);
            ActivityLog::create([
                'module' => 'contract',
                'module_id' => $ContractModified->contract_id,
                'description' => "Contract modify  $ContractModified->slug  updated by " . auth()->user()->name,
                'user_id' => auth()->user()->id,
            ]);
            $message = "Modify has been update.";
            $redirect_url = route('contract.show', ['contract' => $ContractModified->contract->slug]);

        }else{
            //send to client email
            $companyName = Company::where('auth_code',Auth::user()->auth_code)->first();
            $userContract = Contract::with('client')->where('id', $ContractModified->contract_id)->first();
            $toEmails = $userContract->client->email;
            $auth_code = Auth::user()->auth_code;
            $templateIdentifier = 'modify_email';

            $data = [
                'username' => $userContract->client->username,
                'company_name' => $companyName->name,
                'contract_number' => $userContract->slug,
                'contract_modify_number'=>$ContractModified->slug
            ];
            
            
            try {
                UserMailer::sendTemplate($auth_code, $toEmails, $templateIdentifier, $data);
            } catch (\Exception $e) {
                return false;
            }

            ActivityLog::create([
                'module' => 'contract',
                'module_id' => $ContractModified->contract_id,
                'description' => "Contract modify  $ContractModified->slug sent to client by " . auth()->user()->name,
                'user_id' => auth()->user()->id,
            ]);
            $message = "Contract Modification has been sent to client.";
            // $redirect_url = '#';
            $redirect_url = route('contract.show', ['contract' => $ContractModified->contract->slug]);

        }

        return response()->json([
            'status' => true,
            'message' => $message,
            'redirect_url' => $redirect_url
        ]);
    }

    public function generateUpdateContract($contractId, $request)
    {
        //   try {
            $contract = Contract::findOrFail($contractId);

            $ContractModified = ContractModified::with([
                                    'items',
                                    'taxes' ,
                                    'installments',
                                    'discounts'
                                ])->where('id',$request->contract_modified_id)->first();
                

                if ($ContractModified) {
                   

                    $get_updated_estimate = ContractModified::with('items.itemTaxes')->with('taxes')->with('discounts')->with('installments')->where('id', $request->contract_modified_id)->firstOrFail();

                    $subtotal = $get_updated_estimate->items->sum(fn($item) => $item->total_price);

                    $taxTotal = $get_updated_estimate->items->sum(fn($item) => 
                        $item->itemTaxes->sum(fn($tax) => round($item->total_price * ($tax->percentage / 100), 2))
                    );

                    
                    $discountPercent = $get_updated_estimate->discounts->sum(fn($discount) => $discount->value);
                    $total = ($subtotal + $taxTotal) * (1 - ($discountPercent / 100));
                    $discountAmount = ($subtotal + $taxTotal) * ($discountPercent / 100);

                    $get_updated_estimate->update(['subtotal' => $subtotal,'total' => $total,'discount_total' => $discountAmount,'tax_total' => $taxTotal]);

                    $update_contract = Contract::find($contractId);
                    $update_contract->total += $get_updated_estimate->total;
                    $update_contract->save();

                    Invoice::generateModifyInvoice($contractId,$request);                    
                }


                $data =[
                    'contract_modify_id'=>$ContractModified->id,
                    'authCode'=>$contract->auth_code,
                    'date'=>$contract->event_date,                    
                ];
                $payload = UserHoldTickets::updateOrderPayload($data);
                $response = $this->apiService->updateOrder($payload);

                // if($response['tickets']){
                //     UserOrders::updateStoreOrder($response['tickets'],$ContractModified->id);
                // }

                $invoicPayload = UserHoldTickets::updateInvoiceOrderPayload($data);
                // dd(json_encode($invoicPayload));
                $invoiceResponse = $this->apiService->updateModifyOrderInvoice($invoicPayload,$contract->auth_code);

                // dd($payload);
                 return response()->json([
                    'status' => true,
                    'message' => 'Contract updated successfully',
                ]);

         

        // } catch (\Exception $e) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Error: '.$e->getMessage(),
        //     ], 500);
        // }
    }

}
