<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration adds social login columns to the users table
     * for Google, Facebook, and GitHub authentication.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Google OAuth fields
            $table->string('google_id')->nullable()->after('remember_token');
            
            // Facebook OAuth fields
            $table->string('facebook_id')->nullable()->after('google_id');
            
            // GitHub OAuth fields (optional, for future use)
            $table->string('github_id')->nullable()->after('facebook_id');
            
            // OAuth provider field (to track which provider was used)
            $table->string('oauth_provider')->nullable()->after('github_id');
            
            // Add indexes for faster lookups
            $table->index('google_id');
            $table->index('facebook_id');
            $table->index('github_id');
            $table->index('oauth_provider');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['google_id']);
            $table->dropIndex(['facebook_id']);
            $table->dropIndex(['github_id']);
            $table->dropIndex(['oauth_provider']);
            
            $table->dropColumn('google_id');
            $table->dropColumn('facebook_id');
            $table->dropColumn('github_id');
            $table->dropColumn('oauth_provider');
        });
    }
};
