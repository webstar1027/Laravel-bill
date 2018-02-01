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
use App\Models\OrderHistory;
use App\Models\Location;


class OrdersController extends BaseController {

    public function __construct(User $user){
        $this->orderHistory_model = new OrderHistory();
    }

    public function index() {

    	$store = Location::where('adminUserid', Auth::user()->id)
    						->first();
        $orders = $this->orderHistory_model->getAllOrders($store->id);
        return view('orders', ['orders'=>$orders]);
    }

}