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


class UserController extends BaseController {

    public function __construct(User $user){
        $this->user_model = new User();
    }
    

    public function index() {
        $users = $this->user_model->getUsers();
        return view('users', ['users' => $users]);
    }   

    public function showUser($userid = false){
        
    }


    public function login(){
        return view('login', []);
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
    
    public function logout(){
        Auth::logout();
        return redirect('/');
    }

    public function register($id = false, Request $request) {
        $email = Cookie::get('email');
        if (!$email) {
            return redirect('/');        
        }
        return view('register', array('email'=>$email));
    }

    public function sendtoken($id = false, Request $request) {
        $rules = array(
            'email' => 'required|email'
        );

        $data = array(
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $error_messages = array_merge($validator->messages()->get('password'), $validator->messages()->get('password_confirmed'));
            return \Redirect::back()->withInput()->with('error_messages', $error_messages);
        }

        $email_token = rand(100000, 999999);
        $sms_token = rand(100000, 999999);
        $db_user = User::where('email', $data['email'])
                    ->first();
        if ($db_user) {
            User::where('id', $db_user['id'])
                ->update(['phone' => $request->phone,
                        'email_token' => $email_token,
                        'sms_token' => $sms_token]);            
        }
        else {
            $res = User::create([
                'email' => $request->email,
                'phone' => $request->phone,
                'email_token' => $email_token,
                'sms_token' => $sms_token
            ]);
        }

        $trax_id = $request->phone . time();
        $sms_message = "Your code is <b>" . $sms_token . "</b>";
        $sms_url = 'https://quickairtime.com/webservices/v2/index.php/noksms/pay?authtoken=672dea3c9238b3f7fbc01d54528dd64452635eb5&client_id=NDM5MDk=&tranx_id=' . $trax_id . '&gateway_type=3&payment_gateway_id=11&transaction_amount=0.01&transaction_currency=244&transaction_email=support@postpaidbills.com&sender_country_code=172&sender_id=PostpaidBill&subject=Postpaidbills%20Account%20verification&recipients[]='. $data['phone'] . '&sms_message=' . $sms_message  . '&merchant_email=support@postpaidbills.com&merchant_password=' . md5("2e66ULfX");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $sms_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $httpCode2 = curl_getinfo($ch , CURLINFO_HTTP_CODE); // this results 0 every time
        $response2 = curl_exec($ch);
        $email_message = "Your code is <b>" . $email_token . "</b>";
        $email_url = 'https://quickairtime.com/webservices/api.php?cmd=100&from=Postpaid%20Bills&message=' . $email_message . '&subject=Postpaidbills%20Account%20verification&recipients[]='. $request->email . '&recipients[]=' . $request->email;
        
        curl_setopt($ch, CURLOPT_URL, $email_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $httpCode1 = curl_getinfo($ch , CURLINFO_HTTP_CODE); // this results 0 every time
        $response1 = curl_exec($ch);
       
        curl_close($ch);

        Cookie::queue("email", $request->email, '60');
        return view('register', array('email'=>$request->email));
    }

    public function doRegister($id = false, Request $request) {
        $rules = array(
            'password' => 'required|min:6',
            'password_confirmed' => 'required|min:6|same:password'
        );

        $data = array(
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'password_confirmed' => $request->get('password_confirm')
        );


        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $error_messages = array_merge($validator->messages()->get('password'), $validator->messages()->get('password_confirmed'));
            return \Redirect::back()->withInput()->with('error_messages', $error_messages);
        }
        $res = User::where('email', $data['email'])
            ->where('email_token', $request->get('email_token'))
            ->where('sms_token', $request->get('sms_token'))
            ->update(['password' => bcrypt($data['password'])]);

        if ($res) {
            Auth::attempt($data);
            return redirect('/');
        }
        $res = "Oops! An error occurred while registereing";
        
        
        return \Redirect::back()->withInput()->with('error_messages', [$res]);            
        
    }

    private function generateToken() {
        list($usec, $sec) = explode(" ", microtime());
        $microtime = ((float)$usec + (float)$sec);
        return md5($microtime);
    }

    public function account() {
        return view('account', ['user'=>Auth::user()]);
    }

    public function updateprofile(Request $request) {
        $email = $request->email;
        $phone = $request->phone;
        $password = $request->password;


        $rules = array(
            'email' => 'required|email',
            'password' => 'required|min:6',
            'password_confirmed' => 'required|min:6|same:password'
        );

        $data = array(
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'password_confirmed' => $request->get('password_confirm')
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $error_messages = array_merge($validator->messages()->get('password'), $validator->messages()->get('password_confirmed'));
            return \Redirect::back()->withInput()->with('error_messages', $error_messages);
        }      
        
        User::where('id', $request->id)
            ->update([
                'email'=>$request->email,
                'phone'=>$request->phone,
                'password'=>$request->password
            ]);

        if (Auth::attempt($data)) {
            return redirect('/');
        } else {
            return \Redirect::back()->with('error_messages', array("Your username/password combination was incorrect"));
        }

        return view('account', ['user'=>Auth::user()]);
    }

    public function getResetPassword($forgot_token){
        $data = array(
            'forgot_token' => $forgot_token,
            'error_messages' => array()
        );
        return view('reset-password', $data);
    }

    public function resetPassword($forgot_token, Request $request){
        $error_messages = array();
        
        $rules = array(
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        $post = array(
            'password' => $request->get('password'),
            'email' => $request->get('email'),
        );

        if ($validator->fails() || !$forgot_token)
        {
            $error_messages = $validator->messages()->all();
            if(!$forgot_token){
                $error_messages[] = "You have nothing permission to change a password.";
            }
        } else {
            $user = User::where('forgot_token', $forgot_token)->first();
            
            if(!$user){
                $error_messages[] = "You don't have permission to change password.";
                $data = array(
                    'forgot_token' => $forgot_token,
                );
            } else {
                $password = bcrypt($post['password']);

                $updatedUser = array(
                    'password' => $password
                );
                
                User::where('id', $user->id)
                    ->update(
                        array(
                            'password' => $password,
                            'forgot_token' => '',
                        )
                    );
           }
        }

        if($error_messages){
            //return \Redirect::back()->withInput()->with('error_messages', $error_messages);
            return Redirect::to('reset-password/'.$forgot_token)->with('error_messages', $error_messages); 
            
        }else{                 
            return Redirect::to('success-password'); 
        }
    }

    public function getForgotPasswordSuccess(){
        return view('forgot-password-success');
    }

}