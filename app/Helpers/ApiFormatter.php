<?php

namespace App\Helpers;

class ApiFormatter
{
    public static function createJson($code, $message, $data = [])
    {
        // Definisikan array respons secara lokal
        $response = [
            'code'      => $code,
            'status'    => $message, // Menggunakan 'status' atau 'message'
            'data'      => $data
        ];

        // HANYA MENGEMBALIKAN ARRAY DATA
        return $response; 
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