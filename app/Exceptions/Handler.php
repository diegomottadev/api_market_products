<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Asm89\Stack\CorsService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    // Excepciones relacionadas a peticiones https de cualquier indole
    public function render($request, Throwable $exception)
    {

        $response = $this->handleException($request,$exception);
        //agrega las cabeceras faltantas al request
        // app(CorsService::class)->addActualRequestHeaders($response,$request);

        return $response;
    }

    public function handleException($request, Exception $exception){
        if ($exception instanceof ValidationException){
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof ModelNotFoundException){
            $modelo = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("No existe una instancia de {$modelo} con el id identificado",400);
        }

        if ($exception instanceof AuthenticationException){
            return $this->unauthenticated($request, $exception);
        }


        if ($exception instanceof AuthorizationException){
            return $this->errorResponse('No posee permisos para ejecutar esta acción',403);
        }


        if ($exception instanceof NotFoundHttpException){
            return $this->errorResponse('No se encontro la url especificada',404);
        }

        if ($exception instanceof MethodNotAllowedHttpException){
            return $this->errorResponse('El método especificado en la petición no es válido',405);
        }

        if ($exception instanceof HttpException){
            return $this->errorResponse($exception->getMessage(),$exception->getStatusCode());
        }

        if ($exception instanceof QueryException){
            $codigo = $exception->errorInfo[1];
            if ($codigo ==1451){
                return $this->errorResponse("No se puede eliminar de forma permanente el recurso porque esta relacionado con algún otro.",409);
            }
        }

        if($exception instanceof TokenMismatchException){
            return redirect()->back()->withInput($request->input());
        }
        //Falla inesperada
        if (config('app.debug')){
            return parent::render($request, $exception);
        }
        return $this->errorResponse("Falla inesperada, Intente luego.",409);

    }
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();


        if($this->isFrontend($request)){
            return $request->ajax() ? response()->json($errors,422) :
                                      redirect()->back()
                                                ->with($request->input())
                                                ->withErrors($errors);
        }

        return response()->json($errors, 422);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {

        if($this->isFrontend($request)){
            return redirect()->guest('login');
        }

        return $this->errorResponse('No autenticado',404);
    }

    private function isFrontend($request){
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
