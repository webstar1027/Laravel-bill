<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use App\Services\SteamSignIn;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Input;
use Hash;
use Cookie;


class AccountController extends BaseController {

    public function index() {
        return view('account', []);
    }

    public function __construct(User $user){
    }
        
    public function doLogin(Request $request){
        $rules = array(
            'password' => 'required|min:6',
            'email' => 'required|email'
        );
        $data = array(
            'email' => $request->get('email'),
            'password' => $request->get('password')
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $error_messages = array_merge($validator->messages()->get('email'), $validator->messages()->get('password'));
            return \Redirect::back()->withInput()->with('error_messages', $error_messages);            
        }
        
        if(Auth::attempt($data)){
            return redirect('/');
        }else{
            return \Redirect::back()->with('error_messages', array("Your username/password combination was incorrect"));
        }
    }
    

}