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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeNeedsService($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('next_service_date')
              ->orWhere('next_service_date', '<=', now());
        });
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->brand} {$this->model} ({$this->year})";
    }

    public function getDisplayNameAttribute()
    {
        if ($this->license_plate) {
            return "{$this->brand} {$this->model} - {$this->license_plate}";
        }
        return $this->full_name;
    }

    public function getAgeAttribute()
    {
        return date('Y') - $this->year;
    }

    public function getIsOverdueForServiceAttribute()
    {
        if (!$this->next_service_date) {
            return false;
        }
        return $this->next_service_date->isPast();
    }

    public function getServiceOverdueDaysAttribute()
    {
        if (!$this->next_service_date || !$this->is_overdue_for_service) {
            return 0;
        }
        return $this->next_service_date->diffInDays(now());
    }

    public function getIsInsuranceExpiredAttribute()
    {
        if (!$this->insurance_expiry) {
            return false;
        }
        return $this->insurance_expiry->isPast();
    }

    public function getIsWarrantyExpiredAttribute()
    {
        if (!$this->warranty_expiry) {
            return false;
        }
        return $this->warranty_expiry->isPast();
    }

    // Methods
    public function markAsPrimary()
    {
        // Remove primary from other vehicles of the same user
        $this->user->vehicles()->where('id', '!=', $this->id)->update(['is_primary' => false]);
        
        // Set this vehicle as primary
        $this->update(['is_primary' => true]);
    }

    public function addServiceRecord($data)
    {
        $serviceHistory = $this->service_history ?? [];
        $serviceHistory[] = array_merge($data, [
            'recorded_at' => now()->toISOString(),
            'recorded_by' => auth()->id()
        ]);

        $this->update([
            'service_history' => $serviceHistory,
            'last_service_date' => $data['service_date'] ?? now(),
            'next_service_date' => $data['next_service_date'] ?? null,
            'mileage' => $data['mileage'] ?? $this->mileage,
        ]);
    }

    public function getServiceHistory()
    {
        return collect($this->service_history ?? [])->sortByDesc('recorded_at');
    }

    public function getModifications()
    {
        return collect($this->modifications ?? [])->sortByDesc('date');
    }

    public function getTotalServiceCost()
    {
        return collect($this->service_history ?? [])
            ->sum('cost');
    }

    public function getAverageServiceCost()
    {
        $services = collect($this->service_history ?? []);
        if ($services->isEmpty()) {
            return 0;
        }
        return $services->avg('cost');
    }

    public function getLastServiceDate()
    {
        $services = collect($this->service_history ?? []);
        if ($services->isEmpty()) {
            return null;
        }
        return $services->sortByDesc('service_date')->first()['service_date'] ?? null;
    }

    public function getNextServiceDate()
    {
        return $this->next_service_date;
    }

    public function getServiceReminderDays()
    {
        if (!$this->next_service_date) {
            return null;
        }
        return $this->next_service_date->diffInDays(now());
    }

    public function isServiceDue()
    {
        return $this->next_service_date && $this->next_service_date->isPast();
    }

    public function isServiceDueSoon($days = 30)
    {
        return $this->next_service_date && 
               $this->next_service_date->isFuture() && 
               $this->next_service_date->diffInDays(now()) <= $days;
    }
}
