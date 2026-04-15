<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\UserOrderTickets;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Resources\UserOrder;

class UserOrders extends Model
{
    use SoftDeletes,CRUDGenerator;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_orders';

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
        'estimate_id',
        'created_by',
        'auth_code',
        'slug',
        'status',
        'name',
        'session_id',
        'order_number'
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

     public static function generateUniqueSlug($slug)
    {
        $slug = Str::slug($slug);
        $query = self::where('slug',$slug)->count();
        if( $query > 0){
            $slug = $slug . $query . rand(111,999);
        }
        return Str::slug($slug);
    }


    public function userOrderTickets()
    {
        return $this->hasMany(UserOrderTickets::class, 'user_order_id', 'id');
    }

    public static function storeOrder($data,$estimate_id,$signature)
    {
        $estimate = DB::table('user_estimate')->where('id', $estimate_id)->first();
        
        if(!$estimate){
            return null;
        }

        DB::table('user_estimate')->where('id', $estimate_id)->update(['signature' => $signature]);
        DB::table('contracts')->where('id', $estimate->contract_id)->update(['signature' => $signature]);

        

            DB::transaction(function () use ($estimate_id, $data, &$recordList,$estimate) {

                $record = self::create([
                    'estimate_id' => $estimate_id,
                    'slug'        => self::generateUniqueSlug(uniqid()),
                    'session_id'  => $data['sessionId'] ?? null,
                    'order_number' => $estimate->slug,
                ]);

                $dataArr = [];

                if(isset($data['data']) && is_array($data['data'])) {
                foreach ($data['data'] as $item) {

                    $dataArr[] = [
                        'user_order_id' => $record->id,
                        'estimate_id' => $estimate_id,
                        'visualId' => $item['visualId'] ?? null,
                        'childVisualId' => $item['childVisualId'] ?? null,
                        'parentVisualId' => $item['parentVisualId'] ?? null,
                        'ticketType' => $item['ticketType'] ?? null,
                        'ticketSlug' => $item['ticketSlug'] ?? null,
                        'description' => $item['description'] ?? null,
                        'seat' => $item['seat'] ?? null,
                        'price' => $item['price'] ?? null,
                        'ticketDate' => $item['ticketDate'] ?? null,
                        'ticketDisplayDate' => $item['ticketDisplayDate'] ?? null,
                        'orderDate' => $item['orderDate'] ?? null,
                        'orderDisplayDate' => $item['orderDisplayDate'] ?? null,
                        'firstName' => $item['firstName'] ?? null,
                        'lastName' => $item['lastName'] ?? null,
                        'email' => $item['email'] ?? null,
                        'phone' => $item['phone'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                }

                UserOrderTickets::insert($dataArr);

                $recordList = self::with('userOrderTickets')->find($record->id);
            });

            return $recordList;
    }

     public static function updateStoreOrder($data,$modify_id)
    {
        $modify = DB::table('contract_modified')->where('id', $modify_id)->first();    
        if(!$modify){
            return null;
        }
        $estimate = DB::table('user_estimate')->where('id', $modify->user_estimate_id)->first();
        $contract = DB::table('contracts')->where('id', $modify->contract_id)->first();

    
            DB::transaction(function () use ($data, &$recordList,$estimate,$modify) {

                $record = self::where('estimate_id', $estimate->id)->first();
                if (!$record) {
                    // $record = self::create([
                    //     'estimate_id' => $estimate->id,
                    //     'slug'        => self::generateUniqueSlug(uniqid()),
                    //     'session_id'  => $data['sessionId'] ?? null,
                    //     'order_number' => $estimate->slug,
                    // ]);
                }

                $dataArr = [];
                // dd($data);
                if(isset($data) && is_array($data)) {
                foreach ($data as $item) {

                    $dataArr[] = [
                        'user_order_id' => $record->id,
                        'estimate_id' => $estimate->id,
                        'modified_id' => $modify->id,
                        'visualId' => $item['visualId'] ?? null,
                        'childVisualId' => $item['childVisualId'] ?? null,
                        'parentVisualId' => $item['parentVisualId'] ?? null,
                        'ticketType' => $item['ticketType'] ?? null,
                        'ticketSlug' => $item['ticketSlug'] ?? null,
                        'description' => $item['description'] ?? null,
                        'seat' => $item['seat'] ?? null,
                        'price' => $item['price'] ?? null,
                        'ticketDate' => $item['ticketDate'] ?? null,
                        'ticketDisplayDate' => $item['ticketDisplayDate'] ?? null,
                        'orderDate' => $item['orderDate'] ?? null,
                        'orderDisplayDate' => $item['orderDisplayDate'] ?? null,
                        'firstName' => $item['firstName'] ?? null,
                        'lastName' => $item['lastName'] ?? null,
                        'email' => $item['email'] ?? null,
                        'phone' => $item['phone'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                }

                UserOrderTickets::insert($dataArr);

                $recordList = self::with('userOrderTickets')->find($record->id);
            });

            return $recordList;
    }
}
