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
        if (!Schema::hasTable('media')) {
            Schema::create('media', function (Blueprint $table) {
                $table->id();
                $table->morphs('model');
                $table->uuid('uuid')->nullable()->unique();
                $table->string('collection_name');
                $table->string('name');
                $table->string('file_name');
                $table->string('mime_type')->nullable();
                $table->string('disk');
                $table->string('conversions_disk')->nullable();
                $table->unsignedBigInteger('size');
                $table->json('manipulations');
                $table->json('custom_properties');
                $table->json('generated_conversions');
                $table->json('responsive_images');
                $table->unsignedInteger('order_column')->nullable();
                $table->timestamps();
            });

            // Add indexes separately to avoid conflicts
            if (!Schema::hasIndex('media', 'media_model_type_model_id_index')) {
                Schema::table('media', function (Blueprint $table) {
                    $table->index(['model_type', 'model_id']);
                });
            }

            if (!Schema::hasIndex('media', 'media_collection_name_index')) {
                Schema::table('media', function (Blueprint $table) {
                    $table->index(['collection_name']);
                });
            }

            if (!Schema::hasIndex('media', 'media_order_column_index')) {
                Schema::table('media', function (Blueprint $table) {
                    $table->index(['order_column']);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
