<?php

namespace App\Controllers\Web;

use Myth\Auth\Models\UserModel;
use App\Controllers\BaseController;
use CodeIgniter\Session\Session;
use Google_client;
use Myth\Auth\Config\Auth as AuthConfig;

class Admin extends BaseController
{
    protected $auth;
    protected $googleClient;
    
    /**
     * @var AuthConfig
     */
    protected $config;
    
    /**
     * @var Session
     */
    protected $session;
    
    protected $userModel;

    public function __construct()
    {
        $this->session = service('session');
        $this->config = config('Auth');
        $this->auth = service('authentication');
        $this->userModel = new UserModel();

        $this->googleClient = new Google_Client();
        $this->googleClient->setClientId('392583878097-0qcl7pq6gls21h8vgr4tr468id64p6n8.apps.googleusercontent.com');
        $this->googleClient->setClientSecret('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');
        $this->googleClient->setRedirectUri(base_url('login/proses'));
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
    }
    
    public function login() {

        $data = [
            'title' => 'Login',
            'config' => $this->config,
            'link' => $this->googleClient->createAuthUrl(),
        ];
        return view('auth/login', $data);
    }

    public function failedlogin() {

        $data = [
            'title' => 'Login',
            'config' => $this->config,
            'link' => $this->googleClient->createAuthUrl(),
        ];
        return view('auth/failedlogin', $data);
    }
    
    public function register()
    {
        $data = [
            'title' => 'Register',
            'config' => $this->config,
        ];
        return view('auth/register', $data);
    }
}
