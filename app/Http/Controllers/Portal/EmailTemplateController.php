<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{Manager};
use DB;
use Auth;

class EmailTemplateController extends CRUDCrontroller
{
    public function __construct(Request $request)
    {
        parent::__construct('EmailTemplate');
        $this->__request = $request;
        $this->__data['page_title'] = 'Email Templates';
        $this->__indexView = 'email_template.index';
        $this->__createView = 'email_template.add';
        $this->__editView = 'email_template.edit';
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
        $options = '<a href="' . route('email-template.edit', ['email_template' => $record->slug]) . '" title="Edit" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>';

        return [
            $record->identifier,
            $record->to_emails,
            $record->subject,
            // $record->status == 1 ? '<span class="btn btn-xs btn-success">Active</span>' : '<span class="btn btn-xs btn-danger">Disabled</span>',
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


    public function smtpConfigView()
    {
        $this->__data['smtp'] = DB::table('user_smtp_settings')->where('auth_code',Auth::user()->auth_code)->first();
        return $this->__cbAdminView('email_template.email_config',$this->__data);

    }

    public function createOrupdate(Request $request)
    {
        $request->validate([
            'mail_driver' => 'nullable|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer',
            'mail_username' => 'required|string',
            'mail_password' => 'required|string',
            'mail_encryption' => 'nullable|string',
            'mail_no_replay' => 'required|email',
        ]);

        DB::table('user_smtp_settings')->updateOrInsert(
                ['auth_code' => Auth::user()->auth_code], // condition
                [
                    'mail_driver'    => $request->mail_driver,
                    'mail_host'      => $request->mail_host,
                    'mail_port'      => $request->mail_port,
                    'mail_username'  => $request->mail_username,
                    'mail_password'  => $request->mail_password,
                    'mail_encryption'=> $request->mail_encryption,
                    'mail_no_replay' => $request->mail_no_replay,
                    'updated_at'     => now(),
                    'created_at'=> now(),
                ]
            );

    return redirect()->back()->with('success', 'SMTP settings saved successfully!');   
 }



}
