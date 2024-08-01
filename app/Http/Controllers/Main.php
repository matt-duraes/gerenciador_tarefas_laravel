<?php
namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\TaskRequest;
use App\Models\TaskModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Main extends Controller
{
    public function store(TaskRequest $request)
    {
        // O código para processar a tarefa vai aqui
    }

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

    public function login_submit(LoginRequest $request)
    {

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

    public function new_task_submit(TaskRequest $request)
    {

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
