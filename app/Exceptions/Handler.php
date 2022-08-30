<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Swift_TransportException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;


class Handler extends ExceptionHandler
{
    use ApiResponseTrait;
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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    public function render($request, Throwable $e): Response
    {
        if ($e instanceof AuthenticationException) {
            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => 'Unauthenticated or Token Expired, Please Login'
                ],
                401
            );
        }
        if ($e instanceof ThrottleRequestsException) {
            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => 'Too Many Requests,Please Slow Down'
                ],
                429
            );
        }
        if ($e instanceof ModelNotFoundException) {
            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => 'Entry for ' . str_replace('App\\', '', $e->getModel()) . ' not found'
                ],
                404
            );
        }
        if ($e instanceof ValidationException) {

            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => $e->errors()
                ],
                422
            );
        }
        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'exception' => $e
                ],
                405
            );
        }
        if ($e instanceof QueryException) {
            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => 'There was Issue with the Query',
                    'exception' => $e

                ],
                500
            );
        }
        if ($e instanceof Swift_TransportException) {
            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => 'Connection could not be established with mail host',
                    'exception' => $e
                ],
                500
            );
        }

        if ($e instanceof NotFoundHttpException) {
            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => 'Route not found',
                    'exception' => $e
                ],
                404
            );
        }

        if ($e instanceof AuthorizationException) {
            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'exception' => $e
                ],
                403
            );
        }
        if ($e instanceof \Error) {
            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => "There was some internal error",
                    'exception' => $e
                ],
                500
            );
        }
        return parent::render($request, $e);
    }
}
