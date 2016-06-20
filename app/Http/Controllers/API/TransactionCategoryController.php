<?php

namespace App\Http\Controllers\API;

use App\Models\TransactionCategory as Model;
use MFebriansyah\LaravelAPIManager\Controllers\MainController;

class TransactionCategoryController extends MainController
{
	public function __construct(Model $model)
    {
        $this->model = $model;
    }
}