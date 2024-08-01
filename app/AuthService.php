<?php

namespace App;

use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
class AuthService
{
    public function authenticate($username, $password)
    {
        $user = UserModel::where('username', $username)->whereNull('deleted_at')->first();
        if ($user && Hash::check($password, $user->password)) {
            return $user;
        }
        return null;
    }
}
