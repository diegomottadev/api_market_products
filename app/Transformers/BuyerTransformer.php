<?php

namespace App\Transformers;

use App\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Buyer $buyer)
    {
        return [
            //
            'identificador' => (int)$buyer->id,
            'nombre' => (string) $buyer->name,
            'correo'=> (string) $buyer->email,
            'esVerificado' => (int) $buyer->verified,
            'esAdministrador' => ($buyer->admin === 'true'),
            'fechaCreacion' =>(string)$buyer->created_at,
            'fechaActualizacion' => (string)$buyer->updated_at,
            'fechaEliminacion' => isset($buyer->deleted_at) ? (string) $buyer->deleted_at : null,
            'links'=>[
                [
                    'rel'=> 'self',
                    'href'=> route('buyers.show', $buyer->id)
                ],
                [
                    'rel'=> 'buyers.categories',
                    'href'=> route('buyers.categories.index', $buyer->id)
                ],
                [
                    'rel'=> 'buyers.products',
                    'href'=> route('buyers.products.index', $buyer->id)
                ],
                [
                    'rel'=> 'buyers.sellers',
                    'href'=> route('buyers.sellers.index', $buyer->id)
                ],
                [
                    'rel'=> 'buyers.transactions',
                    'href'=> route('buyers.transactions.index', $buyer->id)
                ],
                [
                    'rel'=> 'user',
                    'href'=> route('users.show', $buyer->id)
                ],
            ],
        ];
    }

    public static function originalAttributes($index){
        $attributes = [
            //
            'identificador' => 'id',
            'nombre' => 'nombre',
            'correo'=> 'email',
            'esVerificado' => 'verified',
            'esAdministrador' =>'admin',
            'fechaCreacion' =>'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'deleted_at'
        ];

        return isset($attributes[$index]) ? $attributes[$index]:null;
    }


    public static function transformedAttributes($index){
        $attributes = [
            //
             'id' => 'identificador' ,
             'nombre' => 'nombre' ,
             'email' => 'correo',
             'verified' => 'esVerificado' ,
             'admin' => 'esAdministrador' ,
             'created_at' => 'fechaCreacion' ,
             'updated_at' => 'fechaActualizacion' ,
             'deleted_at' => 'fechaEliminacion',
        ];

        return isset($attributes[$index]) ? $attributes[$index]:null;
    }
}
