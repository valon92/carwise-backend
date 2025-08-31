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
        Schema::create('ai_chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('session_id')->index();
            $table->text('message');
            $table->text('response');
            $table->json('context')->nullable();
            $table->string('intent')->nullable();
            $table->decimal('confidence', 3, 3)->default(0.0);
            $table->boolean('is_resolved')->default(false);
            $table->integer('feedback_rating')->nullable();
            $table->text('feedback_comment')->nullable();
            $table->decimal('processing_time', 5, 3)->nullable();
            $table->integer('tokens_used')->nullable();
            $table->string('model_used')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'session_id']);
            $table->index(['intent', 'confidence']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_chats');
    }
};
