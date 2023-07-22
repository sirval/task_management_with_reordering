@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
@endsection
@section('content')
<div style="padding-right: 20% !important; padding-left: 20% !important; justify-content: center" class="reorder-container">
    <div  class="card">
        <h1 style="text-align: center;">Drag and Drop to Roeorder Table</h1>
        @include('layouts.partials.messages')
       
        <div class="button-container">
            <a style="" href="{{ route('task.create') }}" class="create-button">New Task</a>
            <span id="edit_project_container">
            </span>
            <span id="delete_project_container">
            </span>
        </div>
        <br />
        <br />
        <div>
            <span>
                <label>Select a project to view task: </label>
                <select id="project_id">
                    <option selected>--Select a project to view tasks--</option>
                    @foreach ($projects as $row)
                        <option value="{{ $row->id }}">{{ $row->project_name }}</option>
                    @endforeach
                </select>

            </span>
        </div><br /> <br />
        <div id="succ-alert" class="alert alert-success">
            {{-- Order updated successfully! --}}
        </div>
        <ul id="sortable">
        {{-- value will be rendered by js  --}}
        </ul>
    </div>
</div>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <script>
        $(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#sortable").sortable({
                update: function(event, ui) {
                    updateOrder();
                }
            });

        });
        
        function showSuccessAlert(message) {
            $('#succ-alert').text(message);
            $('#succ-alert').fadeIn();
            setTimeout(function() {
                $('#succ-alert').fadeOut();
            }, 3000); // 3 seconds
        }
        function updateOrder() {
            let task_ordering = new Array();
            $('#sortable li').each(function() {
                task_ordering.push($(this).attr("id"));
            });
            let order_string = 'order=' + task_ordering;
            $.ajax({
                type: "POST",
                url: "{{ route('task.reorder') }}",
                data: order_string,
                cache: false,
            
                success: function(res) {
                if (res.status === 200) {
                    showSuccessAlert(res.message);
                    getTasks();
                }else{
                    console.log("Server response:", res);
                }

                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                }
            });
        }

        function getTasks(update = false) {
            const selected_project = document.getElementById("project_id");
            if (update == true) {
                const selectedValue = selected_project.value;
                getRecentTask(selectedValue)
            }else{
                selected_project.addEventListener("change", function() {
                    const selectedValue = selected_project.value;
                    getRecentTask(selectedValue)
                
                });
            }
        }

        function getRecentTask(selectedValue) {
                $.ajax({
                type: "GET",
                url: "{{ route('task.all') }}",
                data: { project_id: selectedValue },
                cache: false,
                success: function(res) {
                    let newContent = '';
                    
                    if (res.status === 200) {
                        console.log(res.data);
                        if (res.data.length > 0) {
                            $("#edit_project_container").html(`<a style="" href="{{ route('project.edit', '') }}/${selectedValue}" class="create-button">Edit Project</a>`);
                            $("#delete_project_container").html(`<a href="javascript:void()" onclick="confirmDelete(${selectedValue}, 'project')" class="delete-button">Delete Project</a>`);

                            $.each(res.data, function(key, value) {
                                newContent += `<li class="ui-state-default" id="${value.id}">
                                                <span class="drag-icon"><i class="fas fa-grip-vertical"></i></span>
                                            ${value.task_name}
                                                <span class="edit-icon"><i class="fas fa-edit" onclick="window.location.href = '{{ route('task.edit', '') }}/' + ${value.id}"></i></span>
                                                <span class="delete-icon"><i class="fas fa-trash-alt" onclick="confirmDelete(${value.id}, 'task')"></i></span>
                                            </li>`;
                            });
                        }else{
                            newContent += `<h2 style="color: red">No Available Task. Click on New Task Button to add new task to list<h2>`;
                        }
                        
                    } else {
                        console.error(res.message);
                    }
                    $("#sortable").html(newContent);
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                }
            });
            // }
        }

        function confirmDelete(id, info) {
            let confirmation = '';
            if (info === 'project') {
                confirmation = confirm("Are you sure you want to delete this project? This will also delete  all associated tasks and cause automatic page reload");
            }
            if (info === 'task') {
                confirmation = confirm("Are you sure you want to delete this task?");
            }
            if (confirmation) {
                deleteDetail(id, info);
            }
        }

        function deleteDetail(id, info){
            if (info === 'task') {
                url = "{{ route('task.destroy', '') }}/"+id;
            }else if (info === 'project') {
                url = "{{ route('project.destroy') }}?id="+id;
            }else{
                console.error('Unknown Request');
                return;
            }
            $.ajax({
                type: "DELETE",
                url: url,
                data: id,
                cache: false,
            
                success: function(res) {
                if (res.status === 200) {
                    showSuccessAlert(res.message);
                    getTasks(update = true);
                    if (info === 'project') {
                        setTimeout(function() {
                            window.location.reload();
                        }, 3000);
                    }
                }else{
                    console.log("Server response:", res);
                }

                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                }
            });
        }

        getTasks(update = false);
    </script>
@endsection