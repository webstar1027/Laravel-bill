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
use App\Models\Category;
use App\Models\Location;


class CategoryController extends BaseController {

    public function __construct(User $user){
        $this->category_model = new Category();
    }

    public function index() {

		$store = Location::where('adminUserid', Auth::user()->id)
                            ->first();
        $categories = $this->category_model->getCategoryByLocation($store->id);

        return view('category', ['categories'=>$categories]);
    }

}