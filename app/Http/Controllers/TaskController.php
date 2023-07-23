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
            $ordered_data = json_decode($input["order"], true);
            $succ = false;
            foreach ($ordered_data as $ordered) {
                $id = $ordered[1];
                $priority = $ordered[0];
                Task::where('id', $id)->update(['priority' => $priority]);
                $succ = true;
            }
            if ($succ === true) {
                return $this->resp(true, 200, 'Priorities swapped successfully', []);
            }
            return $this->resp(false, 500, 'Invalid data provided', []);
        } else {
            return $this->resp(false, 409, 'No request received from client', []);
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
            if (!$task) {
                return $this->resp(false, 404, 'Task not found.', []);
            }
           if($task->delete()){
               return $this->resp(true, 200, 'Task successfully deleted.', []);
            }
            return $this->resp(false, 500, 'Task could not be deleted. Try again later.', []);
        } catch (\Throwable $th) {
            return $this->resp(false, 500, 'An error occurred', []);
        }
       
    }

   
}
