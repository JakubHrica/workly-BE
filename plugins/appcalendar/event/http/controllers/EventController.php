<?php namespace AppCalendar\Event\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use AppCalendar\Event\Models\Event;
use Exception;

class EventController extends Controller
{
    public function newEvent(Request $request)
    {
        // Retrieve the authenticated user
        $authUser = $request->user;

        // Extract only the necessary fields from the request
        $data = $request->post();

        // Validate that the required fields are present
        if (empty($data)) {
            throw new Exception('Missing event data', 400);
        }

        // Create a new Event
        $event = new Event();
        $event->user_id = $authUser->id; // Assign the authenticated user's ID to the event
        $event->fill($data); // Fill the event with the provided data
        $event->save(); // Save the event to the database

        // Return a success response with the created event
        return[
            'event' => $event
        ];
    }

    public function updateEvent(Request $request, $event_id)
    {
        // Retrieve the authenticated user
        $authUser = $request->user;

        // Use the event ID from the route parameter and get all input data
        $data = $request->post();

        // Validate that the required fields are present
        if (empty($data)) {
            throw new Exception('Missing event data', 400);
        }

        // Find the event that belongs to the authenticated user and matches the provided ID
        $event = Event::where('user_id', $authUser->id)
        ->where('id', $event_id)
        ->first();

        // If the event is not found, throw an exception with a 404 status code
        if (!$event) {
            throw new Exception('Event not found', 404);
        }

        // Update the event with the new data
        $event->update($data);

        // Return a success response with the updated event
        return [
            'event' => $event
        ];
    }

    public function deleteEvent(Request $request, $event_id)  // This isn't working, IDK why
    {
        // Retrieve the authenticated user
        $authUser = $request->user;

        // Use the event ID from the route parameter
        $data = $request->post();

        // Validate that the required fields are present
        if (empty($data)) {
            throw new Exception('Missing event data', 400);
        }

        // Find the event that belongs to the authenticated user and matches the provided ID
        $event = Event::where('user_id', $authUser->id)
        ->where('id', $event_id)
        ->first();

        // Check if the event exists
        if (!$event) {
            // If the event is not found, throw an exception with a 404 status code
            throw new Exception('Event not found', 404);
        }

        // Delete the event
        $event->delete();

        // Return a success response indicating the event was deleted
        return null;
    }
}
