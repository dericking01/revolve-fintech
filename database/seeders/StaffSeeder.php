<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;


class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Fetch all branch IDs to ensure validity
        $branchIds = Branch::pluck('id')->toArray();

        if (empty($branchIds)) {
            $this->command->error('No branches found. Please seed the branches table first.');
            return;
        }

        // Seed staff records
        foreach (range(1, 10) as $index) {
            Staff::create([
                'admin_id' => 1,
                'branch_id' => $faker->randomElement($branchIds), // Random valid branch ID
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => '255' . $faker->numberBetween(100000000, 999999999),
                'role' => 'staff',
                'status' => $faker->randomElement(['active', 'inactive']),
                'location' => $faker->city,
                'password' => Hash::make('staff2025'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
