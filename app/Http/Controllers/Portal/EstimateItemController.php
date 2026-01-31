<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{EstimateItem,Product};
use Auth;
use Illuminate\Support\Facades\Crypt;
use DB;


class EstimateItemController extends CRUDCrontroller
{
    public function __construct(Request $request)
    {
        parent::__construct('EstimateItem');
        $this->__request = $request;
        $this->__data['page_title'] = 'Estimate';
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

    public function productAdd(Request $request)
{
    // Validate the request
    $request->validate([
        'user_estimate_id' => 'required',
        'products' => 'required|array'
    ]);

    $estimateId = $request->input('user_estimate_id');
    $addedItems = [];
    $duplicateProducts = [];

    foreach ($request->products as $productData) {
        $product = Product::find($productData['product_id']);
        if (!$product) {
            continue; // skip if product not found
        }

        // Check if product already exists for this estimate
        $exists = EstimateItem::where('user_estimate_id', $estimateId)
                              ->where('product_id', $product->id)
                              ->first();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => "Product '{$exists['name']}' is already added to this estimate."
            ]);
        }

        $item = EstimateItem::create([
            'user_estimate_id' => $estimateId,
            'product_id' => $product->id,
            'name' => $product->name,
            'quantity' => (int) $productData['qty'],
            'unit' => $productData['unit'] ?? 'pcs',
            'price' => (float) $productData['price'],
            'total_price' => (int) $productData['qty'] * (float) $productData['price'],
            'product_price' => (float) $productData['price'],
            'tax' => 0,
            'gratuity' => 0,
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


    public function updateItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:10',
            'estimate_id' => 'required'
        ]);

        $item = EstimateItem::findOrFail($request->item_id);
        $item->quantity = $request->quantity;
        $item->unit = $request->unit;
        $item->price = $request->price;
        $item->total_price = $request->quantity * $request->price;
        $item->save();

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully',
            'item' => $item
        ]);
    }

    public function deleteItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'estimate_id' => 'required'
        ]);

        $item = EstimateItem::findOrFail($request->item_id);
        $item->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ]);
    }




}
