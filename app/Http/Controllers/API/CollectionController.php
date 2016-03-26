<?php

namespace App\Http\Controllers\API;

use App\Models\Transaction as Transaction;
use App\Models\Source as Source;

class CollectionController extends MainController
{
	/*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
    */

    #GET

    public function getDashboard()
    {
        $transaction = new Transaction();
        $transactions = $transaction->getAll()->toArray();

        $source = new Source();
        $sources = $source->getAll()->toArray();

        return [
            'status' => SUCCESS,
            'transactions' => $transactions['data'],
            'sources' => $sources['data'],
            'total_amount' => 2000
        ];
    }

}