<?php

namespace App\Controllers\Api;

use Myth\Auth\Models\UserModel;
use Myth\Auth\Password;
use CodeIgniter\RESTful\ResourceController;



class Auth extends ResourceController
{
    public function register()
    {
        $rules = [
            'username' => 'required|alpha_numeric_space|min_length[3]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            return $this->response->redirect(base_url('register'));
        }

        $userModel = new UserModel();

        $userData = [
            'username' => $this->request->getVar('username'),
            'email'    => $this->request->getVar('email'),
            'password_hash' => Password::hash($this->request->getVar('password')),
            'active'   => 1,
        ];

        $userId = $userModel->insert($userData);

        if (! $userId) {
            session()->setFlashdata('error', 'Failed to register user.');
            return $this->response->redirect(base_url('register'));
        }

        // Assign group after registration
        $groupName = $this->request->getVar('group') ?? 'customer';
        $groupModel = model('Myth\Auth\Models\GroupModel');
        $group = $groupModel->where('name', $groupName)->first();

        if ($group) {
            $groupModel->addUserToGroup($userId, $group->id);
        }

        // Set Myth Auth message block for successful registration
        session()->setFlashdata('message', lang('Auth.registerSuccess'));

        // Redirect to login page after registration (like Myth Auth)
        return $this->response->redirect(base_url('login'));
    }
}
