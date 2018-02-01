<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderItems extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'orderId','menuId', 'menuName', 'options', 'price', 'quantity',
    ];
   
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orderItems';

    /**
     * Insert orders
     *
     * 
     */
    
    public function insertOrderItems($insertData)
    {
        DB::table($this->table)->insert(
            $insertData
        );
    } 

    public function refundOrderItem($id)
    {
        DB::table($this->table)
            ->whereIn('id', $id)
            ->update(['refunded' => '1']);
    } 


    public function getOrderDetails($orderId)
    {
        $details = DB::table($this->table)
                ->where('orderId', $orderId)
                ->orderBy('id', 'desc')
                ->get();
        return $details;
    } 

    public function getAllOrderDetails()
    {
        $details = DB::table($this->table)
                ->orderBy('id', 'desc')
                ->get();
        return $details;
    } 
}
