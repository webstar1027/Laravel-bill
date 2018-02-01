<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Location;
use App\Models\Group;
use App\Services\SteamSignIn;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Input;
use Hash;
use Cookie;


class StoreController extends BaseController {

    public function __construct(User $user){
        $this->location_model = new Location();
    }
    

    public function index() {
        $userid = Auth::user()->id;
        $store = Location::where('adminUserId', $userid)
                    ->first();

        return view('account', ['store' => $store]);
    }   

    private function generateToken() {
        list($usec, $sec) = explode(" ", microtime());
        $microtime = ((float)$usec + (float)$sec);
        return md5($microtime);
    }

    public function updateProfile(Request $request) {

        $rules = array(
            'name'        => 'required',
            'address'     => 'required',
            'openingHour' => 'required',
            'closingHour' => 'required',
            'waitingTime' => 'required'
        );

        $data = array(
            'name'        => $request->get('name'),
            'address'     => $request->get('address'),
            'openingHour' => $request->get('openingHour'),
            'closingHour' => $request->get('closingHour'),
            'waitingTime' => $request->get('waitingTime')
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $error_messages = $validator->messages()->all();
            return \Redirect::back()->withInput()->with('error_messages', $error_messages);
        }      
        
        Location::where('adminUserId', Auth::user()->id)
            ->update([
                'name'=>$data['name'],
                'address'=>$data['address'],
                'openingHour'=>$data['openingHour'],
                'closingHour'=>$data['closingHour'],
                'waitingTime'=>$data['waitingTime']
            ]);

        $store = Location::where('adminUserId', Auth::user()->id)
                    ->first();

        return view('account', ['store' => $store]);
    }

}