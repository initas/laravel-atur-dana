<?php

namespace App\Models;

use MFebriansyah\LaravelContentManager\Models\MainModel;

class Source extends MainModel
{
    /*
    |--------------------------------------------------------------------------
    | VARIABLES
    |--------------------------------------------------------------------------
    */

	#protected

    protected $table = 'sources';

    #public

    public $hide = ['user_id', 'created_at', 'status_id'];
    public $add = ['amount', 'transaction_count', 'collaborator_count', 'user', 'collaborators'];
    public $rules = [
        'name' => 'required',
        'hex_color' => 'required',
        'icon_class' => 'required'
    ];

    /*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
    */

    #POST

    public function postNew()
    {
        $this->name = request()->input('name');
        $this->icon_class = request()->input('icon_class');
        $this->hex_color = request()->input('hex_color');
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

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction', 'source_id', 'id');
    }

    public function transfers()
    {
        return $this->hasMany('App\Models\Transaction', 'to_source_id', 'id');
    }

    public function collaborators()
    {
        return $this->belongsToMany('App\Models\User', 'sources_collaborators', 'source_id', 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGGED ON APPENDS
    |--------------------------------------------------------------------------
    */

    public function getTransactionCountAttribute()
    {
        return $this->transactions()->count() + $this->transfers()->count();
    }

    public function getCollaboratorCountAttribute()
    {
        return $this->collaborators()->count();
    }

    public function getAmountAttribute()
    {
        return $this->transactions()->sum('amount') + $this->transfers()->sum('amount');
    }

    public function getUpdatedAtAttribute($value)
    {
        return strtotime($value);
    }

    public function getCollaboratorsAttribute()
    {
        $user = new User();

        $filter = $this->collaborators()
            ->select(['users.id', 'full_name', 'users.image_url', 'username'])
            ->get();

        return $user->setAppends(['image'])
            ->setHidden($user->hide)
            ->transform($filter);
    }

    public function getUserAttribute()
    {
        return $this->user()
            ->select(['id', 'full_name', 'image_url', 'username'])
            ->first()
            ->setHidden(['image_url'])
            ->setAppends(['image']);
    }
}
