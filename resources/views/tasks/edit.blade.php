@extends('layouts.app')
@section('css')
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('/assets/css/admin_css.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <div style="padding-right: 20% !important; padding-left: 20% !important; justify-content: center" class="container-fluid px-4">
      <br />
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Task | Edit</li>
        </ol>
        
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Edit task
            </div>
                
            <div class="card-body">
              @include('layouts.partials.messages')
                <form class="was-validated" action="{{ route('task.update', request('id')) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                      <select style="dsplay: none;" class="form-select form-select-sm" aria-label=".form-select-sm example" id="existing_project" name="project_id">
                        <option selected value="">--Select Project--</option>
                        @foreach ($projects as $row)
                          <option  @if ($task->project_id == $row->id) {{ 'selected' }} @endif value="{{ $row->id }}">{{ $row->project_name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="validationCustom01" class="form-label">Task Name</label>
                      <input type="text" class="form-control" name="task_name" id="validationCustom01" value="{{ $task->task_name }}" required>
                      <div class="invalid-feedback">
                          Task name is Required!
                      </div>
                      <div class="valid-feedback">
                        Nice one! 👍
                      </div>
                    </div>
                    <div class="mb-3">
                      <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="priority">
                        
                        @for ($i = 1; $i <= 4; $i++)
                          <option @if ($task->priority == $i) {{ 'selected' }} @endif value="{{ $i }}">
                              @if ($i === 1) High
                              @elseif ($i === 2) Middle
                              @elseif ($i === 3) Normal
                              @else Low
                              @endif
                          </option>
                        @endfor
                      </select>
                      <div class="invalid-feedback">
                        Please enter task in the textarea.
                      </div>
                      <div class="valid-feedback">
                        Nice one! 👍
                      </div>
                    </div>
                  
                  
                    <div style="float: right" class="mb-3">
                      <button class="btn btn-primary" type="submit">Update Task</button>
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
      function redirect() {
          window.location.href="{{ route('task.index') }}";
      }
  </script>
@endsection
