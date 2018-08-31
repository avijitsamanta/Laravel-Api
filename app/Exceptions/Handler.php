<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\QueryException;
use App\Traits\ApiResponse;

class Handler extends ExceptionHandler {

      /**
       * A list of the exception types that are not reported.
       *
       * @var array
       */
      use ApiResponse;

      protected $dontReport = [
          //

          AuthenticationException::class,
          AuthorizationException::class,
          HttpException::class,
          HttpResponseException::class,
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
       * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
       *
       * @param  \Exception  $exception
       * @return void
       */
      public function report(Exception $exception) {
            parent::report($exception);
      }

      /**
       * Render an exception into an HTTP response.
       *
       * @param  \Illuminate\Http\Request  $request
       * @param  \Exception  $exception
       * @return \Illuminate\Http\Response
       */
      public function render($request, Exception $exception) {
            if ($exception instanceof ValidationException) {

                  return $this->convertValidationExceptionToResponse($exception,
                                  $request);
            }
            if ($exception instanceof ModelNotFoundException) {

                  $modelname = strtolower(class_basename($exception->getModel()));

                  return $this->errorResponse("Does not exists any model {$modelname} with this specifier",
                                  404);
            }
             if ($exception instanceof AuthenticationException) {

                  return $this->unauthenticated($request,$exception);
            }
             if ($exception instanceof AuthorizationException) {

                  return $this->errorResponse($exception->getMessage(),
                                  403);
            }
            if ($exception instanceof NotFoundHttpException) {

                  return $this->errorResponse("Url not found",
                                  404);
            }
            if ($exception instanceof MethodNotAllowedHttpException) {

                  return $this->errorResponse("Method not available for the request.",
                                  405);
            }
            if ($exception instanceof HttpException) {

                  return $this->errorResponse($exception->getMessage(),
                                  $exception->getStatusCode());
            }
            if ($exception instanceof QueryException) {

                  $errorCode = $exception->errorInfo[1];
                  if($errorCode == 1451)
                  {
                         return $this->errorResponse("Can not remove this resorce permanantly",
                                409);
                  }
            }  
            
           // return $this->errorResponse("Unexpected ex!",500);
             return parent::render($request,
                            $exception);
           
      }
      
      protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('Unauthenticated.', 401);
    }
      
      protected function convertValidationExceptionToResponse(ValidationException $e,$request) {
            
            $errors = $e->validator->errors()->getMessages();
            
            return $this->errorResponse($errors,
                            422);
      }

}
