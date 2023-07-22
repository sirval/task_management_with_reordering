@extends('layouts.app')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('/assets/css/admin_css.css') }}" rel="stylesheet" />
   
@endsection
@section('content')
    <div style="padding-right: 20% !important; padding-left: 20% !important; justify-content: center" class="container-fluid px-4">
        <br />
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Project | Edit</li>
        </ol>
        
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Edit Project
            </div>
                
            <div class="card-body">
            @include('layouts.partials.messages')
                <form class="was-validated" action="{{ route('project.update', request('id')) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                    <label for="validationCustom01" class="form-label">Project Name</label>
                    <input type="text" class="form-control" name="project_name" id="validationCustom01" value="{{ $project->project_name }}" required>
                    <div class="invalid-feedback">
                        Project name is Required!
                    </div>
                    <div class="valid-feedback">
                        Nice one! 👍
                    </div>
                    </div>
                
                    <div style="float: right" class="mb-3">
                    <button class="btn btn-primary" type="submit">Update Project</button>
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
