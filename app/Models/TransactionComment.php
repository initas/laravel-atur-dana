<?php

namespace App\Models;

use MFebriansyah\LaravelContentManager\Model\MainModel;

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
