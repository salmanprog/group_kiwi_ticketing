<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{ProductCategory,CompanyUser};
use Auth;

class ProductController extends CRUDCrontroller
{
    public function __construct(Request $request)
    {
        parent::__construct('Product');
        $this->__request    = $request;
        $this->__data['page_title'] = 'Product';
        $this->__indexView  = 'product.index';
        $this->__createView = 'product.add';
        $this->__editView   = 'product.edit';
        //$this->__detailView = 'folder_name.detail_file_name';
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
        switch ($action){
            case 'POST':
                $validator = Validator::make($this->__request->all(), [
                    'name' => 'required',
                    'company_product_category_id'   => 'required',
                    'unit'   => 'required',
                    'price'   => 'required|numeric',
                    'description'   => 'nullable',
                ]);
                break;
            case 'PUT':
                $validator = Validator::make($this->__request->all(), [
                    '_method'   => 'required|in:PUT',
                    'name' => 'required',
                    'company_product_category_id'   => 'required',
                    'unit'   => 'required',
                    'price'   => 'required|numeric',
                    'description'   => 'nullable',
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
        $options  = '<a href="'. route('product.edit',['product' => $record->slug]) .'" title="Edit" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>';
        $options .= '<a title="Delete" class="btn btn-xs btn-danger _delete_record" data-slug="'.$record->slug.'"><i class="fa fa-trash" ></i></a>';
        return [
            $record->category_name,
            $record->name,
            $record->price,
            $record->unit,
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
        $company = CompanyUser::getCompany(Auth::user()->id); 
        $this->__data['categories'] = ProductCategory::where('company_id',$company->id)->get();
    }

    /**
     * This function is called before a model load
     */
    public function beforeStoreLoadModel()
    {
        $this->__success_store_message   = 'Product created successfully';
    }

    /**
     * This function is used for before the edit view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     * @param string @slug
     */
    public function beforeRenderEditView($slug)
    {
        $company = CompanyUser::getCompany(Auth::user()->id); 
        $this->__data['categories'] = ProductCategory::where('company_id',$company->id)->get();

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
}
