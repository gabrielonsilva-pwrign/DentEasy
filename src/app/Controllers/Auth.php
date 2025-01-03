<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if(!session()->get('user_id'))    {
            return view('auth/login');
        } else {
            return redirect()->to('/dashboard');
        }
    }

    public function attemptLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            $session = session();
            $session->set('user_id', $user['id']);
            $session->set('user_email', $user['email']);
            $session->set('user_group', $user['group_id']);
            return redirect()->to('/dashboard');
        } else {
            return redirect()->back()->with('error', 'Dados inválidos');
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('login');
    }
}
