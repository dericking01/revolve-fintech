<?php

namespace App\Console\Commands;

use App\Models\Loan;
use Illuminate\Console\Command;

class PenaltyCalculate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loans:penalty-calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate penalties for overdue loans';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // In a scheduler or a command
        $overdueLoans = Loan::where('status', 'ongoing')->where('due_date', '<', now())->get();

        foreach ($overdueLoans as $loan) {
            $loan->updatePenalty();
        }

    }
}
