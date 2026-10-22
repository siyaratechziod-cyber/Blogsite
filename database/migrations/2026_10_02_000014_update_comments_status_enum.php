<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Align comment statuses with the CMS moderation spec: pending / approved / rejected.
     * Existing 'spam' rows are mapped to 'rejected'.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver !== 'sqlite') {
            DB::statement("ALTER TABLE comments MODIFY status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'approved'");

            return;
        }

        // SQLite cannot alter enum columns in place; rebuild the column safely.
        if (! Schema::hasColumn('comments', 'status_new')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->string('status_new')->default('approved');
            });

            DB::table('comments')->update([
                'status_new' => DB::raw("CASE WHEN status = 'spam' THEN 'rejected' ELSE status END"),
            ]);
        }

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        if (Schema::hasColumn('comments', 'status')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        if (Schema::hasColumn('comments', 'status_new')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->renameColumn('status_new', 'status');
            });
        }

        Schema::table('comments', function (Blueprint $table) {
            $table->index('status');
        });
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver !== 'sqlite') {
            DB::statement("ALTER TABLE comments MODIFY status ENUM('pending','approved','spam') NOT NULL DEFAULT 'approved'");

            return;
        }

        if (! Schema::hasColumn('comments', 'status_old')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->string('status_old')->default('approved');
            });

            DB::table('comments')->update([
                'status_old' => DB::raw("CASE WHEN status = 'rejected' THEN 'spam' ELSE status END"),
            ]);
        }

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        if (Schema::hasColumn('comments', 'status')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        if (Schema::hasColumn('comments', 'status_old')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->renameColumn('status_old', 'status');
            });
        }

        Schema::table('comments', function (Blueprint $table) {
            $table->index('status');
        });
    }
};
