<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start with a base query
        $query = Review::query()->with([
            'patient:id,name',
            'student:id,name'
        ]);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', '%' . $search . '%');
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

        // Filter by rating
        if ($request->has('rating')) {
            $query->where('rating', $request->input('rating'));
        }

        // Sorting
        if ($request->has('sort_by')) {
            $sortField = $request->input('sort_by');
            $sortDirection = $request->input('sort_dir', 'asc'); // Default to ascending
            $query->orderBy($sortField, $sortDirection);
        }

        // Paginate the results
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $reviews = $query->paginate($perPage);

        // Return the paginated reviews as a JSON response
        return response()->json($reviews);
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
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:255',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new review record
        $review = Review::create([
            'patient_id' => $request->patient_id,
            'student_id' => $request->student_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Return the created review as a JSON response
        return response()->json($review, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the review by ID
        $review = Review::find($id);

        // If the review doesn't exist, return a 404 error
        if (!$review) {
            return response()->json([
                'message' => 'Review not found',
            ], 404);
        }

        // Return the review as a JSON response
        return response()->json($review);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the review by ID
        $review = Review::find($id);

        // If the review doesn't exist, return a 404 error
        if (!$review) {
            return response()->json([
                'message' => 'Review not found',
            ], 404);
        }

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'patient_id' => 'sometimes|exists:patients,id',
            'student_id' => 'sometimes|exists:students,id',
            'rating' => 'sometimes|integer|between:1,5',
            'comment' => 'sometimes|string|max:255',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update the review record
        $review->update([
            'patient_id' => $request->patient_id ?? $review->patient_id,
            'student_id' => $request->student_id ?? $review->student_id,
            'rating' => $request->rating ?? $review->rating,
            'comment' => $request->comment ?? $review->comment,
        ]);

        // Return the updated review as a JSON response
        return response()->json($review);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the review by ID
        $review = Review::find($id);

        // If the review doesn't exist, return a 404 error
        if (!$review) {
            return response()->json([
                'message' => 'Review not found',
            ], 404);
        }

        // Delete the review record
        $review->delete();

        // Return a success response
        return response()->json([
            'message' => 'Review deleted successfully',
        ], 204);
    }

    /**
     * Get a list of reviews with only id, patient_id, student_id, rating, and comment.
     */
    public function select(Request $request)
    {
        // Start with a base query
        $query = Review::query()->with([
            'patient:id,name',
            'student:id,name'
        ]);

        // Apply filters if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('comment', 'like', '%' . $search . '%');
        }

        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->input('patient_id'));
        }

        if ($request->has('student_id')) {
            $query->where('student_id', $request->input('student_id'));
        }

        if ($request->has('rating')) {
            $query->where('rating', $request->input('rating'));
        }

        // Select only id, patient_id, student_id, rating, and comment
        $reviews = $query->select('id', 'patient_id', 'student_id', 'rating', 'comment')->get();

        // Return the reviews as a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Reviews retrieved successfully',
            'data' => $reviews,
        ]);
    }
}
