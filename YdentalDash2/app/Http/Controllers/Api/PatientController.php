<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendOtp;
use Illuminate\Support\Carbon;
class PatientController extends Controller
{
    public function index(Request $request)
    {
        // Start with a base query
        $query = Patient::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('id_card', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%');
            });
        }

        // Filter by gender
        if ($request->has('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        // Sorting
        if ($request->has('sort_by')) {
            $sortField = $request->input('sort_by');
            $sortDirection = $request->input('sort_dir', 'asc'); // Default to ascending
            $query->orderBy($sortField, $sortDirection);
        }

        // Paginate the results
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $patients = $query->paginate($perPage);

        // Return the paginated patients as a JSON response
        return response()->json($patients);
    }


    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:patients',
                'password' => 'required|string',
                'confirmPassword' => 'required|string|same:password',
                'id_card' => 'required|string',
                'gender' => 'required|string',
                'address' => 'required|string',
                'date_of_birth' => 'required|date',
                'phone_number' => 'required|integer|unique:patients',
                'userType' => 'required|string',
                'isBlocked' => 'required|string',
            ]);
            // Hash the password before saving
            $validatedData['password'] = bcrypt($validatedData['password']);
            $validatedData['confirmPassword'] = bcrypt($validatedData['confirmPassword']);
            $patient = Patient::create($validatedData);

            return response()->json($patient, 201);
        } catch (ValidationException $e) {
            // Return a 422 error with validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }


    public function show(Patient $patient)
    {
        return $patient;
    }

    public function update(Request $request, Patient $patient)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|string',
                'email' => 'sometimes|email|unique:patients,email,' . $patient->id,
                'password' => 'sometimes|string',
                'confirmPassword' => 'sometimes|string|same:password',
                'id_card' => 'sometimes|string',
                'gender' => 'sometimes|string',
                'address' => 'sometimes|string',
                'date_of_birth' => 'sometimes|date',
                'phone_number' => 'sometimes|integer|unique:patients,phone_number,' . $patient->id,
                'userType' => 'sometimes|string',
                'isBlocked' => 'sometimes|string',
            ]);
            // Hash the password if it is being updated
            if ($request->has('password')) {
                $validatedData['password'] = bcrypt($validatedData['password']);
            }
            if ($request->has('confirmPassword')) {
                $validatedData['confirmPassword'] = bcrypt($validatedData['confirmPassword']);
            }
            $patient->update($validatedData);

            return response()->json($patient, 200);
        } catch (ValidationException $e) {
            // Return a 422 error with validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return response()->json(null, 204);
    }



    public function getPatientsByStudentId(Request $request, $studentId)
    {
        // Validate the student ID (optional but recommended)
        if (!is_numeric($studentId)) {
            return response()->json([
                'message' => 'Invalid student ID',
            ], 400);
        }

        // Find all appointments for the given student ID
        $appointments = Appointment::where('student_id', $studentId)->get();

        // If no appointments are found, return an empty response
        if ($appointments->isEmpty()) {
            return response()->json([
                'message' => 'No appointments found for this student',
                'patients' => [],
            ], 200);
        }

        // Extract patient IDs from the appointments
        $patientIds = $appointments->pluck('patient_id')->unique();

        // Start building the query for patients
        $query = Patient::whereIn('id', $patientIds);

        // Add search by patient name if the 'name' parameter is provided
        if ($request->has('name') && !empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Retrieve the patients with pagination
        $patients = $query->paginate(10); // 10 patients per page

        // Return the patients with pagination metadata as a JSON response
        return response()->json([
            'message' => 'Patients retrieved successfully',
            'patients' => $patients->items(), // Patients for the current page
            'pagination' => [
                'current_page' => $patients->currentPage(),
                'per_page' => $patients->perPage(),
                'total' => $patients->total(),
                'last_page' => $patients->lastPage(),
                'next_page_url' => $patients->nextPageUrl(),
                'prev_page_url' => $patients->previousPageUrl(),
            ],
        ], 200);
    }

    public function login(Request $request)
    {
        try {
            // Validate the incoming request data
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            // Attempt to authenticate the patient
            if (!Auth::guard('patients')->attempt($request->only('email', 'password'))) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            // Get the authenticated patient
            $patient = Auth::guard('patients')->user();

            // Create a new API token for the patient
            $token = $patient->createToken('patient-token')->plainTextToken;

            // Return the token and patient data as a JSON response
            return response()->json([
                'success' => true,
                'message' => 'Patient logged in successfully',
                'patient' => $patient,
                'token' => $token,
            ], 200);

        } catch (ValidationException $e) {
            // Return a 422 error with validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Return a 500 error for other exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function requestOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:patients,email',
        ]);

        // Find the patient by email
        $patient = Patient::where('email', $request->email)->first();

        // Generate a 6-digit OTP
        $otp = rand(100000, 999999); // Random 6-digit number
        $patient->otp = $otp;
        $patient->otp_expires_at = Carbon::now()->addMinutes(10); // OTP expires in 10 minutes
        $patient->save();

        // Send the OTP to the patient's email
        Mail::to($patient->email)->send(new SendOtp($otp));

        return response()->json([
            'success' => true,
            'message' => 'OTP sent to your email.',
        ]);
    }

    public function resetPasswordWithOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:patients,email',
            'otp' => 'required|string|size:6', // OTP must be 6 digits
            'password' => 'required|string|min:8',
        ]);
        // Find the patient by email and OTP
        $patient = Patient::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('otp_expires_at', '>', Carbon::now())
            ->first();

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP.',
            ], 400);
        }

        // Update the patient's password
        $patient->password = Hash::make($request->password);
        $patient->otp = null; // Clear the OTP
        $patient->otp_expires_at = null; // Clear the OTP expiry time
        $patient->save();

        return response()->json([
            'success' => true,
            'message' => 'Your password has been reset successfully.',
        ]);
    }
    /**
     * Get a list of patients with only id, name, and email.
     */
    public function select(Request $request)
    {
        // Start with a base query
        $query = Patient::query();

        // Apply filters if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        // Select only id, name, and email
        $patients = $query->select('id', 'name', 'email')->get();

        // Return the patients as a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Patients retrieved successfully',
            'data' => $patients,
        ]);
    }
}
