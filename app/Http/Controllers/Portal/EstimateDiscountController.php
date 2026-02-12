<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{EstimateItem,Product,EstimateTax,UserEstimateItemTax,EstimateDiscount};
use Auth;
use Illuminate\Support\Facades\Crypt;
use DB;


class EstimateDiscountController extends CRUDCrontroller
{
    public function __construct(Request $request)
    {
        parent::__construct('EstimateDiscount');
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

    public function productDiscountAdd(Request $request)
    {
        // Validate the request
        $request->validate([
            'user_estimate_id' => 'required|integer',
            'products.*.discount_name' => 'required|string',
            'products.*.discount_value' => 'required|numeric|min:0',
        ]);

        $estimateId = $request->input('user_estimate_id');
        $products = $request->input('products');

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
            $discountId = \DB::table('user_estimate_discounts')->insertGetId([
                'estimate_id' => $estimateId,
                'name' => $products[0]['discount_name'],
                'type' => 'percent',
                'value' => $products[0]['discount_value'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Optionally attach discount to each product
            // foreach ($products as $product) {
            //     \DB::table('user_estimate_item_discounts')->insert([
            //         'discount_id' => $discountId,
            //         'user_estimate_item_id' => $product['id'],
            //         'name' => $product['discount_name'],
            //         'type' => $product['discount_type'],
            //         'value' => $product['discount_value'],
            //         'created_at' => now(),
            //         'updated_at' => now(),
            //     ]);
            // }

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




    public function updateDiscount(Request $request)
    {
        $request->validate([
            'discount_id' => 'required',
            'estimate_id' => 'required',
            'name'        => 'required|string|max:255',
            'value'       => 'required|numeric|min:0',
        ]);

        $discount = EstimateDiscount::where('id', $request->discount_id)
            ->where('estimate_id', $request->estimate_id)
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
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Discount updated successfully',
            'item'    => $discount
        ]);
    }

    public function deleteDiscount(Request $request, $id)
    {
        $discount = EstimateDiscount::find($id);

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


    public function getItem(Request $request)
    {
        $item = EstimateDiscount::where('id', $request->id)->get();

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
