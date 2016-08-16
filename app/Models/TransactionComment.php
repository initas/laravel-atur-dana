<?php

namespace App\Models;

use MFebriansyah\LaravelContentManager\Models\MainModel;

class TransactionComment extends MainModel
{
    /*
    |--------------------------------------------------------------------------
    | VARIABLES
    |--------------------------------------------------------------------------
    */

	#protected

    protected $table = 'transaction_comments';

    #public

    public $hide = ['user_id', 'transaction_id', 'created_at', 'status_id'];
    public $add = ['user'];
    public $rules = [
        'transaction_id' => 'required|int',
        'description' => 'required'
    ];


    /*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
    */

    #POST

    public function postNew()
    {
        $this->description = request()->input('description');
        $this->transaction_id = request()->input('transaction_id');
        $this->user_id = (new User)->getLogOnData()->id;

        return $this->validSave();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

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

        if($transaction = request()->input('transaction')){
            $model = $model->where('transaction_id', $transaction);
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

    public function getUpdatedAtAttribute($value)
    {
        return strtotime($value);
    }

    public function getUserAttribute($value)
    {
        return $this->user()
            ->select(['id', 'full_name', 'image_url'])
            ->first()
            ->setHidden(['image_url'])
            ->setAppends(['image']);
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
