<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{EstimateItem,Product,EstimateTax,UserEstimateItemTax,EstimateInstallment};
use Auth;
use Illuminate\Support\Facades\Crypt;
use DB;


class EstimateInstallmentController extends CRUDCrontroller
{
    public function __construct(Request $request)
    {
        parent::__construct('EstimateInstallment');
        $this->__request = $request;
        $this->__data['page_title'] = 'Estimate Tax';
        $this->__indexView = 'estimate.index';
        $this->__createView = 'estimate.add';
        $this->__editView = 'estimate.edit';
        $this->__detailView = 'estimate.detail';
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
            'client_id.required' => 'Please select client',
        ];
        switch ($action) {
            case 'POST':
                $validator = Validator::make($this->__request->all(), [
                    'client_id' => 'required',
                    'estimate_date' => 'required',
                    'estimate_expiry_date' => 'nullable|date|after:estimate_date',
                ], $custom_messages);

                break;
            case 'PUT':
                $validator = Validator::make($this->__request->all(), [
                    '_method' => 'required|in:PUT',
                    'client_id' => 'required',
                    'estimate_date' => 'required',
                    'estimate_expiry_date' => 'nullable|date|after:estimate_date',
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
        
    }

    /**
     * This function is used for before the create view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     */
    public function beforeRenderCreateView()
    {
        
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
    public function beforeDeleteLoadModel()
    {
    }

    public function savePaymentSchedule(Request $request, $estimateId)
    {
        $request->validate([
            'installments' => 'required|array',
            'installments.*.amount' => 'required|numeric|min:0.01',
            'installments.*.date' => 'required|date',
        ]);

        $estimate = EstimateInstallment::where('estimate_id',$estimateId)->delete();

        // Remove old installments (soft delete)
        //$estimate->installments()->delete();

        foreach ($request->installments as $inst) {
            EstimateInstallment::create([
                'estimate_id' => $estimateId,
                'amount' => $inst['amount'],
                'installment_date' => $inst['date'],
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Payment schedule saved successfully!'
        ]);
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
            $totalTaxAmount += ($product['price'] * $taxPercent / 100);
        }

        try {
            // Update main tax record
            \DB::table('user_estimate_taxes')
                ->where('id', $taxId)
                ->update([
                    'name' => $taxName,
                    'percent' => $taxPercent,
                    'amount' => $totalTaxAmount,
                    'updated_at' => now(),
                ]);

            // Remove old product-tax relations for this tax
            \DB::table('user_estimate_item_taxes')
                ->where('estimate_tax_id', $taxId)
                ->delete();

            // Re-insert updated products
            foreach ($products as $product) {
                \DB::table('user_estimate_item_taxes')->insert([
                    'estimate_tax_id' => $taxId,
                    'user_estimate_item_id' => $product['id'],
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
            \DB::table('user_estimate_item_taxes')->where('estimate_tax_id', $taxId)->delete();
            \DB::table('user_estimate_taxes')->where('id', $taxId)->delete();

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


    public function getItem(Request $request)
    {
        $item = EstimateItem::where('user_estimate_id', $request->estimate_id)->get();

        return response()->json([
            'status' => true,
            'item' => $item
        ]);
    }

    public function editGetItem(Request $request)
    {
        $items = EstimateItem::with('itemTaxes')
            ->where('user_estimate_id', $request->estimate_id)
            ->get();

        $tax = UserEstimateItemTax::where('estimate_tax_id', $request->tax_id)
            ->first();

        return response()->json([
            'status' => true,
            'item' => $items,
            'tax' => $tax
        ]);
    }





}
