<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Report;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user if it doesn't exist
        $user = User::firstOrCreate(
            ['email' => 'test@carwise.com'],
            [
                'name' => 'Test User',
                'email' => 'test@carwise.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create sample vehicles
        $vehicles = [
            [
                'brand' => 'BMW',
                'model' => 'X5',
                'year' => 2020,
                'vin' => 'WBAJL0C50L1234567',
                'license_plate' => 'PZ-123-AB',
                'mileage' => 45000,
                'fuel_type' => 'diesel',
                'transmission' => 'automatic',
                'engine_size' => '3.0L',
                'color' => 'Alpine White',
                'purchase_date' => '2020-03-15',
                'purchase_price' => 65000,
                'is_active' => true,
                'is_primary' => true,
                'notes' => 'Primary family vehicle',
            ],
            [
                'brand' => 'Mercedes',
                'model' => 'C-Class',
                'year' => 2019,
                'vin' => 'WDDWF4HB0FR123456',
                'license_plate' => 'PZ-456-CD',
                'mileage' => 32000,
                'fuel_type' => 'petrol',
                'transmission' => 'automatic',
                'engine_size' => '2.0L',
                'color' => 'Obsidian Black',
                'purchase_date' => '2019-06-20',
                'purchase_price' => 45000,
                'is_active' => true,
                'is_primary' => false,
                'notes' => 'Daily commuter',
            ],
            [
                'brand' => 'Audi',
                'model' => 'A4',
                'year' => 2021,
                'vin' => 'WAUZZZ8K9KA123456',
                'license_plate' => 'PZ-789-EF',
                'mileage' => 28000,
                'fuel_type' => 'petrol',
                'transmission' => 'automatic',
                'engine_size' => '2.0L',
                'color' => 'Glacier White',
                'purchase_date' => '2021-01-10',
                'purchase_price' => 52000,
                'is_active' => true,
                'is_primary' => false,
                'notes' => 'Weekend car',
            ],
        ];

        foreach ($vehicles as $vehicleData) {
            Vehicle::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'vin' => $vehicleData['vin']
                ],
                array_merge($vehicleData, ['user_id' => $user->id])
            );
        }

        // Get the created vehicles
        $bmw = Vehicle::where('vin', 'WBAJL0C50L1234567')->first();
        $mercedes = Vehicle::where('vin', 'WDDWF4HB0FR123456')->first();
        $audi = Vehicle::where('vin', 'WAUZZZ8K9KA123456')->first();

        // Create sample reports
        $reports = [
            [
                'title' => 'Zë i çuditshëm nga motori',
                'vehicle_id' => $bmw->id,
                'brand' => $bmw->brand,
                'model' => $bmw->model,
                'year' => $bmw->year,
                'vin' => $bmw->vin,
                'license_plate' => $bmw->license_plate,
                'color' => $bmw->color,
                'fuel_type' => $bmw->fuel_type,
                'transmission' => $bmw->transmission,
                'engine_size' => $bmw->engine_size,
                'mileage' => $bmw->mileage,
                'problem_category' => 'engine',
                'severity' => 'medium',
                'severity_level' => 'medium',
                'description' => 'Kur nis motori, dëgjohet një zë i çuditshëm që duket si një kërcitje. Zëri zhduket pas disa minutash.',
                'location' => 'Prishtinë',
                'priority' => 'normal',
                'status' => 'pending',
                'estimated_cost' => 200,
            ],
            [
                'title' => 'Probleme me frenat',
                'vehicle_id' => $mercedes->id,
                'brand' => $mercedes->brand,
                'model' => $mercedes->model,
                'year' => $mercedes->year,
                'vin' => $mercedes->vin,
                'license_plate' => $mercedes->license_plate,
                'color' => $mercedes->color,
                'fuel_type' => $mercedes->fuel_type,
                'transmission' => $mercedes->transmission,
                'engine_size' => $mercedes->engine_size,
                'mileage' => $mercedes->mileage,
                'problem_category' => 'brakes',
                'severity' => 'high',
                'severity_level' => 'high',
                'description' => 'Frenat nuk po funksionojnë siç duhet. Ka një zë të çuditshëm kur frenoj dhe ndjesia nuk është e mirë.',
                'location' => 'Prishtinë',
                'priority' => 'high',
                'status' => 'in_progress',
                'estimated_cost' => 500,
            ],
            [
                'title' => 'Probleme me transmisionin',
                'vehicle_id' => $audi->id,
                'brand' => $audi->brand,
                'model' => $audi->model,
                'year' => $audi->year,
                'vin' => $audi->vin,
                'license_plate' => $audi->license_plate,
                'color' => $audi->color,
                'fuel_type' => $audi->fuel_type,
                'transmission' => $audi->transmission,
                'engine_size' => $audi->engine_size,
                'mileage' => $audi->mileage,
                'problem_category' => 'transmission',
                'severity' => 'critical',
                'severity_level' => 'critical',
                'description' => 'Transmisioni nuk po ndërron shpejtësitë siç duhet. Ka ndërprerje dhe dridhje kur ndërron shpejtësitë.',
                'location' => 'Prishtinë',
                'priority' => 'urgent',
                'status' => 'urgent',
                'is_urgent' => true,
                'estimated_cost' => 1500,
            ],
            [
                'title' => 'Probleme me sistemin elektrik',
                'vehicle_id' => $bmw->id,
                'brand' => $bmw->brand,
                'model' => $bmw->model,
                'year' => $bmw->year,
                'vin' => $bmw->vin,
                'license_plate' => $bmw->license_plate,
                'color' => $bmw->color,
                'fuel_type' => $bmw->fuel_type,
                'transmission' => $bmw->transmission,
                'engine_size' => $bmw->engine_size,
                'mileage' => $bmw->mileage,
                'problem_category' => 'electrical',
                'severity' => 'low',
                'severity_level' => 'low',
                'description' => 'Disa drita në brendësi nuk po funksionojnë. Gjithashtu ka probleme me sistemin e navigimit.',
                'location' => 'Prishtinë',
                'priority' => 'low',
                'status' => 'completed',
                'estimated_cost' => 150,
                'resolved_at' => '2024-02-05',
            ],
        ];

        foreach ($reports as $reportData) {
            Report::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'vehicle_id' => $reportData['vehicle_id'],
                    'title' => $reportData['title'],
                ],
                array_merge($reportData, ['user_id' => $user->id])
            );
        }

        $this->command->info('Sample data created successfully!');
        $this->command->info('Test user: test@carwise.com / password');
        $this->command->info('Created ' . Vehicle::count() . ' vehicles and ' . Report::count() . ' reports');
    }
}
