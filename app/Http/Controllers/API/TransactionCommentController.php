<?php

namespace App\Http\Controllers\API;

use App\Models\TransactionComment as Model;
use MFebriansyah\LaravelAPIManager\Controllers\MainController;

class TransactionCommentController extends MainController
{
	public function __construct(Model $model)
    {
        $this->model = $model;
    }
}