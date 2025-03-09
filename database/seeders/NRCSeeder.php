<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NRCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Construct the path to your SQL file
        $path = database_path('/seeds/nrc.sql');

        // Read the SQL file content
        if (file_exists($path)) {
            $sql = file_get_contents($path);

            // Execute the SQL statements
            DB::unprepared($sql);

            // Optionally, you can output a message to indicate success
            $this->command->info('SQL file executed successfully.');
        } else {
            // Optionally, you can output a message to indicate failure
            $this->command->error('SQL file does not exist at the specified path.');
        }
    }
}
