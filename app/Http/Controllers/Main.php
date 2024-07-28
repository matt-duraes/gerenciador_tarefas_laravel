<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}