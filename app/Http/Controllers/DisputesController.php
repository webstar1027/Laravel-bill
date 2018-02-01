<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Services\SteamSignIn;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use App\Models\User;
use App\Models\Group;
use App\Models\Dispute;

use Input;
use Hash;
use Cookie;


class DisputesController extends BaseController {

    public function __construct(User $user){
        $this->dispute_model = new Dispute();
    }

    public function index() {
        $disputes = $this->dispute_model->getDisputes(Auth::user()->id);
        return view('disputes', ['disputes'=>$disputes]);
    }

    public function respond() {
        $disputes = $this->dispute_model->getDisputes(Auth::user()->id);
        return view('disputes', ['disputes'=>$disputes]);
    }
        
    public function fileDispute(Request $request){
        $res = $this->dispute_model->addDispute($request->record_id, $request->comment, $request->amount, Auth::user()->id);
        if ($res)
            return "OK";
        return "NO";
    }

    
    public function sendToken($id = false, Request $request){
        $from = "admin@admin.com";
        $email = Auth::user()->email;
        $token = $this->generateToken();
        $params = array(
            'email' => $email,
            'subject' => "Create/Forgot Token",
            'message' => $token,
            'headers' => 'From: '.$from . "\r\n" .
                        'Reply-To: '.$from . "\r\n" .
                        'X-Mailer: PHP/' . phpversion()
        );
        $result = @mail($email, "token", $token, $params['headers']);

        return "OK";
    }

    private function generateToken() {
        list($usec, $sec) = explode(" ", microtime());
        $microtime = ((float)$usec + (float)$sec);
        return md5($microtime);
    }
}