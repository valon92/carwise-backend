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
        // Add indexes to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasIndex('users', 'users_email_index')) {
                $table->index('email');
            }
            if (!Schema::hasIndex('users', 'users_created_at_index')) {
                $table->index('created_at');
            }
            if (!Schema::hasIndex('users', 'users_email_email_verified_at_index')) {
                $table->index(['email', 'email_verified_at']);
            }
        });

        // Add indexes to vehicles table
        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasIndex('vehicles', 'vehicles_user_id_index')) {
                $table->index('user_id');
            }
            if (!Schema::hasIndex('vehicles', 'vehicles_license_plate_index')) {
                $table->index('license_plate');
            }
            if (!Schema::hasIndex('vehicles', 'vehicles_vin_index')) {
                $table->index('vin');
            }
            if (!Schema::hasIndex('vehicles', 'vehicles_status_index')) {
                $table->index('status');
            }
            if (!Schema::hasIndex('vehicles', 'vehicles_is_primary_index')) {
                $table->index('is_primary');
            }
            if (!Schema::hasIndex('vehicles', 'vehicles_next_service_date_index')) {
                $table->index('next_service_date');
            }
            if (!Schema::hasIndex('vehicles', 'vehicles_user_id_status_index')) {
                $table->index(['user_id', 'status']);
            }
            if (!Schema::hasIndex('vehicles', 'vehicles_user_id_is_primary_index')) {
                $table->index(['user_id', 'is_primary']);
            }
        });

        // Add indexes to reports table
        Schema::table('reports', function (Blueprint $table) {
            if (!Schema::hasIndex('reports', 'reports_user_id_index')) {
                $table->index('user_id');
            }
            if (!Schema::hasIndex('reports', 'reports_vehicle_id_index')) {
                $table->index('vehicle_id');
            }
            if (!Schema::hasIndex('reports', 'reports_status_index')) {
                $table->index('status');
            }
            if (!Schema::hasIndex('reports', 'reports_priority_index')) {
                $table->index('priority');
            }
            if (!Schema::hasIndex('reports', 'reports_severity_level_index')) {
                $table->index('severity_level');
            }
            if (!Schema::hasIndex('reports', 'reports_created_at_index')) {
                $table->index('created_at');
            }
            if (!Schema::hasIndex('reports', 'reports_updated_at_index')) {
                $table->index('updated_at');
            }
            if (!Schema::hasIndex('reports', 'reports_completed_at_index')) {
                $table->index('completed_at');
            }
            if (!Schema::hasIndex('reports', 'reports_user_id_status_index')) {
                $table->index(['user_id', 'status']);
            }
            if (!Schema::hasIndex('reports', 'reports_user_id_priority_index')) {
                $table->index(['user_id', 'priority']);
            }
            if (!Schema::hasIndex('reports', 'reports_vehicle_id_status_index')) {
                $table->index(['vehicle_id', 'status']);
            }
            if (!Schema::hasIndex('reports', 'reports_status_priority_index')) {
                $table->index(['status', 'priority']);
            }
        });

        // Add indexes to ai_chats table
        Schema::table('ai_chats', function (Blueprint $table) {
            if (!Schema::hasIndex('ai_chats', 'ai_chats_user_id_index')) {
                $table->index('user_id');
            }
            if (!Schema::hasIndex('ai_chats', 'ai_chats_session_id_index')) {
                $table->index('session_id');
            }
            if (!Schema::hasIndex('ai_chats', 'ai_chats_intent_index')) {
                $table->index('intent');
            }
            if (!Schema::hasIndex('ai_chats', 'ai_chats_created_at_index')) {
                $table->index('created_at');
            }
            if (!Schema::hasIndex('ai_chats', 'ai_chats_user_id_created_at_index')) {
                $table->index(['user_id', 'created_at']);
            }
            if (!Schema::hasIndex('ai_chats', 'ai_chats_session_id_created_at_index')) {
                $table->index(['session_id', 'created_at']);
            }
        });

        // Add indexes to personal_access_tokens table
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            if (!Schema::hasIndex('personal_access_tokens', 'personal_access_tokens_tokenable_type_index')) {
                $table->index('tokenable_type');
            }
            if (!Schema::hasIndex('personal_access_tokens', 'personal_access_tokens_tokenable_id_index')) {
                $table->index('tokenable_id');
            }
            if (!Schema::hasIndex('personal_access_tokens', 'personal_access_tokens_name_index')) {
                $table->index('name');
            }
            if (!Schema::hasIndex('personal_access_tokens', 'personal_access_tokens_created_at_index')) {
                $table->index('created_at');
            }
            if (!Schema::hasIndex('personal_access_tokens', 'personal_access_tokens_tokenable_type_tokenable_id_index')) {
                $table->index(['tokenable_type', 'tokenable_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndexIfExists(['email']);
            $table->dropIndexIfExists(['created_at']);
            $table->dropIndexIfExists(['email', 'email_verified_at']);
        });

        // Remove indexes from vehicles table
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropIndexIfExists(['user_id']);
            $table->dropIndexIfExists(['license_plate']);
            $table->dropIndexIfExists(['vin']);
            $table->dropIndexIfExists(['status']);
            $table->dropIndexIfExists(['is_primary']);
            $table->dropIndexIfExists(['next_service_date']);
            $table->dropIndexIfExists(['user_id', 'status']);
            $table->dropIndexIfExists(['user_id', 'is_primary']);
        });

        // Remove indexes from reports table
        Schema::table('reports', function (Blueprint $table) {
            $table->dropIndexIfExists(['user_id']);
            $table->dropIndexIfExists(['vehicle_id']);
            $table->dropIndexIfExists(['status']);
            $table->dropIndexIfExists(['priority']);
            $table->dropIndexIfExists(['severity_level']);
            $table->dropIndexIfExists(['created_at']);
            $table->dropIndexIfExists(['updated_at']);
            $table->dropIndexIfExists(['completed_at']);
            $table->dropIndexIfExists(['user_id', 'status']);
            $table->dropIndexIfExists(['user_id', 'priority']);
            $table->dropIndexIfExists(['vehicle_id', 'status']);
            $table->dropIndexIfExists(['status', 'priority']);
        });

        // Remove indexes from ai_chats table
        Schema::table('ai_chats', function (Blueprint $table) {
            $table->dropIndexIfExists(['user_id']);
            $table->dropIndexIfExists(['session_id']);
            $table->dropIndexIfExists(['intent']);
            $table->dropIndexIfExists(['created_at']);
            $table->dropIndexIfExists(['user_id', 'created_at']);
            $table->dropIndexIfExists(['session_id', 'created_at']);
        });

        // Remove indexes from personal_access_tokens table
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropIndexIfExists(['tokenable_type']);
            $table->dropIndexIfExists(['tokenable_id']);
            $table->dropIndexIfExists(['name']);
            $table->dropIndexIfExists(['created_at']);
            $table->dropIndexIfExists(['tokenable_type', 'tokenable_id']);
        });
    }
};
