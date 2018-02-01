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
use App\Models\Record;
use App\Models\Phone;
use App\Models\Dispute;

use Input;
use Hash;
use Cookie;
use Response;

class RecordsController extends BaseController {

    public function __construct(User $user){
        $this->record_model = new Record();
        $this->phone_model = new Phone();
        $this->dispute_model = new Dispute();
    }
        
    public function index(Request $request) {
        $userid = Auth::user()->id;
        if(isset($request->month)) {
            $month = $request->month;
        }
        else {
            $month = $this->record_model->getFirstMonth();
        }
        $phones = $this->phone_model->getPhones($userid);
        // print_r($phones[0]->phone);exit;
        return redirect('records/'.$month.'/'.$phones[0]->phone);
    }

    public function getRecords(Request $request){
        $userid = Auth::user()->id;
        $records = $this->record_model->getRecords($request->month, $request->phone);
        $phones = $this->phone_model->getPhones($userid);

        $disputes = $this->dispute_model->getDisputes(Auth::user()->id);
        foreach ($records as $key=>$record) {
            foreach ($disputes as $key=>$dispute) {
                if ($record->id == $dispute->record_id) {
                    unset($records[$key]);
                    continue;
                }
            }
        }

        // print_r($records);exit;
        return view('records', ['records'=> $records, 'phones'=>$phones, 'selected_month'=>$request->month, 'selected_phone'=>$request->phone]);
    }

    public function getRecordDetail(Request $request){
        $res = $this->record_model->getRecordDetail($request->record_id);
        if ($res)
            return \Response::json($res);
        return \Response::json(array('result'=>'fail'));
    }

    public function pay(Request $request){
        $userid = Auth::user()->id;
        $res = $this->record_model->pay($request->month, $request->phone);
        // print_r($records);exit;
        if (!$res)
            return \Redirect::back()->with('error_messages', array("Failed to pay. Please try again later."));
        return redirect('/payments');
    }
}