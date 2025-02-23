<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Thecase; // Updated model name to match your model
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
        $query = Thecase::with([
            'service',
            'schedules',
            'student:id,name,student_image,city_id,university_id'
        ]);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('procedure', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('student', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('service', function ($q) use ($search) {
                        $q->where('service_name', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->has('service_id')) {
            $query->where('service_id', $request->input('service_id'));
        }

        // If filtering by schedule, use whereHas on the schedules relationship.
        if ($request->has('schedules_id')) {
            $query->whereHas('schedules', function ($q) use ($request) {
                $q->where('id', $request->input('schedules_id'));
            });
        }

        if ($request->has('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        if ($request->has('min_cost')) {
            $query->where('cost', '>=', $request->input('min_cost'));
        }
        if ($request->has('max_cost')) {
            $query->where('cost', '<=', $request->input('max_cost'));
        }

        if ($request->has('min_age')) {
            $query->where('min_age', '>=', $request->input('min_age'));
        }
        if ($request->has('max_age')) {
            $query->where('max_age', '<=', $request->input('max_age'));
        }

        if ($request->has('student_id')) {
            $query->where('student_id', $request->input('student_id'));
        }

        if ($request->has('city_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('city_id', $request->input('city_id'));
            });
        }

        if ($request->has('university_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('university_id', $request->input('university_id'));
            });
        }

        if ($request->has('sort_by')) {
            $sortField = $request->input('sort_by');
            $sortDirection = $request->input('sort_dir', 'asc'); // Default ascending
            $query->orderBy($sortField, $sortDirection);
        }

        $perPage = $request->input('per_page', 10); // Default 10 per page
        $cases = $query->paginate($perPage);

        return response()->json($cases);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data.
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|exists:services,id',
            'procedure' => 'required|string|max:255',
            'gender' => 'required|string',
            'description' => 'required|string',
            'cost' => 'required|numeric',
            'min_age' => 'required|integer',
            'max_age' => 'required|integer',
            'student_id' => 'nullable|exists:students,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new case record.
        $case = Thecase::create([
            'service_id' => $request->service_id,
            'procedure' => $request->procedure,
            'gender' => $request->gender,
            'description' => $request->description,
            'cost' => $request->cost,
            'min_age' => $request->min_age,
            'max_age' => $request->max_age,
            'student_id' => $request->student_id,
        ]);

        return response()->json($case, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $case = Thecase::with('student')->find($id);

        if (!$case) {
            return response()->json([
                'message' => 'Case not found',
            ], 404);
        }

        return response()->json($case);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $case = Thecase::find($id);

        if (!$case) {
            return response()->json([
                'message' => 'Case not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'service_id' => 'sometimes|exists:services,id',
            'procedure' => 'sometimes|string|max:255',
            'gender' => 'sometimes|string',
            'description' => 'sometimes|string',
            'cost' => 'sometimes|numeric',
            'min_age' => 'sometimes|integer',
            'max_age' => 'sometimes|integer',
            'student_id' => 'nullable|exists:students,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

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

        return response()->json($case);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $case = Thecase::find($id);

        if (!$case) {
            return response()->json([
                'message' => 'Case not found',
            ], 404);
        }

        $case->delete();

        return response()->json([
            'message' => 'Case deleted successfully',
        ], 204);
    }

    /**
     * Create a case along with a schedule.
     */
    public function createCaseWithSchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|exists:services,id',
            'procedure' => 'required|string|max:255',
            'gender' => 'required|string',
            'description' => 'required|string',
            'cost' => 'required|numeric',
            'min_age' => 'required|integer',
            'max_age' => 'required|integer',
            'student_id' => 'nullable|exists:students,id',

            // Now expecting an array of schedules
            'schedules' => 'required|array|min:1',
            'schedules.*.available_date' => 'required|date',
            'schedules.*.available_time' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            // First, create the case record.
            $case = Thecase::create([
                'service_id' => $request->service_id,
                'procedure' => $request->procedure,
                'gender' => $request->gender,
                'description' => $request->description,
                'cost' => $request->cost,
                'min_age' => $request->min_age,
                'max_age' => $request->max_age,
                'student_id' => $request->student_id,
            ]);

            // Then, create each schedule record and link them to the case.
            $schedules = [];
            foreach ($request->schedules as $scheduleData) {
                $schedules[] = Schedule::create([
                    'available_date' => $scheduleData['available_date'],
                    'available_time' => $scheduleData['available_time'],
                    'thecase_id' => $case->id,
                ]);
            }

            DB::commit();

            return response()->json([
                'case' => $case,
                'schedules' => $schedules,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error creating records',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Update a case along with its schedule.
     */
    public function updateCaseWithSchedule(Request $request, $caseId)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'sometimes|required|exists:services,id',
            'procedure' => 'sometimes|required|string|max:255',
            'gender' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'cost' => 'sometimes|required|numeric',
            'min_age' => 'sometimes|required|integer',
            'max_age' => 'sometimes|required|integer',
            'student_id' => 'nullable|exists:students,id',

            // Expect an array of schedules.
            'schedules' => 'sometimes|required|array|min:1',
            'schedules.*.id' => 'sometimes|exists:schedules,id',
            'schedules.*.available_date' => 'required|date',
            'schedules.*.available_time' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $case = Thecase::findOrFail($caseId);

            // Update the case record.
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

            // Process the list of schedules.
            $schedulesData = $request->input('schedules', []);
            $updatedScheduleIds = [];

            foreach ($schedulesData as $scheduleItem) {
                if (isset($scheduleItem['id'])) {
                    // Update existing schedule record.
                    $schedule = Schedule::findOrFail($scheduleItem['id']);

                    // Optionally ensure the schedule belongs to the case.
                    if ($schedule->thecase_id != $case->id) {
                        // Here you could throw an exception or simply skip this item.
                        continue;
                    }

                    $schedule->update([
                        'available_date' => $scheduleItem['available_date'],
                        'available_time' => $scheduleItem['available_time'],
                    ]);
                } else {
                    // Create a new schedule record linked to this case.
                    $schedule = Schedule::create([
                        'available_date' => $scheduleItem['available_date'],
                        'available_time' => $scheduleItem['available_time'],
                        'thecase_id' => $case->id,
                    ]);
                }
                $updatedScheduleIds[] = $schedule->id;
            }

            // Optionally, remove any schedules that belong to the case but were not included in the update request.
            // Comment out the next block if you do not want to delete missing schedules.
            if (!empty($updatedScheduleIds)) {
                $case->schedules()->whereNotIn('id', $updatedScheduleIds)->delete();
            }

            DB::commit();

            // Reload the case schedules for a fresh response.
            $case->load('schedules');

            return response()->json([
                'case' => $case,
                'schedules' => $case->schedules,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error updating records',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Retrieve a list of cases with specific fields.
     */
    public function select(Request $request)
    {
        $query = Thecase::query();

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

        $cases = $query->select('id', 'procedure', 'service_id', 'gender')->get();

        return response()->json([
            'success' => true,
            'message' => 'Cases retrieved successfully',
            'data' => $cases,
        ]);
    }
}
