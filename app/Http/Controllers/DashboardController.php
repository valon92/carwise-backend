<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Vehicle;
use App\Models\User;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    protected AiService $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
        $this->middleware('auth');
    }

    /**
     * Show the main dashboard
     */
    public function index(): Response
    {
        $user = Auth::user();
        
        // Get user statistics
        $userStats = $user->getReportsStats();
        $assignedStats = $user->getAssignedReportsStats();
        
        // Get AI insights
        $aiInsights = $this->aiService->getUserInsights($user);
        
        // Get recent reports
        $recentReports = $user->reports()
            ->with(['assignedTechnician'])
            ->latest()
            ->limit(5)
            ->get();
            
        // Get urgent reports
        $urgentReports = $user->reports()
            ->where('is_urgent', true)
            ->whereIn('status', ['pending', 'in_progress'])
            ->latest()
            ->limit(3)
            ->get();
            
        // Get vehicles that need service
        $vehiclesNeedingService = $user->vehicles()
            ->needsService()
            ->limit(3)
            ->get();
            
        // Get assigned reports (for technicians)
        $assignedReports = collect();
        if ($user->isTechnician) {
            $assignedReports = $user->assignedReports()
                ->whereIn('status', ['pending', 'in_progress'])
                ->latest()
                ->limit(5)
                ->get();
        }
        
        // Get system-wide statistics (for admins)
        $systemStats = null;
        if ($user->isAdmin) {
            $systemStats = $this->getSystemStats();
        }
        
        // Get notifications
        $notifications = $user->notifications()
            ->where('read_at', null)
            ->latest()
            ->limit(10)
            ->get();
            
        // Get upcoming events
        $upcomingEvents = $this->getUpcomingEvents($user);
        
        // Get performance metrics
        $performanceMetrics = $this->getPerformanceMetrics($user);
        
        return Inertia::render('Dashboard', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar_url,
                'role' => $user->role_display_name,
                'is_technician' => $user->is_technician,
                'is_admin' => $user->is_admin,
                'last_login' => $user->last_login_at?->diffForHumans(),
                'unread_notifications' => $user->getUnreadNotificationsCount()
            ],
            'stats' => [
                'user' => $userStats,
                'assigned' => $assignedStats,
                'system' => $systemStats
            ],
            'ai_insights' => $aiInsights,
            'recent_reports' => $recentReports,
            'urgent_reports' => $urgentReports,
            'vehicles_needing_service' => $vehiclesNeedingService,
            'assigned_reports' => $assignedReports,
            'notifications' => $notifications,
            'upcoming_events' => $upcomingEvents,
            'performance_metrics' => $performanceMetrics,
            'quick_actions' => $this->getQuickActions($user)
        ]);
    }

    /**
     * Get system-wide statistics
     */
    private function getSystemStats(): array
    {
        return [
            'total_users' => User::count(),
            'total_reports' => Report::count(),
            'total_vehicles' => Vehicle::count(),
            'reports_by_status' => [
                'pending' => Report::where('status', 'pending')->count(),
                'in_progress' => Report::where('status', 'in_progress')->count(),
                'completed' => Report::where('status', 'completed')->count(),
                'cancelled' => Report::where('status', 'cancelled')->count()
            ],
            'reports_by_severity' => [
                'low' => Report::where('severity_level', 'low')->count(),
                'medium' => Report::where('severity_level', 'medium')->count(),
                'high' => Report::where('severity_level', 'high')->count(),
                'critical' => Report::where('severity_level', 'critical')->count()
            ],
            'urgent_reports' => Report::where('is_urgent', true)->count(),
            'vehicles_needing_service' => Vehicle::needsService()->count(),
            'average_resolution_time' => $this->calculateAverageResolutionTime(),
            'total_cost_this_month' => Report::whereMonth('created_at', now()->month)
                ->sum('estimated_cost')
        ];
    }

    /**
     * Get upcoming events for user
     */
    private function getUpcomingEvents(User $user): array
    {
        $events = [];
        
        // Service appointments
        $vehiclesNeedingService = $user->vehicles()
            ->where('next_service_date', '<=', now()->addDays(30))
            ->get();
            
        foreach ($vehiclesNeedingService as $vehicle) {
            $events[] = [
                'type' => 'service',
                'title' => "Servis për {$vehicle->full_name}",
                'date' => $vehicle->next_service_date,
                'description' => "Servisi i planifikuar për {$vehicle->full_name}",
                'priority' => $vehicle->next_service_date->isPast() ? 'high' : 'medium'
            ];
        }
        
        // Warranty expirations
        $vehiclesWithExpiringWarranty = $user->vehicles()
            ->where('warranty_expiry', '<=', now()->addDays(90))
            ->get();
            
        foreach ($vehiclesWithExpiringWarranty as $vehicle) {
            $events[] = [
                'type' => 'warranty',
                'title' => "Garancia po skadon për {$vehicle->full_name}",
                'date' => $vehicle->warranty_expiry,
                'description' => "Garancia do të skadojë më {$vehicle->warranty_expiry->format('d/m/Y')}",
                'priority' => $vehicle->warranty_expiry->isPast() ? 'high' : 'medium'
            ];
        }
        
        // Insurance expirations
        $vehiclesWithExpiringInsurance = $user->vehicles()
            ->where('insurance_expiry', '<=', now()->addDays(30))
            ->get();
            
        foreach ($vehiclesWithExpiringInsurance as $vehicle) {
            $events[] = [
                'type' => 'insurance',
                'title' => "Sigurimi po skadon për {$vehicle->full_name}",
                'date' => $vehicle->insurance_expiry,
                'description' => "Sigurimi do të skadojë më {$vehicle->insurance_expiry->format('d/m/Y')}",
                'priority' => $vehicle->insurance_expiry->isPast() ? 'high' : 'medium'
            ];
        }
        
        // Sort by date
        usort($events, function ($a, $b) {
            return $a['date']->compare($b['date']);
        });
        
        return array_slice($events, 0, 10);
    }

    /**
     * Get performance metrics for user
     */
    private function getPerformanceMetrics(User $user): array
    {
        $reports = $user->reports();
        $completedReports = $reports->where('status', 'completed');
        
        $totalReports = $reports->count();
        $completedCount = $completedReports->count();
        
        return [
            'completion_rate' => $totalReports > 0 ? round(($completedCount / $totalReports) * 100, 1) : 0,
            'average_resolution_time' => $this->calculateUserAverageResolutionTime($user),
            'total_cost_saved' => $completedReports->sum('estimated_cost'),
            'efficiency_score' => $this->calculateEfficiencyScore($user),
            'customer_satisfaction' => $this->calculateCustomerSatisfaction($user),
            'response_time' => $this->calculateAverageResponseTime($user)
        ];
    }

    /**
     * Get quick actions for user
     */
    private function getQuickActions(User $user): array
    {
        $actions = [
            [
                'title' => 'Krijo Raport të Ri',
                'description' => 'Raporto një problem të automjetit',
                'icon' => 'plus-circle',
                'route' => route('reports.create'),
                'color' => 'blue'
            ],
            [
                'title' => 'Shto Automjet',
                'description' => 'Regjistro një automjet të ri',
                'icon' => 'truck',
                'route' => route('vehicles.create'),
                'color' => 'green'
            ],
            [
                'title' => 'AI Asistent',
                'description' => 'Bisedo me AI për ndihmë',
                'icon' => 'chat-bubble-left-right',
                'route' => route('ai.chat'),
                'color' => 'purple'
            ]
        ];
        
        if ($user->isTechnician) {
            $actions[] = [
                'title' => 'Raportet e Caktuara',
                'description' => 'Shiko raportet që të janë caktuar',
                'icon' => 'clipboard-document-list',
                'route' => route('reports.index', ['assigned' => 'true']),
                'color' => 'orange'
            ];
        }
        
        if ($user->isAdmin) {
            $actions[] = [
                'title' => 'Analitikat AI',
                'description' => 'Shiko analitikat e sistemit',
                'icon' => 'chart-bar',
                'route' => route('ai.analytics'),
                'color' => 'indigo'
            ];
        }
        
        return $actions;
    }

    /**
     * Calculate average resolution time for system
     */
    private function calculateAverageResolutionTime(): float
    {
        $completedReports = Report::where('status', 'completed')
            ->whereNotNull('completed_at');
            
        if ($completedReports->count() === 0) {
            return 0;
        }
        
        $totalHours = $completedReports->get()->sum(function ($report) {
            return $report->created_at->diffInHours($report->completed_at);
        });
        
        return round($totalHours / $completedReports->count(), 1);
    }

    /**
     * Calculate user average resolution time
     */
    private function calculateUserAverageResolutionTime(User $user): float
    {
        $completedReports = $user->reports()
            ->where('status', 'completed')
            ->whereNotNull('completed_at');
            
        if ($completedReports->count() === 0) {
            return 0;
        }
        
        $totalHours = $completedReports->get()->sum(function ($report) {
            return $report->created_at->diffInHours($report->completed_at);
        });
        
        return round($totalHours / $completedReports->count(), 1);
    }

    /**
     * Calculate efficiency score for user
     */
    private function calculateEfficiencyScore(User $user): int
    {
        $reports = $user->reports();
        $totalReports = $reports->count();
        
        if ($totalReports === 0) {
            return 100; // Perfect score for new users
        }
        
        $completedReports = $reports->where('status', 'completed')->count();
        $urgentReports = $reports->where('is_urgent', true)->count();
        
        // Base score from completion rate
        $score = ($completedReports / $totalReports) * 60;
        
        // Bonus for handling urgent reports
        if ($urgentReports > 0) {
            $score += min(20, $urgentReports * 5);
        }
        
        // Bonus for fast resolution
        $avgResolutionTime = $this->calculateUserAverageResolutionTime($user);
        if ($avgResolutionTime < 24) {
            $score += 20;
        } elseif ($avgResolutionTime < 48) {
            $score += 10;
        }
        
        return min(100, round($score));
    }

    /**
     * Calculate customer satisfaction (placeholder)
     */
    private function calculateCustomerSatisfaction(User $user): float
    {
        // This would typically come from customer feedback
        // For now, return a placeholder value
        return 4.2;
    }

    /**
     * Calculate average response time
     */
    private function calculateAverageResponseTime(User $user): float
    {
        // This would typically measure time from report creation to first response
        // For now, return a placeholder value
        return 2.5;
    }
}
