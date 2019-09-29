<?php

namespace App\Exceptions;

use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
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
     * @param  Exception  $exception
     *
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }
    
    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request    $request
     * @param  Exception  $exception
     *
     * @return Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            Auth::logout();
            Session::flush();
            
            return redirect()->back();
        }
        
        if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
            return response()->json([
                'error' => 'Resource not found',
            ], 404);
        }
        if ($exception instanceof NotFoundHttpException && $request->wantsJson()) {
            return response()->json([
                'error' => 'not found',
            ], 404);
        }
    
        if ($exception instanceof HttpException && $request->wantsJson()) {
            return response()->json([
                'error' => $exception->getMessage(),
            ], $exception->getStatusCode());
        
        }
        
        return parent::render($request, $exception);
    }
    
    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  Request                  $request
     * @param  AuthenticationException  $exception
     *
     * @return Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        
        return redirect()->guest('login');
    }
}
