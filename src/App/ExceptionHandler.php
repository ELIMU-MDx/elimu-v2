<?php

declare(strict_types=1);

namespace App;

use Illuminate\Foundation\Exceptions\Handler;
use Throwable;

class ExceptionHandler extends Handler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [

    ];
    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
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
        $this->reportable(function (Throwable $e): void {
            if (app()->bound('honeybadger') && $this->shouldReport($e)) {
                app('honeybadger')->notify($e, app('request'));
            }
        });
    }
}
