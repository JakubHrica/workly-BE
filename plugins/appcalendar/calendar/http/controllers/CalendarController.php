<?php namespace AppCalendar\Calendar\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use AppCalendar\Event\Models\Event;
use AppCalendar\Task\Models\Task;
use Exception;

class CalendarController extends Controller
{
    public function getCalendar(Request $request)
    {
        // Get the authenticated user from the request
        $authUser = $request->user;

        // Get all POST data from the request
        $data = $request->post();

        // Extract the date range from the request data
        $from = $data['from_datetime'];
        $to = $data['to_datetime'];

        // Validate that both dates are provided
        if (!$from || !$to) {
            throw new Exception('Missing date range', 400);
        }

        // Build base queries for events and tasks for the current user
        $eventQuery = Event::where('user_id', $authUser->id);
        $taskQuery = Task::where('user_id', $authUser->id);

        // If both dates are set, filter events and tasks that overlap with the range
        if ($from && $to) {
            $eventQuery->where(function ($q) use ($from, $to) {
                // Event starts or ends within the range, or spans the entire range
                $q->whereBetween('start_datetime', [$from, $to])
                    ->orWhereBetween('end_datetime', [$from, $to])
                    ->orWhere(function ($q2) use ($from, $to) {
                        $q2->where('start_datetime', '<=', $from)
                            ->where('end_datetime', '>=', $to);
                    });
            });

            $taskQuery->where(function ($q) use ($from, $to) {
                // Task starts or ends within the range, or spans the entire range
                $q->whereBetween('start_datetime', [$from, $to])
                    ->orWhereBetween('end_datetime', [$from, $to])
                    ->orWhere(function ($q2) use ($from, $to) {
                        $q2->where('start_datetime', '<=', $from)
                            ->where('end_datetime', '>=', $to);
                    });
            });
        }

        // Fetch events, add a 'type' property to each
        $events = $eventQuery->get()->map(function ($event) {
            $event->type = 'event';
            return $event;
        });

        // Fetch tasks, add a 'type' property to each
        $tasks = $taskQuery->get()->map(function ($task) {
            $task->type = 'task';
            return $task;
        });

        // Merge events and tasks into a single collection
        $items = $events->merge($tasks);

        // If no items found, throw a 404 exception
        if ($items->isEmpty()) {
            throw new Exception('No calendar items found for this specific criteria', 404);
        }

        // Return the merged items as a response
        return [
            'items' => $items
        ];
    }
}