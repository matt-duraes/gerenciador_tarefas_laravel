@extends('templates/main_layout')
@section('content')

<div class="container">
    <div class="row mt-5">
        <div class="col">
            <h4>Nova Tarefa</h4>
            <hr>
            <form action="{{route('new_task_submit')}}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="text_task_name" class="form-label">
                        Nome da tarefa
                    </label>
                    <input type="text" name="text_task_name" id="text_task_name" class="form-control" placeholder="Nome da tarefa" required value="{{old('text_task_name')}}">
                    @error('text_task_name')
                        <div class="text-warning">
                            {{$errors->get('text_task_name'[0])}}
                        </div>
                    @enderror
                </div>
            </form>
        </div>
    </div>
</div>
