<?php

namespace App\Http\Controllers\API;

use App\Models\Source as Model;
use MFebriansyah\LaravelAPIManager\Controllers\MainController;

class SourceController extends MainController
{
	public function __construct(Model $model)
    {
        $this->model = $model;
    }
}