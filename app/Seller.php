<?php

namespace App;
use App\Product;
use App\Scopes\SellerScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seller extends User
{
    //
    use SoftDeletes;
    //Aplicara automaticamente esta restriccion
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new SellerScope);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }
}
