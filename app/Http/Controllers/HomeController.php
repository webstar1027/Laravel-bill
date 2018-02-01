<?php
namespace App\Http\Controllers;

//use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Requests;
use App\Services\trackerFunctions;
use App\Services\chartMagic;
use ClientQuery;
use RbucketQuery;

class HomeController extends BaseController {

    public function index(){
        $data = array();
        return view('home', $data);
    }

	public function showWelcome()
    {
        return view('home');
//		return View::make('hello');
    }

}
