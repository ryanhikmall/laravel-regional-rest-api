<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\LogModel;
use App\Helpers\ApiFormatter;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    // ... properti $levels, $dontReport, $dontFlash biarkan default ...

    public function render($request, Throwable $exception)
    {
        // Tangani Error 404 (Not Found)
        if ($exception instanceof NotFoundHttpException) {
            $user = null;
            try {
                $user = JWTAuth::parseToken()->authenticate();
            } catch (Exception $e) {
                $user = null;
            }

            $filteredRequest = ApiFormatter::filterSensitiveData($request->all());

            LogModel::create([
                'user_id' => $user ? $user->id : null,
                'log_method' => $request->method(),
                'log_url' => $request->fullUrl(),
                'log_ip' => $request->ip(),
                'log_request' => json_encode($filteredRequest),
                'log_response' => json_encode(ApiFormatter::createJson(404, 'Not Found', 'Route not found.')),
            ]);

            return response()->json(ApiFormatter::createJson(404, 'Not Found', 'Route not found.'), 404);
        }

        return parent::render($request, $exception);
    }
}