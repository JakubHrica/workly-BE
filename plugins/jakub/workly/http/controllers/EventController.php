<?php namespace Jakub\Workly\Http\Controllers;

use Jakub\Workly\Models\Event;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class EventController
{
    private $key = 'workly_default_token';  // Key for JWT encoding/decoding

    public function getEvents(Request $request)
    {
        try {
            // Duplicate code: Extract user ID from token
            $userId = $this->getUserIdFromToken($request);

            // Get filter parameters from the request
            $fromDatetime = $request->query('from_datetime');
            $toDatetime = $request->query('to_datetime');

            // Build the query to fetch events for the user with optional filtering
            $query = Event::where('user_id', $userId);

            if ($fromDatetime && $toDatetime) {
                // Ensure events that overlap with the given date range are included
                $query->where(function ($q) use ($fromDatetime, $toDatetime) {
                    $q->whereBetween('start_datetime', [$fromDatetime, $toDatetime])
                      ->orWhereBetween('end_datetime', [$fromDatetime, $toDatetime])
                      ->orWhere(function ($q2) use ($fromDatetime, $toDatetime) {
                          $q2->where('start_datetime', '<=', $fromDatetime)
                             ->where('end_datetime', '>=', $toDatetime);
                      });
                });
            }

            $events = $query->get();

            if ($events->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No events were set already'], 200);
            }

            return response()->json(['events' => $events], 200);
        } catch (\Exception $e) {
            // Duplicate code: Handle server error
            return $this->handleServerError($e);
        }
    }

    public function newEvent(Request $request)
    {
        try {
            // Duplicate code: Extract user ID from token
            $userId = $this->getUserIdFromToken($request);

            $data = $request->all();

            // Create a new event
            $event = new Event();
            $event->user_id = $userId;
            $event->fill($data);
            $event->save();

            return response()->json(['message' => 'Event created successfully'], 201);
        } catch (\Exception $e) {
            // Duplicate code: Handle server error
            return $this->handleServerError($e);
        }
    }

    public function updateEvent(Request $request)
    {
        try {
            // Duplicate code: Extract user ID from token
            $userId = $this->getUserIdFromToken($request);

            $oldData = $request->input('oldData');
            $newData = $request->input('newData');

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

            if (!$event) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Event not found or you do not have permission to update it'
                ], 404);
            }

            // Update the event with the new data
            $event->update($newData);

            return response()->json([
                'status' => 'success',
                'message' => 'Event updated successfully',
                'event' => $event
            ], 200);
        } catch (\Exception $e) {
            // Duplicate code: Handle server error
            return $this->handleServerError($e);
        }
    }

    public function deleteEvent(Request $request)
    {
        try {
            // Duplicate code: Extract user ID from token
            $userId = $this->getUserIdFromToken($request);

            $data = $request->all();

            // Find the event by user ID and old data
            $event = Event::where('user_id', $userId)
                ->where('start_datetime', $data['start_datetime'])
                ->where('end_datetime', $data['end_datetime'])
                ->where('title', $data['title'])
                ->where('description', $data['description'])
                ->where('type', $data['type'])
                ->first();

            if (!$event) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Event not found or you do not have permission to delete it'
                ], 404);
            }

            // Delete the event
            $event->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Event deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            // Duplicate code: Handle server error
            return $this->handleServerError($e);
        }
    }

    private function getUserIdFromToken(Request $request)
    {
        $authHeader = $request->header('Authorization');
        if (!$authHeader) {
            throw new \Exception('Authorization token not provided', 401);
        }

        $token = str_replace('Bearer ', '', $authHeader);
        $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
        return $decoded->sub;
    }

    private function handleServerError(\Exception $e)
    {
        return response()->json([
            'error' => 'Server error',
            'message' => $e->getMessage()
        ], 500);
    }
}
