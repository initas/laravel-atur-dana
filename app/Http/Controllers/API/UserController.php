<?php

namespace App\Http\Controllers\API;

use App\Model\User as Model;

class UserController extends MainController
{
	/*
    |--------------------------------------------------------------------------
    | VARIABLES
    |--------------------------------------------------------------------------
    */

	protected $model;

	/*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
    */

    #GET

	public function getAll()
	{
		return $this->wrapper(
			$this->model->getAll()
		);
	}

	public function getOne($id)
	{
		return $this->wrapper(
			$this->model->getOne($id)
		);
	}

	#POST

	public function postNew()
	{
		return $this->wrapper(
			$this->model->postNew()
		);
	}

	public function postLogin()
	{
		return $this->wrapper(
			$this->model->postLogin()
		);
	}

	#PUT

	public function putUpdate($id)
	{
		$model = $this->model->find($id);

		if($model){
			$model = $model->putUpdate();
		}

		return $this->wrapper($model);
	}

	#DELETE

	public function deleteRecord($id)
	{
		$model = $this->model->select('status_id')->find($id);

		if($model){
			$model = $model->deleteRecord();
		}

		return $this->wrapper($model);
	}

	/*
    |--------------------------------------------------------------------------
    | CONSTRUCTOR
    |--------------------------------------------------------------------------
    */
    
	public function __construct(Model $model)
    {
        $this->model = $model;
    }
}