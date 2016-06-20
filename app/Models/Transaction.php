<?php

namespace App\Models;

use MFebriansyah\LaravelContentManager\Model\MainModel;

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

    #public

    public $hide = ['user_id', 'source_id', 'to_source_id', 'transaction_category_id', 'image_url', 'geo_location', 'latitude', 'longitude', 'altitude', 'created_at', 'status_id'];
    public $add = ['user', 'source', 'to_source', 'category', 'image', 'location', 'logged_on_user'];
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

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->hasOne('App\Models\TransactionCategory', 'id', 'transaction_category_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\TransactionComment');
    }

    public function likes()
    {
        return $this->belongsToMany('App\Models\User', 'transaction_likes', 'transaction_id', 'user_id');
    }

    public function source()
    {
        return $this->hasOne('App\Models\Source', 'id', 'source_id');
    }

    public function toSource()
    {
        return $this->hasOne('App\Models\Source', 'id', 'to_source_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
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
        $result = $this->category()->select(['id', 'name', 'hex_color', 'icon_class'])->first();

        if($this->to_source_id){
            $result->hex_color = $this->source()->pluck('hex_color')->first();
        }

        return $result;
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

    public function getSourceAttribute()
    {
        return $this->source()->select(['id', 'name', 'hex_color'])->first();
    }

    public function getToSourceAttribute()
    {
        return $this->toSource()->select(['id', 'name', 'hex_color'])->first();
    }

    public function getUserAttribute()
    {
        return $this->user()
            ->select(['id', 'full_name', 'image_url'])
            ->first()
            ->setHidden(['image_url'])
            ->setAppends(['image']);
    }

    public function getUpdatedAtAttribute($value)
    {
        return strtotime($value);
    }

    public function getTransactionAtAttribute($value)
    {
        return strtotime($value);
    }

    public function getImageAttribute($value, $fieldName = 'image_url'){
        if($this->$fieldName){
            $images['original'] = $this->imagesFolder.'/'.$this->$fieldName.'?index='.INDEX;

            foreach($this->coverResolutions as $name => $value){
                $images[$name] = $this->imagesFolder.$value.'/'.$this->$fieldName.'?index='.INDEX;
            }
        }else{
            $images['original'] = null;

            foreach($this->coverResolutions as $name => $value){
                $images[$name] = null;
            }
        }

        return $images;
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
