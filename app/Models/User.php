<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }
    public function getReminderEmail()
    {
        return $this->email;
    }

    public function getUsers()
    {
        $users = DB::table($this->table)
            ->where('isAdmin', '0')
            ->orderBy('id')
            ->get();
               
        return $users;
    }  
    
    public function addUser($user)
    {
        DB::table($this->table)->insert(
            $user
        );
        $user_id = DB::getPdo()->lastInsertId();
        return $user_id;
    }

    public function updateUser($user) {
        User::where('id', $user['id'])
                ->update(
                    array(
                        'email'         => $user['email'],
                        'firstname'     => $user['firstname'],
                        'lastname'      => $user['lastname'],
                        'image'         => $user['image'],
                        'rewardStar'    => $user['rewardStar'],
                        'balance'       => $user['balance'],
                        'password'      => $user['password'],
                        'device'        => $user['device'],
                        'device_token'  => $user['device_token']
                    )
                );

        $user = User::where('id', $user['id'])
        ->first();

        return $user;
    }

      
    public function forgotPassword($email){
        $user = User::where('email', $email)->first();
        $result = array('error' => false);
        if(!$user){
            $result['error'] = true;
            $result['message'] = 'Invalid user.';
        }else{
            $forgotToken = md5($this->microtime_float().$email);
            User::where('id', $user->id)
                ->update(
                    array(
                        'forgot_token' => $forgotToken
                    )
                );
            $result['message'] = $forgotToken;
            $result['email'] = $user->email;
        }
        return $result;
    }
    
    function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

}
