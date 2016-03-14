<?php

namespace App\Models;

use App\Libraries\Request;

class TransactionCategory extends MainModel
{
    /*
    |--------------------------------------------------------------------------
    | VARIABLES
    |--------------------------------------------------------------------------
    */

	#protected

    protected $table = 'transaction_categories';
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
        $this->name = Request::input('name');
        $this->icon_class = Request::input('icon_class');
        $this->hex_color = Request::input('hex_color');
        $this->user_id = (new User)->getLogOnData()->id;

        return $this->validSave();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    public function transaction()
    {
        return $this->belongsTo('App\Model\Transaction');
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
