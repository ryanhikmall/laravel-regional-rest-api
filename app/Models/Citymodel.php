<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityModel extends Model
{
    use HasFactory;
    
    // Nama tabel di database
    protected $table = 'city'; 

    // Primary key untuk model ini
    protected $primaryKey = 'city_id'; 
    
    // Field yang diizinkan untuk diisi secara massal (Mass Assignment)
    protected $fillable = [
        'city_code',
        'city_name',
        'province_id', // Foreign Key
    ];
    
    // Matikan timestamp (jika Anda tidak menggunakan created_at dan updated_at)
    // Jika Anda menggunakan timestamp, hapus baris ini.
    // public $timestamps = false;
}