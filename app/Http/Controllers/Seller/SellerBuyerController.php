<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use Illuminate\Http\Request;

class SellerBuyerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        //
        $buyers = $seller->products()
                ->whereHas('transactions')
                ->with('transactions.buyer') //relacion compuesta trae transaccions con el buyer
                ->get()
                ->pluck('transactions') // selecciona solo las transacciones
                ->collapse() //los coloca todo en una collecion
                ->pluck('buyer') //puedo seleccionar solo buyer
                ->unique() //evita repetidos
                ->values(); //elimina los elementos vacios
                return $this->showAll($buyers);
    }

}
