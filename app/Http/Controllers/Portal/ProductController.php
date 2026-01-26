<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{ProductCategory,CompanyUser,Product};
use App\Services\ThirdPartyApiService;
use Auth;

class ProductController extends CRUDCrontroller
{
    protected $apiService;

    public function __construct(Request $request, ThirdPartyApiService $apiService)
    {
        parent::__construct('Product');
        $this->__request    = $request;
        $this->apiService = $apiService;
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
        // $response = $this->apiService->getTicketPricingRecord();

        // if ($response->successful()) {
        //     $this->__data['ticketPricing'] = $response->json();
        // } else {
        //     $this->__data['ticketPricing'] = [];
        // }

    }

    /**
     * This function is used to add data in datatable
     * @param object $record
     * @return array
     */
    public function dataTableRecords($record)
    {
        $options  = '<a href="'. route('product.api.edit',$record->slug) .'" title="Edit" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>';
        //$options .= '<a title="Delete" class="btn btn-xs btn-danger _delete_record" data-slug="'.$record->slug.'"><i class="fa fa-trash" ></i></a>';
        return [
            //$record->category_name,
            $record->name,
            $record->price,
            $record->unit,
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

    // Show Create Form
    public function createApi()
    {
        return view('portal.product.api-create');
    }

    // Store New Ticket
    public function storeApi(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'ticketName'  => 'required|string',
            'ticketPrice' => 'required|numeric',
            'ticketType'  => 'required|string',
            'saleChannel' => 'required|string',
        ]);

        $company = CompanyUser::getCompany(Auth::user()->id);

        // Prepare DB data
        $dbData = [
            'company_id' => $company->id,
            'company_product_category_id' => 0, // or choose default
            'name'       => $validated['ticketName'],
            'description'=> $validated['ticketName'],
            'price'      => $validated['ticketPrice'],
            'unit'       => 'Ticket',
            'status'     => '1',
            'slug'       => strtolower(str_replace(' ', '-', $validated['ticketName'])),
        ];

        $product = Product::create($dbData);

        // Sync to API
        $apiParams = [
            'TicketName'  => $validated['ticketName'],
            'TicketPrice' => $validated['ticketPrice'],
            'TicketType'  => $validated['ticketType'],
            'SaleChannel' => $validated['saleChannel'],
            'UserId'      => 'dev', // dynamic if needed
            'TicketId'    => 0,     // 0 means new
        ];

        $apiResponse = $this->apiService->get('StaticTicketPricing/AddTicketPricing', $apiParams);

        if ($apiResponse->failed()) {
            return redirect()->back()->with('error', 'Ticket created locally, but failed to sync API.');
        }

        $apiData = $apiResponse->json();
        if (isset($apiData['data']['ticketSlug'])) {
            $product->update([
                'slug' => $apiData['data']['ticketSlug']
            ]);
        }

        return redirect()->route('product.index')->with('success', 'Ticket created and synced with API.');
    }


    public function editApi($slug)
    {
        $response = $this->apiService->getTicketPricingRecord();

        $ticket = collect($response->json()['data'])
            ->firstWhere('ticketSlug', $slug);

        abort_if(!$ticket, 404);

        return view('portal.product.api-edit', compact('ticket'));
    }

    public function updateApi(Request $request, $id)
    {
        // Validate incoming request
        $validated = $request->validate([
            'ticketName'  => 'required|string|max:255',
            'ticketPrice' => 'required|numeric',
        ]);

        // Fetch ticket from API
        $response = $this->apiService->getTicketPricingRecord();
        $ticket = collect($response->json()['data'])->firstWhere('id', $id);

        if (!$ticket) {
            return redirect()->back()->with('error', 'Ticket not found.');
        }

        $company = CompanyUser::getCompany(Auth::user()->id);

        // Local DB update or create
        $product = Product::updateOrCreate(
            ['slug' => $ticket['ticketSlug'] ?? strtolower(str_replace(' ', '-', $validated['ticketName']))],
            [
                'company_id'                  => $company->id,
                'company_product_category_id' => 0, // you can set default or dynamic category
                'name'                        => $validated['ticketName'],
                'description'                 => $validated['ticketName'],
                'price'                       => $validated['ticketPrice'],
                'unit'                        => 'Ticket',
                'status'                      => '1',
            ]
        );

        // API update
        $apiParams = [
            'AuthCode'    => config('services.third_party.auth_code'),
            'TicketName'  => $validated['ticketName'],
            'TicketPrice' => $validated['ticketPrice'],
            'UserId'      => 'dev',
            'TicketType'  => $validated['ticketType'] ?? $ticket['ticketType'] ?? 'Tickets',
            'SaleChannel' => $validated['saleChannel'] ?? $ticket['saleChannel'] ?? 'Groups',
            'TicketId'    => $ticket['id'],
        ];

        $apiResponse = $this->apiService->get('StaticTicketPricing/AddTicketPricing', $apiParams);

        if ($apiResponse->failed()) {
            return redirect()->back()->with('error', 'Local DB updated, but failed to sync API.');
        }

        return redirect()->route('product.index')->with('success', 'Ticket updated locally and synced with API.');
    }



    // public function ajaxListing()
    // {
    //     $response = $this->apiService->getTicketPricingRecord();

    //     if ($response->failed()) {
    //         return response()->json(['data' => []]);
    //     }

    //     $tickets = $response->json()['data'] ?? [];

    //     $rows = [];

    //     foreach ($tickets as $ticket) {
    //         $rows[] = [
    //             $ticket['saleChannel'] ?? '-',
    //             $ticket['ticketName'],
    //             $ticket['ticketPrice'],
    //             'Ticket',
    //             '<a href="'.route('product.api.edit', $ticket['id']).'" class="btn btn-xs btn-primary">Edit</a>'
    //         ];
    //     }

    //     return response()->json([
    //         'data' => $rows
    //     ]);
    // }


}
