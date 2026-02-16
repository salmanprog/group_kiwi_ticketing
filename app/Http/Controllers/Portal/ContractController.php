<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{Organization, CompanyUser, Contract, Invoice, CreditNote, Estimate, EstimateItem, EstimateTax, UserEstimateItemTax, EstimateInstallment, InstallmentPlan, AccountActivityLog,InvoiceItem};
use Auth;

class ContractController extends CRUDCrontroller
{

    public function __construct(Request $request)
    {
        parent::__construct('Contract');
        $this->__request = $request;
        $this->__data['page_title'] = 'Contract';
        $this->__indexView = 'contract.index';
        // $this->__createView = 'contractss.edit';
        $this->__detailView = 'contract.detail';
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
        // $this->__data['record'] = Contract::with([
        //     'organization',
        //     'company',
        //     'client',
        //     'estimates.items',
        //     'invoices.installmentPlan.payments',
        //     'invoices.creditNotes',
        //     'items',
        //     'taxes'
        // ])
        //     ->where('slug', $slug)
        //     ->first();

        // $this->__data['record'] = Contract::with([
        //                     'organization',
        //                     'company',
        //                     'client',
        //                     // Load estimates and all the same relationships you used before
        //                     'estimates.items.itemTaxes',   // nested: items -> itemTaxes
        //                     'estimates.taxes',             // estimate -> taxes
        //                     'estimates.discounts',          // estimate -> discounts
        //                     'estimates.installments',      // estimate -> installments
        //                     // Other relationships
        //                     'invoices.installmentPlan.payments',
        //                     'invoices.creditNotes',
        //                     'items',
        //                     'taxes'
        //                 ])
        //                 ->where('slug', $slug)
        //                 ->first();

        

        $this->__data['record'] = Contract::with([
                                    'organization',
                                    'company',
                                    'client',
                                    'invoices.installmentPlan.payments',
                                    'invoices.creditNotes',
                                    'items',
                                    'taxes',

                                    'estimates' => function ($estimateQuery) {
                                        $estimateQuery->with([
                                            'items' => function ($itemQuery) {
                                                $itemQuery->where('is_modify', 0)
                                                        ->with('itemTaxes');
                                            },
                                            'taxes' => function ($taxQuery) {
                                                $taxQuery->where('is_modify', 0);
                                            },
                                            'discounts',
                                            'installments',
                                        ]);
                                    }

                                ])
                                ->where('slug', $slug)
                                ->first();

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

        // dd($this->__data['record']->company);

        $this->__data['activityLog'] = AccountActivityLog::with('createdBy')->where('module','contracts')->where('module_id',$this->__data['record']->id)->get();

        return $this->__cbAdminView($this->__detailView, $this->__data);
    }

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
        $this->__data['contracts'] = Contract::with('organization', 'userestimates', 'userestimates.items')->get();
        $this->__data['estimates'] = Estimate::with('client')->get();
        // dd($this->_data['contracts']);
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
        $delete_estimate_item_tax = UserEstimateItemTax::where('estimate_tax_id', $get_estimate_tax->id)->where('user_estimate_item_id', $request->product_id)->delete();

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
        $insert_tax = EstimateTax::create([
                'estimate_id' => $get_estimate->id,
                'name'  => $request->tax_name,
                'percent'        => $request->tax_percent,
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
                        ->where('is_modify', 1)
                        ->update(['is_modify' => 0]);
                        
                    EstimateTax::where('estimate_id', $get_estimate->id)
                        ->where('is_modify', 1)
                        ->update(['is_modify' => 0]);
                    
                    EstimateInstallment::where('estimate_id', $get_estimate->id)
                        ->where('is_modify', 1)
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
                            'name' => $item->name,
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                            'unit' => $item->unit,
                            'total_price' => $item->total_price,
                        ]);
                        ContractItem::create([
                            'contract_id' => $request->cont_id,
                            'name' => $item->name,
                            'quantity' => $item->quantity,
                            'unit' => $item->unit,
                            'price' => $item->price,
                            'total_price' => $item->total_price,
                            'taxes' => $item->tax,
                            'product_price' => $item->product_price,
                            'gratuity' => $item->gratuity,
                            'accepted_by_client' => 1,
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




}
