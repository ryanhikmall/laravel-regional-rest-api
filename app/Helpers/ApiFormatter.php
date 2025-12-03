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
}