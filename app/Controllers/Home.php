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
        $village = $this->KubuGadangModel->get_desa_wisata_info()->getRowArray();
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
        $gallery = [
            [
                'url' => 'drone view kuga.jpg',
                'title' => 'Kubu Gadang Tourism Village',
                'description' => 'Kubu Gadang Tourism Village is one of the Community Based Tourism in West Sumatra Province located on Jalan Haji Miskin, Ekor Lubuk Village, Padang Panjang City. Kubu Gadang was pioneered as a Tourism Village since 2014. Kubu Gadang tourist village is a refreshing and romantic place to evoke nostalgia for beautiful memories in your hometown. Kubu Gadang Tourism Village has a variety of natural and cultural potentials that are packaged in various educational programs with activities that provide learning and experience for tourists. ',
            ],
            [
                'url' => 'adek-homestay.png',
                'title' => 'Adek Homestay',
                'description' => 'Adek homestay is one of 11 existing homestay accommodations and is managed directly by local residents and the Kubu Gadang tourism group.',
            ],
            [
                'url' => 'silek lanyahhhh.jpg',
                'title' => 'Silek Lanyah',
                'description' => 'When visiting Kubu Gadang Village, visitors will be able to enjoy the beauty of nature and culture. Silek Lanyah Kubu Gadang is a unique traditional Minangkabau martial art performed on muddy fields, usually in rice fields after harvest. This tour is unique because it is not only a sport or performing art but also acts as a means of moral and social education. Silek Lanyah is performed by three generations, namely children, teenagers and adults and is the original community of Kubu Gadang.',
            ],
            [
                'url' => 'bajamba 1.jpg',
                'title' => 'Makan Bajamba',
                'description' => 'A unique communal eating tradition in Kubu Gadang Tourism Village. This tradition involves eating rice and side dishes from a tray (jamba) together while sitting in a circle, symbolizing togetherness and unity.',
            ],
            [
                'url' => 'batik 2.jpg',
                'title' => 'Minang Batik',
                'description' => 'Minang batik is one of the flagship products of the Kubu Gadang Tourism Village, which helps promote the richness of Minangkabau culture and the creative economy.<br>
Minang batik, particularly that produced in Kubu Gadang, has been showcased at various events, including the World Islamic Entrepreneur Summit (WIES) in collaboration with Minang Kayo and Batik Rang Minang.',
],
[
    'url' => 'baju saisuak cewek.jpg',
    'title' => 'Saiusak Attire',
    'description' => 'The saisuak attire in Kubu Gadang Tourism Village is a traditional Minangkabau attire that is a major attraction in cultural preservation efforts. "Saisuak" itself means "olden days" or "long ago." The Kubu Gadang community has a unique tradition of wearing saisuak, a family heirloom, some dating back to the 1960s. This event, such as a saisuak fashion show, is held to introduce and recall the way Minangkabau women dressed in the past and foster a love of cultural traditions among the younger generation.',
],
[
                'url' => 'miniatur rumah gdang.jpg',
                'title' => 'Rumah Gadang Miniature',
                'description' => 'In Kubu Gadang Village, there are several interesting souvenir options. Among them are miniature wooden traditional houses, a product of the tourist village. These miniatures closely mimic the architecture of the traditional Rumah Gadang, and tourists can also learn how to make them.',
            ],
            [
                'url' => 'nanam padi.jpg',
                'title' => 'Rice Planting',
                'description' => 'At Kubu Gadang Tourism Village, tourists can participate in rice planting activities as part of a learning experience about Minangkabau culture. The village offers educational tour packages, including rice planting experiences.',
            ],
            [
                'url' => 'pacu upiah.jpg',
                'title' => 'Pacu Upiah',
                'description' => 'Pacu Upiah is a traditional game found in the Kubu Gadang Tourism Village. The game uses areca nut fronds as the primary instrument and can help break up the tension within a team or organization. In this village, Pacu Upiah is one of the attractions offered to tourists.',
            ],
            [
                'url' => 'menangkap ikan.jpg',
                'title' => 'Fishing in Rice Paddies',
                'description' => 'At Kubu Gadang Tourism Village, visitors can experience fishing in managed rice paddies as part of an educational tourism attraction. This activity is a major draw, especially for tourists who want to experience the thrill of traditional fishing.',
            ],
            [
                'url' => 'pasar digital 1.jpg',
                'title' => '\"Duit\" Leather Coins',
                'description' => 'In the Kubu Gadang Tourism Village, during the "digital market" event, a unique transaction attraction is held using leather coins called "duit." These coins serve as a special means of payment in the village and are a hallmark of Kubu Gadang. These leather coins can be exchanged at the exchange post located in the village.',
            ],
            [
                'url' => 'tari.jpg',
                'title' => 'Dance Attractions',
                'description' => 'Dance attractions in Kubu Gadang Tourism Village, West Sumatra, include the Pasambahan Dance, Indang Dance, Bagurau Dance, and Silek Lanyah. These dances are often performed to welcome guests and are part of the village\'s cultural heritage, preserved by the local community.',
            ],
            [
                'url' => 'musik tradisional.jpg',
                'title' => 'Traditional Music Attractions',
                'description' => 'Kubu Gadang also offers traditional musical attractions such as talempong, gandang tambua, gandang tasa, and saluang, which are often used to accompany dances and guest processions.',
            ],
            [
                'url' => 'randai.jpg',
                'title' => 'Randai',
                'description' => 'This art form combines elements of drama, dance, music, and singing, with stories drawn from the "kaba" (traditional folklore) that address themes of goodness, shame, morality, and education. In Kubu Gadang, randai has become a tourist attraction and is often performed to welcome guests.',
            ],
        ];

        $data = [
            'gallery' => $gallery,
            'data' => $contents,
            'village' => $village,
        ];
        // dd($data);
        // return view('web/list_package', $data);
        return view('landing_page_3', $data);
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
