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
        return $this->successResponse(['data'=>$colletion],$code);
    }
    protected function showOne(Model $instance,$code= 200){
        return $this->successResponse(['data'=>$instance],$code);
    }

    protected function errorResponse($message,$code){
        return response()->json([   'error'=> $message,'code'=>$code],$code);
    }
}
