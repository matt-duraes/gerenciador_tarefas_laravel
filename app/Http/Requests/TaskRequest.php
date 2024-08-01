<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

     public function rules()
     {
         return [
             'text_task_name' => 'required|min:3|max:200',
             'text_task_description' => 'required|min:5|max:1000',
         ];
     }

     public function messages()
     {
         return [
             'text_task_name.required' => 'O nome da tarefa é obrigatório.',
             'text_task_description.required' => 'O campo descrição é obrigatório.',
             'text_task_name.min' => 'O campo nome da tarefa deve ter no mínimo ao menos 3 caracteres.',
             'text_task_description.min' => 'O campo descrição da tarefa deve ter no mínimo deve receber ao menos 5 caracteres.',
             'text_task_name.max' => 'O campo nome da tarefa deve ter no máximo 200 caracteres.',
             'text_task_description.max' => 'O campo descrição da tarefa deve ter no máximo 1000 caracteres.',
         ];
     }

}
