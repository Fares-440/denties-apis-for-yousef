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
        // Eager load student, patient,schedule, and thecase information.
        $query = Appointment::with([
            'student:id,name',
            'patient:id,name',
            'thecase.schedules',
            'schedule',
        ]);


        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', '%' . $search . '%')
                    ->orWhereHas('patient', function ($pq) use ($search) {
                        $pq->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('student', function ($sq) use ($search) {
                        $sq->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->input('patient_id'));
        }

        if ($request->has('student_id')) {
            $query->where('student_id', $request->input('student_id'));
        }

        if ($request->has('schedule_id')) {
            $query->where('schedule_id', $request->input('schedule_id'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('sort_by')) {
            $sortField = $request->input('sort_by');
            $sortDirection = $request->input('sort_dir', 'asc');
            $allowedSortFields = ['status', 'patient_id', 'student_id', 'created_at']; // Allowed fields

            if (in_array($sortField, $allowedSortFields)) {
                $query->orderBy($sortField, $sortDirection);
            } else {
                return response()->json(['error' => 'Invalid sort field'], 400);
            }
        }

        $perPage = $request->input('per_page', 10);
        $appointments = $query->paginate($perPage);

        return response()->json($appointments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data.
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'student_id' => 'required|exists:students,id',
            'thecase_id' => 'required|exists:thecases,id',
            'schedule_id' => 'required|exists:schedules,id',
            // 'status' => 'required|in:بانتظار التأكيد,مؤكد,مكتمل,ملغي',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }
        // Check for existing appointment with the same details and status not cancelled or completed
        $existingAppointment = Appointment::where('patient_id', $request->patient_id)
            ->where('student_id', $request->student_id)
            ->where('thecase_id', $request->thecase_id)
            ->where('schedule_id', $request->schedule_id)
            ->whereNotIn('status', [Appointment::STATUS_CANCELLED, Appointment::STATUS_COMPLETED])
            ->exists();

        if ($existingAppointment) {
            return response()->json([
                'message' => 'يوجد لديك حجز بالفعل لهذه البيانات',
            ], 409);
        }
        // Create a new appointment record.
        $appointment = Appointment::create([
            'patient_id' => $request->patient_id,
            'student_id' => $request->student_id,
            'thecase_id' => $request->thecase_id,
            'schedule_id' => $request->schedule_id,
            'status' => "بانتظار التأكيد",
            // 'status' => $request->status,
        ]);

        // Return the created appointment as a JSON response.
        // Optionally, you may want to load the related thecase info.
        $appointment->load(['student:id,name', 'patient:id,name', 'thecase', 'schedule']);
        return response()->json($appointment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the appointment by ID with the related thecase, student, and patient data.
        $appointment = Appointment::with(['student:id,name', 'patient:id,name', 'thecase', 'schedule'])->find($id);

        if (!$appointment) {
            return response()->json([
                'message' => 'Appointment not found',
            ], 404);
        }

        return response()->json($appointment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the appointment by ID.
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json([
                'message' => 'Appointment not found',
            ], 404);
        }

        // Validate the incoming request data.
        $validator = Validator::make($request->all(), [
            'patient_id' => 'sometimes|exists:patients,id',
            'student_id' => 'sometimes|exists:students,id',
            'thecase_id' => 'required|exists:thecases,id',
            'schedule_id' => 'required|exists:schedules,id',
            'status' => 'sometimes|in:بانتظار التأكيد,مؤكد,مكتمل,ملغي',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update the appointment record.
        $appointment->update([
            'patient_id' => $request->patient_id ?? $appointment->patient_id,
            'student_id' => $request->student_id ?? $appointment->student_id,
            'thecase_id' => $request->thecase_id ?? $appointment->thecase_id,
            'schedule_id' => $request->schedule_id ?? $appointment->schedule_id,
            'status' => $request->status ?? $appointment->status,
        ]);

        // Return the updated appointment as a JSON response.
        $appointment->load(['student:id,name', 'patient:id,name', 'thecase']);
        return response()->json($appointment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the appointment by ID.
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json([
                'message' => 'Appointment not found',
            ], 404);
        }

        // Delete the appointment record.
        $appointment->delete();

        return response()->json([
            'message' => 'Appointment deleted successfully',
        ], 204);
    }

    /**
     * Retrieve a simplified list of appointments.
     */
    public function select(Request $request)
    {
        $query = Appointment::query();

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

        if ($request->has('schedule_id')) {
            $query->where('schedule_id', $request->input('schedule_id'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('sort_by')) {
            $sortField = $request->input('sort_by');
            $sortDirection = $request->input('sort_dir', 'asc');
            $query->orderBy($sortField, $sortDirection);
        }

        // Select only specific fields (id and status)
        $query->select('id', 'status');

        $perPage = $request->input('per_page', 10);
        $appointments = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Appointments retrieved successfully',
            'data' => $appointments,
        ]);
    }
}
