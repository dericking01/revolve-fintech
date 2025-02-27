<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoansSeeder extends Seeder
{
    public function run()
    {
        $borrowers = DB::table('borrowers')->pluck('id'); // Fetch all borrower IDs
        $admins = DB::table('admins')->pluck('id');       // Fetch all admin IDs
        $staffs = DB::table('staffs')->pluck('id');       // Fetch all staff IDs
        $faker = \Faker\Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            $creatorType = $faker->randomElement(['App\Models\Admin', 'App\Models\Staff']); // Random creator type
            $creatorId = $creatorType === 'App\Models\Admin'
                ? $faker->randomElement($admins)
                : $faker->randomElement($staffs);

            DB::table('loans')->insert([
                'borrower_id' => $faker->randomElement($borrowers),
                'created_by_id' => $creatorId,
                'created_by_type' => $creatorType,
                'application_fee' => $faker->numberBetween(5000, 20000), // Application fee between 5k and 25k
                'loan_amount' => $faker->numberBetween(100000, 5000000), // Loan amount between 100k and 5M
                'interest_rate' => $faker->numberBetween(2, 5, 20),     // Interest rate between 5% and 20%
                'term' => $faker->randomElement([1, 2, 3]),          // Term in months (1, 2, or 3)
                'repayment_mode' => $faker->randomElement(['daily', 'weekly', 'monthly']),
                'start_date' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
                'due_date' => $faker->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
                'sponsor_name' => $faker->name,
                'sponsor_phone' => $faker->unique()->phoneNumber,
                'collaterals' => $faker->sentence,
                'description' => $faker->sentence,
                'status' => $faker->randomElement(['pending', 'ongoing', 'rejected', 'paid', 'overdue']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
