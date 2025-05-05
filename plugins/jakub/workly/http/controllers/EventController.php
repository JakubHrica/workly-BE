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
            return $this->handleServerError($e);
        }
    }

    public function newEvent(Request $request)
    {
        try {
            $userId = $this->getUserIdFromToken($request);

            $data = $request->all();

            // Create a new event
            $event = new Event();
            $event->user_id = $userId;
            $event->fill($data);
            $event->save();

            return response()->json(['message' => 'Event created successfully'], 201);
        } catch (\Exception $e) {
            return $this->handleServerError($e);
        }
    }

    public function updateEvent(Request $request)
    {
        try {
            // Your code here
        } catch (\Exception $e) {
            return $this->handleServerError($e);
        }
    }

    public function deleteEvent(Request $request)
    {
        try {
            // Your code here
        } catch (\Exception $e) {
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
