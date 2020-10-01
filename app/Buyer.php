<?php

namespace App;

use App\Scopes\BuyerScope;
use App\Transaction;
use Illuminate\Database\Eloquent\SoftDeletes;

class Buyer extends User
{
    use SoftDeletes;
    //Aplicara automaticamente esta restriccion
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new BuyerScope);
    }


    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
}
