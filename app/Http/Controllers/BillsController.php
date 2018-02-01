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

use Input;
use Hash;
use Cookie;


class BillsController extends BaseController {

    public function __construct(User $user){
        $this->record_model = new Record();
    }

    public function index() {
        $bills = $this->record_model->getbills();
        // print_r($bills);exit;
        return view('bills', ['bills' => $bills]);
    }   

}