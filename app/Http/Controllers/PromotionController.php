<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Services\SteamSignIn;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Input;
use Hash;
use Cookie;

use App\Models\User;
use App\Models\Group;
use App\Models\Menu;
use App\Models\Location;


class PromotionController extends BaseController {

    public function __construct(User $user){
        $this->menu_model = new Menu();
    }

    public function index() {

        $store = Location::where('adminUserid', Auth::user()->id)
                            ->first();
        $menus = $this->menu_model->getMenusByLocation($store->id);
        return view('promotion', ['menus'=>$menus]);
    }

}