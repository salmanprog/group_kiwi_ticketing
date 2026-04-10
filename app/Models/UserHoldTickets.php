<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use DB;

class UserHoldTickets extends Model
{
    use SoftDeletes,CRUDGenerator;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_hold_tickets';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'estimate_id','hold_date','expiry_date','created_at', 'updated_at', 'deleted_at','auth_code','slug','order_slug'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * It is used to enable or disable DB cache record
     * @var bool
     */
    protected $__is_cache_record = false;

    /**
     * @var
     */
    protected $__cache_signature;

    /**
     * @var string
     */
    protected $__cache_expire_time = 1; //days
    
    public function user_hold_ticket_items()
    {
        return $this->hasMany(UserHoldTicketItems::class , 'user_hold_ticket_id', 'id');
    }

  public static function createOrderPayload($request)
    {
        // Get estimate, company and client data
        $estimate = Estimate::where('id', $request['estimate_id'])->first();
        
        if (!$estimate) {
            return ['error' => 'Estimate not found'];
        }
        
        $company = Company::where('id', $estimate->company_id)->first();
        $client = Client::where('client_id', $estimate->client_id)->first();
        
        if (!$client) {
            return ['error' => 'Client not found'];
        }
        
        // Initialize purchases array
        $purchases = [];

        // Get hold tickets with relationships
        $HoldTickets = UserHoldTickets::with('user_hold_ticket_items.hold_ticket_item_seats')
            ->where('estimate_id', $estimate->id)
            ->first();


        $session_id = 0;
        
        if (!$HoldTickets) {
            Log::warning('No hold tickets found for estimate: ' . $estimate->id);
        } else {
            // Process each hold ticket item
            foreach ($HoldTickets->user_hold_ticket_items as $hold_ticket_item) {
                if ($hold_ticket_item->session_id != '0') {
                    $session_id = $hold_ticket_item->session_id;
                }

                // Determine ticket type/slug
                $ticketType = $hold_ticket_item->slug ?? $hold_ticket_item->hold_ticket_item_product_id;
                            // dd($ticketType);

                  $purchase = [
                        "ticketType" => $ticketType,
                        "capacityId" => $hold_ticket_item->capacity_id,
                        "VisualId" => null,
                        "ParentVisualId" => null,
                        "VisualIdStockCount" => 0,
                        "quantity" => (int)$hold_ticket_item->quantity,
                        "amount" => (float)$hold_ticket_item->price,
                        "FirstName" => $client->first_name,
                        "LastName" => $client->last_name,
                        "IsSeasonPass" => ($hold_ticket_item->category == "Season Passes" ? 1 : 0),
                        "ticketExpiryDate" => $HoldTickets['expiry_date'] ?? null,
                        "DOB" => null,
                        "WavierForm" => "0",
                        "IsTicketUpgraded" => 0,
                        "TicketUpgradedNotes" => "",
                        "QrCodeImage" => "",
                        "SeasonPassEmail" => "",
                        "AccessQrCodeWithThisEmail" => null,
                        "AccessQrCodeAssigningDateTime" => null,
                        "IsSeaonPassRenewal" => 0,
                        "IsTicketGiftCard" => 0
                    ];

                if ($hold_ticket_item->hold_ticket_item_seats->isEmpty()) {
                    // Case 1: No seats - create single purchase for the quantity
                  
                    $purchase['sectionId'] = "0";
                    // Add to purchases array
                    $purchases[] = $purchase;
                    Log::info('Added purchase without seats', [
                        'ticketType' => $ticketType,
                        'quantity' => $hold_ticket_item->quantity
                    ]);
                    
                } else {
                    $seat_count = 0;
                    $seats ="";
                    // Case 2: Has seats - create one purchase per seat
                    foreach ($hold_ticket_item->hold_ticket_item_seats as $seat) {
                     
                        $seat_count++;
                        // Add each seat purchase to array
                        $seats .= $seat->sectionId . ($seat_count < count($hold_ticket_item->hold_ticket_item_seats) ? "," : "");
                    }
                    $purchase['sectionId'] = $seats;
                    $purchases[] = $purchase;                    
                    Log::info('Added purchases with seats', [
                        'ticketType' => $ticketType,
                        'seats_count' => count($hold_ticket_item->hold_ticket_item_seats)
                    ]);
                }
            }
        }

        // Ensure purchases is not empty
        if (empty($purchases)) {
            Log::error('No purchases generated for estimate: ' . $estimate->id);
            return ['error' => 'No purchases generated for estimate: ' . $estimate->id];
        }

        $installment =  \App\Models\InstallmentPlan::with('payments')->where('estimate_id', $estimate->id)->first();
    

        // Build the complete payload
        $payload = [
            "AuthCode" => $request['user']->auth_code ?? null,
            "sessionId" => $session_id,
            "OrderId" => (string)$estimate->slug,
            "PromoCode" => $request->promo_code ?? "",
            "OrderSource" => "Groups",
            "PreviousOrderNumber" => $request->previous_order_number ?? null,
            "IsterminalPayment" => (bool) false,
            "OrderCreationWithScript" => (int)($request->order_creation_with_script ?? 0),
            "isOfficeUse" => false,
            "StaffDiscount" => (float)($request->staff_discount ?? 0.0),
            "IsAnyDayOrder" => (int)($request->is_any_day_order ?? 0),
            "CompanyName" => $company->name ?? null,
            "ImportOrders" => (int)($request->import_orders ?? 0),
            "OrderCreationDate" => Carbon::now()->toIso8601String(),
            "IsPaymentThroughSubscriptionPlan" => (int)($request->is_payment_through_subscription_plan ?? 0),
            "isContractBasedGroupOrder" => 1,
            "TotalInstallments" => (int)($request->total_installments ?? 0),
            "installmentType" => $request->installment_type ?? null,
            "IsCashlessEnabled" => (int)($request->is_cashless_enabled ?? 0),
            "campaignName"=> "kiwigroup",
            "campaignSlug"=>"kiwi-group",
            "Customer" => [
                "firstName" => $client->first_name,
                "lastName" => $client->last_name,
                "email" => $client->email,
                "phone" => $client->mobile_no ?? "-"
            ],
            "Purchases" => $purchases,
        ];

        // Add payment data if payment method exists
        // if (isset($request->payment_method) && !empty($request->payment_method)) {
            $payload['Payment'] = [
                "cardholerName" => $request->cardholderName ?? "Bradd Pitt",
                "billingStreet" => $request->billingStreet ?? "California , Florida , CA",
                "billingZipCode" => $request->billingZipCode ?? "90001",
                "expDate" => $request->expDate ?? "Omitted",
                "paymentCode" => $request->paymentCode ?? "32",
                "amount" => (float)($installment->total_amount ?? 0.0),
                "StaffTip" => (float)($request->StaffTip ?? 0.0),
                "Tax" => (float)($request->Tax ?? 0.0),
                "ServiceCharges" => (float)($request->ServiceCharges ?? 0.0),
                "TransactionId" => $request->TransactionId ?? "",
                "ccNumber" => $request->ccNumber ?? "Omitted",
                "cvn" => $request->cvn ?? "Omitted",
                "PaymentMethodId" => $request->PaymentMethodId ?? "pm_1Ss0HgEyfVF19QwA3a6viasp"
            ];
        // }



        // dd($installment);

        // Add group subscription plan if needed
        // if (isset($request->group_subscription_plan) || isset($request->subscription_start_date)) {
        // dd($installment);
            $payload['groupSubscriptionPlan'] = [
                "totalAmountOfContract" => (float)($installment->total_amount ?? 0),
                "initialPayment" => (float)($installment->total_amount ?? 0),
                "subscriptionStartDate" => $installment->start_date ?? $request->subscription_start_date ?? null,
                "subscriptionEndDate" => $installment->end_date ?? $request->subscription_end_date ?? null,
                "totalInstallments" => (int)($installment->installment_count ?? 0),
                "invoices" => ($installment->payments) ? $installment->payments->map(function($item) {
                    return [
                        'invoiceId' => (string) $item->id,
                        'paymentMethod' => "online",
                        'invoiceStatus' => $item->status,
                        'paymentIntent' => $item->due_date,
                        "invoiceSubmissionDate"=>$item->due_date ,
                    ];
                })->toArray() : [],
            ];
        // }

        // Add EasyPayPlanContractSignature if exists
        if (isset($request->signature)) {
            $payload['EasyPayPlanContractSignature'] = $request->signature;
        }

        // Log the final payload for debugging
        Log::info('Order payload created', [
            'estimate_id' => $estimate->id,
            'purchases_count' => count($purchases),
            'has_payment' => isset($payload['Payment']),
            'payload_keys' => array_keys($payload)
        ]);

        return $payload;
    }

    public static function updateOrderPayload($data)
    {
        // Initialize purchases array
        $ticketChanges = [];

        $contractModified = ContractModified::find($data['contract_modify_id']);
        $contract = Contract::find($contractModified->contract_id);
        $estimate = Estimate::where('contract_id', $contractModified->contract_id)->first();

        // Get hold tickets with relationships
        $HoldTickets = UserHoldTickets::with([
            'user_hold_ticket_items' => function ($query) use ($data) {
                $query->where('modified_contract_id', $data['contract_modify_id']);
            }
        ])
            ->where('estimate_id', $estimate->id)
            ->first();

        $session_id = 0;

        if (!$HoldTickets) {
            Log::warning('No hold tickets found for estimate: ' . $estimate->id);
            $ticketChanges = [];
        } else {
            foreach ($HoldTickets->user_hold_ticket_items as $hold_ticket_item) {
                // Get session ID (only if not zero)
                $session_id = ($hold_ticket_item->session_id != '0') ? $hold_ticket_item->session_id : null;

                // Determine ticket type/slug
                $ticketType = $hold_ticket_item->slug ?? $hold_ticket_item->hold_ticket_item_product_id;

                // Check if tickets have seats
                if ($hold_ticket_item->hold_ticket_item_seats->isEmpty()) {
                    // Case 1: No seats - create single entry for the quantity
                    $ticketChanges[] = [
                        "ticketType" => $ticketType,
                        "visualId" => "string",
                        "date" => $contract->event_date,
                        "sectionId" => "0",
                        "capacityId" => $hold_ticket_item->capacity_id,
                        "amount" => $hold_ticket_item->quantity,
                        "isSeasonPass" => 0
                    ];

                    Log::info('Added purchase without seats', [
                        'ticketType' => $ticketType,
                        'quantity' => $hold_ticket_item->quantity
                    ]);
                } else {
                    // Case 2: Has seats - create one entry per seat
                    foreach ($hold_ticket_item->hold_ticket_item_seats as $seat) {
                        $ticketChanges[] = [
                            "ticketType" => $ticketType,
                            "visualId" => "string",
                            "date" => $contract->event_date,
                            "sectionId" => $seat->sectionId,
                            "capacityId" => $hold_ticket_item->capacity_id,
                            "amount" => 1,
                            "isSeasonPass" => 0
                        ];
                    }

                    Log::info('Added purchases with seats', [
                        'ticketType' => $ticketType,
                        'seatCount' => count($hold_ticket_item->hold_ticket_item_seats),
                        'seatIds' => implode(',', $hold_ticket_item->hold_ticket_item_seats->pluck('sectionId')->toArray())
                    ]);
                }
            }
        }

        // Now $ticketChanges is ready to use in your payload

        $installment = \App\Models\InstallmentPlan::with('payments')->where('contract_modified_id', $data['contract_modify_id'])->first();
        $payload = [
            "sessionId" => $session_id,
            "previousOrderNumber" => $estimate->slug,
            "orderNumber" => $estimate->slug,
            "transactionId" => "string",
            "authCode" => $data['authCode'],
            "isterminalPayment" => true,
            "posStaffIdentity" => "string",
            "date" => $estimate->event_date,
            "makeThisAddonsAsChild" => true,
            "isPaymentThroughSubscriptionPlan" => 0,
            "totalInstallments" => 0,
            "installmentType" => "string",
            "isModifyingExistingOrder" => 0,
            "ticketChanges" => $ticketChanges,
            "payment" => [
                "cardholerName" => "string",
                "billingStreet" => "string",
                "billingZipCode" => "string",
                "expDate" => "string",
                "paymentCode" => "string",
                "amount" => 0,
                "staffTip" => 0,
                "tax" => 0,
                "serviceCharges" => 0,
                "transactionId" => "string",
                "ccNumber" => "string",
                "cvn" => "string",
                "paymentMethodId" => "string"
            ],
            "easyPayPlanContractSignature" => "string"
        ];

        return $payload;
    }

    public static function createUpdateInvoicePayload($data)
    {

        $paidDate = !empty($data['paid_date'])
            ? Carbon::parse($data['paid_date'])->toIso8601String()
            : Carbon::now()->toIso8601String();

        $dueDate = !empty($data['due_date'])
            ? Carbon::parse($data['due_date'])->toIso8601String()
            : null;

        $payload = [
            "subscriptionId" => (string) $data['subscription_id'] ?? "0",
            "subscriptionStatus" => $data['status'],
            "subscriptionEndDate" => $dueDate,
            "numberOfInstallments" => (int) $data['number_of_Installments'],
            "isSubscriptionCompleted" => false,
            "invoices" => [
                [
                    "stripeInvoiceId" => $data['invoice_id'],
                    "paymentIntentId" => $data['payment_intent_id'] ?? "",
                    "amountPaid" => $data['amount'],
                    "invoiceStatus" => $data['status'],
                    "notes" => $data['notes'],
                    "paidDate" => $paidDate,
                    "paymentMethod" => $data['payment_method'],
                    "amountDue" => $data['amount_due'] ?? 0,
                    "dueDate" => $dueDate,
                ]
            ]
        ];

        return $payload;
    }
}
