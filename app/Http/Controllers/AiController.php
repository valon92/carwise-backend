<?php

namespace App\Http\Controllers;

use App\Models\AiChat;
use App\Models\Report;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class AiController extends Controller
{
    protected AiService $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
        $this->middleware('auth');
    }

    public function chat(): Response
    {
        $user = Auth::user();
        
        // Get user's chat history
        $chatHistory = $user->aiChats()
            ->latest()
            ->take(50)
            ->get();
            
        // Get AI insights for user
        $insights = $user->getAiInsights();
        
        // Get user's vehicles for context
        $vehicles = $user->vehicles()->get();
        
        // Get user stats
        $userStats = [
            'total_reports' => $user->reports()->count(),
            'total_vehicles' => $vehicles->count(),
            'ai_interactions' => $chatHistory->count(),
            'avg_confidence' => $chatHistory->avg('confidence') ?? 0
        ];

        return Inertia::render('Ai/Chat', [
            'chatHistory' => $chatHistory,
            'insights' => $insights,
            'userStats' => $userStats,
            'vehicles' => $vehicles
        ]);
    }

    public function processMessage(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'message' => 'required|string|max:1000',
            'session_id' => 'nullable|string'
        ]);

        $result = $this->aiService->processMessage(
            $user,
            $request->message,
            $request->session_id
        );

        return response()->json($result);
    }

    public function getChatHistory()
    {
        $user = Auth::user();
        
        $chatHistory = $user->aiChats()
            ->latest()
            ->take(100)
            ->get();

        return response()->json($chatHistory);
    }

    public function provideFeedback(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'chat_id' => 'required|exists:ai_chats,id',
            'rating' => 'required|integer|between:1,5',
            'feedback' => 'nullable|string|max:500'
        ]);

        $chat = $user->aiChats()->findOrFail($request->chat_id);
        $chat->update([
            'user_rating' => $request->rating,
            'user_feedback' => $request->feedback
        ]);

        return response()->json(['success' => true]);
    }

    public function getInsights()
    {
        $user = Auth::user();
        $insights = $user->getAiInsights();
        
        return response()->json($insights);
    }

    public function analytics(): Response
    {
        $user = Auth::user();
        
        // Check if user can view analytics
        if (!$user->canViewAnalytics()) {
            abort(403, 'Nuk keni leje për të parë analitikat.');
        }

        // Get system-wide analytics
        $analytics = $this->getSystemAnalytics();
        
        // Get user-specific analytics
        $userAnalytics = $this->getUserAnalytics($user);
        
        // Merge analytics
        $analytics['user_insights'] = $userAnalytics;

        return Inertia::render('Ai/Analytics', [
            'analytics' => $analytics
        ]);
    }

    /**
     * Assess report severity using AI
     */
    private function assessSeverity(Report $report): array
    {
        $severity = 'medium';
        $confidence = 0.7;
        $factors = [];

        // Analyze description for severity indicators
        $description = strtolower($report->description);
        
        if (str_contains($description, 'urgjent') || str_contains($description, 'emergjencë')) {
            $severity = 'critical';
            $confidence = 0.9;
            $factors[] = 'Urgjencë e përmendur';
        }

        if (str_contains($description, 'nuk funksionon') || str_contains($description, 'nuk punon')) {
            $severity = 'high';
            $confidence = 0.8;
            $factors[] = 'Probleme funksionale';
        }

        if (str_contains($description, 'zhurmë') || str_contains($description, 'vibrim')) {
            $severity = 'medium';
            $confidence = 0.6;
            $factors[] = 'Probleme me zhurmë';
        }

        return [
            'level' => $severity,
            'confidence' => $confidence,
            'factors' => $factors,
            'recommendation' => $this->getSeverityRecommendation($severity)
        ];
    }

    /**
     * Estimate repair cost using AI
     */
    private function estimateCost(Report $report): array
    {
        $baseCost = 100; // Base cost in EUR
        
        // Adjust based on brand
        $brandMultiplier = match($report->brand) {
            'Mercedes', 'BMW', 'Audi' => 1.5,
            'Toyota', 'Honda' => 0.8,
            'Volkswagen', 'Ford' => 1.0,
            default => 1.0
        };

        // Adjust based on problem category
        $problemMultiplier = match($report->problem_category) {
            'engine' => 2.0,
            'transmission' => 1.8,
            'electrical' => 1.2,
            'brakes' => 1.0,
            'suspension' => 1.3,
            default => 1.0
        };

        $estimatedCost = $baseCost * $brandMultiplier * $problemMultiplier;

        return [
            'estimated_cost' => round($estimatedCost, 2),
            'cost_range' => [
                'min' => round($estimatedCost * 0.7, 2),
                'max' => round($estimatedCost * 1.3, 2)
            ],
            'factors' => [
                'brand' => $report->brand,
                'problem_category' => $report->problem_category,
                'vehicle_age' => now()->year - $report->year
            ]
        ];
    }

    /**
     * Estimate repair time using AI
     */
    private function estimateRepairTime(Report $report): array
    {
        $baseTime = 2; // Base time in hours
        
        // Adjust based on severity
        $severityMultiplier = match($report->severity_level) {
            'low' => 0.5,
            'medium' => 1.0,
            'high' => 2.0,
            'critical' => 4.0,
            default => 1.0
        };

        $estimatedTime = $baseTime * $severityMultiplier;

        return [
            'hours' => round($estimatedTime, 1),
            'days' => ceil($estimatedTime / 8),
            'complexity' => $this->assessComplexity($estimatedTime)
        ];
    }

    /**
     * Recommend parts using AI
     */
    private function recommendParts(Report $report): array
    {
        $parts = [];
        
        // Basic parts based on problem category
        switch ($report->problem_category) {
            case 'engine':
                $parts = ['spark_plugs', 'air_filter', 'oil_filter', 'timing_belt'];
                break;
            case 'brakes':
                $parts = ['brake_pads', 'brake_rotors', 'brake_fluid'];
                break;
            case 'electrical':
                $parts = ['battery', 'alternator', 'starter'];
                break;
            case 'transmission':
                $parts = ['transmission_fluid', 'clutch'];
                break;
            default:
                $parts = ['general_parts'];
        }

        return [
            'recommended_parts' => $parts,
            'estimated_parts_cost' => count($parts) * 50, // Rough estimate
            'availability' => 'in_stock'
        ];
    }

    /**
     * Find similar cases using AI
     */
    private function findSimilarCases(Report $report): array
    {
        $similarCases = Report::where('brand', $report->brand)
            ->where('problem_category', $report->problem_category)
            ->where('id', '!=', $report->id)
            ->where('status', 'completed')
            ->limit(5)
            ->get();

        return [
            'count' => $similarCases->count(),
            'cases' => $similarCases->map(function ($case) {
                return [
                    'id' => $case->id,
                    'description' => $case->description,
                    'resolution_time' => $case->completed_at ? $case->created_at->diffInHours($case->completed_at) : null,
                    'cost' => $case->estimated_cost
                ];
            })
        ];
    }

    /**
     * Suggest preventive measures using AI
     */
    private function suggestPreventiveMeasures(Report $report): array
    {
        $measures = [];
        
        switch ($report->problem_category) {
            case 'engine':
                $measures = [
                    'Kontrolloni nivelin e vajit çdo muaj',
                    'Ndryshoni filtrimin e ajrit çdo 15,000 km',
                    'Përdorni karburant të cilësisë së mirë'
                ];
                break;
            case 'brakes':
                $measures = [
                    'Kontrolloni frenat çdo 6 muaj',
                    'Ndryshoni lëngun e frenave çdo 2 vjet',
                    'Shmangni frenimin e papritur'
                ];
                break;
            case 'electrical':
                $measures = [
                    'Kontrolloni baterinë çdo 3 muaj',
                    'Pastroni terminalet e baterisë',
                    'Shmangni përdorimin e pajisjeve shtesë'
                ];
                break;
            default:
                $measures = [
                    'Kryeni mirëmbajtjen e rregullt',
                    'Kontrolloni automjetin çdo muaj',
                    'Përdorni pjesë origjinale'
                ];
        }

        return $measures;
    }

    /**
     * Get severity recommendation
     */
    private function getSeverityRecommendation(string $severity): string
    {
        return match($severity) {
            'critical' => 'Kërkohet ndërhyrje e menjëhershme',
            'high' => 'Kërkohet riparim i shpejtë',
            'medium' => 'Riparimi mund të pritet',
            'low' => 'Problemi mund të zgjidhet me kohë',
            default => 'Kërkohet vlerësim i mëtejshëm'
        };
    }

    /**
     * Assess complexity based on time
     */
    private function assessComplexity(float $time): string
    {
        return match(true) {
            $time <= 1 => 'e thjeshtë',
            $time <= 4 => 'e moderuar',
            $time <= 8 => 'e ndërlikuar',
            default => 'shumë e ndërlikuar'
        };
    }

    /**
     * Get system statistics
     */
    private function getSystemStats(): array
    {
        return [
            'total_reports' => Report::count(),
            'total_users' => User::count(),
            'total_vehicles' => Vehicle::count(),
            'ai_chats' => AiChat::count(),
            'reports_by_status' => [
                'pending' => Report::where('status', 'pending')->count(),
                'in_progress' => Report::where('status', 'in_progress')->count(),
                'completed' => Report::where('status', 'completed')->count(),
                'cancelled' => Report::where('status', 'cancelled')->count()
            ]
        ];
    }

    /**
     * Get AI performance statistics
     */
    private function getAiPerformanceStats(): array
    {
        $totalChats = AiChat::count();
        $resolvedChats = AiChat::where('is_resolved', true)->count();
        $highConfidenceChats = AiChat::where('confidence', '>=', 0.8)->count();
        
        $averageRating = AiChat::whereNotNull('feedback_rating')
            ->avg('feedback_rating');

        return [
            'total_chats' => $totalChats,
            'resolved_chats' => $resolvedChats,
            'resolution_rate' => $totalChats > 0 ? round(($resolvedChats / $totalChats) * 100, 1) : 0,
            'high_confidence_rate' => $totalChats > 0 ? round(($highConfidenceChats / $totalChats) * 100, 1) : 0,
            'average_rating' => round($averageRating, 1),
            'average_response_time' => AiChat::avg('processing_time')
        ];
    }

    /**
     * Get trends data
     */
    private function getTrends(): array
    {
        $lastMonth = now()->subMonth();
        
        return [
            'reports_trend' => [
                'current_month' => Report::whereMonth('created_at', now()->month)->count(),
                'last_month' => Report::whereMonth('created_at', $lastMonth->month)->count()
            ],
            'ai_usage_trend' => [
                'current_month' => AiChat::whereMonth('created_at', now()->month)->count(),
                'last_month' => AiChat::whereMonth('created_at', $lastMonth->month)->count()
            ]
        ];
    }
}
