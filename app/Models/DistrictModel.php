<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistrictModel extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'district'; 

    // Primary key untuk model ini
    protected $primaryKey = 'district_id'; 
    
    // Field yang diizinkan untuk diisi secara massal (Mass Assignment)
    protected $fillable = [
        'district_code',
        'district_name',
        'city_id', // Foreign Key
    ];

    // Matikan timestamp (jika Anda tidak menggunakan created_at dan updated_at)
    // public $timestamps = false;
}