<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\TheCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TheCaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start with a base query and eager load relationships
        $query = TheCase::with(['service', 'schedules', 'student:id,name,student_image']);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('procedure', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter by service ID
        if ($request->has('service_id')) {
            $query->where('service_id', $request->input('service_id'));
        }

        // Filter by schedule ID
        if ($request->has('schedules_id')) {
            $query->where('schedules_id', $request->input('schedules_id'));
        }

        // Filter by gender
        if ($request->has('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        // Filter by cost range
        if ($request->has('min_cost')) {
            $query->where('cost', '>=', $request->input('min_cost'));
        }
        if ($request->has('max_cost')) {
            $query->where('cost', '<=', $request->input('max_cost'));
        }

        // Filter by age range
        if ($request->has('min_age')) {
            $query->where('min_age', '>=', $request->input('min_age'));
        }
        if ($request->has('max_age')) {
            $query->where('max_age', '<=', $request->input('max_age'));
        }

        // Filter by student ID (if provided)
        if ($request->has('student_id')) {
            $query->where('student_id', $request->input('student_id'));
        }

        // Sorting
        if ($request->has('sort_by')) {
            $sortField = $request->input('sort_by');
            $sortDirection = $request->input('sort_dir', 'asc'); // Default to ascending
            $query->orderBy($sortField, $sortDirection);
        }

        // Paginate the results
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $cases = $query->paginate($perPage);

        // Return the paginated cases as a JSON response
        return response()->json($cases);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|exists:services,id',
            'schedules_id' => 'required|exists:schedules,id',
            'procedure' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female,Other',
            'description' => 'required|string',
            'cost' => 'required|numeric',
            'min_age' => 'required|integer',
            'max_age' => 'required|integer',
            'student_id' => 'nullable|exists:students,id', // Allow student_id to be nullable
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new case record
        $case = TheCase::create([
            'service_id' => $request->service_id,
            'schedules_id' => $request->schedules_id,
            'procedure' => $request->procedure,
            'gender' => $request->gender,
            'description' => $request->description,
            'cost' => $request->cost,
            'min_age' => $request->min_age,
            'max_age' => $request->max_age,
            'student_id' => $request->student_id
        ]);

        // Return the created case as a JSON response
        return response()->json($case, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the case by ID and eager load the student relationship
        $case = TheCase::with('student')->find($id);

        // If the case doesn't exist, return a 404 error
        if (!$case) {
            return response()->json([
                'message' => 'Case not found',
            ], 404);
        }

        // Return the case as a JSON response
        return response()->json($case);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the case by ID
        $case = TheCase::find($id);

        // If the case doesn't exist, return a 404 error
        if (!$case) {
            return response()->json([
                'message' => 'Case not found',
            ], 404);
        }

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'service_id' => 'sometimes|exists:services,id',
            'schedules_id' => 'sometimes|exists:schedules,id',
            'procedure' => 'sometimes|string|max:255',
            'gender' => 'sometimes|string|in:Male,Female,Other',
            'description' => 'sometimes|string',
            'cost' => 'sometimes|numeric',
            'min_age' => 'sometimes|integer',
            'max_age' => 'sometimes|integer',
            'student_id' => 'nullable|exists:students,id', // Allow student_id to be nullable

        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update the case record
        $case->update([
            'service_id' => $request->service_id ?? $case->service_id,
            'schedules_id' => $request->schedules_id ?? $case->schedules_id,
            'procedure' => $request->procedure ?? $case->procedure,
            'gender' => $request->gender ?? $case->gender,
            'description' => $request->description ?? $case->description,
            'cost' => $request->cost ?? $case->cost,
            'min_age' => $request->min_age ?? $case->min_age,
            'max_age' => $request->max_age ?? $case->max_age,
            'student_id' => $request->student_id ?? $case->student_id

        ]);

        // Return the updated case as a JSON response
        return response()->json($case);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the case by ID
        $case = TheCase::find($id);

        // If the case doesn't exist, return a 404 error
        if (!$case) {
            return response()->json([
                'message' => 'Case not found',
            ], 404);
        }

        // Delete the case record
        $case->delete();

        // Return a success response
        return response()->json([
            'message' => 'Case deleted successfully',
        ], 204);
    }

    public function createCaseWithSchedule(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|exists:services,id',
            'procedure' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female,Other',
            'description' => 'required|string',
            'cost' => 'required|numeric',
            'min_age' => 'required|integer',
            'max_age' => 'required|integer',
            'student_id' => 'nullable|exists:students,id',

            // Schedule related fields
            'available_date' => 'required|date',
            'available_time' => 'required|date_format:H:i',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Use a transaction to ensure both records are created successfully
        DB::beginTransaction();

        try {
            // Create a new schedule record
            $schedule = Schedule::create([
                'available_date' => $request->available_date,
                'available_time' => $request->available_time,
            ]);

            // Create a new case record
            $case = TheCase::create([
                'service_id' => $request->service_id,
                'schedules_id' => $schedule->id, // Use the ID of the newly created schedule
                'procedure' => $request->procedure,
                'gender' => $request->gender,
                'description' => $request->description,
                'cost' => $request->cost,
                'min_age' => $request->min_age,
                'max_age' => $request->max_age,
                'student_id' => $request->student_id,
            ]);

            // Commit the transaction
            DB::commit();

            // Return the created case and schedule as a JSON response
            return response()->json([
                'case' => $case,
                'schedule' => $schedule,
            ], 201);

        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            // Return error response
            return response()->json([
                'message' => 'Error creating records',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateCaseWithSchedule(Request $request, $caseId)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'service_id' => 'sometimes|required|exists:services,id',
            'procedure' => 'sometimes|required|string|max:255',
            'gender' => 'sometimes|required|string|in:Male,Female,Other',
            'description' => 'sometimes|required|string',
            'cost' => 'sometimes|required|numeric',
            'min_age' => 'sometimes|required|integer',
            'max_age' => 'sometimes|required|integer',
            'student_id' => 'nullable|exists:students,id',

            // Schedule related fields
            'available_date' => 'sometimes|required|date',
            'available_time' => 'sometimes|required|date_format:H:i',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Use a transaction to ensure both records are updated successfully
        DB::beginTransaction();

        try {
            // Find the case record
            $case = TheCase::findOrFail($caseId);

            // Find the associated schedule record
            $schedule = Schedule::findOrFail($case->schedules_id);

            // Update the schedule record if schedule-related fields are provided
            if ($request->has('available_date') || $request->has('available_time')) {
                $schedule->update([
                    'available_date' => $request->available_date ?? $schedule->available_date,
                    'available_time' => $request->available_time ?? $schedule->available_time,
                ]);
            }

            // Update the case record
            $case->update([
                'service_id' => $request->service_id ?? $case->service_id,
                'procedure' => $request->procedure ?? $case->procedure,
                'gender' => $request->gender ?? $case->gender,
                'description' => $request->description ?? $case->description,
                'cost' => $request->cost ?? $case->cost,
                'min_age' => $request->min_age ?? $case->min_age,
                'max_age' => $request->max_age ?? $case->max_age,
                'student_id' => $request->student_id ?? $case->student_id,
            ]);

            // Commit the transaction
            DB::commit();

            // Return the updated case and schedule as a JSON response
            return response()->json([
                'case' => $case,
                'schedule' => $schedule,
            ], 200);

        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            // Return error response
            return response()->json([
                'message' => 'Error updating records',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Retrieve a list of cases with specific fields (id, procedure, etc.).
     */
    public function select(Request $request)
    {
        // Start with a base query
        $query = TheCase::query();

        // Apply filters if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('procedure', 'like', '%' . $search . '%');
        }

        if ($request->has('service_id')) {
            $query->where('service_id', $request->input('service_id'));
        }

        if ($request->has('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        // Select only specific fields (id, procedure, etc.)
        $cases = $query->select('id', 'procedure', 'service_id', 'gender')->get();

        // Return the cases as a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Cases retrieved successfully',
            'data' => $cases,
        ]);
    }
}
