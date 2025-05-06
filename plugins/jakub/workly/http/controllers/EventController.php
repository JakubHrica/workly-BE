<?php namespace Jakub\Workly\Http\Controllers;

use Jakub\Workly\Models\Event;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class EventController
{
    private $key = 'workly_default_token';  // Key for JWT encoding/decoding

    // Method to retrieve events for a user with optional filtering
    public function getEvents(Request $request)
    {
        try {
            // Extract user ID from the JWT token
            $userId = $this->getUserIdFromToken($request);

            // Get filter parameters from the request
            $fromDatetime = $request->query('from_datetime');
            $toDatetime = $request->query('to_datetime');

            // Build the query to fetch events for the user
            $query = Event::where('user_id', $userId);

            // Apply date range filtering if provided
            if ($fromDatetime && $toDatetime) {
                $query->where(function ($q) use ($fromDatetime, $toDatetime) {
                    $q->whereBetween('start_datetime', [$fromDatetime, $toDatetime])
                      ->orWhereBetween('end_datetime', [$fromDatetime, $toDatetime])
                      ->orWhere(function ($q2) use ($fromDatetime, $toDatetime) {
                          $q2->where('start_datetime', '<=', $fromDatetime)
                             ->where('end_datetime', '>=', $toDatetime);
                      });
                });
            }

            // Execute the query and retrieve events
            $events = $query->get();

            // Return error response if no events are found
            if ($events->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No events found for the specified criteria'
                ], 404);
            }

            // Return success response with the events
            return response()->json([
                'status' => 'success',
                'message' => 'Events retrieved successfully',
                'events' => $events
            ], 200);
        } catch (\Exception $e) {
            // Handle server errors
            return $this->handleServerError($e);
        }
    }

    // Method to create a new event
    public function newEvent(Request $request)
    {
        try {
            // Extract user ID from the JWT token
            $userId = $this->getUserIdFromToken($request);

            // Get event data from the request
            $data = $request('start_datetime', 'end_datetime', 'title', 'description', 'type');

            // Create and save a new event
            $event = new Event();
            $event->user_id = $userId;
            $event->fill($data);
            $event->save();

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Event created successfully'
            ], 201);
        } catch (\Exception $e) {
            // Handle server errors
            return $this->handleServerError($e);
        }
    }

    // Method to update an existing event
    public function updateEvent(Request $request)
    {
        try {
            // Extract user ID from the JWT token
            $userId = $this->getUserIdFromToken($request);

            // Get old and new event data from the request
            $oldData = $request->input('oldData');
            $newData = $request->input('newData');

            // Validate input data
            if (!$oldData || !$newData) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid input data'
                ], 400);
            }

            // Find the event by user ID and old data
            $event = Event::where('user_id', $userId)
                ->where('start_datetime', $oldData['start_datetime'])
                ->where('end_datetime', $oldData['end_datetime'])
                ->where('title', $oldData['title'])
                ->where('description', $oldData['description'])
                ->where('type', $oldData['type'])
                ->first();

            // Return error response if the event is not found
            if (!$event) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Event not found or you do not have permission to update it'
                ], 404);
            }

            // Update the event with the new data
            $event->update($newData);

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Event updated successfully',
                'event' => $event
            ], 200);
        } catch (\Exception $e) {
            // Handle server errors
            return $this->handleServerError($e);
        }
    }

    // Method to delete an event
    public function deleteEvent(Request $request)
    {
        try {
            // Extract user ID from the JWT token
            $userId = $this->getUserIdFromToken($request);

            // Get event data from the request
            $data = $request->all();

            // Find the event by user ID and data
            $event = Event::where('user_id', $userId)
                ->where('start_datetime', $data['start_datetime'])
                ->where('end_datetime', $data['end_datetime'])
                ->where('title', $data['title'])
                ->where('description', $data['description'])
                ->where('type', $data['type'])
                ->first();

            // Return error response if the event is not found
            if (!$event) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Event not found or you do not have permission to delete it'
                ], 404);
            }

            // Delete the event
            $event->delete();

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Event deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            // Handle server errors
            return $this->handleServerError($e);
        }
    }

    // Helper method to extract user ID from the JWT token
    private function getUserIdFromToken(Request $request)
    {
        $authHeader = $request->header('Authorization');
        if (!$authHeader) {
            throw new \Exception('Authorization token not provided', 401);
        }

        // Decode the JWT token
        $token = str_replace('Bearer ', '', $authHeader);
        $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
        return $decoded->sub;
    }

    // Helper method to handle server errors
    private function handleServerError(\Exception $e)
    {
        return response()->json([
            'error' => 'Server error',
            'message' => $e->getMessage()
        ], 500);
    }
}
