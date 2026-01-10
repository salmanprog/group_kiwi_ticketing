<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{Manager};

class ProductCategoryController extends CRUDCrontroller
{
    public function __construct(Request $request)
    {
        parent::__construct('ProductCategory');
        $this->__request    = $request;
        $this->__data['page_title'] = 'Product Category';
        $this->__indexView  = 'product-category.index';
        $this->__createView = 'product-category.add';
        $this->__editView   = 'product-category.edit';
        $this->__detailView = 'product-category.detail';
    }

    /**
     * This function is used for validate data
     * @param string $action
     * @param string $slug
     * @return array|\Illuminate\Contracts\Validation\Validator
     */
    public function validation(string $action, string $slug=NULL)
    {
         $validator = [];
        $custom_messages = [
        ];
        switch ($action){
            case 'POST':
                $validator = Validator::make($this->__request->all(), [
                    'name'             => 'required|min:2|max:50',
                ],$custom_messages);
                    
                break;
            case 'PUT':
                $validator = Validator::make($this->__request->all(), [
                    '_method'   => 'required|in:PUT',
                    'name'             => 'required|min:2|max:50',
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
        $options  = '<a href="'. route('product-category.edit',['product_category' => $record->slug ]) .'" title="Edit" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>';
        $options .= '<a title="Delete" class="btn btn-xs btn-danger _delete_record" data-slug="'.$record->slug.'"><i class="fa fa-trash"></i></a>';
        return [
            $record->name,
            date(config("constants.ADMIN_DATE_FORMAT") , strtotime($record->created_at)),
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
        $this->__success_store_message   = 'Product Category created successfully';
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
}
