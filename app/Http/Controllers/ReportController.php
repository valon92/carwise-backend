<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Report::latest()->get();

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'vehicle.brand' => 'required|string',
            'vehicle.model' => 'required|string',
            'vehicle.year' => 'required|integer',
            'vehicle.vin' => 'nullable|string',
        ]);
    
        $report = Report::create([
            'description' => $validated['description'],
            'vehicle' => $validated['vehicle'],
        ]);
    
        return response()->json($report, 201);
    }
    

    /**
     * Display the specified resource.
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
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}
