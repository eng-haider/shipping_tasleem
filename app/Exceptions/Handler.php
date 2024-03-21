<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
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
     * Illuminate\Database\QueryException
     */
    public function render($request, Throwable $exception)
    {
        //    if ($exception instanceof ValidationException) {
        //        return response()->json($exception->validator->errors()->messages(), 400);
        //    }

        if ($exception instanceof ValidationException) {
            $errors = $exception->validator->errors()->all();
            return response()->json([
                'status' => false,
                'message' => $exception->validator->errors()->messages(),
                'data' => []

                
            ], 400);
        }
        

           // catch exception of authorization of any API
           if ($exception instanceof AuthorizationException) {
               return response()->json([
                'status' => false,
                'message' => 'model not found',
                'data' => []
           ], 403);
           }

           // catch exception of model not found of any API
           if ($exception instanceof  ModelNotFoundException ) 
           {
               return response()->json([
                    'status' => false,
                    'message' => 'model not found',
                    'data' => []
            ], 404);
           }

           // catch exception of Route Not Found of any API
           if ($exception instanceof NotFoundHttpException) 
           {
               return response()->json([
                    'status' => false,
                    'message' => 'route not found',
                    'data' => []
            ], 404);
           }

           // catch exception of Forbidden of any API
           if ($exception instanceof UnauthorizedException) 
           {
               return response()->json([
                    'status' => false,
                    'message' => $exception->getMessage(),
                    'data' => []
               ], 403);
           }

           // catch exception of QueryException of any API
           if ($exception instanceof QueryException) 
           {
                if (str_contains($exception->getMessage(), 'Duplicate entry')) {
                    
                    return response()->json([
                        'status' => false,
                        'message' => "this item already exists",
                        'data' => []
                    ]   , 400);
                }
                return response()->json([
                    'status' => false,
                    'message' => 'invalid query',
                'data' => []
                ], 500);
           }
           //catch any exception of any API 
           if($exception->getMessage() == 'Unauthenticated.'){
               return response()->json([
                    'status' => false,
                    'message' => $exception->getMessage(),
                    'data' => []
                ], 401);
           }
           return response()->json([
                    'status' => false,
                    'message' => ($exception->getMessage()) ? $exception->getMessage() : 'internal server error',
                    'data' => []
                ], 500);
    }
}