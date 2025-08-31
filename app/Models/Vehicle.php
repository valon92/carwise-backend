<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Vehicle extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'user_id',
        'brand',
        'model',
        'year',
        'vin',
        'license_plate',
        'mileage',
        'fuel_type',
        'transmission',
        'color',
        'engine_size',
        'horsepower',
        'torque',
        'fuel_efficiency',
        'body_type',
        'doors',
        'seats',
        'weight',
        'length',
        'width',
        'height',
        'warranty_expiry',
        'insurance_expiry',
        'last_service_date',
        'next_service_date',
        'service_history',
        'modifications',
        'notes',
        'is_active',
        'is_primary'
    ];

    protected $casts = [
        'service_history' => 'array',
        'modifications' => 'array',
        'warranty_expiry' => 'date',
        'insurance_expiry' => 'date',
        'last_service_date' => 'date',
        'next_service_date' => 'date',
        'is_active' => 'boolean',
        'is_primary' => 'boolean',
        'mileage' => 'integer',
        'horsepower' => 'integer',
        'torque' => 'integer',
        'fuel_efficiency' => 'decimal:2',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2'
    ];

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['brand', 'model', 'year', 'license_plate', 'mileage'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function maintenanceRecords()
    {
        return $this->hasMany(MaintenanceRecord::class);
    }

    public function fuelRecords()
    {
        return $this->hasMany(FuelRecord::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeByFuelType($query, $fuelType)
    {
        return $query->where('fuel_type', $fuelType);
    }

    public function scopeNeedsService($query)
    {
        return $query->where('next_service_date', '<=', now()->addDays(30));
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->brand} {$this->model} ({$this->year})";
    }

    public function getShortNameAttribute()
    {
        return "{$this->brand} {$this->model}";
    }

    public function getAgeAttribute()
    {
        return now()->year - $this->year;
    }

    public function getServiceStatusAttribute()
    {
        if (!$this->next_service_date) {
            return 'unknown';
        }

        if ($this->next_service_date->isPast()) {
            return 'overdue';
        }

        if ($this->next_service_date->diffInDays(now()) <= 30) {
            return 'due_soon';
        }

        return 'good';
    }

    public function getWarrantyStatusAttribute()
    {
        if (!$this->warranty_expiry) {
            return 'unknown';
        }

        if ($this->warranty_expiry->isPast()) {
            return 'expired';
        }

        if ($this->warranty_expiry->diffInDays(now()) <= 90) {
            return 'expiring_soon';
        }

        return 'active';
    }

    public function getInsuranceStatusAttribute()
    {
        if (!$this->insurance_expiry) {
            return 'unknown';
        }

        if ($this->insurance_expiry->isPast()) {
            return 'expired';
        }

        if ($this->insurance_expiry->diffInDays(now()) <= 30) {
            return 'expiring_soon';
        }

        return 'active';
    }

    public function getMileageFormattedAttribute()
    {
        return number_format($this->mileage) . ' km';
    }

    public function getFuelEfficiencyFormattedAttribute()
    {
        return $this->fuel_efficiency . ' L/100km';
    }

    // Methods
    public function markAsPrimary()
    {
        // Remove primary from other vehicles
        $this->user->vehicles()->update(['is_primary' => false]);
        
        // Set this vehicle as primary
        $this->update(['is_primary' => true]);
    }

    public function updateMileage($newMileage)
    {
        $this->update(['mileage' => $newMileage]);
    }

    public function addServiceRecord($service)
    {
        $history = $this->service_history ?? [];
        $history[] = [
            'date' => now()->toDateString(),
            'service' => $service,
            'mileage' => $this->mileage
        ];

        $this->update([
            'service_history' => $history,
            'last_service_date' => now(),
            'next_service_date' => now()->addMonths(6) // Default 6 months
        ]);
    }

    public function getMaintenanceCosts()
    {
        return $this->reports()
            ->where('status', 'completed')
            ->sum('estimated_cost');
    }

    public function getAverageRepairCost()
    {
        $completedReports = $this->reports()
            ->where('status', 'completed');

        if ($completedReports->count() === 0) {
            return 0;
        }

        return $completedReports->avg('estimated_cost');
    }

    public function getMostCommonIssues()
    {
        return $this->reports()
            ->groupBy('problem_category')
            ->map(function ($group) {
                return $group->count();
            })
            ->sortDesc()
            ->take(5);
    }

    public function getReliabilityScore()
    {
        $totalReports = $this->reports()->count();
        
        if ($totalReports === 0) {
            return 100; // Perfect score for new vehicles
        }

        $criticalIssues = $this->reports()
            ->where('severity_level', 'critical')
            ->count();

        $highIssues = $this->reports()
            ->where('severity_level', 'high')
            ->count();

        // Calculate reliability score (100 = perfect, 0 = very unreliable)
        $score = 100 - ($criticalIssues * 20) - ($highIssues * 10);
        
        return max(0, $score);
    }

    public function getNextServiceMileage()
    {
        $lastServiceMileage = collect($this->service_history)
            ->last()['mileage'] ?? 0;

        return $lastServiceMileage + 10000; // Default 10,000 km interval
    }

    public function needsService()
    {
        return $this->mileage >= $this->getNextServiceMileage() ||
               ($this->next_service_date && $this->next_service_date->isPast());
    }

    public function getAiRecommendations()
    {
        return [
            'reliability_score' => $this->getReliabilityScore(),
            'maintenance_schedule' => $this->getMaintenanceSchedule(),
            'cost_analysis' => $this->getCostAnalysis(),
            'upcoming_services' => $this->getUpcomingServices(),
            'parts_recommendations' => $this->getPartsRecommendations()
        ];
    }

    private function getMaintenanceSchedule()
    {
        return [
            'next_service_mileage' => $this->getNextServiceMileage(),
            'next_service_date' => $this->next_service_date,
            'estimated_next_service' => $this->next_service_date ?? now()->addMonths(6)
        ];
    }

    private function getCostAnalysis()
    {
        return [
            'total_maintenance_cost' => $this->getMaintenanceCosts(),
            'average_repair_cost' => $this->getAverageRepairCost(),
            'cost_per_km' => $this->mileage > 0 ? $this->getMaintenanceCosts() / $this->mileage : 0
        ];
    }

    private function getUpcomingServices()
    {
        $services = [];

        if ($this->needsService()) {
            $services[] = 'General maintenance service';
        }

        if ($this->warranty_expiry && $this->warranty_expiry->diffInDays(now()) <= 90) {
            $services[] = 'Warranty expiring soon';
        }

        if ($this->insurance_expiry && $this->insurance_expiry->diffInDays(now()) <= 30) {
            $services[] = 'Insurance renewal due';
        }

        return $services;
    }

    private function getPartsRecommendations()
    {
        $commonIssues = $this->getMostCommonIssues();
        $recommendations = [];

        foreach ($commonIssues as $issue => $count) {
            $recommendations[] = [
                'issue' => $issue,
                'frequency' => $count,
                'recommended_parts' => $this->getPartsForIssue($issue)
            ];
        }

        return $recommendations;
    }

    private function getPartsForIssue($issue)
    {
        // AI-based parts recommendation logic
        $partsMap = [
            'engine' => ['spark_plugs', 'air_filter', 'oil_filter'],
            'brakes' => ['brake_pads', 'brake_rotors', 'brake_fluid'],
            'transmission' => ['transmission_fluid', 'clutch'],
            'electrical' => ['battery', 'alternator', 'starter'],
            'suspension' => ['shock_absorbers', 'springs', 'bushings']
        ];

        return $partsMap[$issue] ?? [];
    }
}
