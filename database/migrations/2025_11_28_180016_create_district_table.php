<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('district', function (Blueprint $table) {
            // Field primary key sesuai dengan model DistrictModel
            $table->id('district_id'); 
            
            $table->string('district_code', 10)->unique();
            $table->string('district_name');

            // Foreign Key ke tabel 'city'
            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('city_id')->on('city')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('district');
    }
};
