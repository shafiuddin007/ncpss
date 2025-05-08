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
        echo __DIR__;
       
        DB::unprepared(
            file_get_contents($path)
        );

        $this->call([
            ProductSeeder::class,
        ]);
        
    }
}
