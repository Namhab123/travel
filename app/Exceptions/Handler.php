<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof HttpExceptionInterface){
            if($exception->getStatusCode() == 404) {
                //Kiem tra URL cos chua /admin hay khong de quyet dinh hien thi trang loi cho admin
                if($request->is('admin/*')){
                    return response()-> view('admin.errors.404', ['title' => '404 - Không tìm thấy trang'], 404);
                } else {
                    $title = '404';
                    return response()-> view('clients.errors.404',  ['title' => $title], 404);
                }
            }
        }
        return parent::render($request, $exception);    
    }
        
    
}

