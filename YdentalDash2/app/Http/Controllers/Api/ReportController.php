<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start with a base query
        $query = Report::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('report', 'like', '%' . $search . '%');
        }

        // Sorting
        if ($request->has('sort_by')) {
            $sortField = $request->input('sort_by');
            $sortDirection = $request->input('sort_dir', 'asc'); // Default to ascending
            $query->orderBy($sortField, $sortDirection);
        }

        // Paginate the results
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $reports = $query->paginate($perPage);

        // Return the paginated reports as a JSON response
        return response()->json($reports);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'report' => 'required|string',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new report record
        $report = Report::create([
            'report' => $request->report,
        ]);

        // Return the created report as a JSON response
        return response()->json($report, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the report by ID
        $report = Report::find($id);

        // If the report doesn't exist, return a 404 error
        if (!$report) {
            return response()->json([
                'message' => 'Report not found',
            ], 404);
        }

        // Return the report as a JSON response
        return response()->json($report);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the report by ID
        $report = Report::find($id);

        // If the report doesn't exist, return a 404 error
        if (!$report) {
            return response()->json([
                'message' => 'Report not found',
            ], 404);
        }

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'report' => 'sometimes|string',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update the report record
        $report->update([
            'report' => $request->report ?? $report->report,
        ]);

        // Return the updated report as a JSON response
        return response()->json($report);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the report by ID
        $report = Report::find($id);

        // If the report doesn't exist, return a 404 error
        if (!$report) {
            return response()->json([
                'message' => 'Report not found',
            ], 404);
        }

        // Delete the report record
        $report->delete();

        // Return a success response
        return response()->json([
            'message' => 'Report deleted successfully',
        ], 204);
    }
}
