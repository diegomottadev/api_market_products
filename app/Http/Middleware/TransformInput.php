<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next,$transform)
    {
        $transformedInputs = [];
        foreach($request->request->all() as $input=>$value){
            $transformedInputs[$transform::originalAttributes($input)] = $value;

        }

        $request->replace($transformedInputs);

        $response = $next($request);

        if (isset($response->exception) && $response->exception instanceof ValidationException){
            $data = $response->getData();
            $transformedErrors = [];

            foreach ($data as $field => $error){
                $transformedField = $transform::transformedAttributes($field);
                $transformedErrors[$transformedField] = str_replace($field, $transformedField, $error[0]);
            }

            $response->setData($transformedErrors);
        }

        return $response;
    }
}
