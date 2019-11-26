<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        // 全部上报
//		\Symfony\Component\HttpKernel\Exception\HttpException::class,
//		\Illuminate\Database\Eloquent\ModelNotFoundException::class,
//		\Illuminate\Validation\ValidationException::class,
//		\Illuminate\Foundation\Http\Exceptions\MaintenanceModeException::class
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
     * @throws
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
		if (app()->bound('sentry') && $this->shouldReport($exception)) {
			app('sentry')->captureException($exception);
		}
	
		parent::report($exception);
    }
	
	/**
	 * Render an exception into an HTTP response.
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @param Exception                $exception
	 * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 */
    public function render($request, Exception $exception)
    {
		// 得到错误发送到邮件
//		if($this->shouldReport($exception)){
//			email_bug($exception->__toString());
//		}
		
		if($exception instanceof ValidationException){
			$message = $exception->validator->errors()->first();
			abort(422, $message);
		}
		return parent::render($request, $exception);
    }
}
