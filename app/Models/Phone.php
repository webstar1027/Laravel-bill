<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Phone extends Model
{
    use Notifiable;

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
    protected $table = 'phones';

    /**
     * Get the password for the user.
     *
     * @return string
     */
    
    public function getPhones($userid)
    {
        $phones = DB::table($this->table)
            ->where('user_id', $userid)
            ->get();
        return $phones;
    } 
}
