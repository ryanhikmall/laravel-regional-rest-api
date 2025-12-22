<?php 

namespace App\Helpers;

class ApiFormatter 
{
    public static function createJson($code, $message, $data = [])
    {
        $response = [
            'code'      => $code,
            'status'    => $message,
            'data'      => $data
        ];

        // PERBAIKAN: Langsung return JsonResponse Object
        return response()->json($response, $code);
    }

    public static function filterSensitiveData(array $data = []): array
    {
        $sensitiveFields = ['password', 'password_confirmation', 'token', 'api_key', 'secret'];

        foreach ($sensitiveFields as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = '[FILTERED]';
            }
        }

        return $data;
    }
}