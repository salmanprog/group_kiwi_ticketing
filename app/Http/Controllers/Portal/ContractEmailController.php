<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{Manager,ContractEmail};
use Auth;

use DB;
use Carbon\Carbon;
class ContractEmailController extends CRUDCrontroller
{
    public function __construct(Request $request)
    {
        parent::__construct('ContractEmail');
        $this->__request = $request;
        $this->__data['page_title'] = 'Contract Emails';
        $this->__indexView = 'contract-email.index';
        $this->__createView = 'contract-email.add';
        $this->__editView = 'contract-email.edit';
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
        $options = '<a href="' . route('contract-email.edit', ['contract_email' => $record->slug]) . '" title="Edit" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>';
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
    
    public function addContractEmail()
    {
        $this->__data['title'] = 'Add Contract Email';
        $this->__data['data'] = ContractEmail::where('auth_code', Auth::user()->auth_code)->get();
        return $this->__cbAdminView('contract-email.index',$this->__data); 
    }

  
    public function emailStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'auth_code' => 'required|string|max:100',
        ]);

        $check = ContractEmail::where('email', $request->email)->where('auth_code', Auth::user()->auth_code)->first();
        if($check){
            return response()->json([
                'success' => false,
                'message' => 'Email already exists!',
            ]);
        }

        $email = ContractEmail::create([
            'name' => $request->name,
            'email' => $request->email,
            'auth_code' => Auth::user()->auth_code,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contract email added successfully!',
            'data' => [
                'name' => $email->name,
                'email' => $email->email,
                'created_at' => $email->created_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }


   public function SearchajaxListing(Request $request)
    {
         $keyword = $request->keyword ?? '';
    $authCode = Auth::user()->auth_code;

    $emailsQuery = DB::table('contract_emails')
        ->select('name', 'email', 'created_at')
        ->where('auth_code', $authCode)
        ->whereNull('deleted_at');

    if (!empty($keyword)) {
        $emailsQuery->where(function($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('email', 'like', "%{$keyword}%");
        });
    }

    $emails = $emailsQuery
        ->orderByDesc('id')
        ->get();

    // Format created_at
    $emails = $emails->map(function($item) {
        $item->created_at = Carbon::parse($item->created_at)->format('Y-m-d H:i:s');
        return $item;
    });

    return response()->json(['data' => $emails]);
    }


    public function emailDelete($id)
    {
        $email = ContractEmail::where('id', $id)->where('auth_code', Auth::user()->auth_code)->first();
        if($email){
            $email->delete();
            return redirect()->back()->with('success', 'Contract email deleted successfully!');
        }
        return redirect()->back()->with('error', 'Contract email not found!');
    }
}
