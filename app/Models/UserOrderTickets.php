<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserOrderTickets extends Model
{
    use SoftDeletes,CRUDGenerator;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_order_tickets';

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
        'user_order_id',
        'estimate_id',
        'visualId',
        'childVisualId',
        'parentVisualId',
        'ticketType',
        'ticketSlug',
        'description',
        'seat',
        'price',
        'ticketDate',
        'ticketDisplayDate',
        'orderDate',
        'orderDisplayDate',
        'firstName',
        'lastName',
        'email',
        'phone',
        'orderTotal',
        'paidAmount',
        'orderTip',
        'orderNumber',
        'parentOrderNumber',
        'quantity',
        'slotTime',
        'orderSource',
        'posStaffIdentity',
        'isOrderDelete',
        'isRefundedTicket',
        'isRefundedOrder',
        'checkInStatus',
        'totalRefundedAmount',
        'isWavierFormSubmitted',
        'acceptRenewalSeasonPassTC',
        'isQrCodeBurn',
        'wavierSubmittedDateTime',
        'refundedDateTime',
        'tax',
        'serviceCharges',
        'isOrderFraudulent',
        'orderId',
        'orderFraudulentTimeStamp',
        'isTicketUpgraded',
        'ticketUpgradedFrom',
        'isSearchParentRecord',
        'validUntil',
        'transactionId',
        'isSeasonPassRenewal',
        'isSeasonPass',
        'customerAddress',
        'cardNumber',
        'cardType',
        'isEntitlementQrCode',
        'recordsTotal',
        'recordsFiltered',
        'hasNextPage',
        'hasPreviousPage',
        'totalOrderRefundedAmount',
        'isQrCodeAssignedinSeasonPassPortal',
        'isSubscriptionEnabled',
        'isSubscriptionCompleted',
        'isModifyingExistingOrder',
        'groupRecipientName',
        'groupRecipientEmail'
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

    public function userOrder()
    {
        return $this->belongsTo(UserOrders::class, 'user_order_id', 'id');
    }
}
