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
        Schema::table('reports', function (Blueprint $table) {
            // Add missing columns only
            if (!Schema::hasColumn('reports', 'transmission')) {
                $table->string('transmission')->nullable()->after('fuel_type');
            }
            if (!Schema::hasColumn('reports', 'problem_category')) {
                $table->string('problem_category')->nullable()->after('color');
            }
            if (!Schema::hasColumn('reports', 'severity_level')) {
                $table->enum('severity_level', ['low', 'medium', 'high', 'critical'])->default('medium')->after('problem_category');
            }
            if (!Schema::hasColumn('reports', 'ai_analysis')) {
                $table->json('ai_analysis')->nullable()->after('status');
            }
            if (!Schema::hasColumn('reports', 'estimated_cost')) {
                $table->decimal('estimated_cost', 10, 2)->nullable()->after('ai_analysis');
            }
            if (!Schema::hasColumn('reports', 'priority')) {
                $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal')->after('estimated_cost');
            }
            if (!Schema::hasColumn('reports', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('location');
            }
            if (!Schema::hasColumn('reports', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
            if (!Schema::hasColumn('reports', 'is_urgent')) {
                $table->boolean('is_urgent')->default(false)->after('longitude');
            }
            if (!Schema::hasColumn('reports', 'assigned_to')) {
                $table->foreignId('assigned_to')->nullable()->constrained('users')->after('is_urgent');
            }
            if (!Schema::hasColumn('reports', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('assigned_to');
            }
            
            // Add indexes if they don't exist
            if (!Schema::hasIndex('reports', 'reports_status_severity_level_index')) {
                $table->index(['status', 'severity_level']);
            }
            if (!Schema::hasIndex('reports', 'reports_is_urgent_priority_index')) {
                $table->index(['is_urgent', 'priority']);
            }
            if (!Schema::hasIndex('reports', 'reports_assigned_to_status_index')) {
                $table->index(['assigned_to', 'status']);
            }
            if (!Schema::hasIndex('reports', 'reports_problem_category_brand_index')) {
                $table->index(['problem_category', 'brand']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Drop foreign key if exists
            if (Schema::hasColumn('reports', 'assigned_to')) {
                $table->dropForeign(['assigned_to']);
            }
            
            // Drop indexes if they exist
            $indexes = [
                'reports_status_severity_level_index',
                'reports_is_urgent_priority_index',
                'reports_assigned_to_status_index',
                'reports_problem_category_brand_index'
            ];
            
            foreach ($indexes as $index) {
                if (Schema::hasIndex('reports', $index)) {
                    $table->dropIndex($index);
                }
            }
            
            // Drop columns if they exist
            $columns = [
                'transmission',
                'problem_category',
                'severity_level',
                'ai_analysis',
                'estimated_cost',
                'priority',
                'latitude',
                'longitude',
                'is_urgent',
                'assigned_to',
                'completed_at'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('reports', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
