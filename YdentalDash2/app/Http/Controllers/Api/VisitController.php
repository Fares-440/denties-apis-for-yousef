<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisitController extends Controller
{
    public function index(Request $request)
    {
        // Start with a base query
        $query = Visit::query()->with([
            'appointment.patient:id,name',
            'appointment.student:id,name'
        ]);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('procedure', 'like', '%' . $search . '%')
                    ->orWhere('note', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        // Filter by visit date
        if ($request->has('visit_date')) {
            $query->where('visit_date', $request->input('visit_date'));
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Sorting
        if ($request->has('sort_by')) {
            $sortField = $request->input('sort_by');
            $sortDirection = $request->input('sort_dir', 'asc'); // Default to ascending
            $query->orderBy($sortField, $sortDirection);
        }

        // Paginate the results
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $visits = $query->paginate($perPage);

        // Return the paginated visits as a JSON response
        return response()->json($visits);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:appointments,id', // Ensure the appointment exists
            'visit_date' => 'required|date',
            'visit_time' => 'required|date_format:H:i:s',
            'procedure' => 'required|string',
            'note' => 'required|string',
            'status' => 'required|in:غير مكتملة,مكتملة,ملغية',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new visit record
        $visit = Visit::create([
            'appointment_id' => $request->appointment_id,
            'visit_date' => $request->visit_date,
            'visit_time' => $request->visit_time,
            'procedure' => $request->procedure,
            'note' => $request->note,
            'status' => $request->status,
        ]);

        // Return the created visit as a JSON response
        return response()->json($visit, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the visit by ID
        $visit = Visit::find($id);

        // If the visit doesn't exist, return a 404 error
        if (!$visit) {
            return response()->json([
                'message' => 'Visit not found',
            ], 404);
        }

        // Return the visit as a JSON response
        return response()->json($visit);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the visit by ID
        $visit = Visit::find($id);

        // If the visit doesn't exist, return a 404 error
        if (!$visit) {
            return response()->json([
                'message' => 'Visit not found',
            ], 404);
        }

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'sometimes|exists:appointments,id', // Ensure the appointment exists if provided
            'visit_date' => 'sometimes|date',
            'visit_time' => 'required|date_format:H:i:s', // Validate time format (e.g., 14:30)
            'procedure' => 'sometimes|string',
            'note' => 'sometimes|string',
            'status' => 'sometimes|in:غير مكتملة,مكتملة,ملغية',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update the visit record
        $visit->update([
            'appointment_id' => $request->appointment_id ?? $visit->appointment_id,
            'visit_date' => $request->visit_date ?? $visit->visit_date,
            'visit_time' => $request->visit_time ?? $visit->visit_time,
            'procedure' => $request->procedure ?? $visit->procedure,
            'note' => $request->note ?? $visit->note,
            'status' => $request->status ?? $visit->status,
        ]);

        // Return the updated visit as a JSON response
        return response()->json($visit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the visit by ID
        $visit = Visit::find($id);

        // If the visit doesn't exist, return a 404 error
        if (!$visit) {
            return response()->json([
                'message' => 'Visit not found',
            ], 404);
        }

        // Delete the visit record
        $visit->delete();

        // Return a success response
        return response()->json([
            'message' => 'Visit deleted successfully',
        ], 204);
    }

    /**
     * Get a list of visits with only id, visit_date, procedure, and status.
     */
    public function select(Request $request)
    {
        // Start with a base query
        $query = Visit::query();

        // Apply filters if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('procedure', 'like', '%' . $search . '%')
                    ->orWhere('note', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('visit_date')) {
            $query->where('visit_date', $request->input('visit_date'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Select only id, visit_date, procedure, and status
        $visits = $query->select('id', 'visit_date', 'procedure', 'status')->get();

        // Return the visits as a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Visits retrieved successfully',
            'data' => $visits,
        ]);
    }

    public function getTodaysVisits(Request $request)
    {
        $query = Visit::whereDate('visit_date', today())
            ->with([
                'appointment' => function ($q) {
                    $q->select('id', 'student_id', 'patient_id');
                }
            ])
            ->orderBy('visit_time');

        // Add student_id filter
        if ($request->has('student_id')) {
            $query->whereHas('appointment', function ($q) use ($request) {
                $q->where('student_id', $request->student_id);
            });
        }

        // Add patient_id filter
        if ($request->has('patient_id')) {
            $query->whereHas('appointment', function ($q) use ($request) {
                $q->where('patient_id', $request->patient_id);
            });
        }

        $visits = $query->get();

        return response()->json([
            'success' => true,
            'message' => "Today's visits retrieved successfully",
            'data' => $visits,
        ]);
    }

    public function getTodaysVisitsCount(Request $request)
    {
        $query = Visit::whereDate('visit_date', today());

        // Add student_id filter
        if ($request->has('student_id')) {
            $query->whereHas('appointment', function ($q) use ($request) {
                $q->where('student_id', $request->student_id);
            });
        }

        // Add patient_id filter
        if ($request->has('patient_id')) {
            $query->whereHas('appointment', function ($q) use ($request) {
                $q->where('patient_id', $request->patient_id);
            });
        }

        $visits = $query->get();

        $stats = [
            'total' => $visits->count(),
            'statuses' => [
                'غير مكتملة' => $visits->where('status', 'غير مكتملة')->count(),
                'مكتملة' => $visits->where('status', 'مكتملة')->count(),
                'ملغية' => $visits->where('status', 'ملغية')->count(),
            ],
            'date' => now()->toDateString(),
            'student_id' => $request->student_id ?? null,
            'patient_id' => $request->patient_id ?? null
        ];

        return response()->json([
            'success' => true,
            'message' => "Today's visits statistics retrieved successfully",
            'data' => $stats
        ]);
    }
}
