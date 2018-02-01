<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderHistory extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'orderId', 'userId', 'locationId', 'price', 'estimatedTime', 'status',
    ];
   
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orderHistory';

    /**
     * Insert order
     *
     * 
     */
    public function addOrder($order)
    {
        DB::table($this->table)->insert(
            $order
        );
        $order_id = DB::getPdo()->lastInsertId();
        return $order_id;
    }

    public function getOrders($userId)
    {
        $orders = DB::table($this->table)
                ->where('userId', $userId)
                ->select('id', 'subTotal', 'rewardsCredit', 'tax', 'locationId', 'totalPrice', 'status', 'timestamp')
                ->orderBy('id', 'desc')
                ->get();
        return $orders;
    } 

    public function updateRefundOrderHistory($orderId, $new) {

        DB::table($this->table)
            ->where('id', $orderId)
            ->update($new);

        $order = DB::table($this->table)
            ->select('id', 'subTotal', 'rewardsCredit', 'tax', 'locationId', 'totalPrice', 'status', 'timestamp')
            ->where('id', $orderId)
            ->first();

        return (array)$order;
    }

    /////////////////////// Store App  ///////////////////////


    public function getNewOrdersByStore($storeId)
    {
        $orders = DB::table($this->table)
                ->leftJoin('users', 'orderHistory.userId', '=', 'users.id')
                ->select('orderHistory.id', 'orderHistory.locationId', 'orderHistory.userId', 'users.email', 'users.firstname', 'users.lastname', 'users.rewardStar','orderHistory.subTotal', 'orderHistory.rewardsCredit', 'orderHistory.tax', 'orderHistory.totalPrice', 'orderHistory.status', 'orderHistory.timestamp')
                ->where([
                            ['orderHistory.locationId', '=', $storeId],
                            ['orderHistory.status', '<>', '3'],
                        ])
                ->orderBy('orderHistory.id', 'desc')
                ->get();
        return $orders;
    } 

    public function getCompletedOrdersByStore($storeId)
    {
        $orders = DB::table($this->table)
                ->leftJoin('users', 'orderHistory.userId', '=', 'users.id')
                ->select('orderHistory.id', 'orderHistory.locationId', 'orderHistory.userId', 'users.email', 'users.firstname', 'users.lastname', 'users.rewardStar','orderHistory.subTotal', 'orderHistory.rewardsCredit', 'orderHistory.tax', 'orderHistory.totalPrice', 'orderHistory.status', 'orderHistory.timestamp')
                ->where([
                            ['orderHistory.locationId', '=', $storeId],
                            ['orderHistory.status', '=', '3'],
                        ])
                ->orderBy('orderHistory.id', 'desc')
                ->get();
        return $orders;
    } 

    public function getOrdersByUser($storeId, $userId)
    {
        $orders = DB::table($this->table)
                ->leftJoin('users', 'orderHistory.userId', '=', 'users.id')
                ->select('orderHistory.id', 'orderHistory.locationId', 'orderHistory.userId', 'users.email', 'users.firstname', 'users.lastname', 'users.rewardStar','orderHistory.subTotal', 'orderHistory.rewardsCredit', 'orderHistory.tax', 'orderHistory.totalPrice', 'orderHistory.status', 'orderHistory.timestamp')
                ->where([
                            ['orderHistory.locationId', '=', $storeId],
                            ['orderHistory.userId', '=', $userId],
                        ])
                ->orderBy('orderHistory.id', 'desc')
                ->get();
        return $orders;
    } 


    public function updateOrderHistory($orderId, $new) {

        DB::table($this->table)
            ->where('id', $orderId)
            ->update($new);

        $order = DB::table($this->table)
            ->leftJoin('users', 'orderHistory.userId', '=', 'users.id')
            ->select('orderHistory.id', 'orderHistory.locationId', 'orderHistory.userId', 'users.email', 'users.firstname', 'users.lastname', 'users.rewardStar','orderHistory.subTotal', 'orderHistory.rewardsCredit', 'orderHistory.tax', 'orderHistory.totalPrice', 'orderHistory.status', 'orderHistory.timestamp')
            ->where('orderHistory.id', $orderId)
            ->first();

        return (array)$order;
    }


    /////////////////////// Admin  ///////////////////////

    public function getAllOrders($storeId)
    {
        $orders = DB::table($this->table)
                ->leftJoin('users', 'orderHistory.userId', '=', 'users.id')
                ->select('orderHistory.id', 'orderHistory.locationId', 'orderHistory.userId', 'users.email', 'users.firstname', 'users.lastname','orderHistory.subTotal', 'orderHistory.rewardsCredit', 'orderHistory.tax', 'orderHistory.totalPrice', 'orderHistory.status', 'orderHistory.timestamp')
                ->where('orderHistory.locationId', $storeId)
                ->orderBy('orderHistory.id', 'desc')
                ->get();
        return $orders;
    } 
}
