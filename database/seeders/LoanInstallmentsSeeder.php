<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class LoanInstallmentsSeeder extends Seeder
{
    public function run()
    {
        $loans = DB::table('loans')->pluck('id'); // Fetch all loan IDs
        $faker = Faker::create();

        foreach ($loans as $loanId) {
            // Generate 2 to 5 installments per loan
            $installmentsCount = $faker->numberBetween(2, 5);

            for ($i = 1; $i <= $installmentsCount; $i++) {
                $overdueDate = $faker->optional()->dateTimeBetween('now', '+2 months');
                $paidDate = $faker->optional()->dateTimeBetween('now', '+1 month');

                // Only format the date if it is not null
                $overdueDateFormatted = $overdueDate ? $overdueDate->format('Y-m-d') : null;
                $paidDateFormatted = $paidDate ? $paidDate->format('Y-m-d') : null;

                DB::table('loan_installments')->insert([
                    'loan_id' => $loanId,
                    'installed_amount' => $faker->randomFloat(2, 200, 10000), // Random installment amount
                    'overdue_date' => $overdueDateFormatted, // Optional overdue date
                    'paid_date' => $paidDateFormatted,   // Optional paid date
                    'status' => $faker->randomElement(['overdue', 'paid', 'late']), // Random status
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

// Compare this snippet from database/seeders/LoanInstallmentsSeeder.php:
