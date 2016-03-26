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
    protected $hide = ['created_at', 'status_id'];
    protected $add = [];

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
        return $this->belongsTo('App\Model\User');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGGED ON APPENDS
    |--------------------------------------------------------------------------
    */

    public function getUpdatedAtAttribute($value)
    {
        return strtotime($value);
    }

}
