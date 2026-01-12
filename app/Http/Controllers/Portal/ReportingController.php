<?php

namespace App\Http\Controllers\Portal;

use App\Models\CmsRole;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{CompanyAdmin,Company};

class ReportingController extends CRUDCrontroller
{
    public function __construct(Request $request)
    {
        parent::__construct('Company');
        $this->__request    = $request;
        $this->__data['page_title'] = 'Reporting Management';
        $this->__indexView  = 'reporting.index';
        $this->__createView = '';
        $this->__editView   = '';
        $this->__detailView   = '';
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
                $validator = Validator::make($this->__request->all(), [],$custom_messages);
                    
                break;
            case 'PUT':
                $validator = Validator::make($this->__request->all(), [
                    '_method'   => 'required|in:PUT',
                    'status'    => 'required|in:0,1',
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
     * @param string $slug
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

    public function getAllCompanies()
    {
        $company = Company::with('companyAdmin.user')->get();
        $this->__data['company'] = $company;
        return $this->__cbAdminView('reporting.company',$this->__data);

    }

    public function ajaxListing($request = null)
    {
        $request = $request ?? request(); // fallback to current request
        $keyword = $request->input('keyword', '');

        $companies = Company::with('companyAdmin.user')->withCount('organizations','companymanager as manager_count','companysalesman as salesman_count','estimates as estimates_count')
            ->when($keyword, function ($query, $keyword) {
                $query->where('name', 'like', "%$keyword%")
                    ->orWhereHas('admin', function($q) use ($keyword) {
                        $q->where('name', 'like', "%$keyword%")
                            ->orWhere('email', 'like', "%$keyword%");
                    });
            })
            ->get();

        $data = $companies->map(function($company) {
            return [
                'company_name' => $company->name ?? '-',
                'owner_name' => optional($company->companyAdmin->user)->name ?? '-',
                'total_accounts' => $company->organizations_count,
                'total_salesman' => $company->salesman_count ?? 0,
                'total_manager' => $company->manager_count ?? 0,
                'total_estimate' => $company->estimates_count ?? 0,
                'status' => $company->status == 1 ? '<span class="status-badge status-active">Active</span>' : '<span class="status-badge status-inactive">Inactive</span>',
                'action' => '<a href="" class="btn-action btn-edit"><i class="fas fa-edit"></i></a>'
            ];
        });

        return response()->json([
            'data' => $data,
            'total' => $companies->count()
        ]);
    }

}
