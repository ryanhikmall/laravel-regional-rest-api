<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\LogModel;
use App\Helpers\ApiFormatter;
use Throwable;
use Exception;

class LogAPI
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = null;
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            $user = null;
        }

        $filteredRequest = ApiFormatter::filterSensitiveData($request->all());

        // Buat log awal (sebelum respon diproses)
        $log = LogModel::create([
            'user_id' => $user ? $user->id : null,
            'log_method' => $request->method(),
            'log_url' => $request->fullUrl(),
            'log_ip' => $request->ip(),
            'log_request' => json_encode($filteredRequest),
        ]);

        try {
            // Lanjutkan request ke controller
            $response = $next($request);

            // Update log dengan respon sukses
            $log->update([
                'log_response' => $response->getContent(),
            ]);

            return $response;

        } catch (Throwable $e) {
            // Tangani jika terjadi error (Internal Server Error)
            $errorResponse = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            
            $log->update([
                'log_response' => json_encode($errorResponse),
            ]);

            return response()->json($errorResponse, 500);
        }
    }
}