<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Option extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
    ];
   
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $option_table = 'menuOption';
    protected $option_value_table = 'menuOptionValue';

    /**
     * Get the password for the user.
     *
     * @return string
     */
    
    public function getOptions()
    {
        $options = DB::table($this->option_table)
                    ->orderBy('id')
                    ->get();

        return $options;
    } 

    public function getOptionValues()
    {
        $values = DB::table($this->option_value_table)
                    ->orderBy('optionId')
                    ->get();

        return $values;
    } 

}
