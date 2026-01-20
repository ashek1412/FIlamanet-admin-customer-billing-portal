<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to the Excel file
        $filePath = database_path('seeders/data/ebill_users.xlsx');

        // Load the Excel file
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Skip the header row
        array_shift($rows);

        $insertedCount = 0;
        $skippedCount = 0;
        $updatedCount = 0;

        foreach ($rows as $row) {
            // Skip empty rows
            if (empty($row[3])) { // Email column
                continue;
            }

            $customerId = $row[0]; // customer from Excel
            $customerName = trim($row[2] ?? 'N/A'); // Customer name
            $email = trim($row[3]); // Email address
            $password = $row[4] ?? 'password'; // Password
            // $row[1] is Code column - not required, so we ignore it

            // Check if user with this email already exists
            $existingUser = DB::table('users')->where('email', $email)->first();

            // Make name unique if it already exists (but not for the same email)
            $name = $customerName;
            $counter = 1;

            while (DB::table('users')->where('name', $name)->where('email', '!=', $email)->exists()) {
                $name = $customerName . ' (' . $counter . ')';
                $counter++;
            }

            $userData = [
                'name' => $name,
                'email' => $email,
                'email_verified_at' => null,
                'password' => Hash::make($password),
                'remember_token' => null,
                'is_agreed' => null,
                'is_admin' => 0,
                'customer_id' => $customerId,
                'view_dms' => null,
                'view_dws' => null,
                'is_active' => 1,
                'failed_login_attempts' => 0,
                'must_reset_password' => 1,
                'password_changed_at' => null,
                'is_first_login' => 1,
                'updated_at' => now(),
            ];

            if ($existingUser) {
                // Update existing user
                DB::table('users')
                    ->where('email', $email)
                    ->update($userData);

                $updatedCount++;
                $this->command->warn("Updated user: $customerName ($email)");
            } else {
                // Insert new user
                $userData['created_at'] = now();

                try {
                    DB::table('users')->insert($userData);
                    $insertedCount++;
                } catch (\Exception $e) {
                    $this->command->error("Failed to insert user $customerName ($email): " . $e->getMessage());
                    $skippedCount++;
                }
            }
        }

        $this->command->info("Seeding completed!");
        $this->command->info("Inserted: $insertedCount users");
        $this->command->info("Updated: $updatedCount users");
        $this->command->info("Skipped: $skippedCount users");
        $this->command->info("Total processed: " . ($insertedCount + $updatedCount + $skippedCount) . " users");
    }
}
