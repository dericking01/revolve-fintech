<?php

namespace Database\Seeders;

use App\Models\Borrower;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class BorrowersSeeder extends Seeder
{
    public function run()
    {
        $branches = DB::table('branches')->pluck('id'); // Fetch existing branch IDs
        $faker = \Faker\Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            // Generate a unique loanee_id inside the loop
            $borrowerId = 'REV-' . date('Y') . '-' . mt_rand(1000, 9999);

            // Ensure the loanee_id is unique
            while (Borrower::where('loanee_id', $borrowerId)->exists()) {
                $borrowerId = 'REV-' . date('Y') . '-' . mt_rand(1000, 9999); // Regenerate loanee_id if exists
            }

            // Insert a new borrower record
            DB::table('borrowers')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->unique()->phoneNumber,
                'address' => $faker->address,
                'national_id' => $faker->unique()->randomNumber(8, true), // Simulating a National ID
                'employment_status' => $faker->randomElement(['employed', 'self-employed', 'unemployed']),
                'employment_name' => $faker->company,
                'income' => $faker->randomFloat(2, 1000, 10000), // Between 1000 and 10000
                'date_of_birth' => $faker->date('Y-m-d', '2002-01-01'),
                'gender' => $faker->randomElement(['male', 'female', 'other']),
                'branch_id' => $faker->randomElement($branches),
                'loanee_id' => $borrowerId,
                'status' => $faker->randomElement(['active', 'inactive', 'blacklisted']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
