<?php

namespace Database\Seeders;

use App\Models\LineItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LineItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                "name"          => "Personnel Services",
                "code"          => "PS",
                'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
            ],

            [
                "name"          => "Maintenance and Other Operating Expenses",
                "code"          => "MOOE",
                'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
            ],

            [
                "name"          => "Equipment/Capital Outlay",
                "code"          => "CO",
                'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
            ],
        ];

        LineItem::insert($data);
    }
}
