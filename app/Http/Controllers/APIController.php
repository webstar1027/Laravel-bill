<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Location;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Group;
use App\Models\Option;
use App\Models\OrderHistory;
use App\Models\OrderItems;
use App\Services\SteamSignIn;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\URL;

use Input;
use Hash;
use Cookie;
use Image;

class APIController extends BaseController {

    public function __construct(User $user){
        $this->user_model = new User();
        $this->location_model = new Location();
        $this->category_model = new Category();
        $this->menu_model = new Menu();
        $this->option_model = new Option();
        $this->orderHistory_model = new OrderHistory();
        $this->orderItems_model = new OrderItems();

        $this->data = array(
            'error' => false
        );

    }
    
    private function _JsonOutput(){
        header("access-control-allow-origin: *");
        header('Content-Type: application/json');
        echo json_encode($this->data);
        exit;
    }

    public function unsetFields($user){
        
        unset($user['password']);
        unset($user['device']);
        unset($user['device_token']);
        unset($user['forgot_token']);
        unset($user['updated_at']);

        return $user;
    }

    public function parseMenuDetails($menu, $allOptions, $allOptionValues) {
        
        $options = array_filter(explode(",", $menu->options));
        $optionValues = array_filter(explode(",", $menu->optionValues));

        $temp_option = array();
        foreach ($options as $value) {
            foreach ($allOptions as $key => $option) {
                if ($value == $option->id) {
                    array_push($temp_option, $option);
                }   
            }    
        }

        $temp_value = array();
        foreach ($optionValues as $value) {
            foreach ($allOptionValues as $key => $optionValue) {
                if ($value == $optionValue->id) {
                    array_push($temp_value, $optionValue);
                }   
            }    
        }

        $option_array = array();
        foreach ($temp_option as $key => $value) {
            $value_array = array();
            foreach ($temp_value as $key => $value1) {
                if ($value->id == $value1->optionId) {
                    array_push($value_array, $value1);
                }   
            }    

            array_push($option_array, array('id' => $value->id, 'description' => $value->description, 'optionValues' => $value_array));
        }

        $temp_menu = array(
            'id' => $menu->id,
            'name' => $menu->name,
            'image' => $menu->image,
            'options' => $option_array,
            'price' => $menu->price,
            'promoted' => $menu->promoted2
        );

        return $temp_menu;
    }


    public function register(Request $request) {

        $rules = array(
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        );

        $user = array(
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'device' => $request->get('device', ''),
            'device_token' => $request->get('device_token', '')
        );


        $validator = Validator::make($user, $rules);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $this->data = array(
                'error' => true,
                'message' => implode("\n", $messages->all())
            );

        } else {
            $db_user = User::where('email', $user['email'])
                            ->first();

            if($db_user){
                $res = 3;           // USER_ALREADY_EXISTED
            }else{
                $user['password'] = bcrypt($user['password']);
                $user_id = $this->user_model->addUser($user);
                if($user_id){
                    $res = 1;       // USER_CREATED_SUCCESSFULLY
                }else{
                    $res = 2;       // USER_CREATE_FAILED
                }
            }
            
            
            if ($res == 1) {
                $locations = $this->location_model->getLocations();
                $categories = $this->category_model->getCategories();
                $promoted = $this->menu_model->getPromoted();

                $this->data["error"] = false;
                $this->data["user"] = $this->unsetFields(User::where('email', $user['email'])->first()->toArray());
                $this->data["locations"] = $locations;
                $this->data["categories"] = $categories;
                $this->data["promoted"] = $promoted;
                $this->data["message"] = "You are successfully registered";
            } else if ($res == 2) {
                $this->data["error"] = true;
                $this->data["message"] = "Oops! An error occurred while registering";
            } else if ($res == 3) {
                $this->data["error"] = true;
                $this->data["message"] = "Sorry, this email already existed";
            }
        }
        
        $this->_JsonOutput();
    }

    public function login(Request $request) {
        
        $rules = array(
            'email' => 'required|email',
            'password' => 'required'
        );

        $user = array(
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'device' => $request->get('device', ''),
            'device_token' => $request->get('device_token', '')
        );

        $validator = Validator::make($user, $rules);
        
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error' => true,
                'message' => $messages->all()
            );

        }else{
            
            $db_user = User::where('email', $user['email'])
                            ->first();

            if($db_user) {
                if(Hash::check($user['password'], $db_user['password'])) {
                    $db_user['device'] = $user['device'];
                    $db_user['device_token'] = $user['device_token'];        
                    $db_user = $this->user_model->updateUser($db_user);

                    $locations = $this->location_model->getLocations();
                    $categories = $this->category_model->getCategories();
                    $promoted = $this->menu_model->getPromoted();

                    $this->data["error"] = false;
                    $this->data['user'] = $this->unsetFields($db_user); 
                    $this->data["locations"] = $locations;
                    $this->data["categories"] = $categories;
                    $this->data["promoted"] = $promoted;
                    $this->data['message'] = "Logged successfully.";
                } 
                else{
                    $this->data['error'] = true;
                    $this->data['message'] = 'Login failed. Incorrect credentials';
                }
            }else{
                $this->data['error'] = true;
                $this->data['message'] = "You must register to login this app";
            }
        }
        $this->_JsonOutput();
    }

    public function socialLogin(Request $request){
        
        $rules = array(
            'firstname' => 'required',
            'email' => 'required|email'
        );

        $user = array(
            'firstname' => $request->get('name'),
            'lastname' => '',
            'email' => $request->get('email'),
            'password' => $request->get('fb_token', ''),
            'device' => $request->get('device'),
            'device_token' => $request->get('device_token', '')
        );    
        
        $validator = Validator::make($user, $rules);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error' => true,
                'message' => implode("\n", $messages->all())
            );
        }else{
            $db_user = User::where('email', $user['email'])
                    ->first();
                
            $locations = $this->location_model->getLocations();
            $categories = $this->category_model->getCategories();                    
            $promoted = $this->menu_model->getPromoted();

            if($db_user) {
                $db_user['password'] = $user['password'];
                $db_user['device'] = $user['device'];
                $db_user['device_token'] = $user['device_token'];        
                $db_user = $this->user_model->updateUser($db_user);

                $this->data["error"] = false;
                $this->data["user"] = $this->unsetFields($db_user);
                $this->data["locations"] = $locations;
                $this->data["categories"] = $categories;
                $this->data["promoted"] = $promoted;
                $this->data["message"] = "Logged successfully";

            } else {

                $user_id = $this->user_model->addUser($user);
                if($user_id){
                    $this->data["error"] = false;
                    $this->data["user"] = $this->unsetFields(User::where('email', $user['email'])->first()->toArray());
                    $this->data["locations"] = $locations;
                    $this->data["categories"] = $categories;
                    $this->data["promoted"] = $promoted;
                    $this->data["message"] = "You are successfully registered";
                }else{
                    $this->data['error'] = true;
                    $this->data['message'] = "You must register to login this app";    
                }
            }
            
        }   
        $this->_JsonOutput();
    }

    public function forgotPassword(Request $request){
        $rules = array(
            'email' => 'required|email'
        );

        $user = array(
            'email' => $request->get('email')
        );

        $validator = Validator::make($user, $rules);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error' => true,
                'message' => implode("\n", $messages->all())
            );
        }
        
        $result = $this->user_model->forgotPassword($user['email']);
        if($result['error'] === true){
            $this->data = array(
                'error' => true,
                'message' => $result['message']
            );
        } else {
            $link = URL::to('reset-password/'.$result['message']);
            $from = "teaeracafe@gmail.com";
            $params = array(
                'email' => $result['email'],
                'subject' => "Forgot password",
                'message' => "Please reset your password at this link, {$link}",
                'headers' => 'From: '.$from . "\r\n" .
                            'Reply-To: '.$from . "\r\n" .
                            'X-Mailer: PHP/' . phpversion()
            );
            @mail($params['email'], $params['subject'], $params['message'], $params['headers']);
        }
        $this->_JsonOutput();
    }

    public function getUserProfile(Request $request) {
        
        $rules = array(
            'userId' => 'required'
        );

        $user = array(
            'userId' => $request->get('userId')
        );

        $validator = Validator::make($user, $rules);
        
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error' => true,
                'message' => $messages->all()
            );

        }else{
            
            $db_user = User::where('id', $user['userId'])
                            ->first();

            if($db_user) {
                $this->data["error"] = false;
                $this->data['user'] = $this->unsetFields($db_user); 
                $this->data['message'] = "Get user info successfully.";
            }else{
                $this->data['error'] = true;
                $this->data['message'] = "Failed getting user info.";
            }
        }
        $this->_JsonOutput();
    }

    public function updateUserInfo(Request $request) {
        
        $rules = array(
            'userId'    => 'required',
            'username'  => 'required',
            'email'     => 'required|email'
        );

        $user = array(
            'userId'    => $request->get('userId'),
            'username'  => $request->get('username'),
            'email'     => $request->get('email')
        );    
        
        $validator = Validator::make($user, $rules);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error' => true,
                'message' => implode("\n", $messages->all())
            );
        }else{
            $db_user = User::where('id', $user['userId'])
                    ->first();

            if($db_user) {
                $db_user['firstname'] = $user['username'];
                $db_user['lastname'] = "";
                $db_user['email'] = $user['email'];        
                $db_user = $this->user_model->updateUser($db_user);

                $this->data["error"] = false;
                $this->data["user"] = $this->unsetFields($db_user);
                $this->data["message"] = "Updated your profile successfully";

            } else {
                $this->data['error'] = true;
                $this->data['message'] = "Failed update your profile.";    
            }
            
        }   
        $this->_JsonOutput();
    }

    public function updateProfilePhoto(Request $request) {
        
        $rules = array(
            'userId'   => 'required',
            'image'    => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        );    
        
        $user = array(
            'userId' => $request->get('userId'),
            'image'  => $request->file('image'),
        ); 

        $validator = Validator::make($user, $rules);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error' => true,
                'message' => implode("\n", $messages->all())
            );
        }else{

            $image = $request->file('image');
            
            $imagename = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('images/profile/');
            
            $image->move($destinationPath, $imagename);
            

            $db_user = User::where('id', $user['userId'])
                    ->first();

            if($db_user) {
                $db_user['image'] = $imagename;
                $db_user = $this->user_model->updateUser($db_user);

                $this->data["error"] = false;
                $this->data["user"] = $this->unsetFields($db_user);
                $this->data["message"] = "Uploaded user's photo successfully";

            } else {
                $this->data['error'] = true;
                $this->data['message'] = "Failed user's photo uploading.";    
            }
            
        }   
        $this->_JsonOutput();
    }

    public function updateUserPassword(Request $request) {
        
        $rules = array(
            'userId'    => 'required',
            'oldPass'   => 'required',
            'newPass'   => 'required|min:6'
        );

        $user = array(
            'userId'    => $request->get('userId'),
            'oldPass'   => $request->get('oldPass'),
            'newPass'   => $request->get('newPass')
        );    
        
        $validator = Validator::make($user, $rules);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error' => true,
                'message' => implode("\n", $messages->all())
            );
        }else{
            $db_user = User::where('id', $user['userId'])
                    ->first();

            if($db_user) {
                if(Hash::check($user['oldPass'], $db_user['password'])) {
                    $db_user['password'] = bcrypt($user['newPass']);
                    $db_user = $this->user_model->updateUser($db_user);

                    $this->data["error"] = false;
                    $this->data['message'] = "Updated password successfully.";
                } 
                else{
                    $this->data['error'] = true;
                    $this->data['message'] = 'Your old password is incorrect.';
                }
            } else {
                $this->data['error'] = true;
                $this->data['message'] = "Failed update your password.";    
            }
            
        }   
        $this->_JsonOutput();
    }


    public function getCategories() {
        $locations = $this->location_model->getLocations();
        $categories = $this->category_model->getCategories();
        $promoted = $this->menu_model->getPromoted();

        $this->data["error"] = false;
        $this->data["locations"] = $locations;
        $this->data["categories"] = $categories;
        $this->data["promoted"] = $promoted;
        $this->data['message'] = "Fetched Category.";    

        $this->_JsonOutput();
    }   


    public function getMenu(Request $request) {

        $rules = array(
            'menuId' => 'required',
            'categoryId' => 'required'
        );

        $menu = array(
            'menuId' => $request->get('menuId'),
            'categoryId' => $request->get('categoryId')
        );

        $validator = Validator::make($menu, $rules);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error' => true,
                'message' => implode("\n", $messages->all())
            );
        } else {
            $category = $this->category_model->getCategory($menu['categoryId']);
            $menu = $this->menu_model->getMenu($menu['menuId']);
            $allOptions = $this->option_model->getOptions();
            $allOptionValues = $this->option_model->getOptionValues();
            
            $result = $this->parseMenuDetails($menu[0], $allOptions, $allOptionValues);

            $this->data["error"] = false;
            $this->data["menu"] = $result;
            // $this->data["locationId"] = $category[0]->locationID;
            $this->data["category"] = $category;
            $this->data['message'] = "Fetched Promoted Menu.";    
        }

        $this->_JsonOutput();
    }



    public function getMenus(Request $request) {

        $rules = array(
            'categoryId' => 'required'
        );

        $category = array(
            'categoryId' => $request->get('categoryId')
        );

        $validator = Validator::make($category, $rules);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error' => true,
                'message' => implode("\n", $messages->all())
            );
        } else {
            $menus = $this->menu_model->getMenus($category['categoryId']);
            $allOptions = $this->option_model->getOptions();
            $allOptionValues = $this->option_model->getOptionValues();

            $result = array();
            foreach ($menus as $key => $menu) {            
                $temp_menu = $this->parseMenuDetails($menu, $allOptions, $allOptionValues);
                array_push($result, $temp_menu);
            }

            $this->data["error"] = false;
            $this->data["menus"] = $result;
            $this->data['message'] = "Fetched Menu.";    
        }

        $this->_JsonOutput();
    }

    public function placeOrder(Request $request) {

        $rules = array(
            'userId'        => 'required',
            'locationId'    => 'required',
            'subTotal'      => 'required',
            'rewardsCredit' => 'required',
            'tax'           => 'required',
            'totalPrice'    => 'required',
            'cartInfos'     => 'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error'   => true,
                'message' => "Failed place order."
            );
        } else {
            $orders = $request->json()->get('cartInfos');
            $order  = array(
                'userId' => $request->json()->get('userId'),
                'locationId' => $request->json()->get('locationId'),
                'subTotal' => $request->json()->get('subTotal'),
                'rewardsCredit' => $request->json()->get('rewardsCredit'),
                'tax' => $request->json()->get('tax'),
                'totalPrice' => $request->json()->get('totalPrice'),
            );

            $orderId = $this->orderHistory_model->addOrder($order);
            if ($orderId) {
                
                $res = 1;
                $insertData = array();
                foreach ($orders as $key => $value) {
                    $row = array(
                        'orderId' => $orderId,
                        'menuId' => $value['menuId'],
                        'menuName' => $value['menuName'],
                        'options' => $value['options'],
                        'price' => $value['price'],
                        'quantity' => $value['quantity'],
                        'redeemed' => $value['redeemed'],
                        'drinkable' => $value['drinkable']
                    );
                    array_push($insertData, $row);
                }

                $this->orderItems_model->insertOrderItems($insertData);    
            } else {
                $res = 2;
            }
            
            if ($res == 1) {
                $this->data["error"] = false;
                $this->data['message'] = "Placed Order Successfully.";  
            } else if ($res == 2) {
                $this->data["error"] = true;
                $this->data["message"] = "Oops! Failed place order.";
            }
        }

        $this->_JsonOutput();
    }

    public function getOrders(Request $request) {

        $rules = array(
            'userId'     => 'required',
            'pageNumber' => 'required'
        );

        $order = array(
            'userId'     => $request->get('userId'),
            'pageNumber' => $request->get('pageNumber')
        );

        $validator = Validator::make($order, $rules);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error' => true,
                'message' => implode("\n", $messages->all())
            );
        } else {
            $orders = $this->orderHistory_model->getOrders($order['userId']);

            $result = array();
            $startPos = 15 * ($order['pageNumber']-1);

            if (count($orders) > 15 * $order['pageNumber']) {
                $endPos = 15 * $order['pageNumber'];
            } else {
                $endPos = count($orders);
            }

            for ($i=$startPos; $i < $endPos; $i++) { 
                array_push($result, $orders[$i]);                                
            }

            $this->data["error"] = false;
            $this->data["orders"] = $result;
            $this->data["pageNumber"] = $order['pageNumber'];
            $this->data['message'] = "Fetched Menu.";    
        }

        $this->_JsonOutput();
    }


    public function getOrderDetails(Request $request) {

        $rules = array(
            'orderId' => 'required'
        );

        $order = array(
            'orderId' => $request->get('orderId')
        );

        $validator = Validator::make($order, $rules);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error' => true,
                'message' => implode("\n", $messages->all())
            );
        } else {
            $result = $this->orderItems_model->getOrderDetails($order['orderId']);

            $this->data["error"] = false;
            $this->data["orders"] = $result;
            $this->data['message'] = "Fetched Order.";    
        }

        $this->_JsonOutput();
    }

    public function refundOrders(Request $request) {

        $rules = array(
            'orderId'         => 'required',
            'subTotal'        => 'required',
            'rewardsCredit'   => 'required',
            'total'           => 'required',
            'tax'             => 'required',
            'refundItems'     => 'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error'   => true,
                'message' => "Failed refund order."
            );
        } else {
            $refundItems = $request->json()->get('refundItems');

            $oldHistory  = array(
                'orderId'       => $request->json()->get('orderId'),
                'subTotal'      => $request->json()->get('subTotal'),
                'rewardsCredit' => $request->json()->get('rewardsCredit'),
                'total'         => $request->json()->get('total'),
                'tax'           => $request->json()->get('tax'),
            );

            
            $refundIds = array();
            $subtotal = 0;
            $rewardsCredit = 0;
            $tax = 0;
            $total = 0;

            foreach ($refundItems as $key => $value) {
                if ($value['redeemed'] == "0") {
                    $subtotal = $subtotal + floatval($value['price']) * intval($value['quantity']);
                } else {
                    $rewardsCredit = $rewardsCredit + floatval($value['price']) * intval($value['quantity']);
                }
                
                array_push($refundIds, $value['id']);
            }

            $total = floatval($oldHistory['total']) - ($subtotal + ($subtotal-$rewardsCredit) * floatval($oldHistory['tax']));
            $newHistory = array(
                'subTotal'      => strval(floatval($oldHistory['subTotal']) - $subtotal),
                'rewardsCredit' => strval(floatval($oldHistory['rewardsCredit']) - $rewardsCredit),
                'totalPrice'    => strval($total)
            );

            $this->orderItems_model->refundOrderItem($refundIds);
            $history = $this->orderHistory_model->updateRefundOrderHistory($oldHistory['orderId'], $newHistory);
            $details = $this->orderItems_model->getOrderDetails($oldHistory['orderId']);

            $this->data["order"] = $history;
            $this->data["orderDetails"] = $details;
            $this->data["error"] = false;
            $this->data['message'] = "Refunded Successfully.";  
        }

        $this->_JsonOutput();
    }



    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

    private function generateToken() {
        list($usec, $sec) = explode(" ", microtime());
        $microtime = ((float)$usec + (float)$sec);
        return md5($microtime);
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



    /////////////////////////////////  Store API  /////////////////////////////////


    public function storeLogin(Request $request) {
        
        $rules = array(
            'locationId' => 'required',
            'password'   => 'required'
        );

        $store = array(
            'locationId' => $request->get('locationId'), 
            'password'   => $request->get('password')
        );

        $validator = Validator::make($store, $rules);
        
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error' => true,
                'message' => $messages->all()
            );

        }else{

            $db_store = $this->location_model->getLocation($store['locationId']);

            if($db_store) {
                if(Hash::check($store['password'], $db_store['password'])) {

                    $this->data["error"] = false;
                    unset($db_store['password']);
                    $this->data['store'] = $db_store; 
                    $this->data['message'] = "Logged successfully.";
                } 
                else{
                    $this->data['error'] = true;
                    $this->data['message'] = 'Login failed. Incorrect credentials';
                }
            }else{
                $this->data['error'] = true;
                $this->data['message'] = "Login failed. Incorrect credentials";
            }
        }
        $this->_JsonOutput();
    }

    
    public function getStores() {
        $stores = $this->location_model->getStores();

        $this->data["error"] = false;
        $this->data["stores"] = $stores;
        $this->data['message'] = "Fetched Category.";    

        $this->_JsonOutput();
    }   

    public function getNewOrdersByStore(Request $request) {

        $rules = array(
            'storeId'     => 'required',
            'pageNumber'  => 'required'
        );

        $order = array(
            'storeId'     => $request->get('storeId'),
            'pageNumber'  => $request->get('pageNumber')
        );

        $validator = Validator::make($order, $rules);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error' => true,
                'message' => implode("\n", $messages->all())
            );
        } else {
            $orders = $this->orderHistory_model->getNewOrdersByStore($order['storeId']);
            $orderDetails = $this->orderItems_model->getAllOrderDetails();

            $result = array();
            $startPos = 20 * ($order['pageNumber']-1);

            if (count($orders) > 20 * $order['pageNumber']) {
                $endPos = 20 * $order['pageNumber'];
            } else {
                $endPos = count($orders);
            }

            for ($i=$startPos; $i < $endPos; $i++) { 
                $details = array();
                $temp1 = (array)$orders[$i];

                for ($j=0; $j < count($orderDetails); $j++) { 
                    $temp2 = (array)$orderDetails[$j];

                    if ($temp1['id'] == $temp2['orderId']) {
                        array_push($details, $orderDetails[$j]);                                
                    }
                }

                if (count($details) > 0) {
                    array_push($result, array(
                                            'id'            => $temp1['id'],
                                            'userId'        => $temp1['userId'],
                                            'email'         => $temp1['email'],
                                            'userName'      => $temp1['firstname']." ".$temp1['lastname'],
                                            'rewardStar'    => $temp1['rewardStar'],
                                            'details'       => $details,
                                            'subTotal'      => $temp1['subTotal'],
                                            'rewardsCredit' => $temp1['rewardsCredit'],
                                            'tax'           => $temp1['tax'],
                                            'totalPrice'    => $temp1['totalPrice'],
                                            'status'        => $temp1['status'],
                                            'timestamp'     => $temp1['timestamp']
                                            ));                                    
                }
                
            }

            $this->data["error"] = false;
            $this->data["orders"] = $result;
            $this->data["pageNumber"] = $order['pageNumber'];
            $this->data['message'] = "Fetched Order.";    
        }

        $this->_JsonOutput();
    }

    public function getCompletedOrdersByStore(Request $request) {

        $rules = array(
            'storeId'     => 'required',
            'pageNumber'  => 'required'
        );

        $order = array(
            'storeId'     => $request->get('storeId'),
            'pageNumber'  => $request->get('pageNumber')
        );

        $validator = Validator::make($order, $rules);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error' => true,
                'message' => implode("\n", $messages->all())
            );
        } else {
            $orders = $this->orderHistory_model->getCompletedOrdersByStore($order['storeId']);
            $orderDetails = $this->orderItems_model->getAllOrderDetails();

            $result = array();
            $startPos = 20 * ($order['pageNumber']-1);

            if (count($orders) > 20 * $order['pageNumber']) {
                $endPos = 20 * $order['pageNumber'];
            } else {
                $endPos = count($orders);
            }

            for ($i=$startPos; $i < $endPos; $i++) { 
                $details = array();
                $temp1 = (array)$orders[$i];

                for ($j=0; $j < count($orderDetails); $j++) { 
                    $temp2 = (array)$orderDetails[$j];

                    if ($temp1['id'] == $temp2['orderId']) {
                        array_push($details, $orderDetails[$j]);                                
                    }
                }

                if (count($details) > 0) {
                    array_push($result, array(
                                            'id'            => $temp1['id'],
                                            'userId'        => $temp1['userId'],
                                            'email'         => $temp1['email'],
                                            'userName'      => $temp1['firstname']." ".$temp1['lastname'],
                                            'rewardStar'    => $temp1['rewardStar'],
                                            'details'       => $details,
                                            'subTotal'      => $temp1['subTotal'],
                                            'rewardsCredit' => $temp1['rewardsCredit'],
                                            'tax'           => $temp1['tax'],
                                            'totalPrice'    => $temp1['totalPrice'],
                                            'status'        => $temp1['status'],
                                            'timestamp'     => $temp1['timestamp']
                                            ));                                    
                }
                
            }

            $this->data["error"] = false;
            $this->data["orders"] = $result;
            $this->data["pageNumber"] = $order['pageNumber'];
            $this->data['message'] = "Fetched Order.";    
        }

        $this->_JsonOutput();
    }


    public function searchOrders(Request $request) {


        $order = array(
            'firstName'    => $request->get('firstName'),
            'lastName'     => $request->get('lastName'),
            'order'        => $request->get('order'),
            'fromDate'     => $request->get('fromDate'),
            'toDate'       => $request->get('toDate'),
            'storeId'      => $request->get('storeId'),
            'kind'         => $request->get('kind')
        );

        if ($order['kind'] == "0") {
            $orders = $this->orderHistory_model->getNewOrdersByStore($order['storeId']);
        } else {
            $orders = $this->orderHistory_model->getCompletedOrdersByStore($order['storeId']);    
        }
        $orderDetails = $this->orderItems_model->getAllOrderDetails();


        $result = array();
        for ($i=0; $i < count($orders); $i++) { 
            
            $temp1 = (array)$orders[$i];

            if (!is_null($order['firstName']) && $temp1['firstname'] != $order['firstName']) {
                continue;
            }

            if (!is_null($order['lastName']) && $temp1['lastname'] != $order['lastName']) {
                continue;
            }

            if (!is_null($order['order']) && $temp1['id'] != $order['order']) {
                continue;
            }

            if (!is_null($order['fromDate']) && !is_null($order['toDate'])) {
                if ( (strtotime($temp1['timestamp']) < strtotime($order['fromDate']) ) || ( strtotime($temp1['timestamp']) > strtotime($order['toDate'])) ) {
                    continue;    
                }
            }

            $details = array();
            for ($j=0; $j < count($orderDetails); $j++) { 
                $temp2 = (array)$orderDetails[$j];

                if ($temp1['id'] == $temp2['orderId']) {
                    array_push($details, $orderDetails[$j]);                                
                }
            }

            if (count($details) > 0) {
                array_push($result, array(
                                        'id'            => $temp1['id'],
                                        'userId'        => $temp1['userId'],
                                        'email'         => $temp1['email'],
                                        'userName'      => $temp1['firstname']." ".$temp1['lastname'],
                                        'rewardStar'    => $temp1['rewardStar'],
                                        'details'       => $details,
                                        'subTotal'      => $temp1['subTotal'],
                                        'rewardsCredit' => $temp1['rewardsCredit'],
                                        'tax'           => $temp1['tax'],
                                        'totalPrice'    => $temp1['totalPrice'],
                                        'status'        => $temp1['status'],
                                        'timestamp'     => $temp1['timestamp']
                                        ));                                    
            }
            
        }

        $this->data["error"] = false;
        $this->data["orders"] = $result;
        $this->data['message'] = "Fetched Order.";

        $this->_JsonOutput();
    }


    public function getStoreById(Request $request) {
        
        $rules = array(
            'storeId' => 'required'
        );

        $store = array(
            'storeId' => $request->get('storeId')
        );

        $validator = Validator::make($store, $rules);
        
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error' => true,
                'message' => $messages->all()
            );

        }else{
            
            $db_store = $this->location_model->getStoreById($store['storeId']);
            
            if($db_store) {
                $this->data["error"] = false;
                $this->data['store'] = $db_store; 
                $this->data['message'] = "Fetched Store.";
            }else{
                $this->data['error'] = true;
                $this->data['message'] = "Failed.";
            }
        }
        $this->_JsonOutput();
    }


    public function updateStoreInfo(Request $request) {
        
        $rules = array(
            'storeId' => 'required', 
            'waiting' => 'required',
            'opening' => 'required',
            'closing' => 'required'
        );

        $store = array(
            'storeId' => $request->get('storeId'),
            'waiting' => $request->get('waiting'),
            'opening' => $request->get('opening'),
            'closing' => $request->get('closing')
        );

        $validator = Validator::make($store, $rules);
        
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error' => true,
                'message' => $messages->all()
            );

        }else{
            
            $db_store = Location::where('id', $store['storeId'])
                            ->first();

            if($db_store) {
                $db_store['waitingTime'] = $store['waiting'];
                $db_store['openingHour'] = $store['opening'];        
                $db_store['closingHour'] = $store['closing'];        
                $db_store = $this->location_model->updateStore($db_store);

                $this->data["error"] = false;
                $this->data['store'] = $db_store; 
                $this->data['message'] = "Updated successfully.";
            }else{
                $this->data['error'] = true;
                $this->data['message'] = "Update Failed.";
            }
        }
        $this->_JsonOutput();
    }


    public function authorizeCode(Request $request) {
        
        $rules = array(
            'locationId' => 'required',
            'password' => 'required'
        );

        $store = array(
            'locationId' => $request->get('locationId'), 
            'password' => $request->get('password')
        );

        $validator = Validator::make($store, $rules);
        
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error' => true,
                'message' => $messages->all()
            );

        }else{
            
            $db_store = $this->location_model->getLocation($store['locationId']);

            if($db_store) {
                if(Hash::check($store['password'], $db_store['password'])) {

                    $this->data["error"] = false;
                    $this->data['message'] = "Authorized successfully.";
                } 
                else{
                    $this->data['error'] = true;
                    $this->data['message'] = 'Authorized failed. Incorrect credentials';
                }
            }else{
                $this->data['error'] = true;
                $this->data['message'] = "Authorized failed. Incorrect credentials";
            }
        }
        $this->_JsonOutput();
    }


    public function refundOrderFromStore(Request $request) {

        $rules = array(
            'orderId'         => 'required',
            'subTotal'        => 'required',
            'rewardsCredit'   => 'required',
            'total'           => 'required',
            'tax'             => 'required',
            'refundItems'     => 'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error'   => true,
                'message' => "Failed refund order."
            );
        } else {
            $refundItems = $request->json()->get('refundItems');

            $oldHistory  = array(
                'orderId' => $request->json()->get('orderId'),
                'subTotal' => $request->json()->get('subTotal'),
                'rewardsCredit' => $request->json()->get('rewardsCredit'),
                'total' => $request->json()->get('total'),
                'tax' => $request->json()->get('tax'),
            );

            
            $res = 1;
            $refundIds = array();
            $subtotal = 0;
            $rewardsCredit = 0;
            $tax = 0;
            $total = 0;

            foreach ($refundItems as $key => $value) {
                if ($value['redeemed'] == "0") {
                    $subtotal = $subtotal + floatval($value['price']) * intval($value['quantity']);
                } else {
                    $rewardsCredit = $rewardsCredit + floatval($value['price']) * intval($value['quantity']);
                }
                
                array_push($refundIds, $value['id']);
            }

            $total = floatval($oldHistory['total']) - ($subtotal + ($subtotal-$rewardsCredit) * floatval($oldHistory['tax']));
            $newHistory = array(
                'subTotal' => strval(floatval($oldHistory['subTotal']) - $subtotal),
                'rewardsCredit' => strval(floatval($oldHistory['rewardsCredit']) - $rewardsCredit),
                'totalPrice' => strval($total)
            );

            $this->orderItems_model->refundOrderItem($refundIds);
            $history = $this->orderHistory_model->updateOrderHistory($oldHistory['orderId'], $newHistory);
            $details = $this->orderItems_model->getOrderDetails($oldHistory['orderId']);

            $result = array(
                        'id'            => $history['id'],
                        'userId'        => $history['userId'],
                        'email'         => $history['email'],
                        'userName'      => $history['firstname']." ".$history['lastname'],
                        'rewardStar'    => $history['rewardStar'],
                        'details'       => $details,
                        'subTotal'      => $history['subTotal'],
                        'rewardsCredit' => $history['rewardsCredit'],
                        'tax'           => $history['tax'],
                        'totalPrice'    => $history['totalPrice'],
                        'status'        => $history['status'],
                        'timestamp'     => $history['timestamp']
            );  

            $this->data["error"] = false;
            $this->data["orderInfo"] = $result;
            $this->data['message'] = "Refunded Successfully.";  
        }

        $this->_JsonOutput();
    }

    public function getCustomerInfo(Request $request) {

        $rules = array(
            'userId'      => 'required',
            'storeId'     => 'required',
            'pageNumber'  => 'required'
        );

        $order = array(
            'userId'      => $request->get('userId'),
            'storeId'     => $request->get('storeId'),
            'pageNumber'  => $request->get('pageNumber')
        );

        $validator = Validator::make($order, $rules);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error' => true,
                'message' => implode("\n", $messages->all())
            );
        } else {
            $orders = $this->orderHistory_model->getOrdersByUser($order['storeId'], $order['userId']);
            $orderDetails = $this->orderItems_model->getAllOrderDetails();

            $result = array();
            $startPos = 20 * ($order['pageNumber']-1);

            if (count($orders) > 20 * $order['pageNumber']) {
                $endPos = 20 * $order['pageNumber'];
            } else {
                $endPos = count($orders);
            }

            for ($i=$startPos; $i < $endPos; $i++) { 
                $details = array();
                $temp1 = (array)$orders[$i];

                for ($j=0; $j < count($orderDetails); $j++) { 
                    $temp2 = (array)$orderDetails[$j];

                    if ($temp1['id'] == $temp2['orderId']) {
                        array_push($details, $orderDetails[$j]);                                
                    }
                }

                if (count($details) > 0) {
                    array_push($result, array(
                                            'id'            => $temp1['id'],
                                            'userId'        => $temp1['userId'],
                                            'email'         => $temp1['email'],
                                            'userName'      => $temp1['firstname']." ".$temp1['lastname'],
                                            'rewardStar'    => $temp1['rewardStar'],
                                            'details'       => $details,
                                            'subTotal'      => $temp1['subTotal'],
                                            'rewardsCredit' => $temp1['rewardsCredit'],
                                            'tax'           => $temp1['tax'],
                                            'totalPrice'    => $temp1['totalPrice'],
                                            'status'        => $temp1['status'],
                                            'timestamp'     => $temp1['timestamp']
                                            ));                                    
                }
                
            }

            $this->data["error"] = false;
            $this->data["orders"] = $result;
            $this->data["pageNumber"] = $order['pageNumber'];
            $this->data['message'] = "Fetched Order.";    
        }

        $this->_JsonOutput();
    }

    public function addRewardsToUser(Request $request) {
        
        $rules = array(
            'storeId' => 'required',
            'userId'  => 'required',
            'rewards' => 'required',
            'password'=> 'required'
        );

        $store = array(
            'storeId'  => $request->get('storeId'), 
            'userId'   => $request->get('userId'), 
            'rewards'  => $request->get('storeId'), 
            'password' => $request->get('password')
        );

        $validator = Validator::make($store, $rules);
        
        if ($validator->fails())
        {
            $messages = $validator->messages();
            $this->data = array(
                'error' => true,
                'message' => $messages->all()
            );

        }else{
            
            $db_store = $this->location_model->getLocation($store['storeId']);

            if($db_store) {
                if(Hash::check($store['password'], $db_store['password'])) {

                    
                    $db_user = User::where('id', $store['userId'])
                            ->first();
                    $db_user['rewardStar'] = $db_user['rewardStar'] + intval($store['rewards']);        
                    $db_user = $this->user_model->updateUser($db_user);
                    if ($db_user) {
                        $this->data["error"] = false;
                        $this->data['message'] = "Authorized successfully.";    
                    } else {
                        $this->data['error'] = true;
                        $this->data['message'] = 'Failed add rewards.';
                    }
                    
                } 
                else{
                    $this->data['error'] = true;
                    $this->data['message'] = 'Authorized failed. Incorrect credentials';
                }
            }else{
                $this->data['error'] = true;
                $this->data['message'] = "Authorized failed. Incorrect credentials";
            }
        }
        $this->_JsonOutput();
    }

}