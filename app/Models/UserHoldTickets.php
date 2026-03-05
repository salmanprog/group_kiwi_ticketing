<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        'estimate_id','hold_date','expiry_date','created_at', 'updated_at', 'deleted_at','auth_code','slug'
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
            throw new \Exception('Estimate not found');
        }
        
        $company = Company::where('id', $estimate->company_id)->first();
        $client = Client::where('client_id', $estimate->client_id)->first();
        
        if (!$client) {
            throw new \Exception('Client not found');
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
                
                // Determine ticket type/slug
                $ticketType = $hold_ticket_item->slug ?? $hold_ticket_item->hold_ticket_item_product_id;
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
                        "IsSeasonPass" => 0,
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
                    $session_id = $hold_ticket_item->session_id;
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
                        $seats .= $seat->sectionId . ",";
                    }
                    $purchase['sectionId'] = $seats;
                    $purchases[] = $purchase;
                    $session_id = $hold_ticket_item->session_id;
                    
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
            throw new \Exception('No purchases items found');
        }

        // Build the complete payload
        $payload = [
            "AuthCode" => $request['user']->auth_code ?? null,
            "sessionId" => $session_id,
            "OrderId" => (string)$request['estimate_id'],
            "PromoCode" => $request->promo_code ?? "",
            "OrderSource" => $request->order_source ?? "",
            "PreviousOrderNumber" => $request->previous_order_number ?? null,
            "IsterminalPayment" => (bool)($request->is_terminal_payment ?? true),
            "OrderCreationWithScript" => (int)($request->order_creation_with_script ?? 0),
            "isOfficeUse" => (bool)($request->is_office_use ?? false),
            "StaffDiscount" => (float)($request->staff_discount ?? 0.0),
            "IsAnyDayOrder" => (int)($request->is_any_day_order ?? 0),
            "CompanyName" => $company->name ?? null,
            "ImportOrders" => (int)($request->import_orders ?? 0),
            "OrderCreationDate" => Carbon::now()->toIso8601String(),
            "IsPaymentThroughSubscriptionPlan" => (int)($request->is_payment_through_subscription_plan ?? 0),
            "isContractBasedGroupOrder" => (int)($request->is_contract_based_group_order ?? 0),
            "TotalInstallments" => (int)($request->total_installments ?? 0),
            "installmentType" => $request->installment_type ?? null,
            "IsCashlessEnabled" => (int)($request->is_cashless_enabled ?? 0),
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
                "billingZipCode" => $request->billingZipCode ?? "12345",
                "expDate" => $request->expDate ?? "12/25",
                "paymentCode" => $request->paymentCode ?? "1234",
                "amount" => (float)($request->amount ?? 1056),
                "StaffTip" => (float)($request->StaffTip ?? 0.0),
                "Tax" => (float)($request->Tax ?? 0.0),
                "ServiceCharges" => (float)($request->ServiceCharges ?? 0.0),
                "TransactionId" => $request->TransactionId ?? "",
                "ccNumber" => $request->ccNumber ?? "Omitted",
                "cvn" => $request->cvn ?? "Omitted",
                "PaymentMethodId" => $request->PaymentMethodId ?? "pm_1Ss0HgEyfVF19QwA3a6viasp"
            ];
        // }

        // Add group subscription plan if needed
        if (isset($request->group_subscription_plan) || isset($request->subscription_start_date)) {
            $payload['groupSubscriptionPlan'] = [
                "totalAmountOfContract" => (float)($request->group_subscription_plan['totalAmountOfContract'] ?? 0),
                "initialPayment" => (float)($request->group_subscription_plan['initialPayment'] ?? 0),
                "subscriptionStartDate" => $request->group_subscription_plan['subscriptionStartDate'] ?? $request->subscription_start_date ?? null,
                "subscriptionEndDate" => $request->group_subscription_plan['subscriptionEndDate'] ?? $request->subscription_end_date ?? null,
                "totalInstallments" => (int)($request->group_subscription_plan['totalInstallments'] ?? 0),
                "invoices" => $request->group_subscription_plan['invoices'] ?? []
            ];
        }

        // Add EasyPayPlanContractSignature if exists
        if (isset($request->easy_pay_plan_contract_signature)) {
            $payload['EasyPayPlanContractSignature'] = $request->easy_pay_plan_contract_signature;
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

}
