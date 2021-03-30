<?php

namespace App\Traits;

use Illuminate\Support\Collection;
// use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponse{


    private function successResponse($data,$code){
        return response()->json($data,$code);
    }

    protected function showAll(Collection $colletion,$code= 200){

        if($colletion->isEmpty()){
            return  $this->successResponse(['data'=>$colletion],$code);
        }
        $transformer = $colletion->first()->transformer;

        $colletion = $this->transformData($colletion,$transformer);
        return $this->successResponse($colletion,$code);
    }
    protected function showOne(Model $instance,$code= 200){
        $transformer = $instance->transformer;
        $instance = $this->transformData($instance,$transformer);

        return $this->successResponse($instance,$code);
    }

    protected function errorResponse($message,$code){
        return response()->json([   'error'=> $message,'code'=>$code],$code);
    }

    protected function transformData($data,$tranformer){
        $transformation = fractal($data,new $tranformer);
        return $transformation->toArray();
    }
}
