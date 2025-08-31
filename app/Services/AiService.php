<?php

namespace App\Services;

use App\Models\AiChat;
use App\Models\Report;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AiService
{
    protected $openaiApiKey;
    protected $openaiEndpoint = 'https://api.openai.com/v1/chat/completions';

    public function __construct()
    {
        $this->openaiApiKey = config('services.openai.api_key');
    }

    /**
     * Process user message and generate AI response
     */
    public function processMessage(User $user, string $message, string $sessionId = null): array
    {
        $startTime = microtime(true);
        
        try {
            // Generate session ID if not provided
            $sessionId = $sessionId ?? Str::uuid()->toString();
            
            // Get user context
            $userContext = $this->getUserContext($user);
            
            // Analyze intent
            $intent = $this->analyzeIntent($message);
            $confidence = $this->calculateConfidence($message, $intent);
            
            // Generate response based on intent
            $response = $this->generateResponse($user, $message, $intent, $confidence, $userContext);
            
            // Calculate processing time
            $processingTime = microtime(true) - $startTime;
            
            // Save chat record
            $chat = AiChat::create([
                'user_id' => $user->id,
                'session_id' => $sessionId,
                'message' => $message,
                'response' => $response['text'],
                'context' => $response['context'] ?? [],
                'intent' => $intent,
                'confidence' => $confidence,
                'processing_time' => $processingTime,
                'tokens_used' => $this->estimateTokens($message . $response['text']),
                'model_used' => 'gpt-4'
            ]);
            
            return [
                'success' => true,
                'response' => $response['text'],
                'intent' => $intent,
                'confidence' => $confidence,
                'session_id' => $sessionId,
                'suggested_actions' => $response['actions'] ?? [],
                'follow_up_questions' => $response['follow_up'] ?? [],
                'chat_id' => $chat->id
            ];
            
        } catch (\Exception $e) {
            Log::error('AI Service Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'response' => 'Na vjen keq, po has probleme teknike. Ju lutem provoni përsëri më vonë.',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get user context for AI
     */
    private function getUserContext(User $user): array
    {
        $vehicles = $user->vehicles()->with('reports')->get();
        $recentReports = $user->reports()->latest()->take(5)->get();
        
        return [
            'user' => [
                'name' => $user->name,
                'total_vehicles' => $vehicles->count(),
                'total_reports' => $user->reports()->count(),
            ],
            'vehicles' => $vehicles->map(function ($vehicle) {
                return [
                    'id' => $vehicle->id,
                    'brand' => $vehicle->brand,
                    'model' => $vehicle->model,
                    'year' => $vehicle->year,
                    'mileage' => $vehicle->mileage,
                    'status' => $vehicle->status,
                    'reports_count' => $vehicle->reports->count(),
                ];
            })->toArray(),
            'recent_reports' => $recentReports->map(function ($report) {
                return [
                    'id' => $report->id,
                    'title' => $report->title,
                    'status' => $report->status,
                    'priority' => $report->priority,
                    'created_at' => $report->created_at->format('Y-m-d'),
                ];
            })->toArray(),
        ];
    }

    /**
     * Generate AI response using OpenAI
     */
    private function generateResponse(User $user, string $message, string $intent, float $confidence, array $context): array
    {
        if (!$this->openaiApiKey) {
            return $this->generateFallbackResponse($message, $intent);
        }

        try {
            $systemPrompt = $this->buildSystemPrompt($context);
            $userPrompt = $this->buildUserPrompt($message, $intent);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->openaiApiKey,
                'Content-Type' => 'application/json',
            ])->post($this->openaiEndpoint, [
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'max_tokens' => 1000,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $aiResponse = $data['choices'][0]['message']['content'] ?? '';
                
                return [
                    'text' => $aiResponse,
                    'context' => $context,
                    'actions' => $this->extractActions($aiResponse),
                    'follow_up' => $this->generateFollowUpQuestions($intent),
                ];
            }

            return $this->generateFallbackResponse($message, $intent);

        } catch (\Exception $e) {
            Log::error('OpenAI API Error: ' . $e->getMessage());
            return $this->generateFallbackResponse($message, $intent);
        }
    }

    /**
     * Build system prompt for AI
     */
    private function buildSystemPrompt(array $context): string
    {
        return "Ti je CarWise AI, një asistent inteligjent për menaxhimin e automjeteve. 

Kontesti i përdoruesit:
- Emri: {$context['user']['name']}
- Automjete: {$context['user']['total_vehicles']}
- Raporte: {$context['user']['total_reports']}

Automjetet e përdoruesit:
" . json_encode($context['vehicles'], JSON_UNESCAPED_UNICODE) . "

Raportet e fundit:
" . json_encode($context['recent_reports'], JSON_UNESCAPED_UNICODE) . "

Udhëzimet:
1. Përgjigju në shqip
2. Jip rekomandime të përshtatshme bazuar në automjetet e përdoruesit
3. Sugjero veprime specifike kur është e nevojshme
4. Përdor informacionin nga konteksti për përgjigje më të personalizuara
5. Jip këshilla për mirëmbajtjen e automjeteve
6. Ndihmo me diagnostikimin e problemeve";
    }

    /**
     * Build user prompt
     */
    private function buildUserPrompt(string $message, string $intent): string
    {
        return "Mesazhi i përdoruesit: $message

Intenti i detektuar: $intent

Ju lutem përgjigju në mënyrë të dobishme dhe të personalizuar.";
    }

    /**
     * Generate fallback response when AI is not available
     */
    private function generateFallbackResponse(string $message, string $intent): array
    {
        $responses = [
            'create_report' => 'Për të krijuar një raport të ri, ju lutem shkoni te faqja "Raporte" dhe klikoni "Krijo Raport". A keni nevojë për ndihmë me detajet e raportit?',
            'check_status' => 'Për të kontrolluar statusin e raporteve tuaj, mund të shkoni te faqja "Raporte" ku do të shihni të gjitha raportet tuaj dhe statusin e tyre.',
            'get_help' => 'Si mund t\'ju ndihmoj? Mund t\'ju ndihmoj me raportimin e problemeve, menaxhimin e automjeteve, ose këshilla për mirëmbajtje.',
            'vehicle_info' => 'Për të parë informacionin e automjeteve tuaj, shkoni te faqja "Automjete" ku do të gjeni të gjitha detajet.',
            'cost_estimate' => 'Për vlerësimin e kostove, rekomandoj të konsultoni me një mekanik profesional. Mund t\'ju ndihmoj të krijohet një raport i detajuar për vlerësim.',
            'schedule_service' => 'Për të programuar servis, mund të përdorni faqen "Automjete" dhe të përditësoni datën e servisit të ardhshëm.',
            'find_parts' => 'Për gjetjen e pjesëve, rekomandoj të kontaktoni një dyqan të specializuar ose të përdorni platforma online të besueshme.',
            'emergency' => 'Për probleme urgjente, rekomandoj të kontaktoni menjëherë një mekanik profesional ose shërbimin e ndihmës rrugore.',
            'general_inquiry' => 'Si mund t\'ju ndihmoj me automjetet tuaj? Mund t\'ju ofroj këshilla për mirëmbajtje, ndihmë me raporte, ose informacion për automjetet tuaj.'
        ];

        return [
            'text' => $responses[$intent] ?? $responses['general_inquiry'],
            'context' => [],
            'actions' => [],
            'follow_up' => [],
        ];
    }

    /**
     * Extract suggested actions from AI response
     */
    private function extractActions(string $response): array
    {
        $actions = [];
        
        if (str_contains(strtolower($response), 'krijo raport')) {
            $actions[] = ['text' => 'Krijo Raport', 'route' => 'reports.create'];
        }
        
        if (str_contains(strtolower($response), 'shiko automjete')) {
            $actions[] = ['text' => 'Shiko Automjete', 'route' => 'vehicles.index'];
        }
        
        if (str_contains(strtolower($response), 'shiko raporte')) {
            $actions[] = ['text' => 'Shiko Raporte', 'route' => 'reports.index'];
        }
        
        return $actions;
    }

    /**
     * Generate follow-up questions based on intent
     */
    private function generateFollowUpQuestions(string $intent): array
    {
        $questions = [
            'create_report' => [
                'Cili automjet ka problemin?',
                'Çfarë lloj problemi po hasni?',
                'Kur filloi problemi?'
            ],
            'check_status' => [
                'Cili raport dëshironi të kontrolloni?',
                'A keni raporte urgjente?',
                'A dëshironi të shihni raportet e fundit?'
            ],
            'vehicle_info' => [
                'Cili automjet dëshironi të shihni?',
                'A dëshironi të shtoni automjet të ri?',
                'A keni nevojë për informacion për mirëmbajtje?'
            ]
        ];

        return $questions[$intent] ?? [];
    }

    /**
     * Analyze user intent from message
     */
    private function analyzeIntent(string $message): string
    {
        $message = strtolower($message);
        
        // Intent patterns
        $patterns = [
            'create_report' => ['raport', 'problem', 'defekt', 'krijo', 'shto', 'regjistro'],
            'check_status' => ['status', 'gjendje', 'progres', 'kontrollo', 'shiko'],
            'get_help' => ['ndihmë', 'help', 'si', 'ku', 'çfarë'],
            'vehicle_info' => ['makinë', 'automjet', 'informacion', 'detaje'],
            'cost_estimate' => ['kosto', 'çmim', 'parashikim', 'vlerësim'],
            'schedule_service' => ['servis', 'mirëmbajtje', 'programo', 'termin'],
            'find_parts' => ['pjesë', 'spare', 'gjej', 'ku mund'],
            'emergency' => ['urgjent', 'emergjencë', 'menjëherë', 'tani']
        ];
        
        foreach ($patterns as $intent => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($message, $keyword)) {
                    return $intent;
                }
            }
        }
        
        return 'general_inquiry';
    }

    /**
     * Calculate confidence score
     */
    private function calculateConfidence(string $message, string $intent): float
    {
        $message = strtolower($message);
        $patterns = [
            'create_report' => ['raport', 'problem', 'defekt', 'krijo', 'shto', 'regjistro'],
            'check_status' => ['status', 'gjendje', 'progres', 'kontrollo', 'shiko'],
            'get_help' => ['ndihmë', 'help', 'si', 'ku', 'çfarë'],
            'vehicle_info' => ['makinë', 'automjet', 'informacion', 'detaje'],
            'cost_estimate' => ['kosto', 'çmim', 'parashikim', 'vlerësim'],
            'schedule_service' => ['servis', 'mirëmbajtje', 'programo', 'termin'],
            'find_parts' => ['pjesë', 'spare', 'gjej', 'ku mund'],
            'emergency' => ['urgjent', 'emergjencë', 'menjëherë', 'tani']
        ];

        if (!isset($patterns[$intent])) {
            return 0.3;
        }

        $keywords = $patterns[$intent];
        $matches = 0;
        
        foreach ($keywords as $keyword) {
            if (str_contains($message, $keyword)) {
                $matches++;
            }
        }

        return min(1.0, $matches / count($keywords) + 0.2);
    }

    /**
     * Estimate tokens used
     */
    private function estimateTokens(string $text): int
    {
        // Rough estimation: 1 token ≈ 4 characters
        return ceil(strlen($text) / 4);
    }

    /**
     * Get AI insights for user
     */
    public function getUserInsights(User $user): array
    {
        $vehicles = $user->vehicles()->with('reports')->get();
        $reports = $user->reports()->latest()->take(10)->get();

        $insights = [];

        // Vehicle insights
        if ($vehicles->count() > 0) {
            $totalMileage = $vehicles->sum('mileage');
            $avgMileage = $totalMileage / $vehicles->count();
            
            $insights[] = [
                'type' => 'vehicle',
                'title' => 'Kilometrazhi Mesatar',
                'description' => "Automjetet tuaj kanë një kilometrazh mesatar prej " . number_format($avgMileage) . " km",
                'priority' => 'medium'
            ];

            $vehiclesNeedingService = $vehicles->filter(function ($vehicle) {
                return $vehicle->next_service_date && $vehicle->next_service_date <= now()->addDays(30);
            });

            if ($vehiclesNeedingService->count() > 0) {
                $insights[] = [
                    'type' => 'service',
                    'title' => 'Servis i Planifikuar',
                    'description' => "{$vehiclesNeedingService->count()} automjet(e) kanë nevojë për servis së shpejti",
                    'priority' => 'high'
                ];
            }
        }

        // Report insights
        if ($reports->count() > 0) {
            $urgentReports = $reports->where('priority', 'high')->count();
            if ($urgentReports > 0) {
                $insights[] = [
                    'type' => 'report',
                    'title' => 'Raporte Urgjente',
                    'description' => "Keni {$urgentReports} raport(e) urgjente që kërkojnë vëmendje",
                    'priority' => 'high'
                ];
            }

            $completedReports = $reports->where('status', 'completed')->count();
            $completionRate = ($completedReports / $reports->count()) * 100;
            
            $insights[] = [
                'type' => 'performance',
                'title' => 'Shkalla e Përfundimit',
                'description' => "Shkalla juaj e përfundimit të raporteve është " . round($completionRate, 1) . "%",
                'priority' => 'medium'
            ];
        }

        return $insights;
    }

    /**
     * Get system analytics
     */
    public function getSystemAnalytics(): array
    {
        $totalUsers = User::count();
        $totalVehicles = Vehicle::count();
        $totalReports = Report::count();
        $totalAiChats = AiChat::count();

        $reportsByStatus = Report::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $reportsByPriority = Report::selectRaw('priority, count(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();

        $aiConfidence = AiChat::whereNotNull('confidence')
            ->avg('confidence') ?? 0;

        return [
            'overview' => [
                'total_users' => $totalUsers,
                'total_vehicles' => $totalVehicles,
                'total_reports' => $totalReports,
                'total_ai_interactions' => $totalAiChats,
            ],
            'reports' => [
                'by_status' => $reportsByStatus,
                'by_priority' => $reportsByPriority,
            ],
            'ai_performance' => [
                'avg_confidence' => round($aiConfidence, 2),
                'total_interactions' => $totalAiChats,
            ],
            'trends' => [
                'reports_this_month' => Report::whereMonth('created_at', now()->month)->count(),
                'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
                'ai_interactions_this_month' => AiChat::whereMonth('created_at', now()->month)->count(),
            ]
        ];
    }
}
