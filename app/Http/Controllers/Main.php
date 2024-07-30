<?php
namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Main extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Gestor de Tarefas'
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

}
