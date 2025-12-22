<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogModel; // Pastikan nama Model sesuai dengan file kamu
use App\Helpers\ApiFormatter; // Import Helper formatter kamu

class LogController extends Controller
{
    public function index()
    {
        try {
            // Ambil semua data dari tabel logs
            $data = LogModel::all();

            if ($data) {
                return ApiFormatter::createJson(200, 'Get Data Log Success', $data);
            } else {
                return ApiFormatter::createJson(400, 'Data Empty');
            }

        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }
}