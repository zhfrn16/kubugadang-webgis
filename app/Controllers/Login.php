<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AccountModel;
use CodeIgniter\Session\Session;
use Google\Client as Google_Client;

class Login extends BaseController
{
    protected $googleClient;
    protected $users;
    protected $session;

    public function __construct()
    {
        session();

        $this->users = new AccountModel();
        $this->googleClient = new Google_Client();

        $this->session = service('session');

        $this->googleClient->setClientId('392583878097-0qcl7pq6gls21h8vgr4tr468id64p6n8.apps.googleusercontent.com');
        $this->googleClient->setClientSecret('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');
        $this->googleClient->setRedirectUri(base_url('.auth/login/google/callback'));
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
    }

    public function index()
    {
        $data = [
            'title' => 'Login',
            'link' => $this->googleClient->createAuthUrl(),
        ];
        return view('login/index', $data);
    }

    public function callback()
    {
        try {
            $code = $this->request->getVar('code');
            
            if ($code) {
                $token = $this->googleClient->fetchAccessTokenWithAuthCode($code);
                
                if (!isset($token['error'])) {
                    $this->googleClient->setAccessToken($token['access_token']);
                    
                    $googleService = new \Google\Service\Oauth2($this->googleClient);
                    $data = $googleService->userinfo->get();

                    $nameWithoutSpace = str_replace(' ', '', $data->name);
                    $row = [
                        'email' => $data->email,
                        'username' => $nameWithoutSpace . $data->id,
                        'fullname' => $data->name,
                        'user_image' => $data->picture,
                        'active' => '1',
                    ];

                    $requestData = ['email' => $data->email];
                    $checkExistingData = $this->users->checkIfDataExists($requestData);
                    $emailaccount = $data->email;

                    $findId = $this->users->get_id_profil($emailaccount)->getRowArray();
                    $currentDateTime = date("Y-m-d H:i:s");

                    if ($checkExistingData && $findId['password_hash'] == null) {
                        $id = $findId['id'];
                        $dataaccount = [
                            'email' => $data->email,
                            'username' => $nameWithoutSpace . $data->id,
                            'fullname' => $data->name,
                            'updated_at' => $currentDateTime,
                        ];
                        $updateRole = $this->users->update_account_users($id, $dataaccount);

                        $ipAddress = $this->request->getIPAddress();

                        $dataLogins = [
                            'ip_address' => $ipAddress,
                            'email'      => $data->email,
                            'user_id' => $id,
                            'date' => date('Y-m-d H:i:s'),
                            'success'   => 1,
                        ];
                        $updateLogins = $this->users->addUserToAuthLogins($id, $dataLogins);

                        if ($updateLogins) {
                            $findId = $this->users->get_profil($id)->getRowArray();
                            $this->session->set('LoggedUserData', $findId);
                            return redirect()->to(site_url('/'));
                        }
                    } else if ($checkExistingData && $findId['password_hash'] != null) {
                        return redirect()->to(site_url('/failedlogin'));
                    } else {
                        $saveNewAccount = $this->users->save($row);

                        if ($saveNewAccount) {
                            $findId = $this->users->get_id_profil($emailaccount)->getRowArray();
                            $id = $findId['id'];
                            $groupId = 2;

                            if ($findId) {
                                $updateRole = $this->users->addUserToGroup($id, $groupId);

                                if ($updateRole) {
                                    $findId = $this->users->get_profil($id)->getRowArray();
                                    $this->session->set('LoggedUserData', $findId);
                                    return redirect()->to(site_url('/'));
                                }
                            }
                        }
                    }
                } else {
                    $errorMessage = $token['error_description'] ?? 'Unknown error';
                    return redirect()->to(site_url('/error?message=' . urlencode($errorMessage)));
                }
            } else {
                return redirect()->to(site_url('/error?message=Authorization code is missing'));
            }
        } catch (\Exception $e) {
            return redirect()->to(site_url('/error?message=' . urlencode($e->getMessage())));
        }
    }
}
