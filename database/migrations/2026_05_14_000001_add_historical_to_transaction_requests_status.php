<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            // MySQL: modify ENUM to add 'historical' value
            DB::statement("ALTER TABLE transaction_requests MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'historical') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        // Revert back to original ENUM (update any 'historical' rows first)
        DB::table('transaction_requests')
            ->where('status', 'historical')
            ->update(['status' => 'pending']);

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE transaction_requests MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending'");
        }
    }
};
