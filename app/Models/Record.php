<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Record extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];
   
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'records';

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getBills()
    {
        $records = DB::table($this->table)
            ->orderBy('created_at')
            ->get();
        
        $month = "";
        $total_charge = 0;
        $bill_array = array();
        foreach ($records as $key=>$record) {
            $time = strtotime($record->created_at);
            $newMonth = date('Y-m', $time);
            if ($month == "") {
                $month = $newMonth;
            }
            else if ($month != $newMonth) {
                $bill_info['month'] = $month;
                $bill_info['charge'] = $total_charge;
                $bill_array[] = $bill_info;
                $month = $newMonth;
            }
            else {
                $total_charge = $total_charge + (int)$record->charge;
            }
        }
        $bill_info['month'] = $month;
        $bill_info['charge'] = $total_charge;
        $bill_array[] = $bill_info;
        
        return $bill_array;
    }   
    
    public function getRecords($month, $phone)
    {
        $records = DB::table($this->table)
            ->where('created_at', 'like', '%' . $month . '%')
            ->where('phone', $phone)
            ->where('paid', '0')
            ->orderBy('created_at')
            ->get();
                
        return $records;
    }

    public function getRecordDetail($record_id)
    {
        $records = DB::table($this->table)
            ->where('id', $record_id)
            ->first();
                
        return $records;
    }

    public function getFirstMonth()
    {
        $record = DB::table($this->table)
            ->orderBy('created_at')
            ->first();
        $time = strtotime($record->created_at);
        $month = date('Y-m', $time);
        return $month;
    } 

    public function pay($month, $phone)
    {
        $res = DB::table($this->table)
            ->where('created_at', 'like', '%' . $month . '%')
            ->where('phone', $phone)
            ->where('paid', '0')
            ->update(['paid'=>'1']);
                
        return $res;
    }

    public function getPayments($user_id)
    {
        $payments = DB::table($this->table)
            ->join('phones', 'phones.phone', '=', 'records.phone')
            ->where('phones.user_id', $user_id)
            ->where('records.paid', '1')
            ->select('records.*', 'phones.user_id')
            ->get();

        return $payments;
    }
}
