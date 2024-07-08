<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                "name"          => "Procurement and Supply Chain Management Unit",
                "shorthand"     => "PSCMU",
                "division_id"   => 1,
                'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
            ],

            [
                "name"          => "Finance Cluster",
                "shorthand"     => "Finance",
                "division_id"   => 1,
                'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
            ],

            [
                "name"          => "General Services Cluster",
                "shorthand"     => "GSC",
                "division_id"   => 1,
                'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
            ],

            [
                "name"          => "Planning and Statistics Unit",
                "shorthand"     => "PSU",
                "division_id"   => 1,
                'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
            ],

            [
                "name"          => "Knowledge Management and Information Technology Cluster",
                "shorthand"     => "KMITS",
                "division_id"   => 1,
                'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
            ],

            [
                "name"          => "Health Human Resource Management and Development Cluster",
                "shorthand"     => "HHRMDC",
                "division_id"   => 1,
                'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
            ],

            [
                "name"          => "Family Health Cluster",
                "shorthand"     => "FHC",
                "division_id"   => 2,
                'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
            ],

            [
                "name"          => "Infectious Diseases Cluster",
                "shorthand"     => "IDC",
                "division_id"   => 2,
                'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
            ],

            [
                "name"          => "Non-Communicable Diseases Cluster",
                "shorthand"     => "NDC",
                "division_id"   => 2,
                'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
            ],

            [
                "name"          => "Environmental and Occupational Health Cluster",
                "shorthand"     => "EOHC",
                "division_id"   => 2,
                'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
            ],

            [
                "name"          => "Local Health Systems Development Cluster",
                "shorthand"     => "LHSDC",
                "division_id"   => 2,
                'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
            ],

            [
                "name"          => "Health Educations Promotions Unit",
                "shorthand"     => "HEPU",
                "division_id"   => 2,
                'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
            ],

            [
                "name"          => "Hospital Licensing Section",
                "shorthand"     => "HLS",
                "division_id"   => 3,
                'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
            ],

            [
                "name"          => "Other Health Facilities Licensing Section",
                "shorthand"     => "OHFLS",
                "division_id"   => 3,
                'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
            ],

            [
                "name"          => "Communications Unit",
                "shorthand"     => "CMU",
                "division_id"   => 4,
                'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
            ],

            [
                "name"          => "Health Facility Development Unit Regional Epidemiology, Surveillance & Disaster Response Unit",
                "shorthand"     => "RESDRU",
                "division_id"   => 4,
                'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
            ],

            [
                "name"          => "Legal Unit and Public Assistance and Complaints Desk",
                "shorthand"     => "LU-PACD",
                "division_id"   => 4,
                'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
            ],
        ];

        Section::insert($data);
    }
}
