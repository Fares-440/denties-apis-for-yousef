<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start with a base query
        $query = Schedule::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('available_date', 'like', '%' . $search . '%')
                    ->orWhere('available_time', 'like', '%' . $search . '%');
            });
        }

        // Filter by available date
        if ($request->has('available_date')) {
            $query->where('available_date', $request->input('available_date'));
        }

        // Filter by available time
        if ($request->has('available_time')) {
            $query->where('available_time', $request->input('available_time'));
        }

        // Sorting
        if ($request->has('sort_by')) {
            $sortField = $request->input('sort_by');
            $sortDirection = $request->input('sort_dir', 'asc'); // Default to ascending
            $query->orderBy($sortField, $sortDirection);
        }

        // Paginate the results
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $schedules = $query->paginate($perPage);

        // Return the paginated schedules as a JSON response
        return response()->json($schedules);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'available_date' => 'required|date',
            'available_time' => 'required|date_format:H:i:s',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new schedule record
        $schedule = Schedule::create([
            'available_date' => $request->available_date,
            'available_time' => $request->available_time,
        ]);

        // Return the created schedule as a JSON response
        return response()->json($schedule, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the schedule by ID
        $schedule = Schedule::find($id);

        // If the schedule doesn't exist, return a 404 error
        if (!$schedule) {
            return response()->json([
                'message' => 'Schedule not found',
            ], 404);
        }

        // Return the schedule as a JSON response
        return response()->json($schedule);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the schedule by ID
        $schedule = Schedule::find($id);

        // If the schedule doesn't exist, return a 404 error
        if (!$schedule) {
            return response()->json([
                'message' => 'Schedule not found',
            ], 404);
        }

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'available_date' => 'sometimes|date',
            'available_time' => 'sometimes|date_format:H:i:s',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update the schedule record
        $schedule->update([
            'available_date' => $request->available_date ?? $schedule->available_date,
            'available_time' => $request->available_time ?? $schedule->available_time,
        ]);

        // Return the updated schedule as a JSON response
        return response()->json($schedule);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the schedule by ID
        $schedule = Schedule::find($id);

        // If the schedule doesn't exist, return a 404 error
        if (!$schedule) {
            return response()->json([
                'message' => 'Schedule not found',
            ], 404);
        }

        // Delete the schedule record
        $schedule->delete();

        // Return a success response
        return response()->json([
            'message' => 'Schedule deleted successfully',
        ], 204);
    }

    /**
     * Get a list of schedules with only id, available_date, and available_time.
     */
    public function select(Request $request)
    {
        // Start with a base query
        $query = Schedule::query();

        // Apply filters if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('available_date', 'like', '%' . $search . '%')
                    ->orWhere('available_time', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('available_date')) {
            $query->where('available_date', $request->input('available_date'));
        }

        if ($request->has('available_time')) {
            $query->where('available_time', $request->input('available_time'));
        }

        // Select only id, available_date, and available_time
        $schedules = $query->select('id', 'available_date', 'available_time')->get();

        // Return the schedules as a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Schedules retrieved successfully',
            'data' => $schedules,
        ]);
    }
}
