<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start with a base query
        $query = Appointment::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', '%' . $search . '%');
            });
        }

        // Filter by patient ID
        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->input('patient_id'));
        }

        // Filter by student ID
        if ($request->has('student_id')) {
            $query->where('student_id', $request->input('student_id'));
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
        $appointments = $query->paginate($perPage);

        // Return the paginated appointments as a JSON response
        return response()->json($appointments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'student_id' => 'required|exists:students,id',
            'thecase_id' => 'required|exists:thecases,id',
            // 'status' => 'required|in:بانتظار التأكيد,مؤكد,مكتمل,ملغي',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new appointment record
        $appointment = Appointment::create([
            'patient_id' => $request->patient_id,
            'student_id' => $request->student_id,
            'thecase_id' => $request->thecase_id,
            'status' => "بانتظار التأكيد",
            // 'status' => $request->status,
        ]);

        // Return the created appointment as a JSON response
        return response()->json($appointment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the appointment by ID
        $appointment = Appointment::find($id);

        // If the appointment doesn't exist, return a 404 error
        if (!$appointment) {
            return response()->json([
                'message' => 'Appointment not found',
            ], 404);
        }

        // Return the appointment as a JSON response
        return response()->json($appointment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the appointment by ID
        $appointment = Appointment::find($id);

        // If the appointment doesn't exist, return a 404 error
        if (!$appointment) {
            return response()->json([
                'message' => 'Appointment not found',
            ], 404);
        }

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'patient_id' => 'sometimes|exists:patients,id',
            'student_id' => 'sometimes|exists:students,id',
            'thecase_id' => 'required|exists:thecases,id',
            'status' => 'sometimes|in:بانتظار التأكيد,مؤكد,مكتمل,ملغي',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update the appointment record
        $appointment->update([
            'patient_id' => $request->patient_id ?? $appointment->patient_id,
            'student_id' => $request->student_id ?? $appointment->student_id,
            'thecase_id' => $request->thecase_id ?? $appointment->thecase_id,
            'status' => $request->status ?? $appointment->status,
        ]);

        // Return the updated appointment as a JSON response
        return response()->json($appointment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the appointment by ID
        $appointment = Appointment::find($id);

        // If the appointment doesn't exist, return a 404 error
        if (!$appointment) {
            return response()->json([
                'message' => 'Appointment not found',
            ], 404);
        }

        // Delete the appointment record
        $appointment->delete();

        // Return a success response
        return response()->json([
            'message' => 'Appointment deleted successfully',
        ], 204);
    }

    public function select(Request $request)
    {
        // Start with a base query
        $query = Appointment::query();

        // Apply filters if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('status', 'like', '%' . $search . '%');
        }

        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->input('patient_id'));
        }

        if ($request->has('student_id')) {
            $query->where('student_id', $request->input('student_id'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Sorting
        if ($request->has('sort_by')) {
            $sortField = $request->input('sort_by');
            $sortDirection = $request->input('sort_dir', 'asc'); // Default to ascending
            $query->orderBy($sortField, $sortDirection);
        }

        // Select only specific fields (id and status)
        $query->select('id', 'status');

        // Paginate the results (optional)
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $appointments = $query->paginate($perPage);

        // Return the appointments as a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Appointments retrieved successfully',
            'data' => $appointments,
        ]);
    }
}
