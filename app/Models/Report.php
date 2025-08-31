<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Report extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'user_id',
        'title',
        'description', 
        'brand', 
        'model', 
        'year', 
        'vin',
        'license_plate',
        'mileage',
        'fuel_type',
        'transmission',
        'color',
        'problem_category',
        'severity_level',
        'status',
        'ai_analysis',
        'estimated_cost',
        'priority',
        'location',
        'latitude',
        'longitude',
        'is_urgent',
        'assigned_to',
        'completed_at'
    ];

    protected $casts = [
        'images' => 'array',
        'ai_analysis' => 'array',
        'estimated_cost' => 'decimal:2',
        'is_urgent' => 'boolean',
        'completed_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'completed_at'
    ];

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'status', 'severity_level', 'assigned_to'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTechnician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(ReportComment::class);
    }

    public function attachments()
    {
        return $this->hasMany(ReportAttachment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Scopes
    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity_level', $severity);
    }

    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    // Accessors
    public function getFullVehicleNameAttribute()
    {
        return "{$this->brand} {$this->model} ({$this->year})";
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'in_progress' => 'blue',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray'
        };
    }

    public function getSeverityColorAttribute()
    {
        return match($this->severity_level) {
            'low' => 'green',
            'medium' => 'yellow',
            'high' => 'orange',
            'critical' => 'red',
            default => 'gray'
        };
    }

    // Methods
    public function markAsUrgent()
    {
        $this->update(['is_urgent' => true]);
    }

    public function assignTo($userId)
    {
        $this->update(['assigned_to' => $userId]);
    }

    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
    }

    public function getAiRecommendations()
    {
        // AI logic for recommendations
        return [
            'estimated_repair_time' => $this->calculateEstimatedRepairTime(),
            'recommended_parts' => $this->getRecommendedParts(),
            'cost_breakdown' => $this->getCostBreakdown(),
            'similar_cases' => $this->findSimilarCases()
        ];
    }

    private function calculateEstimatedRepairTime()
    {
        // AI-based repair time estimation
        $baseTime = 2; // hours
        $severityMultiplier = match($this->severity_level) {
            'low' => 1,
            'medium' => 1.5,
            'high' => 2,
            'critical' => 3,
            default => 1
        };
        
        return $baseTime * $severityMultiplier;
    }

    private function getRecommendedParts()
    {
        // AI logic for parts recommendation
        return [];
    }

    private function getCostBreakdown()
    {
        // AI logic for cost breakdown
        return [
            'parts' => $this->estimated_cost * 0.6,
            'labor' => $this->estimated_cost * 0.4
        ];
    }

    private function findSimilarCases()
    {
        // AI logic for finding similar cases
        return self::where('brand', $this->brand)
            ->where('problem_category', $this->problem_category)
            ->where('id', '!=', $this->id)
            ->limit(5)
            ->get();
    }
}
