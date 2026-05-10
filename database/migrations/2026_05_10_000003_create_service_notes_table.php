<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_notes', function (Blueprint $table) {
            $table->id();
            $table->string('service_no')->unique();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->date('received_date')->index();
            $table->text('reported_issue');
            $table->text('initial_diagnosis')->nullable();
            $table->text('repair_action')->nullable();
            $table->text('parts_replaced')->nullable();
            $table->decimal('service_charge', 10, 2)->default(0);
            $table->decimal('parts_charge', 10, 2)->default(0);
            $table->decimal('total_charge', 10, 2)->default(0);
            $table->unsignedInteger('warranty_duration')->nullable();
            $table->string('warranty_unit')->nullable();
            $table->text('warranty_note')->nullable();
            $table->string('status')->index();
            $table->string('technician_name')->nullable()->index();
            $table->string('customer_signature_path')->nullable();
            $table->string('technician_signature_path')->nullable();
            $table->string('pdf_original_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_notes');
    }
};
