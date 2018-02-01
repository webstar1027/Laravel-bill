<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dispute extends Model
{
    use Notifiable;

    protected $connection = 'mysql_external';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone', 'user_id',
    ];
   
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dispute';
    protected $table_external = 'disputes';

    /**
     * Get the password for the user.
     *
     * @return string
     */
    
    public function addDispute($record_id, $message, $amount, $user_id)
    {
        
        $res = DB::table($this->table)
            ->insert(
                ["comment" => $message,
                "record_id" => $record_id,
                "value" => $amount,
                "subscriber_id" => $user_id,
                "created_at" => date("Y-m-d h:i:s")]
                );
        return $res;
    } 

    public function addDisputeExternal($record_id, $message, $amount, $user_id)
    {
        
        $res = DB::connection('mysql_external')->table($this->table_external)
            ->insert(
                ["comment" => $message,
                "record_id" => $record_id,
                "value" => $amount,
                "subscriber_id" => $user_id,
                "created_at" => date("Y-m-d h:i:s")]
                );
        return $res;
    } 

    public function getPhones($userid)
    {
        $phones = DB::table($this->table)
            ->where('user_id', $userid)
            ->get();
        return $phones;
    } 

    public function getDisputes($userid)
    {
        $disputes = DB::table($this->table)
            ->where('subscriber_id', $userid)
            ->get();
        return $disputes;
    }

    public function getDisputesExternal($userid)
    {
        $disputes = DB::connection('mysql_external')->table($this->table_external)
            ->where('subscriber_id', $userid)
            ->get();
        return $disputes;
    } 
}
