<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{Manager,Product};

class UserEstimateItemController extends CRUDCrontroller
{
    public function __construct(Request $request)
    {
        parent::__construct('UserEstimateItem');
        $this->__request = $request;
        $this->__data['page_title'] = 'Events';
        $this->__indexView = 'event.index';
        $this->__createView = 'event.add';
        $this->__editView = 'event.edit';
        // $this->__detailView = 'event.detail';
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
        ];
        switch ($action) {
            case 'POST':
                $validator = Validator::make($this->__request->all(), [
                    'name' => 'required|min:2|max:50',
                ], $custom_messages);

                break;
            case 'PUT':
                $validator = Validator::make($this->__request->all(), [
                    '_method' => 'required|in:PUT',
                    'name' => 'required|min:2|max:50',
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
        $options = '<a href="' . route('event-type.edit', ['event_type' => $record->slug]) . '" title="Edit" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>';
        $options .= '<a title="Delete" class="btn btn-xs btn-danger _delete_record" data-slug="' . $record->slug . '"><i class="fa fa-trash" ></i></a>';

        return [
            $record->name,
            $record->status == 1 ? '<span class="btn btn-xs btn-success">Active</span>' : '<span class="btn btn-xs btn-danger">Disabled</span>',
            // date(config("constants.ADMIN_DATE_FORMAT") , strtotime($record->created_at)),
            $options
        ];
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


    public function show($slug)
    {


    }


    public function store()
    {
        $request = $this->__request;
        $ids = $request->input('ids', []); // array of selected product IDs
        $userEstimateId = $request->input('user_estimate_id');

        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No products selected.'
            ]);
        }

        $products = Product::whereIn('id', $ids)->get();
        $createdItems = [];

        foreach ($products as $product) {
            $productItem = \App\Models\EstimateItem::create([
                'user_estimate_id' => $userEstimateId, // adjust as needed
                'name' => $product->name,
                'quantity' => 1,
                'unit' => 'each',
                'product_price' => $product->price,
                'price' => $product->price,
                'total_price' => 0, // you can calculate later
                'tax' => 0,
                'gratuity' => 0
            ]);

            $createdItems[] = $productItem;
        }

        return response()->json([
            'success' => true,
            'message' => 'Products added successfully!',
            'data' => $createdItems
        ]);
    }



    public function storeEstimateTaxes(Request $request)
    {
        $request = $this->__request;
        $estimateId = $request->input('estimate_id');
        $tax = \App\Models\EstimateTax::updateOrCreate(
            ['id' => $request['id'] ?? null],
            [
                'name' => $request['name'],
                'estimate_id'=>$estimateId,
                'percent' => $request['percent'],
                'amount'=>0
            ]
        );

        if($request->product_ids)
        {
            
            foreach ($request->product_ids as $key => $value) {
                 \App\Models\UserEstimateItemTax::updateOrCreate(
                        ['user_estimate_item_id' => $value],
                        [
                            'estimate_tax_id' => $tax->id,
                            'name' => $tax->name,
                            'percentage' => $tax->percent,
                            'amount' => $tax->amount
                        ]
                    );
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Tax saved successfully!',
            'data' => $tax
        ]);
    }


    public function removeTax(Request $request)
    {
        $request->validate([
            'tax_uid' => 'required|integer'
        ]);

        $tax = \App\Models\EstimateTax::find($request->tax_uid);

        if ($tax) {

            \App\Models\UserEstimateItemTax::where(
                'estimate_tax_id',
                $tax->id
            )->delete();

            $tax->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Tax removed successfully!',
            'data' => $request->tax_uid
        ]);
    }




}
