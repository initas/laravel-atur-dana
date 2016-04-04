<?php

namespace App\Models;

class Source extends MainModel
{
    /*
    |--------------------------------------------------------------------------
    | VARIABLES
    |--------------------------------------------------------------------------
    */

	#protected

    protected $table = 'sources';
    protected $hide = ['user_id', 'created_at', 'status_id'];
    protected $add = ['amount', 'transaction_count', 'collaborator_count', 'user', 'collaborators'];

    #public

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

    public function getAll()
    {
        $model = $this->setAppends($this->add)
            ->setHidden($this->hide)
            ->transform($this->paginate(15));

        return $model;
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
        return 121;
    }

    public function getCollaboratorCountAttribute()
    {
        return 77;
    }

    public function getAmountAttribute()
    {
        return 127000000000;
    }

    public function getUpdatedAtAttribute($value)
    {
        return strtotime($value);
    }

    public function getCollaboratorsAttribute()
    {
        $user = new User();

        $filter = $this->collaborators()
            ->select(['users.id', 'full_name', 'users.image_url'])
            ->get();

        return $user->setAppends(['image'])
            ->setHidden($user->hide)
            ->transform($filter);
    }

    public function getUserAttribute()
    {
        return $this->user()
            ->select(['id', 'full_name', 'image_url'])
            ->first()
            ->setHidden(['image_url'])
            ->setAppends(['image']);
    }
}
