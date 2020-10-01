<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;
use App\Seller;
use App\Transaction;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //
    use SoftDeletes;
    
    const PRODUCTO_DISPONBLE = 'disponible';
    const PRODUCTO_NO_DISPONIBLE = "no disponible";
    protected $fillable =[
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id'
    ];

    public function estaDisponible(){
        return $this->status=== Product::PRODUCTO_DISPONBLE;
    }

    public function seller(){
        return $this->belongsTo(Seller::class);
    }


    public function transactions(){
        return $this->belongsToMany(Transaction::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }
}
