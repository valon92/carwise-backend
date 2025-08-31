<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('brand', 100);
            $table->string('model', 100);
            $table->integer('year');
            $table->string('vin', 17)->nullable()->unique();
            $table->string('license_plate', 20)->nullable();
            $table->integer('mileage')->nullable();
            $table->string('fuel_type', 50)->nullable();
            $table->string('transmission', 50)->nullable();
            $table->string('color', 50)->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->string('engine_size', 50)->nullable();
            $table->integer('horsepower')->nullable();
            $table->integer('torque')->nullable();
            $table->decimal('fuel_efficiency', 8, 2)->nullable();
            $table->string('body_type', 50)->nullable();
            $table->integer('doors')->nullable();
            $table->integer('seats')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->date('last_service_date')->nullable();
            $table->date('next_service_date')->nullable();
            $table->json('service_history')->nullable();
            $table->json('modifications')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'is_active']);
            $table->index(['user_id', 'is_primary']);
            $table->index('vin');
            $table->index('license_plate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
