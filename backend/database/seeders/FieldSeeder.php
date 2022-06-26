<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fields = [
            [
                'title' => 'City',
                'type' => 'string',
            ],
            [
                'title' => 'Date of Birth',
                'type' => 'date',
            ],
            [
                'title' => 'A Number',
                'type' => 'number',
            ],
            [
                'title' => 'A Boolean',
                'type' => 'boolean',
            ],
        ];

        DB::table('fields')->insert($fields);
    }
}
