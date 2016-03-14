<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class MainController extends Controller
{
	public function wrapper($model)
	{
		$wrapper['status'] = BLANK;

		if($model){
			$model = is_array($model) ? $model : $model->toArray();
			$model = !isset($model[0]) ? $model : ['data' => $model];
			$wrapper = array_merge($wrapper, $model);

			$wrapper['status'] = !isset($model['errors']) ? SUCCESS : VALIDATION_ERRORS;
		}

		return $wrapper;
	}
}