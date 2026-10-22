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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('reader')->after('password');
            $table->string('username')->unique()->nullable()->after('name');
            $table->text('bio')->nullable()->after('role');
            $table->string('profile_image')->nullable()->after('bio');
            $table->string('cover_image')->nullable()->after('profile_image');
            $table->json('social_links')->nullable()->after('cover_image');
            $table->string('specialty')->nullable()->after('social_links');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'username',
                'bio',
                'profile_image',
                'cover_image',
                'social_links',
                'specialty',
            ]);
        });
    }
};
