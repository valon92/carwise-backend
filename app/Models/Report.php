<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Report extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'vehicle_id',
        'user_id',
        'status',
        'priority',
        'severity_level',
        'estimated_cost',
        'estimated_repair_time',
        'actual_cost',
        'location',
        'symptoms',
        'diagnosis',
        'recommended_actions',
        'parts_needed',
        'notes',
        'completed_at',
        'completion_notes',
        'ai_analysis',
        'ai_recommendations',
        'ai_confidence_score',
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'estimated_repair_time' => 'integer',
        'completed_at' => 'datetime',
        'ai_analysis' => 'array',
        'ai_recommendations' => 'array',
        'ai_confidence_score' => 'decimal:2',
    ];

    protected $dates = [
        'completed_at',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Priority constants
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_CRITICAL = 'critical';

    // Severity constants
    const SEVERITY_MINOR = 'minor';
    const SEVERITY_MODERATE = 'moderate';
    const SEVERITY_MAJOR = 'major';
    const SEVERITY_CRITICAL = 'critical';

    /**
     * Get the user that owns the report.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the vehicle that the report belongs to.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the assigned technician for this report.
     */
    public function assignedTechnician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_technician_id');
    }

    /**
     * Get the AI chats related to this report.
     */
    public function aiChats(): HasMany
    {
        return $this->hasMany(AiChat::class);
    }

    /**
     * Check if the report is urgent.
     */
    public function isUrgent(): bool
    {
        return in_array($this->priority, [self::PRIORITY_HIGH, self::PRIORITY_CRITICAL]);
    }

    /**
     * Check if the report is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if the report is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    /**
     * Check if the report is pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Get the status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_IN_PROGRESS => 'blue',
            self::STATUS_COMPLETED => 'green',
            self::STATUS_CANCELLED => 'red',
            default => 'gray'
        };
    }

    /**
     * Get the priority badge color.
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'green',
            self::PRIORITY_MEDIUM => 'yellow',
            self::PRIORITY_HIGH => 'orange',
            self::PRIORITY_CRITICAL => 'red',
            default => 'gray'
        };
    }

    /**
     * Get the severity badge color.
     */
    public function getSeverityColorAttribute(): string
    {
        return match($this->severity_level) {
            self::SEVERITY_MINOR => 'green',
            self::SEVERITY_MODERATE => 'yellow',
            self::SEVERITY_MAJOR => 'orange',
            self::SEVERITY_CRITICAL => 'red',
            default => 'gray'
        };
    }

    /**
     * Get the formatted estimated cost.
     */
    public function getFormattedEstimatedCostAttribute(): string
    {
        return $this->estimated_cost ? '€' . number_format($this->estimated_cost, 2) : 'N/A';
    }

    /**
     * Get the formatted actual cost.
     */
    public function getFormattedActualCostAttribute(): string
    {
        return $this->actual_cost ? '€' . number_format($this->actual_cost, 2) : 'N/A';
    }

    /**
     * Get the formatted estimated repair time.
     */
    public function getFormattedEstimatedRepairTimeAttribute(): string
    {
        if (!$this->estimated_repair_time) {
            return 'N/A';
        }

        if ($this->estimated_repair_time < 24) {
            return $this->estimated_repair_time . ' orë';
        }

        $days = floor($this->estimated_repair_time / 24);
        $hours = $this->estimated_repair_time % 24;

        if ($hours === 0) {
            return $days . ' ditë';
        }

        return $days . ' ditë, ' . $hours . ' orë';
    }

    /**
     * Get the resolution time in hours.
     */
    public function getResolutionTimeHoursAttribute(): ?int
    {
        if (!$this->completed_at) {
            return null;
        }

        return $this->created_at->diffInHours($this->completed_at);
    }

    /**
     * Get the formatted resolution time.
     */
    public function getFormattedResolutionTimeAttribute(): string
    {
        $hours = $this->resolution_time_hours;

        if (!$hours) {
            return 'N/A';
        }

        if ($hours < 24) {
            return $hours . ' orë';
        }

        $days = floor($hours / 24);
        $remainingHours = $hours % 24;

        if ($remainingHours === 0) {
            return $days . ' ditë';
        }

        return $days . ' ditë, ' . $remainingHours . ' orë';
    }

    /**
     * Scope for urgent reports.
     */
    public function scopeUrgent($query)
    {
        return $query->whereIn('priority', [self::PRIORITY_HIGH, self::PRIORITY_CRITICAL]);
    }

    /**
     * Scope for completed reports.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for pending reports.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for in progress reports.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    /**
     * Scope for reports by priority.
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for reports by severity.
     */
    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity_level', $severity);
    }

    /**
     * Scope for reports created in the last X days.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get AI recommendations for this report.
     */
    public function getAiRecommendations(): array
    {
        if ($this->ai_recommendations) {
            return $this->ai_recommendations;
        }

        // Generate basic recommendations based on report data
        $recommendations = [];

        if ($this->severity_level === self::SEVERITY_CRITICAL) {
            $recommendations[] = [
                'type' => 'urgent',
                'title' => 'Kërkohet vëmendje e menjëhershme',
                'description' => 'Ky problem është kritik dhe kërkon riparim të menjëhershëm.',
                'priority' => 'high'
            ];
        }

        if ($this->estimated_cost && $this->estimated_cost > 1000) {
            $recommendations[] = [
                'type' => 'cost',
                'title' => 'Kosto e lartë e riparimit',
                'description' => 'Konsideroni të merrni disa oferta para se të vazhdoni.',
                'priority' => 'medium'
            ];
        }

        if ($this->vehicle && $this->vehicle->mileage > 200000) {
            $recommendations[] = [
                'type' => 'maintenance',
                'title' => 'Mirëmbajtje e rregullt',
                'description' => 'Automjeti ka kilometrazh të lartë, rekomandohet mirëmbajtje e rregullt.',
                'priority' => 'medium'
            ];
        }

        return $recommendations;
    }

    /**
     * Register media collections for this model.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->withResponsiveImages();

        $this->addMediaCollection('documents')
            ->acceptsMimeTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
    }
}
