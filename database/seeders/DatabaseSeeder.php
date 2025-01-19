<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);
        $this->call(AgentSeeder::class);
        $this->call(BranchSeeder::class);
        $this->call(StaffSeeder::class);
        $this->call(BorrowersSeeder::class);
        $this->call(LoansSeeder::class);
        $this->call(LoanInstallmentsSeeder::class);

    }
}
