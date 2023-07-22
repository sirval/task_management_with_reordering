@extends('layouts.app')
@section('css')
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('/assets/css/admin_css.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <div style="padding-right: 20% !important; padding-left: 20% !important; justify-content: center" class="container-fluid px-4">
        <h1 class="mt-4">Task</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Tasks | New Task</li>
        </ol>
        
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Create New Task
            </div>
                
            <div class="card-body">
              @include('layouts.partials.messages')
                <form class="was-validated" action="{{ route('task.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                      <div class="mb-4">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="project_option" id="new_project_radio" value="1" checked>
                          <label class="form-check-label" for="new_project_radio">New Project</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="project_option" id="existing_project_radio" value="2">
                          <label class="form-check-label" for="existing_project_radio">Select Existing Project</label>
                        </div>
                      </div>
                    

                      
                      <select style="dsplay: none;" class="form-select form-select-sm" aria-label=".form-select-sm example" id="existing_project" name="project_name2">
                        <option selected value="">--Select Project--</option>
                        @foreach ($projects as $row)
                            <option value="{{ $row->id }}">{{ $row->project_name }}</option>
                        @endforeach
                      </select>
                      <input type="text" class="form-control" name="project_name1" id="new_project" required>
                    
                      
                    </div>
                    <div class="mb-3">
                      <label for="validationCustom01" class="form-label">Task Name</label>
                      <input type="text" class="form-control" name="task_name" id="validationCustom01" required>
                      <div class="invalid-feedback">
                          New Task Name is Required!
                      </div>
                      <div class="valid-feedback">
                        Nice one! 👍
                      </div>
                    </div>
                    <div class="mb-3">
                      <label for="validationTextarea" class="form-label">Priority</label>
                      <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="priority">
                        <option selected value="">--Select Priority</option>
                        <option value="1">High</option>
                        <option value="2">Middle</option>
                        <option value="3">Normal</option>
                        <option value="4">Low</option>
                      </select>
                      <div class="invalid-feedback">
                        Please enter New Task in the textarea.
                      </div>
                      <div class="valid-feedback">
                        Nice one! 👍
                      </div>
                    </div>
                    <div style="float: right" class="mb-3">
                      <button class="btn btn-primary" type="submit">Create</button>
                    </div>
                    <div style="float: left" class="mb-3">
                      <a href="javascript:void()" onclick="redirect()" class="btn btn-danger" >Back</a>
                    </div>
                  </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" ></script>

  <script>
    const newProjectRadio = document.getElementById('new_project_radio');
    const existingProjectRadio = document.getElementById('existing_project_radio');
    const existingProject = document.getElementById('existing_project');
    const newProject = document.getElementById('new_project');

    function toggleDivs() {
      if (newProjectRadio.checked) {
        newProject.style.display = 'block';
        existingProject.style.display = 'none';
        newProject.required = true;
        existingProject.removeAttribute('required');
      } else if (existingProjectRadio.checked) {
        newProject.style.display = 'none';
        existingProject.style.display = 'block';
        existingProject.required = true;
        newProject.removeAttribute('required');
      }
    }

    newProjectRadio.addEventListener('change', toggleDivs);
    existingProjectRadio.addEventListener('change', toggleDivs);
    toggleDivs();
      function redirect() {
          window.location.href="{{ route('task.index') }}";
      }
  </script>

@endsection
