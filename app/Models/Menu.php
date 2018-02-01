<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Menu extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'categoryId', 'image', 'options', 'optionValues', 'price',
    ];
   
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'menu';

    /**
     * Get the password for the user.
     *
     * @return string
     */
    
    public function getMenus($categoryId)
    {
        $menus = DB::table($this->table)
            ->where('categoryId', $categoryId)
            ->orderBy('promoted2')
            ->get();

        return $menus;
    } 

    public function getMenu($menuId)
    {
        $menu = DB::table($this->table)
            ->where('id', $menuId)
            ->get();

        return $menu;
    } 

    public function getPromoted()
    {
        $menus = DB::table($this->table)
            ->select('id','name','image','categoryId')
            ->where('promoted1', '>', 0)
            ->orderBy('promoted1')
            ->get();

        return $menus;
    } 
    
    public function getMenusByLocation($locationId)
    {
        $menus = DB::table($this->table)
                ->leftJoin('category', 'menu.categoryId', '=', 'category.id')
                ->select('menu.id', 'menu.name', 'menu.image', 'menu.price', 'menu.promoted1', 'menu.promoted2', 'category.name as categoryName', 'category.locationID','category.drinkable')
                ->where('category.locationID', $locationId)
                ->get();
        return $menus;
    } 
}
