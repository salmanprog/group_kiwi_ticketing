<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{Estimate, UserHoldTickets,Product,UserHoldTicketItems};
use Auth;
use App\Services\ThirdPartyApiService;
use Carbon\Carbon;
class UserHoldTicketsController extends CRUDCrontroller
{
    protected $apiService;

    public function __construct(Request $request, ThirdPartyApiService $apiService)
    {
        parent::__construct('UserHoldTickets');
        $this->__request    = $request;
        $this->__data['page_title'] = 'User Hold Tickets';
        $this->__indexView  = 'user_hold_tickets.index';
        $this->__createView = 'user_hold_tickets.add';
        $this->__editView   = 'user_hold_tickets.edit';
        $this->__detailView = 'user_hold_tickets.detail';
        $this->apiService = $apiService;
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
                    'estimate_id'             => 'required',
                ],$custom_messages);
                    
                break;
            case 'PUT':
                $validator = Validator::make($this->__request->all(), [
                    '_method'   => 'required|in:PUT',
                    'estimate_id'             => 'required',
                    // 'name'             => 'required|min:2|max:50',
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
        $options  = '<a href="'.route('hold-tickets.show', $record->slug).'" title="Edit" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>';
        $options .= '<a href="'.route('hold-tickets.edit', $record->slug).'" title="Edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>';
        $options .= '<a href="'.route('hold-tickets.release', $record->slug).'" title="Release" class="btn btn-xs btn-primary" onclick="return confirm(Are you sure you want to release this ticket?)">Release</a>';
        return [
            $record->estimate_slug,
            date(config("constants.ADMIN_DATE_FORMAT") , strtotime($record->hold_date)),
            date(config("constants.ADMIN_DATE_FORMAT") , strtotime($record->expiry_date)),
            $options
        ];
    }

    /**
     * This function is used for before the create view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     */
    public function beforeRenderCreateView()
    {

        $this->__data['Estimates'] = Estimate::where('auth_code',  Auth::user()->auth_code)->get();
    }

    /**
     * This function is called before a model load
     */
    public function beforeStoreLoadModel()
    {
        $this->__success_store_message   = 'User Hold Tickets created successfully';
    }

    /**
     * This function is used for before the edit view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     * @param string @slug
     */
    public function beforeRenderEditView($slug)
    {
        $this->__data['record'] = UserHoldTickets::with('user_hold_ticket_items')->where('auth_code',  Auth::user()->auth_code)->where('slug', $slug)->first();
        $this->__data['Estimates'] = Estimate::where('auth_code',  Auth::user()->auth_code)->get();
        $this->__data['products'] = Product::where('auth_code',  Auth::user()->auth_code)->get();


        // dd($this->__data['products']);


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
         $this->__data['record'] = UserHoldTickets::with('user_hold_ticket_items')->where('auth_code',  Auth::user()->auth_code)->where('slug', $slug)->first();
        $this->__data['Estimates'] = Estimate::where('id',  $this->__data['record']->estimate_id)->first();
        $this->__data['products'] = Product::where('auth_code',  Auth::user()->auth_code)->get();

        return view('portal.user_hold_tickets.detail', $this->__data);
    }



    public function storeItem(Request $request)
    {
        // =========================
        // 1️⃣ Validate Request
        // =========================
        $request->validate([
            'product_slug' => 'required|string',
            'hold_date'    => 'required|date',
            'expiry_date'  => 'required|date',
            'quantity'     => 'required|integer|min:1',
            'estimate_id'  => 'required'
        ]);

        try {

            // =========================
            // 2️⃣ Prepare Data
            // =========================
            $productSlug = $request->product_slug;
            $holdDate    = $request->hold_date;
            $qty         = $request->quantity;

            // Convert expiry date to ISO 8601 format
            $expiryDate = Carbon::parse($request->expiry_date)
                ->toIso8601String();

            $body = [
                "SessionId" => "",
                "OrderId" => $request->estimate_id,
                "OrderSource" => "Groups",
                "TicketHoldItem" => [
                    [
                        "TicketType" => $productSlug,
                        "Quantity"   => $qty,
                    ]
                ],
                "holdExpiryDate" => $expiryDate,
            ];
            // dd($body);
            // =========================
            // 3️⃣ Call External API
            // =========================
            $response = $this->apiService->holdTicket(
                $holdDate,
                $body,
                Auth::user()->auth_code
            );

            $result = $response->json();
            // dd($result);

            // =========================
            // 4️⃣ Handle API Error
            // =========================
            if (
                isset($result['status']['errorCode']) &&
                $result['status']['errorCode'] != 0
            ) {
                return response()->json([
                    'status'  => false,
                    'message' => $result['status']['errorMessage'] ?? 'API Error'
                ], 422);
            }


            $session_id = $result['sessionId'];
            $capacity_id = $result['data'][0]['capacityId'];

            $product = Product::where('slug', $request->product_slug)->first();
           $userHoldTicketItem = UserHoldTicketItems::create([
                'capacity_id' => $capacity_id,
                'session_id' => $session_id,
                'user_hold_ticket_id' => $request->user_hold_ticket_id,
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => $qty,
                'price' => $product->price
            ]);

            // =========================
            // 5️⃣ Success Response
            // =========================
            return response()->json([
                'status'  => true,
                'message' => 'Ticket held successfully',
                'data'    => $userHoldTicketItem
            ]);

        } catch (\Exception $e) {

            // =========================
            // 6️⃣ System Error Handling
            // =========================
            return response()->json([
                'status'  => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }


  public function release($slug)
    {
        // Find the hold ticket record
        $record = UserHoldTickets::where('slug', $slug)->first();

        if (!$record) {
            return redirect()->route('user_hold_tickets.index')
                            ->with('error', 'Ticket not found.');
        }

        try {
            // Prepare body for release API
         $body = [
                "orderId" => (string) $record->order_id,   // ensure string
                "orderSource" => "Groups",
                "date" => null,
                "seatNo" => null,
                "ticketName" => $record->ticket_slug
            ];

            $response = $this->apiService->releaseTicket($body, Auth::user()->auth_code);
            // dd($response->json());

            if ($response->successful()) {
                $record->delete();

                return redirect()->back()
                                ->with('success', 'Ticket released successfully.');
            }

            $errorMsg = $response->json()['status']['errorMessage'] ?? 'Release API failed.';
            return redirect()->back()
                            ->with('error', $errorMsg);

        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()
                            ->with('error', 'Something went wrong while releasing the ticket.');
        }
    }



 



}
