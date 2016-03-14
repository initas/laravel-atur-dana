<?php

namespace App\Models;

use App\Libraries\Request;

class User extends MainModel
{
    /*
    |--------------------------------------------------------------------------
    | VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'users';
    protected $hidden = ['password'];
    protected $hide = ['password', 'fb_id', 'unique_id', 'created_at', 'status_id', 'auth_token'];

    /*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
    */

    public function getOne($id)
    {
        $model = $this->setHidden($this->hide)->one($id);

        return $model;
    }

    public function getAll()
    {
        $model = $this->setHidden($this->hide)
            ->transform($this->paginate(15));

        return $model;
    }

    public function getUniqueId($uniqueId = 0, $field = 'unique_id')
    {
        if(!$uniqueId){
            $count = $this->count();
            $uniqueId = rand(0, 100).$count.($uniqueId+1).NOW;
        }

        $model = $this->where($field, $uniqueId)->count();

        if($model > 0){
            $this->getUniqueId($uniqueId);
        }

        return $uniqueId;
    }

    public function getUniqueUsername(){}
    public function postNew(){}
    public function puUpdate(){}

    public function postLogIn()
    {
        $username = Request::input('username', Request::input('email'));
        $password = Request::input('password');

        $hide = array_diff($this->hide, ['auth_token']);

        $model = $this->where('username', $username)
            ->orWhere('email', $username)
            ->first();

        $model = ($model) ? $model->setHidden($hide) : $model;

        $compare = false;

        if($model){
            $compare = self::compareHash($password, $model->password);
        }else{
            if($model){
                $compare = true;
            }
        }

        if($compare){
            $_SESSION['user'] = $model->toArray();
            $model->last_login_at = TODAY_LABEL;
            $model->auth_token = $this->getUniqueId(md5(NOW), 'auth_token');
            $model->save();
        }else{
            $model = null;
        }

        return $model;
    }

    public function postLogOut()
    {
        $model = null;
        $auth_token = Request::header('auth-token', Request::header('username'));

        // for client (android and ios)
        if ($auth_token) {
            
            $member = User::where('auth_token', $auth_token)->first();
            
            if ($member) {
                $member->auth_token = null;
                $member->save();
            }
        }

        // for web version
        if(Request::session('user')){
            $model = $this->find(Request::session('user')['id']);
            $model->auth_token = null;
            $model->save();
        }

        unset($_SESSION['user']);

        return $model;
    }

    public function getLogOnData()
    {
        $model = $this->getAPILogOnData();

        if(!$model) {
            $model = $this->getHTTPLogOnData();
        }
        
        return $model;
    }

    public function getHTTPLogOnData()
    {
        $user = null;

        if (Request::session('user')){
            $user = Request::session('user');
            $user = User::find($user['id']);
        }

        return $user;
    }

    public function getAPILogOnData()
    {
        $auth_token = Request::header('auth-token');

        $user = $this->where(\DB::raw('(
                (username = "'.$auth_token.'" or email = "'.$auth_token.'")
                or (auth_token = "'.$auth_token.'" and auth_token is not null)
            )'), true)
            ->first();

        return $user;
    }

    public function toHash($string, $random = null)
    {
        $random = ($random) ? $random : rand(10, 30);
        $string = md5($string);
        $start = md5(substr($string, 0, $random));
        $end = md5(substr($string, $random, 99));
        $hash = $random.$start.$end;

        return $hash;
    }
    
    public function compareHash($string, $toCompare)
    {
        $random = substr($toCompare, 0, 2);
        $hash = self::toHash($string, $random);

        return ($hash == $toCompare);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */
   
    public function comments()
    {
        return $this->hasMany('App\Model\ContentComment');
    }
}
