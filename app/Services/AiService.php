<?php

namespace App\Services;

use App\Models\AiChat;
use App\Models\Report;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AiService
{
    /**
     * Process user message and generate AI response
     */
    public function processMessage(User $user, string $message, string $sessionId = null): array
    {
        $startTime = microtime(true);
        
        try {
            // Generate session ID if not provided
            $sessionId = $sessionId ?? Str::uuid()->toString();
            
            // Analyze intent
            $intent = $this->analyzeIntent($message);
            $confidence = $this->calculateConfidence($message, $intent);
            
            // Generate response based on intent
            $response = $this->generateResponse($user, $message, $intent, $confidence);
            
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
                'model_used' => 'carwise-ai-v1'
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
     * Calculate confidence score for intent
     */
    private function calculateConfidence(string $message, string $intent): float
    {
        $message = strtolower($message);
        $confidence = 0.5; // Base confidence
        
        // Boost confidence based on keyword matches
        $keywordMatches = 0;
        $totalKeywords = 0;
        
        $patterns = [
            'create_report' => ['raport', 'problem', 'defekt', 'krijo', 'shto'],
            'check_status' => ['status', 'gjendje', 'progres', 'kontrollo'],
            'get_help' => ['ndihmë', 'help', 'si', 'ku'],
            'vehicle_info' => ['makinë', 'automjet', 'informacion'],
            'cost_estimate' => ['kosto', 'çmim', 'parashikim'],
            'schedule_service' => ['servis', 'mirëmbajtje', 'programo'],
            'find_parts' => ['pjesë', 'spare', 'gjej'],
            'emergency' => ['urgjent', 'emergjencë', 'menjëherë']
        ];
        
        if (isset($patterns[$intent])) {
            $totalKeywords = count($patterns[$intent]);
            foreach ($patterns[$intent] as $keyword) {
                if (str_contains($message, $keyword)) {
                    $keywordMatches++;
                }
            }
        }
        
        if ($totalKeywords > 0) {
            $confidence += ($keywordMatches / $totalKeywords) * 0.4;
        }
        
        // Boost for longer, more specific messages
        if (strlen($message) > 20) {
            $confidence += 0.1;
        }
        
        return min(1.0, $confidence);
    }
    
    /**
     * Generate AI response based on intent
     */
    private function generateResponse(User $user, string $message, string $intent, float $confidence): array
    {
        $context = [];
        
        switch ($intent) {
            case 'create_report':
                return $this->handleCreateReport($user, $message, $context);
                
            case 'check_status':
                return $this->handleCheckStatus($user, $message, $context);
                
            case 'get_help':
                return $this->handleGetHelp($user, $message, $context);
                
            case 'vehicle_info':
                return $this->handleVehicleInfo($user, $message, $context);
                
            case 'cost_estimate':
                return $this->handleCostEstimate($user, $message, $context);
                
            case 'schedule_service':
                return $this->handleScheduleService($user, $message, $context);
                
            case 'find_parts':
                return $this->handleFindParts($user, $message, $context);
                
            case 'emergency':
                return $this->handleEmergency($user, $message, $context);
                
            default:
                return $this->handleGeneralInquiry($user, $message, $context);
        }
    }
    
    /**
     * Handle create report intent
     */
    private function handleCreateReport(User $user, string $message, array &$context): array
    {
        $userStats = $user->getReportsStats();
        
        $response = "Mirë! Po ju ndihmoj të krijoni një raport të ri për problemin e automjetit tuaj. ";
        
        if ($userStats['total'] > 0) {
            $response .= "Deri tani keni krijuar {$userStats['total']} raporte. ";
        }
        
        $response .= "Për të filluar, më tregoni:\n\n";
        $response .= "🚗 **Marka dhe modeli i automjetit**\n";
        $response .= "📅 **Viti i prodhimit**\n";
        $response .= "🔧 **Përshkrimi i problemit**\n";
        $response .= "⚡ **Sa urgjent është?**";
        
        $context['intent'] = 'create_report';
        $context['step'] = 'initial';
        
        return [
            'text' => $response,
            'context' => $context,
            'actions' => ['redirect_to_report_form'],
            'follow_up' => [
                'Cili është marka dhe modeli i automjetit tuaj?',
                'Në çfarë viti është prodhuar automjeti?',
                'Si mund ta përshkruani problemin?'
            ]
        ];
    }
    
    /**
     * Handle check status intent
     */
    private function handleCheckStatus(User $user, string $message, array &$context): array
    {
        $userStats = $user->getReportsStats();
        
        if ($userStats['total'] === 0) {
            return [
                'text' => "Ju nuk keni asnjë raport të krijuar ende. Dëshironi të krijoni një raport të ri?",
                'context' => $context,
                'actions' => ['redirect_to_report_form']
            ];
        }
        
        $response = "📊 **Statusi i raporteve tuaja:**\n\n";
        $response .= "📋 **Total:** {$userStats['total']} raporte\n";
        $response .= "⏳ **Në pritje:** {$userStats['pending']} raporte\n";
        $response .= "🔄 **Në progres:** {$userStats['in_progress']} raporte\n";
        $response .= "✅ **Përfunduar:** {$userStats['completed']} raporte\n";
        
        if ($userStats['urgent'] > 0) {
            $response .= "🚨 **Urgjente:** {$userStats['urgent']} raporte\n";
        }
        
        $response .= "\nDëshironi të shihni detajet e një raporti specifik?";
        
        return [
            'text' => $response,
            'context' => $context,
            'actions' => ['show_reports_list', 'show_dashboard'],
            'follow_up' => [
                'Cili raport dëshironi të shihni?',
                'Dëshironi të filtroni sipas statusit?',
                'A keni ndonjë raport urgjent?'
            ]
        ];
    }
    
    /**
     * Handle get help intent
     */
    private function handleGetHelp(User $user, string $message, array &$context): array
    {
        $response = "🤖 **Mirëseerdhët në CarWise AI!**\n\n";
        $response .= "Unë jam asistenti juaj virtual për të gjitha çështjet e automjeteve. Mund t'ju ndihmoj me:\n\n";
        $response .= "📝 **Krijimin e raporteve** - Raportoni probleme të automjeteve\n";
        $response .= "📊 **Kontrollimin e statusit** - Shikoni progresin e raporteve\n";
        $response .= "💰 **Vlerësimin e kostos** - Merrni parashikime për riparimet\n";
        $response .= "🔧 **Informacionin e automjeteve** - Detaje për makinat tuaja\n";
        $response .= "📅 **Programimin e servisit** - Organizoni mirëmbajtjen\n";
        $response .= "🛠️ **Gjetjen e pjesëve** - Gjeni pjesët e nevojshme\n\n";
        $response .= "Si mund t'ju ndihmoj sot?";
        
        return [
            'text' => $response,
            'context' => $context,
            'actions' => ['show_help_center', 'show_tutorial'],
            'follow_up' => [
                'Dëshironi të krijoni një raport të ri?',
                'A keni probleme me një raport ekzistues?',
                'Dëshironi të mësoni më shumë për funksionalitetet?'
            ]
        ];
    }
    
    /**
     * Handle vehicle info intent
     */
    private function handleVehicleInfo(User $user, string $message, array &$context): array
    {
        $vehicles = $user->vehicles()->active()->get();
        
        if ($vehicles->isEmpty()) {
            return [
                'text' => "Ju nuk keni regjistruar asnjë automjet ende. Dëshironi të shtoni një automjet të ri?",
                'context' => $context,
                'actions' => ['redirect_to_vehicle_form']
            ];
        }
        
        $response = "🚗 **Automjetet tuaja:**\n\n";
        
        foreach ($vehicles as $vehicle) {
            $response .= "**{$vehicle->full_name}**\n";
            $response .= "📍 Targat: {$vehicle->license_plate}\n";
            $response .= "📏 Kilometrazhi: {$vehicle->mileage_formatted}\n";
            $response .= "⛽ Karburanti: {$vehicle->fuel_type}\n";
            $response .= "🔧 Statusi i servisit: " . $this->getServiceStatusText($vehicle->service_status) . "\n\n";
        }
        
        return [
            'text' => $response,
            'context' => $context,
            'actions' => ['show_vehicles_list', 'add_new_vehicle'],
            'follow_up' => [
                'Dëshironi të shtoni një automjet të ri?',
                'A dëshironi të përditësoni informacionin e një automjeti?',
                'Dëshironi të shihni detajet e mirëmbajtjes?'
            ]
        ];
    }
    
    /**
     * Handle cost estimate intent
     */
    private function handleCostEstimate(User $user, string $message, array &$context): array
    {
        $response = "💰 **Vlerësimi i kostos së riparimit**\n\n";
        $response .= "Për të ju dhënë një vlerësim të saktë, më nevojiten disa informacione:\n\n";
        $response .= "🚗 **Marka dhe modeli i automjetit**\n";
        $response .= "🔧 **Lloji i problemit** (motor, frenat, transmisioni, etj.)\n";
        $response .= "📏 **Kilometrazhi aktual**\n";
        $response .= "⚡ **Urgjenca e riparimit**\n\n";
        $response .= "Bazuar në të dhënat tona AI, mund t'ju jap një vlerësim të përafërt të kostos.";
        
        return [
            'text' => $response,
            'context' => $context,
            'actions' => ['show_cost_calculator', 'get_ai_estimate'],
            'follow_up' => [
                'Cili është marka dhe modeli i automjetit?',
                'Çfarë problemi po hasni?',
                'A është një riparim urgjent?'
            ]
        ];
    }
    
    /**
     * Handle schedule service intent
     */
    private function handleScheduleService(User $user, string $message, array &$context): array
    {
        $vehicles = $user->vehicles()->needsService()->get();
        
        $response = "📅 **Programimi i servisit**\n\n";
        
        if ($vehicles->isNotEmpty()) {
            $response .= "🚨 **Automjetet që kanë nevojë për servis:**\n\n";
            
            foreach ($vehicles as $vehicle) {
                $response .= "**{$vehicle->full_name}**\n";
                $response .= "📏 Kilometrazhi: {$vehicle->mileage_formatted}\n";
                $response .= "📅 Servisi i fundit: " . ($vehicle->last_service_date ? $vehicle->last_service_date->format('d/m/Y') : 'Nuk është regjistruar') . "\n";
                $response .= "⏰ Servisi i radhës: " . ($vehicle->next_service_date ? $vehicle->next_service_date->format('d/m/Y') : 'Nuk është programuar') . "\n\n";
            }
        } else {
            $response .= "Të gjitha automjetet tuaja janë në rregull! 🎉\n\n";
        }
        
        $response .= "Dëshironi të programoni një termin për servis?";
        
        return [
            'text' => $response,
            'context' => $context,
            'actions' => ['schedule_service', 'show_calendar'],
            'follow_up' => [
                'Kur dëshironi të programoni servisin?',
                'Cili automjet dëshironi të servisoni?',
                'A keni preferenca për orarin?'
            ]
        ];
    }
    
    /**
     * Handle find parts intent
     */
    private function handleFindParts(User $user, string $message, array &$context): array
    {
        $response = "🛠️ **Gjetja e pjesëve të automjetit**\n\n";
        $response .= "Për të gjetur pjesët e duhura, më tregoni:\n\n";
        $response .= "🚗 **Marka dhe modeli i automjetit**\n";
        $response .= "🔧 **Pjesa që kërkoni** (frenat, filtri i ajrit, etj.)\n";
        $response .= "📏 **Viti i prodhimit**\n";
        $response .= "💰 **Buxheti juaj** (opsional)\n\n";
        $response .= "Unë do t'ju ndihmoj të gjeni pjesët më të mira dhe më të lira!";
        
        return [
            'text' => $response,
            'context' => $context,
            'actions' => ['search_parts', 'show_parts_catalog'],
            'follow_up' => [
                'Cila pjesë po kërkoni?',
                'Për çfarë automjeti është?',
                'A keni preferenca për markën?'
            ]
        ];
    }
    
    /**
     * Handle emergency intent
     */
    private function handleEmergency(User $user, string $message, array &$context): array
    {
        $response = "🚨 **EMERGJENCË - Ndihmë e menjëhershme**\n\n";
        $response .= "Nëse keni një problem urgjent me automjetin tuaj:\n\n";
        $response .= "📞 **Telefoni emergjencë:** +383 44 123 456\n";
        $response .= "🚗 **Shërbimi i tërheqjes:** +383 44 789 012\n";
        $response .= "🏥 **Spitali më i afërt:** Spitali i Prizrenit\n\n";
        $response .= "Për probleme jo urgjente, mund të krijojmë një raport të ri që do të trajtohet me prioritet të lartë.";
        
        return [
            'text' => $response,
            'context' => $context,
            'actions' => ['create_urgent_report', 'call_emergency'],
            'follow_up' => [
                'A është kjo një emergjencë e vërtetë?',
                'Dëshironi të krijojmë një raport urgjent?',
                'A keni nevojë për ndihmë të menjëhershme?'
            ]
        ];
    }
    
    /**
     * Handle general inquiry
     */
    private function handleGeneralInquiry(User $user, string $message, array &$context): array
    {
        $response = "🤖 **Mirëseerdhët në CarWise AI!**\n\n";
        $response .= "Si mund t'ju ndihmoj sot? Mund të më pyesni për:\n\n";
        $response .= "• Krijimin e raporteve të problemeve\n";
        $response .= "• Kontrollimin e statusit të raporteve\n";
        $response .= "• Informacionin e automjeteve\n";
        $response .= "• Vlerësimin e kostos së riparimit\n";
        $response .= "• Programimin e servisit\n";
        $response .= "• Gjetjen e pjesëve\n";
        $response .= "• Ose çdo gjë tjetër që lidhet me automjetet!";
        
        return [
            'text' => $response,
            'context' => $context,
            'actions' => ['show_help_center', 'show_tutorial'],
            'follow_up' => [
                'Dëshironi të krijoni një raport të ri?',
                'A keni probleme me automjetin tuaj?',
                'Dëshironi të mësoni më shumë për CarWise?'
            ]
        ];
    }
    
    /**
     * Get service status text
     */
    private function getServiceStatusText(string $status): string
    {
        return match($status) {
            'good' => '✅ Në rregull',
            'due_soon' => '⚠️ Duhet servis së shpejti',
            'overdue' => '🚨 Servis i vonuar',
            default => '❓ E panjohur'
        };
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
        $insights = $user->getAiInsights();
        
        return [
            'user_stats' => $insights,
            'recommendations' => $this->generateUserRecommendations($user, $insights),
            'predictions' => $this->generatePredictions($user, $insights)
        ];
    }
    
    /**
     * Generate user recommendations
     */
    private function generateUserRecommendations(User $user, array $insights): array
    {
        $recommendations = [];
        
        if ($insights['total_reports'] === 0) {
            $recommendations[] = [
                'type' => 'welcome',
                'title' => 'Mirëseerdhët në CarWise!',
                'message' => 'Krijoni raportin tuaj të parë për të filluar të përdorni platformën.',
                'priority' => 'high'
            ];
        }
        
        if ($insights['average_resolution_time'] > 48) {
            $recommendations[] = [
                'type' => 'performance',
                'title' => 'Kohëzgjatja e riparimit',
                'message' => 'Raportet tuaja po marrin më shumë kohë sesa mesatarja. Kontrolloni statusin.',
                'priority' => 'medium'
            ];
        }
        
        $vehicles = $user->vehicles()->needsService()->get();
        if ($vehicles->isNotEmpty()) {
            $recommendations[] = [
                'type' => 'maintenance',
                'title' => 'Servisi i nevojshëm',
                'message' => 'Disa automjete kanë nevojë për servis. Programoni një termin.',
                'priority' => 'high'
            ];
        }
        
        return $recommendations;
    }
    
    /**
     * Generate predictions
     */
    private function generatePredictions(User $user, array $insights): array
    {
        $predictions = [];
        
        if ($insights['total_reports'] > 0) {
            $predictions['next_maintenance'] = now()->addMonths(3);
            $predictions['estimated_yearly_cost'] = $insights['cost_analysis']['total_spent'] * 1.2;
            $predictions['reliability_trend'] = 'stable';
        }
        
        return $predictions;
    }
}
