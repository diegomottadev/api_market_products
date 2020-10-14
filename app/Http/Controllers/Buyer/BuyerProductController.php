<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;

class BuyerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        //Obtiene todos los productos de cada transaccion de un buyer
        $products = $buyer->transactions()->with('product')
                    ->get()
                    ->pluck('product');

        return $this->showAll($products);
    }


}
