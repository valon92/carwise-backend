<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Shfaq të gjitha raportet.
     */
    public function index()
    {
        return Report::latest()->get();
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
