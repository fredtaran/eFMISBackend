<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name'      =>  'Management Support Division',
                'shorthand' =>  'MSD'
            ],
            [
                'name'      =>  'Local Health Support Division',
                'shorthand' =>  'LHSD'
            ],
            [
                'name'      =>  'Regulations, Licensing, and Enforcement Division',
                'shorthand' =>  'RLED'
            ],
            [
                'name'      =>  'Office of the Regional Director/Office of the Assistant Regional Director',
                'shorthand' =>  'ORD/OARD'
            ]
        ];

        Division::insert($data);
    }
}
