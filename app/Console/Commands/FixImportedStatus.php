<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixImportedStatus extends Command
{
    protected $signature = 'import:fix-status';
    protected $description = 'Update all imported transactions from pending to historical status';

    public function handle()
    {
        $count = DB::table('transaction_requests')
            ->where('is_imported', true)
            ->where('status', 'pending')
            ->update(['status' => 'historical']);

        $this->info("✅ Updated {$count} imported transactions to 'historical' status.");
        return 0;
    }
}
