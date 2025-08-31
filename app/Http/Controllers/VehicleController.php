<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class VehicleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of vehicles
     */
    public function index(): Response
    {
        $vehicles = Auth::user()->vehicles()->with('reports')->get();

        return Inertia::render('Vehicles/Index', [
            'vehicles' => $vehicles,
            'stats' => [
                'total' => $vehicles->count(),
                'active' => $vehicles->where('is_active', true)->count(),
                'primary' => $vehicles->where('is_primary', true)->count(),
            ]
        ]);
    }

    /**
     * Show the form for creating a new vehicle
     */
    public function create(): Response
    {
        return Inertia::render('Vehicles/Create');
    }

    /**
     * Store a newly created vehicle
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'vin' => 'nullable|string|max:17|unique:vehicles,vin',
            'license_plate' => 'nullable|string|max:20',
            'mileage' => 'nullable|integer|min:0',
            'fuel_type' => 'nullable|string|max:50',
            'transmission' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'engine_size' => 'nullable|string|max:50',
            'horsepower' => 'nullable|integer|min:0',
            'torque' => 'nullable|integer|min:0',
            'fuel_efficiency' => 'nullable|numeric|min:0',
            'body_type' => 'nullable|string|max:50',
            'doors' => 'nullable|integer|min:1|max:10',
            'seats' => 'nullable|integer|min:1|max:20',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'warranty_expiry' => 'nullable|date',
            'insurance_expiry' => 'nullable|date',
            'last_service_date' => 'nullable|date',
            'next_service_date' => 'nullable|date',
            'service_history' => 'nullable|array',
            'modifications' => 'nullable|array',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
            'is_primary' => 'boolean',
        ]);

        $validated['user_id'] = Auth::id();

        // If this is the first vehicle, make it primary
        if (Auth::user()->vehicles()->count() === 0) {
            $validated['is_primary'] = true;
        }

        $vehicle = Vehicle::create($validated);

        return redirect()->route('vehicles.show', $vehicle)
            ->with('success', 'Automjeti u shtua me sukses!');
    }

    /**
     * Display the specified vehicle
     */
    public function show(Vehicle $vehicle): Response
    {
        $this->authorize('view', $vehicle);

        $vehicle->load(['reports']);

        return Inertia::render('Vehicles/Show', [
            'vehicle' => $vehicle,
            'reports' => $vehicle->reports()->latest()->paginate(10),
            'serviceHistory' => $vehicle->getServiceHistory(),
            'modifications' => $vehicle->getModifications(),
        ]);
    }

    /**
     * Show the form for editing the specified vehicle
     */
    public function edit(Vehicle $vehicle): Response
    {
        $this->authorize('update', $vehicle);

        return Inertia::render('Vehicles/Edit', [
            'vehicle' => $vehicle
        ]);
    }

    /**
     * Update the specified vehicle
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        $validated = $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'vin' => 'nullable|string|max:17|unique:vehicles,vin,' . $vehicle->id,
            'license_plate' => 'nullable|string|max:20',
            'mileage' => 'nullable|integer|min:0',
            'fuel_type' => 'nullable|string|max:50',
            'transmission' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'engine_size' => 'nullable|string|max:50',
            'horsepower' => 'nullable|integer|min:0',
            'torque' => 'nullable|integer|min:0',
            'fuel_efficiency' => 'nullable|numeric|min:0',
            'body_type' => 'nullable|string|max:50',
            'doors' => 'nullable|integer|min:1|max:10',
            'seats' => 'nullable|integer|min:1|max:20',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'warranty_expiry' => 'nullable|date',
            'insurance_expiry' => 'nullable|date',
            'last_service_date' => 'nullable|date',
            'next_service_date' => 'nullable|date',
            'service_history' => 'nullable|array',
            'modifications' => 'nullable|array',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
            'is_primary' => 'boolean',
        ]);

        $vehicle->update($validated);

        return redirect()->route('vehicles.show', $vehicle)
            ->with('success', 'Automjeti u përditësua me sukses!');
    }

    /**
     * Remove the specified vehicle
     */
    public function destroy(Vehicle $vehicle)
    {
        $this->authorize('delete', $vehicle);

        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Automjeti u fshi me sukses!');
    }

    /**
     * Mark vehicle as primary
     */
    public function markPrimary(Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        // Remove primary from other vehicles
        Auth::user()->vehicles()->update(['is_primary' => false]);

        // Set this vehicle as primary
        $vehicle->update(['is_primary' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Automjeti u bë kryesor!'
        ]);
    }

    /**
     * Add service record
     */
    public function addServiceRecord(Request $request, Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        $validated = $request->validate([
            'service_type' => 'required|string|max:100',
            'description' => 'required|string',
            'cost' => 'nullable|numeric|min:0',
            'service_date' => 'required|date',
            'next_service_date' => 'nullable|date|after:service_date',
            'mileage' => 'nullable|integer|min:0',
            'service_provider' => 'nullable|string|max:100',
        ]);

        $vehicle->addServiceRecord($validated);

        return response()->json([
            'success' => true,
            'message' => 'Regjistri i servisit u shtua me sukses!'
        ]);
    }
}
