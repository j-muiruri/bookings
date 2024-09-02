<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\Testing\Fakes\ExceptionHandlerFake;
use Throwable;

class CustomException extends ExceptionHandlerFake
{
     /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    // public function render(Request $request, Throwable $e)
    // {
    //     switch ($e) {
    //         case empty(auth('sanctum')->user()):

    //             $data = [
    //                 'success' => false,
    //                 'message' => 'Please login to access this module',
    //                 'error' => env('APP_ENV') == 'local' ? $e->getTraceAsString() : $e->getCode(),
    //             ];

    //             break;

    //         default:
    //             // code...
    //             break;
    //     }

    //     return response($data, 401);

    // }
}
