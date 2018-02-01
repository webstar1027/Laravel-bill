<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'locationID', 'image',
    ];
   
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'category';

    /**
     * Get the lists for the category.
     *
     * @return array
     */
    
    public function getCategories()
    {
        $categories = DB::table($this->table)
                ->orderBy('id')
                ->get();
        return $categories;
    } 

    public function getCategory($categoryId)
    {
        $category = DB::table($this->table)
                ->where('id', $categoryId)
                ->first();
        return (array)$category;
    } 

    public function getCategoryByLocation($locationId)
    {
        $categories = DB::table($this->table)
                ->select('id', 'name', 'image','drinkable')
                ->where('locationID', $locationId)
                ->get();
        return $categories;
    } 

}
