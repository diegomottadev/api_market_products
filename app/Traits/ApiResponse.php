<?php

namespace App\Traits;

use Illuminate\Support\Collection;
// use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

trait ApiResponse{


    private function successResponse($data,$code){
        return response()->json($data,$code);
    }

    protected function showAll(Collection $collection,$code= 200){

        if($collection->isEmpty()){
            return  $this->successResponse(['data'=>$collection],$code);
        }
        $transformer = $collection->first()->transformer;

        $collection = $this->filterData($collection,$transformer);
        $collection = $this->sortData($collection,$transformer);
        $collection = $this->paginate($collection);
        $collection = $this->transformData($collection,$transformer);
        $collection = $this->cacheResponse($collection);

        return $this->successResponse($collection ,$code);
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
    //ordena las colecciones por attribute que se le mande
    protected function sortData(Collection $collection,$transformer ){
        if(request()->has('sort_by')){
            $attribute = $transformer::originalAttributes(request()->sort_by);
            $collection = $collection->sort_by->{$attribute};
        }
        return $collection;
    }
    //filtro por atributos
    protected function filterData(Collection $collection,$transformer ){
        foreach(request()->query() as  $query => $value){
            $attribute = $transformer::originalAttributes($query);

            if(isset($attribute,$value)){
                $collection = $collection->where($attribute,$value);
            }
        }

        return $collection;
    }

    //paginar colecciones
    protected function paginate(Collection $collection){
        //permitiendo el paginado personalizado
        $rules = [
            'per_page' => 'integer|min:2|max:50'
        ];
        Validator::validate(request()->all(),$rules);
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;

        if(request()->has('per_page')){
            $perPage = (int)request()->per_page;
        }
        $result = $collection->slice(($page -1) * $perPage,$perPage)->values();
        $paginate  = new LengthAwarePaginator($result,$collection->count(),$perPage,$page,['path'=>LengthAwarePaginator::resolveCurrentPath()]);
        $paginate->appends(request()->all());
        return $paginate;
    }

    protected function cacheResponse($data){
        $url = request()->url();
        $queryParams = request()->query();
        ksort($queryParams);
        $queryString = http_build_query($queryParams);
        $fullUrl = "{$url}?{$queryString}";
        return Cache::remember($fullUrl,15/60,function()use ($data){
            return $data;
        });
    }
}
