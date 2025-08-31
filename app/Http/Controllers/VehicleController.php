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

    public function index(): Response
    {
        $user = Auth::user();
        $vehicles = $user->vehicles()->with('reports')->get();
        
        $stats = [
            'total' => $vehicles->count(),
            'active' => $vehicles->where('status', 'active')->count(),
            'primary' => $vehicles->where('is_primary', true)->count(),
        ];

        return Inertia::render('Vehicles/Index', [
            'vehicles' => $vehicles,
            'stats' => $stats
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Vehicles/Create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate',
            'vin' => 'nullable|string|max:17|unique:vehicles,vin',
            'color' => 'required|string|max:50',
            'fuel_type' => 'required|in:gasoline,diesel,electric,hybrid,lpg',
            'transmission' => 'required|in:manual,automatic',
            'engine_size' => 'nullable|string|max:20',
            'mileage' => 'required|integer|min:0',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'insurance_expiry' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'next_service_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = $user->id;
        
        // Set as primary if it's the first vehicle
        if ($user->vehicles()->count() === 0) {
            $validated['is_primary'] = true;
        }

        $vehicle = Vehicle::create($validated);

        return redirect()->route('vehicles.show', $vehicle)
            ->with('success', 'Automjeti u shtua me sukses!');
    }

    public function show(Vehicle $vehicle): Response
    {
        $user = Auth::user();
        
        // Check if user owns this vehicle
        if ($vehicle->user_id !== $user->id) {
            abort(403, 'Nuk keni leje për të parë këtë automjet.');
        }

        $vehicle->load(['reports']);

        return Inertia::render('Vehicles/Show', [
            'vehicle' => $vehicle
        ]);
    }

    public function edit(Vehicle $vehicle): Response
    {
        $user = Auth::user();
        
        // Check if user owns this vehicle
        if ($vehicle->user_id !== $user->id) {
            abort(403, 'Nuk keni leje për të edituar këtë automjet.');
        }

        return Inertia::render('Vehicles/Edit', [
            'vehicle' => $vehicle
        ]);
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $user = Auth::user();
        
        // Check if user owns this vehicle
        if ($vehicle->user_id !== $user->id) {
            abort(403, 'Nuk keni leje për të përditësuar këtë automjet.');
        }

        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate,' . $vehicle->id,
            'vin' => 'nullable|string|max:17|unique:vehicles,vin,' . $vehicle->id,
            'color' => 'required|string|max:50',
            'fuel_type' => 'required|in:gasoline,diesel,electric,hybrid,lpg',
            'transmission' => 'required|in:manual,automatic',
            'engine_size' => 'nullable|string|max:20',
            'mileage' => 'required|integer|min:0',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'insurance_expiry' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'next_service_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $vehicle->update($validated);

        return redirect()->route('vehicles.show', $vehicle)
            ->with('success', 'Automjeti u përditësua me sukses!');
    }

    public function destroy(Vehicle $vehicle)
    {
        $user = Auth::user();
        
        // Check if user owns this vehicle
        if ($vehicle->user_id !== $user->id) {
            abort(403, 'Nuk keni leje për të fshirë këtë automjet.');
        }

        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Automjeti u fshi me sukses!');
    }

    public function markPrimary(Vehicle $vehicle)
    {
        $user = Auth::user();
        
        // Check if user owns this vehicle
        if ($vehicle->user_id !== $user->id) {
            abort(403, 'Nuk keni leje për të ndryshuar këtë automjet.');
        }

        // Remove primary from all other vehicles
        $user->vehicles()->update(['is_primary' => false]);
        
        // Set this vehicle as primary
        $vehicle->update(['is_primary' => true]);

        return response()->json(['success' => true]);
    }

    public function addServiceRecord(Request $request, Vehicle $vehicle)
    {
        $user = Auth::user();
        
        // Check if user owns this vehicle
        if ($vehicle->user_id !== $user->id) {
            abort(403, 'Nuk keni leje për të shtuar regjistër servisi.');
        }

        $validated = $request->validate([
            'service_date' => 'required|date',
            'service_type' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'cost' => 'required|numeric|min:0',
            'next_service_date' => 'nullable|date',
            'mileage' => 'required|integer|min:0',
        ]);

        // Update vehicle mileage and next service date
        $vehicle->update([
            'mileage' => $validated['mileage'],
            'next_service_date' => $validated['next_service_date']
        ]);

        // Create service record (you might want to create a separate table for this)
        // For now, we'll just update the vehicle

        return response()->json(['success' => true]);
    }
}
