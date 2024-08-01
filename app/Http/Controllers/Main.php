<?php
namespace App\Http\Controllers;

use App\Models\TaskModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Main extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Gestor de Tarefas',
            'tasks' => $this->_get_tasks(),
            'datatables' => true
        ];
        return view('main', $data);
    }

    public function login()
    {
        $data = [
            'title' => 'Login'
        ];
        return view('login_frm', $data);
    }

    public function login_submit(Request $request)
    {
       //form validation
        $request->validate([
            'text_username' => 'required|min:3',
            'text_password'=> 'required|min:5'
        ], [
            'text_username.required' => 'O campo usuário é obrigatório.',
            'text_password.required' => 'O campo senha é obrigatório.',
            'text_username.min' => 'O campo usuário deve ter no mínimo ao menos 3 caracteres.',
            'text_password.min' => 'O campo senha deve receber ao menos 8 caracteres.'
        ]);
        //get form data
        $username = $request->input('text_username');
        $password = $request->input('text_password');

        $user = UserModel::where('username', $username)->whereNull('deleted_at')->first();
        if($user) {
            //check if password is correct
            if(password_verify($password,$user->password )) {
                $session_data = [
                    'id' => $user->id,
                    'username' => $user->username
                ];
                session($session_data);
                return redirect()->route('index');
            }
        }

       return redirect()->route('login')->withInput()->with('login_error', 'Login inválido');
    }

    public function logout()
    {
        session()->forget('username');
        return redirect()->route('login');
    }


    public function new_task()
    {
        $data = [
            'title' => 'Nova Tarefa'
        ];

        return view('new_task_frm', $data);
    }

    public function new_task_submit(Request $request)
    {
        $request->validate([
            'text_task_name' => 'required|min:3|max:200',
            'text_task_description'=> 'required|min:5|max:1000'
        ], [
            'text_task_name.required' => 'O nome da tarefa é obrigatório.',
            'text_task_description.required' => 'O campo descrição é obrigatório.',
            'text_task_name.min' => 'O campo nome da tarefa deve ter no mínimo ao menos 3 caracteres.',
            'text_task_description.min' => 'O campo descrição da tarefa deve ter no mínimo deve receber ao menos 5 caracteres.',
            'text_task_name.max' => 'O campo nome da tarefa deve ter no máximo 200 caracteres.',
            'text_task_description.max' => 'O campo descrição da tarefa deve ter no máximo 1000 caracteres.',
        ]);

        $task_name = $request->input('text_task_name');
        $task_description = $request->input('text_task_description');

        $model = new TaskModel();
        $task = $model->where('id_user', '=' , session('id'))->where('task_name','=', $task_name)->whereNull('deleted_at')->first();
        if($task) {
            return redirect()->route('new_task')->with('task_error', 'Já existe uma terefa com mesmo nome');
        }
        $model->id_user = session('id');
        $model->task_name = $task_name;
        $model->task_description = $task_description;
        $model->task_status = 'new';
        $model->created_at = date('Y-m-d H:i:s');
        $model->save();
        return redirect()->route('index');
    }

    private function _get_tasks()
    {
        $model = TaskModel::where('id_user', session('id'))->whereNull('deleted_at')->get();
        $collection = [];

        foreach($model as $task) {
            $link_edit = '<a href="'.route('edit_task', ['id' => $task->id]).'" class="btn btn-secondary m-1"> <i class="bi bi-pencil-square"></i></i></a>';
            $link_delete = '<a href="'.route('delete_task', ['id' => $task->id]).'" class="btn btn-secondary m-1"> <i class="bi bi-trash"></i></i></a>';

            $collection[] = [
                'task_name' => $task->task_name,
                'task_status' => $this->_status_name($task->task_status),
                'task_actions' => $link_edit . $link_delete
            ];
        }

        return $collection;
    }
    private function _status_name($status) {
        $status_colletion = [
            'new' => 'Nova',
            'in_progress' => 'Em progresso',
            'cancelled' => 'Cancelada',
            'completed' => 'Concluída',
        ];

        if(key_exists($status, $status_colletion)) {
            return $status_colletion[$status];
        } else {
            return 'Desconhecido';
        }
    }

}
