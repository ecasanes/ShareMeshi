<?php

namespace App\Exceptions;

use App\ShareMeshi\Helpers\Rest;
use Exception;
use Illuminate\Database\QueryException;
use Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

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

    protected $suggestions = [];

    protected $stackResponse = [
        'suggestions' => [],
        'message' => '',
        'class_name' => '',
        'status_code' => null,
        'stack_trace' => []
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        $clasName = get_class($exception);
        $statusCode = $exception->getCode();
        $message = $exception->getMessage();
        $stackTrace = $exception->getTrace();

        if (env('APP_ENV') !== 'production') {
            $this->stackResponse['class_name'] = $clasName;
            $this->stackResponse['status_code'] = $statusCode;
            $this->stackResponse['message'] = $message;
            $this->stackResponse['stack_trace'] = $stackTrace;
        }

        //if(!$request->expectsJson()){
        //    return parent::render($request, $exception);
        //}


        if ($exception instanceof FatalThrowableError) {
            $this->addSuggestion("fatal error thrown");
            return Rest::failed("API failed", $this->stackResponse);
        }

        if ($exception instanceof NotFoundHttpException) {
            $this->addSuggestion("route not found.");
            return Rest::notFound("API not found", $this->stackResponse);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            $this->addSuggestion("Maybe the given route has not yet implemented the method.");
            return Rest::failed("API method not allowed", $this->stackResponse);
        }

        if ($exception instanceof MethodNotAllowedException) {
            return Rest::failed("API method not allowed", $this->stackResponse);
        }

        if ($exception instanceof QueryException) {
            $this->addSuggestion("There might be a constraint violation for the query that is not catch.");
            $this->addSuggestion("There might be a database column that must be not null");
            $this->addSuggestion("There might be a database column that is already a duplicate of an existing row");
            return Rest::failed("API database query problem", $this->stackResponse);
        }

        switch ($statusCode) {
            case 500:
                $response = Rest::failed($message, $this->stackResponse);
                break;
            case 404:
                $response = Rest::notFound($message, $this->stackResponse);
                break;
            default:
                $response = Rest::failed($message, $this->stackResponse);
        }

        return $response;
    }

    private function addSuggestion($message = "")
    {
        $this->stackResponse['suggestions'][] = $message;
    }
}
