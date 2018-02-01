<?php

namespace App\Http\Controllers\Auth;

use Mail;
use App\Models\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function index(){
        return view('auth.login', []);
    }
    
    public function doLogin(Request $request){
        $email = $request->get('email');
        $password = $request->get('password');
        if(Auth::attempt(['email' => $email, 'password' => $password])){
            return redirect('/');
        }else{
            return \Redirect::back()->withInput()->withErrors(['Ukendte login detailer']);
        }
    }
    
    public function logout(){
        Auth::logout();
        return redirect('/');
    }

    public function sendtoken($id = false, Request $request) {
        print_r($request);exit;
        $from = "admin@admin.com";
        $params = array(
            'email' => $request->email,
            'subject' => "Forgot password",
            'message' => "Please reset your password at this link",
            'headers' => 'From: '.$from . "\r\n" .
                        'Reply-To: '.$from . "\r\n" .
                        'X-Mailer: PHP/' . phpversion()
        );
        $result = @mail($request->email, "token", "adaeu9qh8fq048gyq-94ghq-ghq-", $params['headers']);

        return view('register', array('email'=>$request->email));
    }

    public function confirmtoken($id = false, Request $request) {
        User::create([
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);
        return redirect('/');
    }
}
