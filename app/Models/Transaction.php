<?php

namespace App\Models;

use App\Libraries\Request;

class Transaction extends MainModel
{
    /*
    |--------------------------------------------------------------------------
    | VARIABLES
    |--------------------------------------------------------------------------
    */

    #protected

	protected $table = 'transactions';
    protected $hidden = ['pivot'];
    protected $hide = ['user_id', 'transaction_category_id', 'image_url', 'geo_location', 'latitude', 'longitude', 'altitude', 'created_at', 'status_id'];
    protected $add = ['user', 'category', 'image', 'location', 'logged_on_user'];

    #public
    
    public $rules = [
        'amount' => 'required|numeric',
        'transaction_category_id' => 'required|int'
    ];

	/*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
    */

    #GET
    
    public function getAll()
    {
        return $this->setAppends($this->add)
            ->setHidden($this->hide)
            ->transform($this->filter());
    }

    public function getOne($id)
    {
        return $this->setHidden($this->hide)
            ->setAppends($this->add)
            ->one($id);
    }

    #POST

    public function postNew()
    {
        $this->amount = Request::input('amount');
        $this->transaction_category_id = Request::input('transaction_category_id');
        $this->description = Request::input('description');
        $this->image_url = Request::input('image_url');
        $this->geo_location = Request::input('geo_location');
        $this->latitude = Request::input('latitude');
        $this->longitude = Request::input('longitude');
        $this->altitude = Request::input('altitude');
        $this->transaction_at = Request::input('transaction_at');
        $this->user_id = (new User)->getLogOnData()->id;

        return $this->validSave();
    }

    #PUT

    public function putUpdate()
    {
        $this->amount = Request::input('amount');
        $this->transaction_category_id = Request::input('transaction_category_id');
        $this->description = Request::input('description');
        $this->image_url = Request::input('image_url');
        $this->geo_location = Request::input('geo_location');
        $this->latitude = Request::input('latitude');
        $this->longitude = Request::input('longitude');
        $this->altitude = Request::input('altitude');
        $this->transaction_at = Request::input('transaction_at');
        $this->user_id = (new User)->getLogOnData()->id;

        return $this->validSave();
    }

    #DELETE

    public function deleteRecord()
    {
        $this->status_id = 0;
        $this->save();

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->hasOne('App\Models\TransactionCategory', 'id', 'transaction_category_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\TransactionComment');
    }

    public function likes()
    {
        return $this->belongsToMany('App\Models\User', 'transaction_likes', 'transaction_id', 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SORT & FILTERS
    |--------------------------------------------------------------------------
    */

    public function filter($model = null)
    {
        $model = ($model) ? $model : $this;
        $model = $model->where('status_id', '!=', 0)->orderBy('id');

        if($categories = Request::input('categories')){
            $categories = (is_array($categories)) ? $categories : [$categories];
            $model = $model->whereIn('transaction_category_id', $categories);
        }

        if($users = Request::input('users')){
            $users = (is_array($users)) ? $users : [$users];
            $model = $model->whereIn('user_id', $users);
        }

        $limit = Request::input('limit', 15);
        $model = $model->paginate($limit);

        return $model;
    }

    /*
    |--------------------------------------------------------------------------
    | APPENDS
    |--------------------------------------------------------------------------
    */

    public function getCategoryAttribute()
    {
        return $this->category()->select(['id', 'name'])->first();
    }

    public function getUserAttribute()
    {
        return $this->user()->select(['id', 'full_name'])->first();
    }

    public function getLocationAttribute()
    {
        return [
                'name' => $this->geo_location,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'altitude' => $this->altitude,
        ];
    }

    public function getUpdatedAtAttribute($value)
    {
        return strtotime($value);
    }

    public function getTransactionAtAttribute($value)
    {
        return strtotime($value);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGGED ON APPENDS
    |--------------------------------------------------------------------------
    */

    public function getLoggedOnUserAttribute()
    {

    }
}
