<?php

namespace App\Controllers;

use App\Models\AccountModel;
use App\Models\KubuGadangModel;
use App\Models\PackageModel;
use App\Models\GalleryPackageModel;
use CodeIgniter\Session\Session;
use Myth\Auth\Config\Auth as AuthConfig;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Password;
use CodeIgniter\Files\File;
use CodeIgniter\API\ResponseTrait;

class Home extends BaseController
{
    use ResponseTrait;

    protected $auth;
    protected $userModel;
    protected $accountModel;
    protected $KubuGadangModel;
    protected $packageModel;
    protected $galleryPackageModel;

    /**
     * @var AuthConfig
     */
    protected $config;

    /**
     * @var Session
     */
    protected $session;

    public function __construct()
    {
        $this->session = service('session');
        $this->config = config('Auth');
        $this->auth = service('authentication');
        $this->userModel = new UserModel();
        $this->accountModel = new AccountModel();
        $this->KubuGadangModel = new KubuGadangModel();
        $this->packageModel = new PackageModel();
        $this->galleryPackageModel = new GalleryPackageModel();
    }

    public function index()
    {
        return view('welcome_message');
    }

    public function landingPage()
    {
        // $loggedUserData = session()->get('LoggedUserData');
        // if ($loggedUserData) {
        //     print_r($loggedUserData);            
        // }
        $contents = $this->packageModel->get_list_package_default()->getResultArray();

        // $i=0;
        foreach ($contents as &$package) {
            $id = $package['id'];
            $gallery = $this->galleryPackageModel->get_gallery($id)->getRowArray();

            // Assuming you want to associate the gallery with each package
            if (!empty($gallery)) {
                foreach ($gallery as $item) {
                    $package['gallery'] = $item;
                }
            } else {
                $package['gallery'] = 'default.jpg';
            }
        }

        $data = [
            'data' => $contents,
        ];
        // dd($data);
        // return view('web/list_package', $data);
        return view('landing_page', $data);
        // return view('landing_page');
    }

    public function error403()
    {
        return view('errors/html/error_403');
    }

    public function login()
    {
        $data = [
            'title' => 'Login',
            'config' => $this->config,
        ];
        return view('auth/login', $data);
    }

    public function register()
    {
        $data = [
            'title' => 'Register',
            'config' => $this->config,
        ];
        return view('auth/register', $data);
    }

    public function profile()
    {
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();

        $data = [
            'title' => 'My Profile',
            'data2' => $contents2,

        ];

        return view('profile/manage_profile', $data);
    }

    public function update()
    {
        // $acc = $this->accountModel->get_profil(user()->id)->getRowArray();
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();

        $data = [
            'title' => 'Update Profile',
            // 'account' => $acc            
            'data2' => $contents2,

        ];
        // dd($data);
        return view('profile/update_profile', $data);
    }

    public function save($id = null)
    {
        $request = $this->request->getPost();
        $requestData = [
            // 'username' => $request['username'],
            'fullname' => $request['fullname'],
            'address' => $request['address'],
            'phone' => $request['phone'],
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }
        $img = $this->request->getFile('user_image');

        if (empty($_FILES['user_image']['name'])) {
            $query = $this->accountModel->update_account_users($id, $requestData);
            if ($query) {
                $response = [
                    'status' => 200,
                    'message' => [
                        "Success update account avatar"
                    ]
                ];
                return redirect()->back();
            }
            $response = [
                'status' => 400,
                'message' => [
                    "Fail update account"
                ]
            ];
            return $this->respond($response, 400);
        } else {

            $validationRule = [
                'user_image' => [
                    'label' => 'Image File',
                    'rules' => 'uploaded[user_image]'
                        . '|is_image[user_image]'
                        . '|mime_in[user_image,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                ],
            ];
            if (!$this->validate($validationRule) && !empty($_FILES['user_image']['name'])) {
                $response = [
                    'status' => 400,
                    'message' => [
                        "Fail update account y"
                    ]
                ];
                return $this->respond($response, 400);
            }

            if ($img->isValid() && !$img->hasMoved()) {
                $filepath = WRITEPATH . 'uploads/' . $img->store();
                $user_image = new File($filepath);
                $user_image->move(FCPATH . 'media/photos/user');
                $requestData['user_image'] = $user_image->getFilename();

                $query = $this->accountModel->update_account_users($id, $requestData);
                if ($query) {
                    $response = [
                        'status' => 200,
                        'message' => [
                            "Success update your account avatar"
                        ]
                    ];
                    return redirect()->back();
                }
                $response = [
                    'status' => 400,
                    'message' => [
                        "Fail update account."
                    ]
                ];
                return $this->respond($response, 400);
            }
        }
        $response = [
            'status' => 400,
            'message' => [
                "Fail update account ."
            ]
        ];
        return $this->respond($response, 400);
    }

    public function changePassword()
    {
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();

        $data = [
            'title' => 'Change Password',
            'errors' => [],
            'success' => false,
            'data2' => $contents2,
        ];

        if ($this->request->getMethod() == 'post') {
            // $rules = [
            //     'password'     => 'required|strong_password',
            //     'pass_confirm' => 'required|matches[password]',
            // ];

            // if (!$this->validate($rules))
            // {
            //     $data['errors'] = $this->validator->getErrors();
            //     return view('profile/change_password', $data);
            // }

            $request = $this->request->getPost();
            $password = $request['password'];
            $pass_confirm = $request['pass_confirm'];

            if ($password != $pass_confirm) {
                $data['errors'] = ['Failed change password'];
                return view('profile/change_password', $data);
            }

            $requestData = [
                'password_hash' => Password::hash($this->request->getPost()['password']),
                'reset_hash' => null,
                'reset_at' => null,
                'reset_expires' => null,
            ];

            $query = $this->accountModel->change_password_user(user()->id, $requestData);
            if ($query) {
                $data['errors'] = ['Password is changed'];
                $data['success'] = true;
                return view('profile/change_password', $data);
            }
            $data['errors'] = ['Failed change password'];
            return view('profile/change_password', $data);
        }

        return view('profile/change_password', $data);
    }

    public function dbCheck()
    {
        $db = db_connect();
        $content = [
            'Platform' => $db->getPlatform(),
            'Version' => $db->getVersion(),
            'Database' => $db->getDatabase(),
        ];
        $response = [
            'data' => $content,
            'status' => 200,
            'message' => [
                "Successfully Connected to Database"
            ]
        ];
        return $this->respond($response);
    }
}
