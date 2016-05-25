<?php

namespace App\Http\Controllers;

use App\Task;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\TaskRepository;

class TaskController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */

     /**
     * The task repository instance.
     *
     * @var TaskRepository
     */

     protected $tasks;

     public function __construct(TaskRepository $tasks)
     {
     	$this->middleware('auth');
     	$this->tasks = $tasks;
     }

     public function index(Request $request)
     {
     	//$tasks = Task::where('user_id', $request->user()->id)->get();
     	return view('tasks.index', [
            'tasks' => $this->tasks->forUser($request->user()),   
     	]);
     }

     public function store(Request $request)
     {
     	$this->validate($request, [
            'name'=>'required|max:255',
        ]);

        //create task
        $request->user()->tasks()->create([
            'name'=>$request->name,
        ]);

        return redirect('/tasks');
     }

		 /**
		 * Destroy the given task.
		 *
		 * @param  Request  $request
		 * @param  string  $taskId
		 * @return Response
		 */
		public function destroy(Request $request, Task $task)
		{
		    $this->authorize('destroy', $task);
		    $task->delete();

            return redirect('/tasks');
		}
}
