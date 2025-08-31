<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AiChat extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'session_id',
        'message',
        'response',
        'context',
        'intent',
        'confidence',
        'is_resolved',
        'feedback_rating',
        'feedback_comment',
        'processing_time',
        'tokens_used',
        'model_used'
    ];

    protected $casts = [
        'context' => 'array',
        'confidence' => 'decimal:3',
        'is_resolved' => 'boolean',
        'feedback_rating' => 'integer',
        'processing_time' => 'decimal:3',
        'tokens_used' => 'integer'
    ];

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['session_id', 'intent', 'is_resolved', 'feedback_rating'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    // Scopes
    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }

    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    public function scopeByIntent($query, $intent)
    {
        return $query->where('intent', $intent);
    }

    public function scopeHighConfidence($query)
    {
        return $query->where('confidence', '>=', 0.8);
    }

    // Accessors
    public function getResponseTimeAttribute()
    {
        return $this->processing_time . 's';
    }

    public function getConfidencePercentageAttribute()
    {
        return round($this->confidence * 100, 1) . '%';
    }

    public function getFeedbackEmojiAttribute()
    {
        return match($this->feedback_rating) {
            1 => '😞',
            2 => '😐',
            3 => '🙂',
            4 => '😊',
            5 => '😍',
            default => '🤔'
        };
    }

    // Methods
    public function markAsResolved()
    {
        $this->update(['is_resolved' => true]);
    }

    public function addFeedback($rating, $comment = null)
    {
        $this->update([
            'feedback_rating' => $rating,
            'feedback_comment' => $comment
        ]);
    }

    public function getContextData()
    {
        return $this->context ?? [];
    }

    public function setContextData($data)
    {
        $this->update(['context' => $data]);
    }

    public function isHighConfidence()
    {
        return $this->confidence >= 0.8;
    }

    public function getSuggestedActions()
    {
        return match($this->intent) {
            'create_report' => ['redirect_to_report_form'],
            'check_status' => ['show_reports_list', 'check_specific_report'],
            'get_help' => ['show_help_center', 'contact_support'],
            'vehicle_info' => ['show_vehicle_details', 'update_vehicle_info'],
            'cost_estimate' => ['show_cost_calculator', 'get_ai_estimate'],
            default => ['show_general_help']
        };
    }

    public function getRelatedReports()
    {
        if (!$this->user) {
            return collect();
        }

        return $this->user->reports()
            ->where('brand', 'like', '%' . $this->message . '%')
            ->orWhere('problem_category', 'like', '%' . $this->message . '%')
            ->orWhere('description', 'like', '%' . $this->message . '%')
            ->limit(5)
            ->get();
    }

    public function generateFollowUpQuestions()
    {
        return match($this->intent) {
            'create_report' => [
                'What is the brand and model of your vehicle?',
                'What year is your vehicle?',
                'Can you describe the problem you\'re experiencing?',
                'How urgent is this issue?'
            ],
            'check_status' => [
                'Do you want to see all your reports?',
                'Do you have a specific report ID?',
                'Would you like to filter by status?'
            ],
            'cost_estimate' => [
                'What type of repair do you need?',
                'What is your vehicle\'s brand and model?',
                'Do you have any specific parts that need replacement?'
            ],
            default => [
                'How can I help you today?',
                'Do you need help with a specific issue?',
                'Would you like to create a new report?'
            ]
        };
    }

    public function getAiInsights()
    {
        return [
            'intent_confidence' => $this->confidence,
            'processing_efficiency' => $this->processing_time < 2.0 ? 'excellent' : 'good',
            'user_satisfaction' => $this->feedback_rating >= 4 ? 'high' : 'needs_improvement',
            'suggested_improvements' => $this->getSuggestedImprovements()
        ];
    }

    private function getSuggestedImprovements()
    {
        $improvements = [];

        if ($this->confidence < 0.7) {
            $improvements[] = 'Improve intent recognition for this type of query';
        }

        if ($this->processing_time > 3.0) {
            $improvements[] = 'Optimize response generation for faster replies';
        }

        if ($this->feedback_rating < 3) {
            $improvements[] = 'Review response quality for this intent';
        }

        return $improvements;
    }
}
