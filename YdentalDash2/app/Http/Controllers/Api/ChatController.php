<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start with a base query
        $query = Chat::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', '%' . $search . '%');
            });
        }

        // Filter by sender ID
        if ($request->has('sender_id')) {
            $query->where('sender_id', $request->input('sender_id'));
        }

        // Filter by receiver ID
        if ($request->has('receiver_id')) {
            $query->where('receiver_id', $request->input('receiver_id'));
        }

        // Sorting
        if ($request->has('sort_by')) {
            $sortField = $request->input('sort_by');
            $sortDirection = $request->input('sort_dir', 'asc'); // Default to ascending
            $query->orderBy($sortField, $sortDirection);
        }

        // Paginate the results
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $chats = $query->paginate($perPage);

        // Return the paginated chats as a JSON response
        return response()->json($chats);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'sender_id' => 'required|integer|exists:users,id',
            'receiver_id' => 'required|integer|exists:users,id',
            'message' => 'required|string',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new chat record
        $chat = Chat::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'sent_at' => now(), // Automatically set the sent_at timestamp
        ]);

        // Return the created chat as a JSON response
        return response()->json($chat, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the chat by ID
        $chat = Chat::find($id);

        // If the chat doesn't exist, return a 404 error
        if (!$chat) {
            return response()->json([
                'message' => 'Chat not found',
            ], 404);
        }

        // Return the chat as a JSON response
        return response()->json($chat);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the chat by ID
        $chat = Chat::find($id);

        // If the chat doesn't exist, return a 404 error
        if (!$chat) {
            return response()->json([
                'message' => 'Chat not found',
            ], 404);
        }

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'sender_id' => 'sometimes|integer|exists:users,id',
            'receiver_id' => 'sometimes|integer|exists:users,id',
            'message' => 'sometimes|string',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update the chat record
        $chat->update([
            'sender_id' => $request->sender_id ?? $chat->sender_id,
            'receiver_id' => $request->receiver_id ?? $chat->receiver_id,
            'message' => $request->message ?? $chat->message,
        ]);

        // Return the updated chat as a JSON response
        return response()->json($chat);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the chat by ID
        $chat = Chat::find($id);

        // If the chat doesn't exist, return a 404 error
        if (!$chat) {
            return response()->json([
                'message' => 'Chat not found',
            ], 404);
        }

        // Delete the chat record
        $chat->delete();

        // Return a success response
        return response()->json([
            'message' => 'Chat deleted successfully',
        ], 204);
    }

    /**
     * Get a list of chats with only id, sender_id, receiver_id, and message.
     */
    public function select(Request $request)
    {
        // Start with a base query
        $query = Chat::query();

        // Apply filters if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('message', 'like', '%' . $search . '%');
        }

        if ($request->has('sender_id')) {
            $query->where('sender_id', $request->input('sender_id'));
        }

        if ($request->has('receiver_id')) {
            $query->where('receiver_id', $request->input('receiver_id'));
        }

        // Select only id, sender_id, receiver_id, and message
        $chats = $query->select('id', 'sender_id', 'receiver_id', 'message')->get();

        // Return the chats as a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Chats retrieved successfully',
            'data' => $chats,
        ]);
    }
}
