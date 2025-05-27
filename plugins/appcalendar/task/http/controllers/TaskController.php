<?php namespace AppCalendar\Task\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use AppCalendar\Task\Models\Task;
use Exception;

class TaskController extends Controller
{
    public function newTask(Request $request)
    {
        // Retrieve the authenticated user
        $authUser = $request->user;

        // Extract only the necessary fields from the request
        $data = $request->post();

        // Validate that the required fields are present
        if (empty($data)) {
            throw new Exception('Missing task data', 400);
        }

        // Create a new Event
        $task = new Task();
        $task->user_id = $authUser->id; // Assign the authenticated user's ID to the task
        $task->fill($data); // Fill the task with the provided data
        $task->save(); // Save the task to the database

        // Return a success response with the created task
        return[
            'event' => $task
        ];
    }

    public function updateTask(Request $request, $taskId)
    {
        // Retrieve the authenticated user
        $authUser = $request->user;

        // Use the event ID from the route parameter and get all input data
        $data = $request->post();

        if (empty($data)) {
            throw new Exception('Missing task data', 400);
        }

        // Find the event that belongs to the authenticated user and matches the provided ID
        $task = Task::where('user_id', $authUser->id)
        ->where('id', $taskId)
        ->first();

        if (!$task) {
            throw new Exception('Task not found', 404);
        }

        // Update the event with the new data
        $task->update($data);

        // Return a success response with the updated event
        return[
            'event' => $task
        ];
    }

    public function deleteTask(Request $request, $taskId)
    {
        // Retrieve the authenticated user
        $authUser = $request->user;

        // Use the event ID from the route parameter
        if (empty($taskId)) {
            throw new Exception('Missging task ID', 400);
        }

        // Find the event that belongs to the authenticated user and matches the provided ID
        $task = Task::where('user_id', $authUser->id)
        ->where('id', $taskId)
        ->first();

        // If the event is not found, throw an exception
        if (!$task) {
            throw new Exception('Task not found', 404);
        }

        // Delete the event
        $task->delete();

        // Return a success response indicating the event was deleted
        return null;
    }
}
