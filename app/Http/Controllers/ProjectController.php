<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Trait\Utils;

class ProjectController extends Controller
{
    use Utils;
    public function edit($id)
    {
        $project = Project::where('id', $id)->first();
        return view('tasks.edit_project', [
            'project' => $project,
        ]); 
    }

    public function update(Request $request, $id)  {
        try {
            $validator = Validator::make($request->all(), [
                'project_name' => ['required', 'string'],
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            Project::where('id', $id)->update([
                'project_name' => $request->project_name,
            ]);
            return redirect()->route('task.index')
            ->withSuccess(__('Project Updated successfully.'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } 
    }

    public function destroy()
    {
        try {
            $project = project::find(request('id'));
            $project->delete();
            return $this->resp(true, 200, 'Project successfully deleted.', []);
        } catch (\Throwable $th) {
            return $this->resp(false, 500, 'An error occurred', []);
        }
       
    }
}
