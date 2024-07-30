<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReminderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('reminder_for_enforcers')->insert([
            'title' => 'Conduct A Post Meeting',
            'content' => 'sd dffe',
            
        ]);
    }
}
