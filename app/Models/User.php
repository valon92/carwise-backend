<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LogsActivity, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'phone',
        'address',
        'city',
        'country',
        'postal_code',
        'date_of_birth',
        'gender',
        'profile_photo',
        'bio',
        'preferences',
        'last_login_at',
        'is_active',
        'email_verified_at',
        'phone_verified_at',
        'two_factor_enabled',
        'notification_preferences'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
        'preferences' => 'array',
        'notification_preferences' => 'array',
        'is_active' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'date_of_birth' => 'date'
    ];

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'is_active', 'last_login_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function assignedReports()
    {
        return $this->hasMany(Report::class, 'assigned_to');
    }

    // Remove non-existent relationships
    // public function comments()
    // {
    //     return $this->hasMany(ReportComment::class);
    // }

    // public function notifications()
    // {
    //     return $this->hasMany(Notification::class);
    // }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function aiChats()
    {
        return $this->hasMany(AiChat::class);
    }

    // public function favorites()
    // {
    //     return $this->hasMany(Favorite::class);
    // }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeTechnicians($query)
    {
        return $query->role('technician');
    }

    public function scopeAdmins($query)
    {
        return $query->role('admin');
    }

    public function scopeCustomers($query)
    {
        return $query->role('customer');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        $initials = '';
        
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        
        return $initials;
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->profile_photo) {
            return $this->getFirstMediaUrl('profile_photos');
        }
        
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&color=7C3AED&background=EBF4FF";
    }

    public function getRoleDisplayNameAttribute()
    {
        return $this->roles->first()?->display_name ?? 'Customer';
    }

    public function getIsTechnicianAttribute()
    {
        return $this->hasRole('technician');
    }

    public function getIsAdminAttribute()
    {
        return $this->hasRole('admin');
    }

    // Methods
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }

    // Remove method that uses non-existent notifications relationship
    // public function getUnreadNotificationsCount()
    // {
    //     return $this->notifications()->where('read_at', null)->count();
    // }

    public function getReportsStats()
    {
        return [
            'total' => $this->reports()->count(),
            'pending' => $this->reports()->where('status', 'pending')->count(),
            'in_progress' => $this->reports()->where('status', 'in_progress')->count(),
            'completed' => $this->reports()->where('status', 'completed')->count(),
            'urgent' => $this->reports()->where('is_urgent', true)->count()
        ];
    }

    public function getAssignedReportsStats()
    {
        return [
            'total' => $this->assignedReports()->count(),
            'pending' => $this->assignedReports()->where('status', 'pending')->count(),
            'in_progress' => $this->assignedReports()->where('status', 'in_progress')->count(),
            'completed' => $this->assignedReports()->where('status', 'completed')->count(),
            'urgent' => $this->assignedReports()->where('is_urgent', true)->count()
        ];
    }

    public function canManageReports()
    {
        return $this->hasAnyRole(['admin', 'technician', 'manager']);
    }

    public function canViewAnalytics()
    {
        return $this->hasAnyRole(['admin', 'manager']);
    }

    public function canManageUsers()
    {
        return $this->hasRole('admin');
    }

    public function getNotificationPreference($type)
    {
        return $this->notification_preferences[$type] ?? true;
    }

    public function setNotificationPreference($type, $enabled)
    {
        $preferences = $this->notification_preferences ?? [];
        $preferences[$type] = $enabled;
        $this->update(['notification_preferences' => $preferences]);
    }

    // AI Methods
    public function getAiInsights()
    {
        $reports = $this->reports()->get(); // Remove with('comments')
        
        return [
            'total_reports' => $reports->count(),
            'average_resolution_time' => $this->calculateAverageResolutionTime($reports),
            'most_common_issues' => $this->getMostCommonIssues($reports),
            'vehicle_preferences' => $this->getVehiclePreferences($reports),
            'cost_analysis' => $this->getCostAnalysis($reports)
        ];
    }

    private function calculateAverageResolutionTime($reports)
    {
        $completedReports = $reports->where('status', 'completed')->whereNotNull('completed_at');
        
        if ($completedReports->isEmpty()) {
            return 0;
        }

        $totalHours = $completedReports->sum(function ($report) {
            return $report->created_at->diffInHours($report->completed_at);
        });

        return round($totalHours / $completedReports->count(), 1);
    }

    private function getMostCommonIssues($reports)
    {
        return $reports->groupBy('problem_category')
            ->map(function ($group) {
                return $group->count();
            })
            ->sortDesc()
            ->take(5);
    }

    private function getVehiclePreferences($reports)
    {
        return $reports->groupBy('brand')
            ->map(function ($group) {
                return $group->count();
            })
            ->sortDesc()
            ->take(3);
    }

    private function getCostAnalysis($reports)
    {
        return [
            'total_spent' => $reports->sum('estimated_cost'),
            'average_cost' => $reports->avg('estimated_cost'),
            'highest_cost' => $reports->max('estimated_cost'),
            'lowest_cost' => $reports->min('estimated_cost')
        ];
    }
}
