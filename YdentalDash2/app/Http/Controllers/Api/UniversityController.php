<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UniversityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start with a base query
        $query = University::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Filter by city ID
        if ($request->has('city_id')) {
            $query->where('city_id', $request->input('city_id'));
        }

        // Sorting
        if ($request->has('sort_by')) {
            $sortField = $request->input('sort_by');
            $sortDirection = $request->input('sort_dir', 'asc'); // Default to ascending
            $query->orderBy($sortField, $sortDirection);
        }

        // Paginate the results
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $universities = $query->paginate($perPage);

        // Return the paginated universities as a JSON response
        return response()->json($universities);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255|unique:universities,name',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new university record
        $university = University::create([
            'city_id' => $request->city_id,
            'name' => $request->name,
        ]);

        // Return the created university as a JSON response
        return response()->json($university, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the university by ID
        $university = University::find($id);

        // If the university doesn't exist, return a 404 error
        if (!$university) {
            return response()->json([
                'message' => 'University not found',
            ], 404);
        }

        // Return the university as a JSON response
        return response()->json($university);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the university by ID
        $university = University::find($id);

        // If the university doesn't exist, return a 404 error
        if (!$university) {
            return response()->json([
                'message' => 'University not found',
            ], 404);
        }

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'city_id' => 'sometimes|exists:cities,id',
            'name' => 'sometimes|string|max:255|unique:universities,name,' . $university->id,
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update the university record
        $university->update([
            'city_id' => $request->city_id ?? $university->city_id,
            'name' => $request->name ?? $university->name,
        ]);

        // Return the updated university as a JSON response
        return response()->json($university);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the university by ID
        $university = University::find($id);

        // If the university doesn't exist, return a 404 error
        if (!$university) {
            return response()->json([
                'message' => 'University not found',
            ], 404);
        }

        // Delete the university record
        $university->delete();

        // Return a success response
        return response()->json([
            'message' => 'University deleted successfully',
        ], 204);
    }

    /**
     * Get a list of universities with only id, name, and city_id.
     */
    public function select(Request $request)
    {
        // Start with a base query
        $query = University::query();

        // Apply filters if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($request->has('city_id')) {
            $query->where('city_id', $request->input('city_id'));
        }

        // Select only id, name, and city_id
        $universities = $query->select('id', 'name', 'city_id')->get();

        // Return the universities as a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Universities retrieved successfully',
            'data' => $universities,
        ]);
    }
}
