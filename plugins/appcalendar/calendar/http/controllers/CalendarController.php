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
        $authUser = $request->user;
        $data = $request->post();

        $from = $data['from_datetime'];
        $to = $data['to_datetime'];

        if (!$from || !$to) {
            throw new Exception('Missing date range', 400);
        }

        $eventQuery = Event::where('user_id', $authUser->id);
        $taskQuery = Task::where('user_id', $authUser->id);

        if ($from && $to) {
            $eventQuery->where(function ($q) use ($from, $to) {
                $q->whereBetween('start_datetime', [$from, $to])
                    ->orWhereBetween('end_datetime', [$from, $to])
                    ->orWhere(function ($q2) use ($from, $to) {
                        $q2->where('start_datetime', '<=', $from)
                            ->where('end_datetime', '>=', $to);
                    });
            });

            $taskQuery->where(function ($q) use ($from, $to) {
                $q->whereBetween('start_datetime', [$from, $to])
                    ->orWhereBetween('end_datetime', [$from, $to])
                    ->orWhere(function ($q2) use ($from, $to) {
                        $q2->where('start_datetime', '<=', $from)
                            ->where('end_datetime', '>=', $to);
                    });
            });
        }

        $events = $eventQuery->get()->map(function ($event) {
            $event->type = 'event';
            return $event;
        });

        $tasks = $taskQuery->get()->map(function ($task) {
            $task->type = 'task';
            return $task;
        });

        $items = $events->merge($tasks);

        throw new Exception('No calendar items found for this specific criteria', 404);

        return[
            'items' => $items
        ];
    }
}
