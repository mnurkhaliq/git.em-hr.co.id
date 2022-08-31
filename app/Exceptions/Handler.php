<?php

namespace App\Exceptions;

Use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Auth;

use \App\User;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson() ? response()->json(['message' => 'Unauthenticated.'], 401) : redirect('/'.(isset($_COOKIE['company_url']) ? $_COOKIE['company_url'] : 'login'));
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException) {
            if($exception->getPrevious() == null){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Token not found'
                ],404);
            }
            switch (get_class($exception->getPrevious())) {
                case \Tymon\JWTAuth\Exceptions\TokenExpiredException::class:
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Token has expired'
                    ], $exception->getStatusCode());
                case \Tymon\JWTAuth\Exceptions\TokenInvalidException::class:
                case \Tymon\JWTAuth\Exceptions\TokenBlacklistedException::class:
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Token is invalid'
                    ], $exception->getStatusCode());
                default:
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Token not provided'
                    ], $exception->getStatusCode());
                    break;
            }
        }
        if($request->wantsJson() && $exception instanceof HttpException){
            return response()->json(
                [
                    'status' => 'failed',
                    'message' => "Unknown error",
                ], $exception->getStatusCode());
        }
        if($request->wantsJson() && $exception instanceof NotFoundHttpException){
            return response()->json(
                [
                    'status' => 'failed',
                    'message' => "Data Not found",
                ], $exception->getStatusCode());
        }
//        if($request->wantsJson() && $exception instanceof \ErrorException){
//            return response()->json(
//                [
//                    'status' => 'failed',
//                    'message' => "Something is wrong with the server",
//                ], 500);
//        }

        return parent::render($request, $exception);
    }
}
