<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Request;
use Validator;

class MainModel extends Model
{
    /*
    |--------------------------------------------------------------------------
    | VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $imagesFolder = SERVER.'/image/embed';

    protected $coverResolutions = [
        'on_demand' => '/{$1}/{$2}',
    ];
    
	/*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
    */

	public function transform($model)
	{
        if(!(new User)->getLogOnData()){
        	$this->appends = array_diff($this->appends, ['logged_on_user']);
        }

		foreach ($model as $key => $value) {
			$value->append($this->appends)->setHidden($this->hidden);
		}

		return $model;
	}

	public function one($id = null)
	{
        if(!(new User)->getLogOnData()){
        	$this->appends = array_diff($this->appends, ['logged_on_user']);
        }

		if($id){
			$model = $this->find($id);
		}else{
			$model = $this->first();
		}
		
		$model = ($model) ? $model->append($this->appends)->setHidden($this->hidden) : $model;

		return $model;
	}

    public function getImageAttribute()
    {
        if($this->image_url){
            $images['original'] = $this->imagesFolder.'/'.$this->image_url.'?index='.INDEX;

            foreach($this->coverResolutions as $name => $value){
                $images[$name] = $this->imagesFolder.$value.'/'.$this->image_url.'?index='.INDEX;
            }
        }else{
            $images['original'] = $this->imagesFolder.'/not-found?index='. INDEX;;

            foreach($this->coverResolutions as $name => $value){
                $images[$name] = $this->imagesFolder.$value.'/not-found?index='.INDEX;;
            }
        }

        return $images;
    }

    public function validSave(){
        $validator = Validator::make(Request::all(), $this->rules);

        if($validator->fails()){
            $model['errors'] = $validator->errors();
        }else{
            $this->save();

            $model = $this->setHidden($this->hide)
                ->setAppends($this->add)
                ->one($this->id); 
        }

        return $model;
    }

	public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}