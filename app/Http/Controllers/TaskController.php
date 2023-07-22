<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Trait\Utils;

class TaskController extends Controller
{
    use Utils;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::orderBy('id')->get();
        return view('home', [
            'projects' => $projects,
        ]);
    }

    public function getTasksByProjectId(Request $request) {
        $project = Project::find($request->project_id);
        $tasks = $project->tasks()->orderBy('priority')->get();
        if ($tasks !== null) {
            return $this->resp(true, 200, 'Successful', $tasks);
        }
        return $this->resp(false, 404, 'No Available Task for this Project', []);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::orderBy('id')->get();
        return view('tasks.create', [
            'projects' => $projects,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'project_option' => ['required', 'string'],
                'task_name' => ['required', 'string'],
                'priority' => ['required', 'string'],
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        
            if ($request->project_option == 1) {
                $project = Project::create([
                    'project_name' => $request->project_name1,
                ]);
            } else if ($request->project_option == 2) {
                $project = Project::find($request->project_name2);
            } else {
                return redirect()->route('task.index')
                    ->withErrors(__('Task Added Successfully'));
            }
        
            if ($project) {
                $project->tasks()->create([
                    'task_name' => $request->task_name,
                    'priority' => $request->priority
                ]);
            }
        
            return redirect()->route('task.index')
                ->withSuccess(__('Task Added Successfully'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } 
    }  

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return view('tasks.show', [
            'tasks' => $task,
           
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $task = Task::where('id', $id)->first();
   
        return view('tasks.edit', [
            'task' => $task,
            'project' => $task->project,
            'projects' => Project::all(),
        ]);
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        try {
            $validator = Validator::make($request->all(), [
                'project_id' => ['required', 'string'],
                'task_name' => ['required', 'string'],
                'priority' => ['required', 'string'],
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            Task::where('id', $id)->update([
                'task_name' => $request->task_name,
                'priority' => $request->priority,
                'project_id'=> $request->project_id
            
            ]);
            return redirect()->route('task.index')
            ->withSuccess(__('Task Updated successfully.'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } 
    }

    public function updateOrder(Request $request)
    {
        $input = $request->all();
        if (isset($input["order"])) {
            $order_task = explode(",", $input["order"]);
    
            $tasks = Task::whereIn('id', $order_task)->get();
            if (count($tasks) >= 2) {
                foreach ($tasks as $index => $task) {
                    if ($index === count($tasks) - 1) {
                        continue;
                    }
                    // Store the current priority in a temp variable
                    $tempPriority = $task->priority;
                    // swap priorities
                    $task->priority = $tasks[$index + 1]->priority;
                    $tasks[$index + 1]->priority = $tempPriority;
    
                    $task->save();
                    $tasks[$index + 1]->save();
                }

                return $this->resp(true, 200, 'Priorities swapped successfully', []);
            } else {
                return $this->resp(false, 400, 'At least two tasks are required to swap priorities', []);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $task = Task::find($id);
            $task->delete();
            return $this->resp(true, 200, 'Task successfully deleted.', []);
        } catch (\Throwable $th) {
            return $this->resp(false, 500, 'An error occurred', []);
        }
       
    }

   
}
