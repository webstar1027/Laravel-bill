<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Location extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name',
    ];
   
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'location';

    /**
     * Get the names for the location.
     *
     * @return array
     */
    
    public function getLocations()
    {
        $result = DB::table($this->table)
            ->select('id', 'name','tax', 'waitingTime')
            ->get();
        return $result;
    } 

    public function getLocation($locationId)
    {
        $location = DB::table($this->table)
                ->leftJoin('users', 'location.adminUserId', '=', 'users.id')
                ->select('location.id', 'location.name', 'location.address', 'location.tax', 'location.waitingTime', 'location.openingHour', 'location.closingHour','users.password')
                ->where('location.id', $locationId)
                ->first();
        return (array)$location;
    } 


    public function getStores()
    {
        $result = DB::table($this->table)
            ->select('id', 'name', 'address', 'tax', 'waitingTime', 'openingHour', 'closingHour')
            ->get();
        return $result;
    } 

    public function getStoreById($storeId)
    {
        $result = DB::table($this->table)
            ->where('id', $storeId)
            ->select('id', 'name', 'address', 'tax', 'waitingTime', 'openingHour', 'closingHour')
            ->first();
        return $result;
    } 

    public function updateStore($store) {
        Location::where('id', $store['id'])
                ->update(
                    array(
                        'adminUserId' => $store['adminUserId'],
                        'name'        => $store['name'],
                        'address'     => $store['address'],
                        'tax'         => $store['tax'],
                        'waitingTime' => $store['waitingTime'],
                        'openingHour' => $store['openingHour'],
                        'closingHour' => $store['closingHour']
                    )
                );

        $store = Location::where('id', $store['id'])
                ->select('id', 'name', 'address', 'tax', 'waitingTime', 'openingHour', 'closingHour')
                ->first();

        return $store;
    }
}
