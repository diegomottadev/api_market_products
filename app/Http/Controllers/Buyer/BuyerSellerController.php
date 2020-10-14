<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        //obtiene los vendedores de cada producto de todos las transacciones de un buyer
        $seller =   $buyer->transactions()->with('product.seller')
                    ->get()
                    ->pluck('product.seller')
                    ->unique('id')
                    ->values(); //limpia los espacios vacios de la coleccion;
        return $this->showAll($seller);
    }


}
