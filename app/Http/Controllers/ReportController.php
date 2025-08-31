<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ReportController extends Controller
{
    /**
     * Shfaq të gjitha raportet.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Report::with(['user', 'vehicle'])
            ->where('user_id', $user->id);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        if ($request->filled('severity')) {
            $query->where('severity_level', $request->severity);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('symptoms', 'like', "%{$search}%")
                  ->orWhere('diagnosis', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
                      $vehicleQuery->where('brand', 'like', "%{$search}%")
                                  ->orWhere('model', 'like', "%{$search}%")
                                  ->orWhere('license_plate', 'like', "%{$search}%");
                  });
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['created_at', 'updated_at', 'title', 'status', 'priority', 'severity_level', 'estimated_cost'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $reports = $query->paginate(10)->withQueryString();

        $stats = [
            'total' => $user->reports()->count(),
            'pending' => $user->reports()->where('status', 'pending')->count(),
            'in_progress' => $user->reports()->where('status', 'in_progress')->count(),
            'completed' => $user->reports()->where('status', 'completed')->count(),
            'urgent' => $user->reports()->where('priority', 'high')->count(),
        ];

        $vehicles = $user->vehicles()->get();

        // Get filter options for dropdowns
        $filterOptions = [
            'statuses' => [
                'pending' => 'Në pritje',
                'in_progress' => 'Në progres',
                'completed' => 'Përfunduar',
                'cancelled' => 'Anuluar',
            ],
            'priorities' => [
                'low' => 'E ulët',
                'medium' => 'Mesatare',
                'high' => 'E lartë',
                'critical' => 'Kritike',
            ],
            'severities' => [
                'minor' => 'E vogël',
                'moderate' => 'Mesatare',
                'major' => 'E madhe',
                'critical' => 'Kritike',
            ],
        ];

        return Inertia::render('Reports/Index', [
            'reports' => $reports,
            'stats' => $stats,
            'vehicles' => $vehicles,
            'filterOptions' => $filterOptions,
            'filters' => $request->only(['status', 'priority', 'vehicle_id', 'severity', 'search', 'date_from', 'date_to', 'sort_by', 'sort_order'])
        ]);
    }

    /**
     * Ruaj një raport të ri.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'vehicle' => 'required|array',
            'vehicle.brand' => 'required|string',
            'vehicle.model' => 'required|string',
            'vehicle.year' => 'required|integer',
            'vehicle.vin' => 'nullable|string',
        ]);

        $report = new Report();
        $report->description = $validated['description'];
        $report->vehicle_info = json_encode($validated['vehicle']);
        $report->user_id = auth()->id(); // e lidh me userin që është kyçur
        $report->save();

        return response()->json($report, 201);
    }

    /**
     * Shfaq një raport sipas ID-së.
     */
    public function show($id)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json(['message' => 'Raporti nuk u gjet'], 404);
        }

        return response()->json($report);
    }

    /**
     * Përditëso një raport (opsionale në këtë fazë).
     */
    public function update(Request $request, Report $report)
    {
        // opsionale për të ardhmen
    }

    /**
     * Fshij një raport (opsionale).
     */
    public function destroy(Report $report)
    {
        // opsionale për të ardhmen
    }
    public function myReports()
{
    $user = auth()->user();
    $reports = Report::where('user_id', $user->id)->latest()->get();

    return response()->json($reports);
}

}
