<?php
namespace App\Http\Controllers;

use App\AuthService as AppAuthService;
use App\Http\Requests\EditRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\TaskRequest;
use App\Models\TaskModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;


class Main extends Controller
{
    protected $authService;
    protected $taskModel;

    // Injetar AuthService no construtor
    public function __construct(AppAuthService $authService, TaskModel $taskModel)
    {
        $this->authService = $authService;
        $this->taskModel = $taskModel;
    }


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
        $username = $request->input('text_username');
        $password = $request->input('text_password');
        $user = $this->authService->authenticate($username, $password);

        if ($user) {
            $session_data = [
                'id' => $user->id,
                'username' => $user->username
            ];
            session($session_data);

            return redirect()->route('index');
        };

        return back()->withErrors(['text_password' => 'Senha incorreta ou usuário não encontrado.']);

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
        $user_id = session('id');

        $task= TaskModel::where('id_user', session('id'))->where('task_name', $task_name)->whereNull('deleted_at')->first();

        if($task) {
            return redirect()->route('new_task')->with('task_error', 'Já existe uma terefa com mesmo nome');
        }

        // Criar nova tarefa usando Eloquent
        TaskModel::create([
            'id_user' => $user_id,
            'task_name' => $task_name,
            'task_description' => $task_description,
            'task_status' => 'new',
            'created_at' => now()
        ]);

        return redirect()->route('index');
    }

    public function edit_task($id)
    {
        $id = $this->_decryptId($id);

        $task = TaskModel::where('id', $id)->whereNull('deleted_at')->first();
        if(!$task) {
            return redirect()->route('index');
        }

        $data = [
            'title' => 'Editar Tarefa',
            'task' => $task
        ];

        return view('edit_task_frm', $data);
    }


    public function edit_task_submit(EditRequest $request)
    {

        $task_id = $this->_decryptTaskId($request->input('task_id'));
        if (!$task_id) {
            return redirect()->route('index');
        }

        $task_name = $request->input('text_task_name');
        $task_description = $request->input('text_task_description');
        $task_status = $request->input('text_task_status');
        $user_id = session('id');

        if ($this->_taskExists($user_id, $task_name, $task_id)) {
            return redirect()->route('edit_task', ['id' => Crypt::encrypt($task_id)])
                            ->with('task_error', 'Já existe outra tarefa com o mesmo nome');
        }

        $this->_updateTask($task_id, $task_name, $task_description, $task_status);

        return redirect()->route('index');
    }

    public function delete_task($id)
    {
        $id = $this->_decryptId($id);

        $task = TaskModel::where('id', $id)->first();

        if(!$task) {
            return redirect()->route('index');
        }

        $data = [
            'title' => 'Excluir Tarefa',
            'task' => $task
        ];

        return view('delete_task', $data);

    }

    public function delete_task_confirm($id)
    {
       $task_id = $this->_decryptTaskId($id);
        if ($task_id) {
            TaskModel::where('id', $task_id)->delete();
        }

        return redirect()->route('index');
    }

    // ================================================
    //  FUNÇÕES PRIVADAS
    // =================================================
    private function _decryptTaskId($encrypted_task_id)
    {
        try {
            return Crypt::decrypt($encrypted_task_id);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function _decryptId($id)
    {
        try {
            return Crypt::decrypt($id);
        } catch (\Exception $e) {
            return redirect()->route('index');
        }
    }

    private function _taskExists($user_id, $task_name, $task_id)
    {
        return TaskModel::where('id_user', $user_id)
                        ->where('task_name', $task_name)
                        ->where('id', '!=', $task_id)
                        ->whereNull('deleted_at')
                        ->exists();
    }

    private function _updateTask($task_id, $task_name, $task_description, $task_status)
    {
        TaskModel::where('id', $task_id)->update([
            'task_name' => $task_name,
            'task_description' => $task_description,
            'task_status' => $task_status,
            'updated_at' => now()
        ]);
    }

    private function _get_tasks()
    {
        $model = TaskModel::where('id_user', session('id'))->whereNull('deleted_at')->get();
        $collection = [];

        foreach($model as $task) {
            $link_edit = '<a href="'.route('edit_task', ['id' => Crypt::encrypt( $task->id)]).'" class="btn btn-secondary m-1"> <i class="bi bi-pencil-square"></i></i></a>';
            $link_delete = '<a href="'.route('delete_task', ['id' => Crypt::encrypt( $task->id)]).'" class="btn btn-secondary m-1"> <i class="bi bi-trash"></i></i></a>';

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
