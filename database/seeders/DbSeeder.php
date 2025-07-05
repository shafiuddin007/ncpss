<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $path = __DIR__.'/sql_file/required.sql';
        // echo __DIR__; // Optional: remove or comment out debug output

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::unprepared(
            file_get_contents($path)
        );

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([
            ProductSeeder::class,
        ]);
        
    }
}
