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
            'title' => 'teste',
            'description' => 'aPRENDENDO'
        ];
       return view('main', $data);
    }

    public function users()
    {
        //get users with raw sql
        $users = DB::select('SELECT * FROM users');

        //with query builder
        $users1 = DB::table('users')->get();

        //with query builder - return in array
        $users2 = DB::table('users')->get()->toArray();
        
        //using Eloquent ORM
        $model = new UserModel();
        $users3 = $model->all();
        
        foreach ($users3 as $user) {
            echo $user->username . '<br>';
        }
    }

    public function view()
    {
        $data = [
            'title' => 'Titulo da página'
        ];
        return view('home', $data);
    }
}