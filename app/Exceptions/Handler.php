<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if (app()->environment('production')) {
            Log::error($e->getMessage(), [
                'exception' => $e,
                'url' => $request->fullUrl(),
                'input' => $request->all(),
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage() ?? 'Something went wrong.',
                ], 500);
            }

            return response();
        }

        return parent::render($request, $e);
    }
}
