<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start with a base query
        $query = Complaint::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('complaint_type', 'like', '%' . $search . '%')
                    ->orWhere('complaint_title', 'like', '%' . $search . '%')
                    ->orWhere('complaint_desciption', 'like', '%' . $search . '%');
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

        // Filter by complaint type
        if ($request->has('complaint_type')) {
            $query->where('complaint_type', $request->input('complaint_type'));
        }

        // Filter by complaint date
        if ($request->has('complaint_date')) {
            $query->where('complaint_date', $request->input('complaint_date'));
        }

        // Sorting
        if ($request->has('sort_by')) {
            $sortField = $request->input('sort_by');
            $sortDirection = $request->input('sort_dir', 'asc'); // Default to ascending
            $query->orderBy($sortField, $sortDirection);
        }

        // Paginate the results
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $complaints = $query->paginate($perPage);

        // Return the paginated complaints as a JSON response
        return response()->json($complaints);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'patient_id' => 'nullable|exists:patients,id',
            'student_id' => 'nullable|exists:students,id',
            'complaint_type' => 'required|string|max:255',
            'complaint_title' => 'required|string|max:255',
            'complaint_desciption' => 'required|string',
            'complaint_date' => 'required|date',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new complaint record
        $complaint = Complaint::create([
            'patient_id' => $request->patient_id,
            'student_id' => $request->student_id,
            'complaint_type' => $request->complaint_type,
            'complaint_title' => $request->complaint_title,
            'complaint_desciption' => $request->complaint_desciption,
            'complaint_date' => $request->complaint_date,
        ]);

        // Return the created complaint as a JSON response
        return response()->json($complaint, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the complaint by ID
        $complaint = Complaint::find($id);

        // If the complaint doesn't exist, return a 404 error
        if (!$complaint) {
            return response()->json([
                'message' => 'Complaint not found',
            ], 404);
        }

        // Return the complaint as a JSON response
        return response()->json($complaint);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the complaint by ID
        $complaint = Complaint::find($id);

        // If the complaint doesn't exist, return a 404 error
        if (!$complaint) {
            return response()->json([
                'message' => 'Complaint not found',
            ], 404);
        }

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'patient_id' => 'nullable|exists:patients,id',
            'student_id' => 'nullable|exists:students,id',
            'complaint_type' => 'sometimes|string|max:255',
            'complaint_title' => 'sometimes|string|max:255',
            'complaint_desciption' => 'sometimes|string',
            'complaint_date' => 'sometimes|date',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Build an array of only the fields provided in the request.
        // This way, if patient_id or student_id are provided as null, they will be updated to null.
        $data = $request->only([
            'patient_id',
            'student_id',
            'complaint_type',
            'complaint_title',
            'complaint_desciption',
            'complaint_date',
        ]);

        $complaint->update($data);

        // Return the updated complaint as a JSON response
        return response()->json($complaint);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the complaint by ID
        $complaint = Complaint::find($id);

        // If the complaint doesn't exist, return a 404 error
        if (!$complaint) {
            return response()->json([
                'message' => 'Complaint not found',
            ], 404);
        }

        // Delete the complaint record
        $complaint->delete();

        // Return a success response
        return response()->json([
            'message' => 'Complaint deleted successfully',
        ], 204);
    }

    /**
     * Get a list of complaints with only id, complaint_type, and complaint_title.
     */
    public function select(Request $request)
    {
        // Start with a base query
        $query = Complaint::query();

        // Apply filters if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('complaint_type', 'like', '%' . $search . '%')
                    ->orWhere('complaint_title', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->input('patient_id'));
        }

        if ($request->has('complaint_type')) {
            $query->where('complaint_type', $request->input('complaint_type'));
        }

        if ($request->has('complaint_date')) {
            $query->where('complaint_date', $request->input('complaint_date'));
        }

        // Select only id, complaint_type, and complaint_title
        $complaints = $query->select('id', 'complaint_type', 'complaint_title')->get();

        // Return the complaints as a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Complaints retrieved successfully',
            'data' => $complaints,
        ]);
    }
}
