<?php namespace AppCalendar\Event\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use AppCalendar\Event\Models\Event;
use Exception;

class EventController extends Controller
{
    public function getEvents(Request $request)
    {
        try {
            // Retrieve the authenticated user
            $authUser = $request->user;

            // Retrieve all data from the request
            $data = $request->all();

            // Create a query for events specific to the user
            $query = Event::where('user_id', $authUser->id);

            // If "from" and "to" dates are provided, add conditions to the query
            if (!empty($data['from_datetime']) && !empty($data['to_datetime'])) {
                $from = $data['from_datetime'];
                $to = $data['to_datetime'];
                $query->where(function ($q) use ($from, $to) {
                    $q->whereBetween('start_datetime', [$from, $to]) // Events starting within the specified range
                    ->orWhereBetween('end_datetime', [$from, $to]) // Events ending within the specified range
                    ->orWhere(function ($q2) use ($from, $to) {
                    $q2->where('start_datetime', '<=', $from) // Events starting before "from"
                        ->where('end_datetime', '>=', $to);   // and ending after "to"
                    });
                });
            }

            // Retrieve events based on the query
            $events = $query->get();

            // If no events are found, return an error response
            if ($events->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No events found for the specified criteria'
                ], 404);
            }

            // Return a successful response with the retrieved events
            return response()->json([
                'status' => 'success',
                'message' => 'Events retrieved successfully',
                'events' => $events
            ]);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Failed to get the events');
        }
    }

    public function newEvent(Request $request)
    {
        try {
            // Retrieve the authenticated user
            $authUser = $request->user;

            // Extract only the necessary fields from the request
            $data = $request->post();

            // Create a new Event
            $event = new Event();
            $event->user_id = $authUser->id; // Assign the authenticated user's ID to the event
            $event->fill($data); // Fill the event with the provided data
            $event->save(); // Save the event to the database

            // Return a success response with the created event
            return response()->json([
                'status' => 'success',
                'message' => 'Event created successfully',
                'event' => $event
            ], 201);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Failed to create the event');
        }
    }

    public function updateEvent(Request $request, $event_id)
    {
        try {
            // Retrieve the authenticated user
            $authUser = $request->user;

            // Use the event ID from the route parameter and get all input data
            $data = $request->all();

            // Validate that data is provided
            if (empty($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Missing new event data'
                ], 400);
            }

            // Find the event that belongs to the authenticated user and matches the provided ID
            $event = Event::where('user_id', $authUser->id)
            ->where('id', $event_id)
            ->first();

            // If the event is not found, return an error response
            if (!$event) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Event not found'
                ], 404);
            }

            // Update the event with the new data
            $event->update($data);

            // Return a success response with the updated event
            return response()->json([
                'status' => 'success',
                'message' => 'Event updated successfully',
                'event' => $event
            ]);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Failed to update the event');
        }
    }

    public function deleteEvent(Request $request, $event_id)  // This isn't working, IDK why
    {
        try {
            // Retrieve the authenticated user
            $authUser = $request->user;

            // Validate that the event ID is provided
            if (!$event_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Missing event ID'
                ], 400);
            }

            // Find the event that belongs to the authenticated user and matches the provided ID
            $event = Event::where('user_id', $authUser->id)
            ->where('id', $event_id)
            ->first();

            // If the event is not found, return an error response
            if (!$event) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Event not found'
                ], 404);
            }

            // Delete the event
            $event->delete();

            // Return a success response indicating the event was deleted
            return response()->json([
                'status' => 'success',
                'message' => 'Event deleted successfully'
            ]);
        } catch (Exception $e) {
            return $this->handleException($e, 'Failed to delete the event');
        }
    }

    private function handleException(Exception $e, $defaultMessage)
    {
        return response()->json([
            'error' => $defaultMessage,
            'message' => $e->getMessage()
        ], 500);
    }
}
