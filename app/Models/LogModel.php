<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogModel extends Model
{
    use HasFactory;

    // 1. Karena nama tabel kamu 'log' (bukan logs), wajib tulis ini:
    protected $table = 'log';

    // 2. Karena primary key kamu 'log_id' (bukan id), wajib tulis ini:
    protected $primaryKey = 'log_id';

    // 3. Kolom yang boleh diisi (sesuai screenshot kamu)
    protected $fillable = [
        'user_id',
        'log_method',
        'log_url',
        'log_ip',
        'log_request',
        'log_response'
    ];
}