<?php

namespace App\Http\Controllers\API;

use App\Models\Transaction as Model;
use MFebriansyah\LaravelAPIManager\Controllers\MainController;

class TransactionController extends MainController
{
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