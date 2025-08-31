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

    /**
     * Show AI chat interface
     */
    public function chat(): Response
    {
        $user = Auth::user();
        $chatHistory = $user->aiChats()
            ->latest()
            ->limit(50)
            ->get()
            ->reverse();

        $insights = $this->aiService->getUserInsights($user);

        return Inertia::render('Ai/Chat', [
            'chatHistory' => $chatHistory,
            'insights' => $insights,
            'userStats' => $user->getReportsStats(),
            'vehicles' => $user->vehicles()->active()->get()
        ]);
    }

    /**
     * Process AI chat message
     */
    public function processMessage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:1000',
            'session_id' => 'nullable|string|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $result = $this->aiService->processMessage(
            $user,
            $request->input('message'),
            $request->input('session_id')
        );

        return response()->json($result);
    }

    /**
     * Get chat history for a session
     */
    public function getChatHistory(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|string|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $chatHistory = $user->aiChats()
            ->where('session_id', $request->input('session_id'))
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'chat_history' => $chatHistory
        ]);
    }

    /**
     * Provide feedback for AI response
     */
    public function provideFeedback(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'chat_id' => 'required|exists:ai_chats,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $chat = $user->aiChats()->findOrFail($request->input('chat_id'));
        
        $chat->addFeedback(
            $request->input('rating'),
            $request->input('comment')
        );

        return response()->json([
            'success' => true,
            'message' => 'Feedback u ruajt me sukses!'
        ]);
    }

    /**
     * Get AI insights for user
     */
    public function getInsights(): JsonResponse
    {
        $user = Auth::user();
        $insights = $this->aiService->getUserInsights($user);

        return response()->json([
            'success' => true,
            'insights' => $insights
        ]);
    }

    /**
     * Get AI recommendations for reports
     */
    public function getReportRecommendations(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'report_id' => 'required|exists:reports,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $report = $user->reports()->findOrFail($request->input('report_id'));
        
        $recommendations = $report->getAiRecommendations();

        return response()->json([
            'success' => true,
            'recommendations' => $recommendations
        ]);
    }

    /**
     * Get AI recommendations for vehicles
     */
    public function getVehicleRecommendations(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $vehicle = $user->vehicles()->findOrFail($request->input('vehicle_id'));
        
        $recommendations = $vehicle->getAiRecommendations();

        return response()->json([
            'success' => true,
            'recommendations' => $recommendations
        ]);
    }

    /**
     * Analyze report with AI
     */
    public function analyzeReport(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'report_id' => 'required|exists:reports,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $report = $user->reports()->findOrFail($request->input('report_id'));

        // AI analysis logic
        $analysis = [
            'severity_assessment' => $this->assessSeverity($report),
            'cost_estimation' => $this->estimateCost($report),
            'repair_time_estimation' => $this->estimateRepairTime($report),
            'parts_recommendations' => $this->recommendParts($report),
            'similar_cases' => $this->findSimilarCases($report),
            'preventive_measures' => $this->suggestPreventiveMeasures($report)
        ];

        // Update report with AI analysis
        $report->update(['ai_analysis' => $analysis]);

        return response()->json([
            'success' => true,
            'analysis' => $analysis
        ]);
    }

    /**
     * Get AI analytics dashboard
     */
    public function analytics(): Response
    {
        $user = Auth::user();
        
        if (!$user->canViewAnalytics()) {
            abort(403, 'Nuk keni leje për të parë analitikat.');
        }

        $analytics = [
            'user_insights' => $this->aiService->getUserInsights($user),
            'system_stats' => $this->getSystemStats(),
            'ai_performance' => $this->getAiPerformanceStats(),
            'trends' => $this->getTrends()
        ];

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
