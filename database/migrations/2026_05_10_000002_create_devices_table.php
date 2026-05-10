<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('device_type');
            $table->string('device_type_other')->nullable();
            $table->string('brand_model')->index();
            $table->string('serial_number')->nullable()->index();
            $table->text('specifications')->nullable();
            $table->string('device_password')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'serial_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
