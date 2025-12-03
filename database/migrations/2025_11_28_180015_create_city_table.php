<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('city', function (Blueprint $table) {
            // Field primary key sesuai dengan model CityModel
            $table->id('city_id'); 
            
            $table->string('city_code', 10)->unique();
            $table->string('city_name');
            
            // Foreign Key ke tabel 'province'
            $table->unsignedInteger('province_id');
            $table->foreign('province_id')->references('province_id')->on('province')->onDelete('cascade');
            
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('city');
    }
};

