<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use App\Mail\SendOtp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start with a base query
        $query = Student::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('university_card_number', 'like', '%' . $search . '%');
            });
        }
        // Filter by gender
        if ($request->has('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        // Filter by level
        if ($request->has('level')) {
            $query->where('level', $request->input('level'));
        }

        // Filter by city ID
        if ($request->has('city_id')) {
            $query->where('city_id', $request->input('city_id'));
        }

        // Filter by university ID
        if ($request->has('university_id')) {
            $query->where('university_id', $request->input('university_id'));
        }

        // Filter by service ID
        if ($request->has('service_id')) {
            $query->whereHas('cases', function ($q) use ($request) {
                $q->where('service_id', $request->input('service_id'));
            });
        }

        // Sorting
        if ($request->has('sort_by')) {
            $sortField = $request->input('sort_by');
            $sortDirection = $request->input('sort_dir', 'asc'); // Default to ascending
            $query->orderBy($sortField, $sortDirection);
        }

        // Paginate the results
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $students = $query->paginate($perPage);
        // Return the paginated students as a JSON response
        return response()->json($students);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'student_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:students,email',
                'password' => 'required|string|min:6',
                'confirmPassword' => 'required|string|same:password',
                'gender' => 'required|string|in:ذكر,انثى',
                'level' => 'required|string',
                'phone_number' => 'required|string|unique:students,phone_number',
                'university_card_number' => 'required|string',
                'university_card_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
                'city_id' => 'required|exists:cities,id',
                'university_id' => 'required|exists:universities,id',
                'userType' => 'required|string',
                'isBlocked' => 'required|string',
            ]);

            // Handle student image upload
            if ($request->hasFile('student_image')) {
                $studentImage = $request->file('student_image');
                $studentImagePath = 'student_images/' . uniqid() . '.' . $studentImage->getClientOriginalExtension();
                $studentImage->move(public_path('student_images'), basename($studentImagePath));
            } else {
                $studentImagePath = null;
            }

            // Handle university card image upload
            $universityCardImage = $request->file('university_card_image');
            $universityCardImagePath = 'university_card_images/' . uniqid() . '.' . $universityCardImage->getClientOriginalExtension();
            $universityCardImage->move(public_path('university_card_images'), basename($universityCardImagePath));

            // Create a new student record
            $student = Student::create([
                'student_image' => $studentImagePath,
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password), // Hash the password
                'confirmPassword' => bcrypt($request->password), // Hash the password
                'gender' => $request->gender,
                'level' => $request->level,
                'phone_number' => $request->phone_number,
                'university_card_number' => $request->university_card_number,
                'university_card_image' => $universityCardImagePath,
                'city_id' => $request->city_id,
                'university_id' => $request->university_id,
                'userType' => $request->userType,
                'isBlocked' => $request->isBlocked,
            ]);

            // Return the created student as a JSON response
            return response()->json([
                'student' => $student,
                'student_image_url' => $studentImagePath ? url($studentImagePath) : null,
                'university_card_image_url' => url($universityCardImagePath),
            ], 201);

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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the student by ID
        $student = Student::find($id);

        // If the student doesn't exist, return a 404 error
        if (!$student) {
            return response()->json([
                'message' => 'Student not found',
            ], 404);
        }

        // Return the student as a JSON response
        return response()->json($student);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the student by ID
        $student = Student::find($id);

        // If the student doesn't exist, return a 404 error
        if (!$student) {
            return response()->json([
                'message' => 'Student not found',
            ], 404);
        }

        try {
            // Validate the incoming request data with nullable rules for optional fields
            $validatedData = $request->validate([
                'student_image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
                'name' => 'sometimes|nullable|string|max:255',
                'email' => 'sometimes|nullable|email|unique:students,email,' . $student->id,
                'password' => 'sometimes|nullable|string|min:6',
                'confirmPassword' => 'sometimes|nullable|string|same:password',
                'gender' => 'sometimes|nullable|string|in:ذكر,انثى',
                'level' => 'sometimes|nullable|string',
                'phone_number' => 'sometimes|nullable|string|unique:students,phone_number,' . $student->id,
                'university_card_number' => 'sometimes|nullable|string',
                'university_card_image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
                'city_id' => 'sometimes|nullable|exists:cities,id',
                'university_id' => 'sometimes|nullable|exists:universities,id',
                'userType' => 'sometimes|nullable|string',
                'isBlocked' => 'sometimes|nullable|string',
            ]);

            // Handle student image upload
            if ($request->hasFile('student_image')) {
                // Delete the old student image if it exists
                if ($student->student_image && file_exists(public_path($student->student_image))) {
                    unlink(public_path($student->student_image));
                }

                // Upload the new student image
                $studentImage = $request->file('student_image');
                $studentImagePath = 'student_images/' . uniqid() . '.' . $studentImage->getClientOriginalExtension();
                $studentImage->move(public_path('student_images'), basename($studentImagePath));
            } else {
                $studentImagePath = $student->student_image;
            }

            // Handle university card image upload
            if ($request->hasFile('university_card_image')) {
                // Delete the old university card image if it exists
                if ($student->university_card_image && file_exists(public_path($student->university_card_image))) {
                    unlink(public_path($student->university_card_image));
                }

                // Upload the new university card image
                $universityCardImage = $request->file('university_card_image');
                $universityCardImagePath = 'university_card_images/' . uniqid() . '.' . $universityCardImage->getClientOriginalExtension();
                $universityCardImage->move(public_path('university_card_images'), basename($universityCardImagePath));
            } else {
                $universityCardImagePath = $student->university_card_image;
            }

            // Update the student record
            $student->update([
                'student_image' => $studentImagePath,
                'name' => $request->name ?? $student->name,
                'email' => $request->email ?? $student->email,
                'password' => $request->password ? bcrypt($request->password) : $student->password,
                'confirmPassword' => $request->confirmPassword ? bcrypt($request->confirmPassword) : $student->confirmPassword,
                'gender' => $request->gender ?? $student->gender,
                'level' => $request->level ?? $student->level,
                'phone_number' => $request->phone_number ?? $student->phone_number,
                'university_card_number' => $request->university_card_number ?? $student->university_card_number,
                'university_card_image' => $universityCardImagePath,
                'city_id' => $request->city_id ?? $student->city_id,
                'university_id' => $request->university_id ?? $student->university_id,
                'userType' => $request->userType ?? $student->userType,
                'isBlocked' => $request->isBlocked ?? $student->isBlocked,
            ]);

            // Return the updated student as a JSON response
            return response()->json([
                'student' => $student,
                'student_image_url' => $studentImagePath ? url($studentImagePath) : null,
                'university_card_image_url' => $universityCardImagePath ? url($universityCardImagePath) : null,
            ]);

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


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the student by ID
        $student = Student::find($id);

        // If the student doesn't exist, return a 404 error
        if (!$student) {
            return response()->json([
                'message' => 'Student not found',
            ], 404);
        }

        // Delete the student record
        $student->delete();

        // Return a success response
        return response()->json([
            'message' => 'Student deleted successfully',
        ], 204);
    }

    /**
     * Get students by service ID with pagination.
     */
    public function getStudentsByService($serviceId, Request $request)
    {
        // Number of items per page (default to 10 if not provided)
        $perPage = $request->input('per_page', 10);

        // Start the query
        $query = Student::whereHas('cases', function ($query) use ($serviceId) {
            $query->where('service_id', $serviceId);
        })
            ->select('students.id', 'students.name', 'students.city_id', 'students.university_id') // Select only required fields
            ->with(['city:id,name', 'university:id,name']); // Eager load city and university names

        // Filter by city (if provided)
        if ($request->has('city_id')) {
            $query->where('city_id', $request->input('city_id'));
        }

        // Filter by university (if provided)
        if ($request->has('university_id')) {
            $query->where('university_id', $request->input('university_id'));
        }

        // Filter by search key (if provided)
        if ($request->has('search_key')) {
            $searchKey = $request->input('search_key');
            $query->where(function ($q) use ($searchKey) {
                $q->where('students.name', 'like', '%' . $searchKey . '%') // Search by student name
                    ->orWhereHas('city', function ($q) use ($searchKey) {
                        $q->where('name', 'like', '%' . $searchKey . '%'); // Search by city name
                    })
                    ->orWhereHas('university', function ($q) use ($searchKey) {
                        $q->where('name', 'like', '%' . $searchKey . '%'); // Search by university name
                    });
            });
        }

        // Paginate the results
        $students = $query->paginate($perPage);

        // Format the response data
        $formattedStudents = $students->map(function ($student) {
            return [
                'name' => $student->name,
                'city_name' => $student->city->name, // Access city name from the relationship
                'university_name' => $student->university->name, // Access university name from the relationship
            ];
        });

        // Return the paginated students as a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Students retrieved successfully',
            'data' => [
                'current_page' => $students->currentPage(),
                'per_page' => $students->perPage(),
                'total' => $students->total(),
                'last_page' => $students->lastPage(),
                'students' => $formattedStudents,
            ],
        ]);
    }


    public function login(Request $request)
    {
        try {
            // Validate the incoming request data
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            // Attempt to authenticate the student
            if (!Auth::guard('students')->attempt($request->only('email', 'password'))) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            // Get the authenticated student
            $student = Auth::guard('students')->user()->load('city', 'university');;

            // Create a new API token for the student
            $token = $student->createToken('student-token')->plainTextToken;

            // Return the token and student data as a JSON response
            return response()->json([
                'success' => true,
                'message' => 'Student logged in successfully',
                'student' => $student,
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

    public function profile(Request $request)
    {
        // Get the authenticated student
        $student = $request->user();

        // Return the student data as a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Student profile retrieved successfully',
            'student' => $student,
        ], 200);
    }

    /**
     * Get a list of students with only id and name.
     */
    public function select(Request $request)
    {
        // Start with a base query
        $query = Student::query();
        // Apply filters if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($request->has('city_id')) {
            $query->where('city_id', $request->input('city_id'));
        }

        if ($request->has('university_id')) {
            $query->where('university_id', $request->input('university_id'));
        }

        if ($request->has('service_id')) {
            $query->whereHas('cases', function ($q) use ($request) {
                $q->where('service_id', $request->input('service_id'));
            });
        }

        // Select only id and name
        $students = $query->select('id', 'name')->get();

        // Return the students as a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Students retrieved successfully',
            'data' => $students,
        ]);
    }

    public function requestOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:students,email', // Validate against students table
        ]);

        $student = Student::where('email', $request->email)->first();

        $otp = rand(100000, 999999);
        $student->otp = $otp;
        $student->otp_expires_at = Carbon::now()->addMinutes(10);
        $student->save();

        Mail::to($student->email)->send(new SendOtp($otp));

        return response()->json([
            'success' => true,
            'message' => 'OTP sent to your email.',
        ]);

    }

    public function resetPasswordWithOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:students,email',
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:8',
        ]);

        $student = Student::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('otp_expires_at', '>', Carbon::now())
            ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP.',
            ], 400);
        }

        $student->password = Hash::make($request->password);
        $student->otp = null;
        $student->otp_expires_at = null;
        $student->save();

        return response()->json([
            'success' => true,
            'message' => 'Your password has been reset successfully.',
        ]);
    }
}
