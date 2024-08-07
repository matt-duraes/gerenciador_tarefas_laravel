@extends('templates/main_layout')
@section('content')

@section('content')
<div class="container">
    <div class="row mt-5">
        <div class="col">
            <h4>Editar Tarefa</h4>
            <hr>
            <form action="{{route('edit_task_submit')}}" method="post">
                <input type="hidden" name="task_id" value="{{Crypt::encrypt($task->id)}}">
                @csrf
                <div class="mb-3">
                    <label for="text_task_name" class="form-label">Nome Tarefa</label>
                    <input type="text" name="text_task_name" id="text_task_name" class="form-control" placeholder="Nome Tarefa" required value="{{old('text_task_name', $task->task_name)}}">
                    @error('text_task_name')
                        <div class="text-warning">{{$errors->get('text_task_name')[0]}}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="text_task_description" class="form-label">Descrição Tarefa</label>
                    <textarea name="text_task_description" class="form-control" id="text_task_description" rows="5" required>
                        {{old('text_task_description', $task->task_name)}}
                    </textarea>
                    @error('text_task_description')
                        <div class="text-warning">{{$errors->get('text_task_description')[0]}}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="text_task_status" class="form-label">Status da tarefa</label>
                    <select name="text_task_status" id="text_task_status" class="form-select w-25" required>
                        <option value="new" {{old('text_task_status',$task->task_status) == 'new' ? "selected" : ""}}>Nova</option>
                        <option value="in_progress" {{old('text_task_status',$task->task_status) == 'in_progress' ? "selected" : ""}}>Em progresso</option>
                        <option value="cancelled" {{old('text_task_status',$task->task_status) == 'cancelled' ? "selected" : ""}}>Cancelada</option>
                        <option value="completed" {{old('text_task_status',$task->task_status) == 'completed' ? "selected" : ""}}>Concluída</option>
                    </select>
                    @error('text_task_status')
                        <div class="text-warning">{{$errors->get('text_task_status')[0]}}</div>
                    @enderror
                </div>
                <div class="mb-3 text-center mt-3">
                    <a href="{{route('index')}}" class="btn btn-dark px-5 m-1">
                        <i class="bi bi-x-circle me-2 "></i>
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-secondary px-5 m-1">
                        <i class="bi bi-floppy me-2"></i>
                        Editar
                    </button>
                </div>
                @if (session()->has('task_error'))
                    <div class="aler alert-danger text-center p-1 mt-3">
                        {{session()->get('task_error')}}
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
