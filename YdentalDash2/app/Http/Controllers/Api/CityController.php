<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start with a base query
        $query = City::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Sorting
        if ($request->has('sort_by')) {
            $sortField = $request->input('sort_by');
            $sortDirection = $request->input('sort_dir', 'asc'); // Default to ascending
            $query->orderBy($sortField, $sortDirection);
        }

        // Paginate the results
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $cities = $query->paginate($perPage);

        // Return the paginated cities as a JSON response
        return response()->json($cities);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:cities,name',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new city record
        $city = City::create([
            'name' => $request->name,
        ]);

        // Return the created city as a JSON response
        return response()->json($city, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the city by ID
        $city = City::find($id);

        // If the city doesn't exist, return a 404 error
        if (!$city) {
            return response()->json([
                'message' => 'City not found',
            ], 404);
        }

        // Return the city as a JSON response
        return response()->json($city);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the city by ID
        $city = City::find($id);

        // If the city doesn't exist, return a 404 error
        if (!$city) {
            return response()->json([
                'message' => 'City not found',
            ], 404);
        }

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255|unique:cities,name,' . $city->id,
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update the city record
        $city->update([
            'name' => $request->name ?? $city->name,
        ]);

        // Return the updated city as a JSON response
        return response()->json($city);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the city by ID
        $city = City::find($id);

        // If the city doesn't exist, return a 404 error
        if (!$city) {
            return response()->json([
                'message' => 'City not found',
            ], 404);
        }

        // Delete the city record
        $city->delete();

        // Return a success response
        return response()->json([
            'message' => 'City deleted successfully',
        ], 204);
    }

    /**
     * Get a list of cities with only id and name.
     */
    public function select(Request $request)
    {
        // Start with a base query
        $query = City::query();

        // Apply filters if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Select only id and name
        $cities = $query->select('id', 'name')->get();

        // Return the cities as a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Cities retrieved successfully',
            'data' => $cities,
        ]);
    }
}
