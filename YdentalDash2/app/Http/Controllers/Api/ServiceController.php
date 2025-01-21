<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start with a base query
        $query = Service::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('service_name', 'like', '%' . $search . '%')
                    ->orWhere('icon', 'like', '%' . $search . '%');
            });
        }

        // Paginate the results
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $services = $query->paginate($perPage);

        // Return the paginated services as a JSON response
        return response()->json($services);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new service record
        $service = Service::create([
            'service_name' => $request->service_name,
            'icon' => $request->icon,
        ]);

        // Return the created service as a JSON response
        return response()->json($service, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the service by ID
        $service = Service::find($id);

        // If the service doesn't exist, return a 404 error
        if (!$service) {
            return response()->json([
                'message' => 'Service not found',
            ], 404);
        }

        // Return the service as a JSON response
        return response()->json($service);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the service by ID
        $service = Service::find($id);

        // If the service doesn't exist, return a 404 error
        if (!$service) {
            return response()->json([
                'message' => 'Service not found',
            ], 404);
        }

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'service_name' => 'sometimes|string|max:255',
            'icon' => 'sometimes|string|max:255',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update the service record
        $service->update([
            'service_name' => $request->service_name ?? $service->service_name,
            'icon' => $request->icon ?? $service->icon,
        ]);

        // Return the updated service as a JSON response
        return response()->json($service);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the service by ID
        $service = Service::find($id);

        // If the service doesn't exist, return a 404 error
        if (!$service) {
            return response()->json([
                'message' => 'Service not found',
            ], 404);
        }

        // Delete the service record
        $service->delete();

        // Return a success response
        return response()->json([
            'message' => 'Service deleted successfully',
        ], 204);
    }

    /**
     * Get a list of services with only id and service_name.
     */
    public function select(Request $request)
    {
        // Start with a base query
        $query = Service::query();

        // Apply filters if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('service_name', 'like', '%' . $search . '%');
        }

        // Select only id and service_name
        $services = $query->select('id', 'service_name')->get();

        // Return the services as a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Services retrieved successfully',
            'data' => $services,
        ]);
    }
}
