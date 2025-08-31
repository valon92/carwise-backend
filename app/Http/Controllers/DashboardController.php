<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Report;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
        $this->middleware('auth');
    }

    public function index(): Response
    {
        $user = Auth::user();
        
        // Get user statistics
        $stats = [
            'user' => [
                'total' => $user->reports()->count(),
                'pending' => $user->reports()->where('status', 'pending')->count(),
                'in_progress' => $user->reports()->where('status', 'in_progress')->count(),
                'completed' => $user->reports()->where('status', 'completed')->count(),
                'urgent' => $user->reports()->where('priority', 'high')->count(),
            ],
            'vehicles' => [
                'total' => $user->vehicles()->count(),
                'active' => $user->vehicles()->where('status', 'active')->count(),
                'needs_service' => $user->vehicles()->needsService()->count(),
            ],
            'ai' => [
                'total_chats' => $user->aiChats()->count(),
                'avg_confidence' => $user->aiChats()->avg('confidence') ?? 0,
            ]
        ];

        // Get recent reports
        $recent_reports = $user->reports()
            ->with('vehicle')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->id,
                    'title' => $report->title,
                    'status' => $report->status,
                    'priority' => $report->priority,
                    'created_at' => $report->created_at,
                    'brand' => $report->vehicle->brand ?? 'N/A',
                    'model' => $report->vehicle->model ?? 'N/A',
                    'year' => $report->vehicle->year ?? 'N/A',
                ];
            });

        // Get urgent reports
        $urgent_reports = $user->reports()
            ->where('priority', 'high')
            ->with('vehicle')
            ->latest()
            ->take(3)
            ->get();

        // Get vehicles needing service
        $vehicles_needing_service = $user->vehicles()
            ->needsService()
            ->take(3)
            ->get();

        // Get AI insights
        $ai_insights = $user->getAiInsights();

        // Quick actions
        $quick_actions = [
            [
                'title' => 'Krijo Raport',
                'description' => 'Regjistro problem të ri',
                'route' => route('reports.create'),
                'color' => 'red'
            ],
            [
                'title' => 'Shto Automjet',
                'description' => 'Regjistro automjet të ri',
                'route' => route('vehicles.create'),
                'color' => 'blue'
            ],
            [
                'title' => 'AI Chat',
                'description' => 'Bisedo me AI',
                'route' => route('ai.chat'),
                'color' => 'purple'
            ],
            [
                'title' => 'Analitika',
                'description' => 'Shiko statistikat',
                'route' => route('ai.analytics'),
                'color' => 'green'
            ]
        ];

        // Upcoming events
        $upcoming_events = $user->vehicles()
            ->whereNotNull('next_service_date')
            ->where('next_service_date', '<=', now()->addDays(30))
            ->get()
            ->map(function ($vehicle) {
                return [
                    'title' => 'Servis: ' . $vehicle->brand . ' ' . $vehicle->model,
                    'description' => 'Servis i planifikuar',
                    'date' => $vehicle->next_service_date,
                    'type' => 'service',
                    'priority' => $vehicle->next_service_date <= now() ? 'high' : 'medium'
                ];
            })
            ->take(5);

        // Performance metrics
        $performance_metrics = [
            'reports_resolved_this_month' => $user->reports()
                ->where('status', 'completed')
                ->whereMonth('updated_at', now()->month)
                ->count(),
            'avg_resolution_time' => $user->reports()
                ->where('status', 'completed')
                ->whereNotNull('completed_at')
                ->get()
                ->avg(function ($report) {
                    return $report->created_at->diffInHours($report->completed_at);
                }) ?? 0,
            'ai_interactions' => $user->aiChats()
                ->whereMonth('created_at', now()->month)
                ->count()
        ];

        return Inertia::render('Dashboard', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? 'User',
                'last_login' => $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never',
                'unread_notifications' => 0 // Will be implemented later
            ],
            'stats' => $stats,
            'recent_reports' => $recent_reports,
            'urgent_reports' => $urgent_reports,
            'vehicles_needing_service' => $vehicles_needing_service,
            'ai_insights' => $ai_insights,
            'quick_actions' => $quick_actions,
            'upcoming_events' => $upcoming_events,
            'performance_metrics' => $performance_metrics,
            'notifications' => [], // Will be implemented later
        ]);
    }
}
