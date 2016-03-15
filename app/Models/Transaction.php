<?php

namespace App\Models;

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
        'source_id' => 'required|int',
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
        $this->amount = request()->input('amount');
        $this->source_id = request()->input('source_id');
        $this->transaction_category_id = request()->input('transaction_category_id');
        $this->description = request()->input('description');
        $this->image_url = request()->input('image_url');
        $this->geo_location = request()->input('geo_location');
        $this->latitude = request()->input('latitude');
        $this->longitude = request()->input('longitude');
        $this->altitude = request()->input('altitude');
        $this->transaction_at = request()->input('transaction_at');
        $this->user_id = (new User)->getLogOnData()->id;

        return $this->validSave();
    }

    #PUT

    public function putUpdate()
    {
        $this->amount = request()->input('amount');
        $this->source_id = request()->input('source_id');
        $this->transaction_category_id = request()->input('transaction_category_id');
        $this->description = request()->input('description');
        $this->image_url = request()->input('image_url');
        $this->geo_location = request()->input('geo_location');
        $this->latitude = request()->input('latitude');
        $this->longitude = request()->input('longitude');
        $this->altitude = request()->input('altitude');
        $this->transaction_at = request()->input('transaction_at');
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

        if($categories = request()->input('categories')){
            $categories = (is_array($categories)) ? $categories : [$categories];
            $model = $model->whereIn('transaction_category_id', $categories);
        }

        if($users = request()->input('users')){
            $users = (is_array($users)) ? $users : [$users];
            $model = $model->whereIn('user_id', $users);
        }

        $limit = request()->input('limit', 15);
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
