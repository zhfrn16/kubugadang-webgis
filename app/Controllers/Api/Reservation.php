<?php

namespace App\Controllers\Api;

use App\Models\SumpuModel;
use App\Models\ReservationModel;
use App\Models\BackupDetailReservationModel;
use App\Models\DetailReservationModel;
use App\Models\HomestayModel;
use App\Models\UnitHomestayModel;
use App\Models\PackageModel;
use App\Models\CartModel;
use App\Models\GalleryPackageModel;
use App\Models\GalleryHomestayModel;
use App\Models\FacilityHomestayDetailModel;
use App\Models\FacilityUnitDetailModel;
use App\Models\GalleryUnitModel;
use App\Models\DetailPackageModel;
use App\Models\DetailServicePackageModel;
use App\Models\PackageDayModel;
use App\Models\ServicePackageModel;
use App\Models\AccountModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\MY_TCPDF as TCPDF;
use Myth\Auth\Models\UserModel;
use DateTime;

class Reservation extends ResourceController
{
    use ResponseTrait;

    protected $backupDetailReservationModel;
    protected $detailServicePackageModel;
    protected $detailReservationModel;
    protected $sumpuModel;
    protected $reservationModel;
    protected $homestayModel;
    protected $unitHomestayModel;
    protected $packageModel;
    protected $cartModel;
    protected $galleryPackageModel;
    protected $galleryHomestayModel;
    protected $facilityHomestayDetailModel;
    protected $facilityUnitDetailModel;
    protected $detailPackageModel;
    protected $packageDayModel;
    protected $galleryUnitModel;
    protected $accountModel;
    protected $userModel;


    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->sumpuModel = new SumpuModel();
        $this->reservationModel = new ReservationModel();
        $this->detailReservationModel = new DetailReservationModel();
        $this->detailPackageModel = new DetailPackageModel();
        $this->homestayModel = new HomestayModel();
        $this->unitHomestayModel = new UnitHomestayModel();
        $this->facilityHomestayDetailModel = new FacilityHomestayDetailModel();
        $this->packageModel = new PackageModel();
        $this->cartModel = new CartModel();
        $this->galleryPackageModel = new GalleryPackageModel();
        $this->galleryHomestayModel = new GalleryHomestayModel();
        $this->galleryUnitModel = new GalleryUnitModel();
        $this->packageDayModel = new PackageDayModel();
        $this->accountModel = new AccountModel();
        $this->facilityUnitDetailModel = new FacilityUnitDetailModel();
        $this->userModel = new UserModel();
        $this->backupDetailReservationModel = new BackupDetailReservationModel();
        $this->detailServicePackageModel = new DetailServicePackageModel();
    }


    public function payMidtrans($id)
    {
        $reservation_id = str_replace("D", "", $id);
        $datareservation = $this->reservationModel->get_reservation_by_id($reservation_id)->getRowArray();
        $res_id_deposit = $id;
        $amount = $datareservation['deposit'];

        $package_reservation = $datareservation['package_id'];
        $package = $this->packageModel->get_package_by_id($package_reservation)->getRowArray();

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = 'SB-Mid-server-Of1IfaGcLxvAOT-blQIE63_G';
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                // 'order_id' => time(),
                'order_id' => $res_id_deposit,
                // 'gross_amount' => 10000,
                'gross_amount' => $amount,
            ),
            'customer_details' => array(
                'first_name' => user()->username,
                'last_name' => user()->fullname,
                'email' => user()->email,
                'phone' => user()->phone,
            ),
        );


        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $token_of_deposit = $snapToken;
        $deposit_check = '';
        $deposit_channel = '';
        $deposit_date = date('Y-m-d H:i:s');

        $updateDeposit = $this->reservationModel->updateDepositCheck($reservation_id, $token_of_deposit, $deposit_check, $deposit_channel, $deposit_date);


        $response = [
            // 'datareservation' => $datareservation['deposit'],
            'datareservation' => $datareservation,
            'res_id_deposit' => $res_id_deposit,
            'package' => $package,
            'data' => $snapToken,
            'status' => 200,
            'message' => [
                "Success get token"
            ]
        ];

        return $this->respond($response);
    }
    public function payMidtransFull($id)
    {
        $reservation_id = str_replace("F", "", $id);
        $datareservation = $this->reservationModel->get_reservation_by_id($reservation_id)->getRowArray();
        $res_id_full = $id;
        $amount = $datareservation['total_price'] - $datareservation['deposit'];

        $package_reservation = $datareservation['package_id'];
        $package = $this->packageModel->get_package_by_id($package_reservation)->getRowArray();

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = 'SB-Mid-server-Of1IfaGcLxvAOT-blQIE63_G';
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                // 'order_id' => time(),
                'order_id' => $res_id_full,
                // 'gross_amount' => 10000,
                'gross_amount' => $amount,
            ),
            'customer_details' => array(
                'first_name' => user()->username,
                'last_name' => user()->fullname,
                'email' => user()->email,
                'phone' => user()->phone,
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $token_of_payment = $snapToken;
        $payment_check = '';
        $payment_channel = '';
        $payment_date = date('Y-m-d H:i:s');

        $updateFull = $this->reservationModel->updatePaymentCheck($reservation_id, $token_of_payment, $payment_check, $payment_channel, $payment_date);

        $response = [
            // 'datareservation' => $datareservation['deposit'],
            'datareservation' => $datareservation,
            'amount' => $amount,
            'package' => $package,
            'data' => $snapToken,
            'status' => 200,
            'message' => [
                "Success get token"
            ]
        ];

        return $this->respond($response);
    }

    public function chooseHomeLama()
    {
        $request = $this->request->getPost();
        $checkInDate = $request['checkInDate'];
        $totalPeople = $request['totalPeople'];

        // $checkInDate = '2024-04-17';
        // $totalPeople = '10';


        // $list_homestay = $this->unitHomestayModel->get_list_homestay()->getResultArray();
        $list_homestay = $this->unitHomestayModel->get_available_units()->getResultArray();

        // $homestays = array();
        // foreach ($list_homestay as $homestay) {
        //     $homestays[] = $homestay['homestay_id'];
        // }

        $homestays = array();
        foreach ($list_homestay as $homestay) {
            $homestays[] = [
                'homestay_id' => $homestay['homestay_id'],
                'unit_type' => $homestay['unit_type'],
                'unit_number' => $homestay['unit_number']
            ];
        }


        $response_data = ['houses' => []];

        foreach ($homestays as $homestay) {
            $homestay_id = $homestay['homestay_id'];
            $unit_type = $homestay['unit_type'];
            $unit_number = $homestay['unit_number'];

            $homestay = $this->homestayModel->get_homestay_by_id_simple($homestay_id)->getRowArray();

            if (empty($homestay)) {
                continue;
            }

            // Retrieve facility and gallery details
            $list_facility_rumah = $this->facilityHomestayDetailModel->get_detailFacilityHomestay_by_id($homestay_id)->getResultArray();
            $list_gallery = $this->galleryHomestayModel->get_gallery($homestay_id)->getResultArray();
            $galleries = array();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }
            $homestay['gallery'] = $galleries;

            // Retrieve unit details with total reservations
            // $list_units = $this->unitHomestayModel->unit_tersedia($homestay_id, $checkInDate, $totalPeople)->getResultArray();
            $list_units = $this->unitHomestayModel->unit_tersedia($homestay_id, $unit_type, $unit_number, $checkInDate, $totalPeople)->getResultArray();

            $units_selected = []; // Inisialisasi ulang array untuk menyimpan unit yang dipilih di setiap homestay

            foreach ($list_units as $unit) {
                // Cek jika kapasitas unit mencukupi untuk jumlah tamu yang tersisa
                if ($totalPeople > 0 && $unit['capacity'] > 0) {
                    // Tentukan kapasitas unit yang akan dipilih (maksimum antara kapasitas unit dan jumlah tamu yang tersisa)
                    $unit_capacity = min($totalPeople, $unit['capacity']);

                    $facilities = array();
                    $unit_number = $unit['unit_number'];
                    $homestay_id = $unit['homestay_id'];
                    $unit_type = $unit['unit_type'];
                    $list_facility = $this->facilityUnitDetailModel->get_data_facility_unit_detail($unit_number, $homestay_id, $unit_type)->getResultArray();
                    $facilities[] = $list_facility;
                    $fc = $facilities;

                    // Tambahkan unit yang dipilih ke dalam array
                    $units_selected[] = [
                        'homestay_id' => $unit['homestay_id'],
                        'unit_type' => $unit['unit_type'],
                        'unit_number' => $unit['unit_number'],
                        'unit_name' => $unit['unit_name'],
                        'description' => $unit['description'],
                        'price' => $unit['price'],
                        'room_capacity' => $unit['room_capacity'],
                        'url' => $unit['url'],
                        'capacity' => $unit_capacity,
                        'facility_units' => $fc,
                        // Tambahan data lainnya sesuai kebutuhan
                    ];

                    // Kurangi jumlah tamu yang tersisa dengan kapasitas unit yang dipilih
                    $totalPeople -= $unit_capacity;
                } else {
                    // Jika tidak ada tamu yang tersisa atau kapasitas unit tidak mencukupi, keluar dari loop
                    break;
                }
            }

            if (!empty($units_selected)) {
                $response_data['houses'][] = [
                    'id' => $homestay_id,
                    'name' => $homestay['name'],
                    'gallery' => $homestay['gallery'],
                    'facilities' => $list_facility_rumah,
                    'units' => $units_selected,
                ];
            }

            // Jika jumlah tamu sudah mencukupi, keluar dari loop homestay
            if ($totalPeople <= 0) {
                break;
            }
        }

        // // Respon API dengan unit-unit yang dipilih
        // $response = [
        //     'status' => 200,
        //     'message' => 'Success',
        //     'datahome' => $response_data,
        // ];

        if ($totalPeople > 0) {
            // If totalPeople is greater than remaining capacity, send an error response
            $response = [
                'status' => 400,
                'message' => 'Error',
                'error' => 'Not enough available units for the specified criteria.',
            ];
        } else {
            // If all criteria are met, send success response
            $response = [
                'status' => 200,
                'message' => 'Success',
                'datahome' => $response_data,
            ];
        }

        return $this->respond($response, $response['status']);
    }

    public function chooseHome()
    {
        $request = $this->request->getPost();
        $checkInDate = $request['checkInDate'];
        $totalPeople = $request['totalPeople'];

        // $checkInDate = '2024-04-15';
        // $totalPeople = '10';

        if ($totalPeople < 11) {
            $calculatePrice = 250000;
        } else if ($totalPeople > 10) {
            $calculatePrice = 200000;
        }


        // $checkExistingData = true;
        $checkExistingData = $this->detailReservationModel->checkIfUnitReserved($checkInDate);

        if (!$checkExistingData) {

            $checkNormalOrRating = $this->detailReservationModel->get_normal_or_rating()->getResultArray();

            // Menghitung total elemen dalam array
            $total = count($checkNormalOrRating);

            // Mengecek apakah totalnya genap atau tidak
            if ($total % 2 == 0) {
                $tipe_pemilihan = "Normal";

                $list_homestay = $this->unitHomestayModel->get_available_units()->getResultArray();

                $homestays = array();
                foreach ($list_homestay as $homestay) {
                    $homestays[] = [
                        'homestay_id' => $homestay['homestay_id'],
                        'unit_type' => $homestay['unit_type'],
                        'unit_number' => $homestay['unit_number']
                    ];
                }

                $response_data = ['houses' => []];

                foreach ($homestays as $homestay) {
                    $homestay_id = $homestay['homestay_id'];
                    $unit_type = $homestay['unit_type'];
                    $unit_number = $homestay['unit_number'];

                    $homestayData = $this->homestayModel->get_homestay_by_id_simple($homestay_id)->getRowArray();

                    if (empty($homestayData)) {
                        continue;
                    }

                    // Retrieve facility and gallery details
                    $list_facility_rumah = $this->facilityHomestayDetailModel->get_detailFacilityHomestay_by_id($homestay_id)->getResultArray();
                    $list_gallery = $this->galleryHomestayModel->get_gallery($homestay_id)->getResultArray();
                    $galleries = array();
                    foreach ($list_gallery as $gallery) {
                        $galleries[] = $gallery['url'];
                    }
                    $homestayData['gallery'] = $galleries;

                    // Retrieve unit details with total reservations
                    $list_units = $this->unitHomestayModel->unit_tersedia($homestay_id, $unit_type, $unit_number, $checkInDate, $totalPeople)->getResultArray();

                    $units_selected = []; // Array untuk menyimpan unit yang dipilih di setiap homestay

                    foreach ($list_units as $unit) {
                        if ($totalPeople > 0 && $unit['capacity'] > 0) {
                            $unit_capacity = min($totalPeople, $unit['capacity']);

                            $facilities = array();
                            $unit_number = $unit['unit_number'];
                            $homestay_id = $unit['homestay_id'];
                            $unit_type = $unit['unit_type'];
                            $list_facility = $this->facilityUnitDetailModel->get_data_facility_unit_detail($unit_number, $homestay_id, $unit_type)->getResultArray();
                            $facilities[] = $list_facility;
                            $fc = $facilities;

                            $units_selected[] = [
                                'homestay_id' => $unit['homestay_id'],
                                'unit_type' => $unit['unit_type'],
                                'unit_number' => $unit['unit_number'],
                                'unit_name' => $unit['unit_name'],
                                'description' => $unit['description'],
                                // 'price' => $unit['price'],
                                'price' => $calculatePrice * $unit_capacity,
                                'room_capacity' => $unit['room_capacity'],
                                'url' => $unit['url'],
                                'capacity' => $unit_capacity,
                                'facility_units' => $fc,
                            ];

                            $totalPeople -= $unit_capacity;
                        } else {
                            break;
                        }
                    }

                    if (!empty($units_selected)) {
                        $response_data['houses'][] = [
                            'id' => $homestay_id,
                            'name' => $homestayData['name'],
                            'gallery' => $homestayData['gallery'],
                            'facilities' => $list_facility_rumah,
                            'units' => $units_selected,
                        ];
                    }

                    if ($totalPeople <= 0) {
                        break;
                    }
                }

                $response = [
                    'status' => 200,
                    'message' => 'Success',
                    'tipe' => $tipe_pemilihan,
                    'datahome' => $response_data,
                ];
            } else if ($total % 2 != 0) {
                $tipe_pemilihan = "Rating";

                $list_homestay = $this->unitHomestayModel->get_available_units_by_rating()->getResultArray();

                $homestays = array();
                foreach ($list_homestay as $homestay) {
                    $homestays[] = [
                        'homestay_id' => $homestay['homestay_id'],
                        'unit_type' => $homestay['unit_type'],
                        'unit_number' => $homestay['unit_number']
                    ];
                }

                $response_data = ['houses' => []];

                foreach ($homestays as $homestay) {
                    $homestay_id = $homestay['homestay_id'];
                    $unit_type = $homestay['unit_type'];
                    $unit_number = $homestay['unit_number'];

                    $homestayData = $this->homestayModel->get_homestay_by_id_simple($homestay_id)->getRowArray();

                    if (empty($homestayData)) {
                        continue;
                    }

                    // Retrieve facility and gallery details
                    $list_facility_rumah = $this->facilityHomestayDetailModel->get_detailFacilityHomestay_by_id($homestay_id)->getResultArray();
                    $list_gallery = $this->galleryHomestayModel->get_gallery($homestay_id)->getResultArray();
                    $galleries = array();
                    foreach ($list_gallery as $gallery) {
                        $galleries[] = $gallery['url'];
                    }
                    $homestayData['gallery'] = $galleries;

                    // Retrieve unit details with total reservations
                    // $list_units = $this->unitHomestayModel->unit_tersedia_by_rating($homestay_id, $unit_type, $unit_number, $checkInDate, $totalPeople)->getResultArray();
                    $list_units = $this->unitHomestayModel->unit_tersedia($homestay_id, $unit_type, $unit_number, $checkInDate, $totalPeople)->getResultArray();

                    $units_selected = []; // Array untuk menyimpan unit yang dipilih di setiap homestay

                    foreach ($list_units as $unit) {
                        if ($totalPeople > 0 && $unit['capacity'] > 0) {
                            $unit_capacity = min($totalPeople, $unit['capacity']);

                            $facilities = array();
                            $unit_number = $unit['unit_number'];
                            $homestay_id = $unit['homestay_id'];
                            $unit_type = $unit['unit_type'];
                            $list_facility = $this->facilityUnitDetailModel->get_data_facility_unit_detail($unit_number, $homestay_id, $unit_type)->getResultArray();
                            $facilities[] = $list_facility;
                            $fc = $facilities;

                            $units_selected[] = [
                                'homestay_id' => $unit['homestay_id'],
                                'unit_type' => $unit['unit_type'],
                                'unit_number' => $unit['unit_number'],
                                'unit_name' => $unit['unit_name'],
                                'description' => $unit['description'],
                                // 'price' => $unit['price'],
                                'price' => $calculatePrice * $unit_capacity,
                                'room_capacity' => $unit['room_capacity'],
                                'url' => $unit['url'],
                                'capacity' => $unit_capacity,
                                'facility_units' => $fc,
                            ];

                            $totalPeople -= $unit_capacity;
                        } else {
                            break;
                        }
                    }

                    if (!empty($units_selected)) {
                        $response_data['houses'][] = [
                            'id' => $homestay_id,
                            'name' => $homestayData['name'],
                            'gallery' => $homestayData['gallery'],
                            'facilities' => $list_facility_rumah,
                            'units' => $units_selected,
                        ];
                    }

                    if ($totalPeople <= 0) {
                        break;
                    }
                }

                $response = [
                    'status' => 200,
                    'message' => 'Success',
                    'tipe' => $tipe_pemilihan,
                    'datahome' => $response_data,
                ];
            }
        } else {
            // Retrieve list of homestays
            // $list_homestay = $this->unitHomestayModel->get_homestay_by_reserved($checkInDate)->getResultArray();
            // Retrieve list of homestays based on priority
            $list_homestay = $this->unitHomestayModel->get_homestay_by_prioritas_real($checkInDate)->getResultArray();

            $homestays = array();
            foreach ($list_homestay as $homestay) {
                $homestays[] = [
                    'homestay_id' => $homestay['homestay_id'],
                    'unit_type' => $homestay['unit_type'],
                    'unit_number' => $homestay['unit_number']
                ];
            }


            $response_data = ['houses' => []];

            foreach ($homestays as $homestay) {
                $homestay_id = $homestay['homestay_id'];
                $unit_type = $homestay['unit_type'];
                $unit_number = $homestay['unit_number'];

                $homestay = $this->homestayModel->get_homestay_by_id_simple($homestay_id)->getRowArray();

                if (empty($homestay)) {
                    continue;
                }

                // Retrieve facility and gallery details
                $list_facility_rumah = $this->facilityHomestayDetailModel->get_detailFacilityHomestay_by_id($homestay_id)->getResultArray();
                $list_gallery = $this->galleryHomestayModel->get_gallery($homestay_id)->getResultArray();
                $galleries = array();
                foreach ($list_gallery as $gallery) {
                    $galleries[] = $gallery['url'];
                }
                $homestay['gallery'] = $galleries;

                // Retrieve unit details with total reservations
                // $list_units = $this->unitHomestayModel->unit_tersedia($homestay_id, $unit_type, $unit_number, $checkInDate, $totalPeople)->getResultArray();
                $list_units = $this->unitHomestayModel->get_homestay_by_prioritas_real($checkInDate)->getResultArray();

                // $list_units = $this->unitHomestayModel->unit_tersedia($homestay_id, $checkInDate, $totalPeople)->getResultArray();

                $units_selected = []; // Inisialisasi ulang array untuk menyimpan unit yang dipilih di setiap homestay

                foreach ($list_units as $unit) {
                    // Cek jika kapasitas unit mencukupi untuk jumlah tamu yang tersisa
                    if ($totalPeople > 0 && $unit['unit_remaining'] > 0) {
                        // Tentukan kapasitas unit yang akan dipilih (maksimum antara kapasitas unit dan jumlah tamu yang tersisa)
                        $unit_capacity = min($totalPeople, $unit['unit_remaining']);

                        $facilities = array();
                        $unit_number = $unit['unit_number'];
                        $homestay_id = $unit['homestay_id'];
                        $unit_type = $unit['unit_type'];
                        $list_facility = $this->facilityUnitDetailModel->get_data_facility_unit_detail($unit_number, $homestay_id, $unit_type)->getResultArray();
                        $facilities[] = $list_facility;
                        $fc = $facilities;

                        // Tambahkan unit yang dipilih ke dalam array
                        $units_selected[] = [
                            'homestay_id' => $unit['homestay_id'],
                            'unit_type' => $unit['unit_type'],
                            'unit_number' => $unit['unit_number'],
                            'unit_name' => $unit['unit_name'],
                            'description' => $unit['description'],
                            'price' => $calculatePrice * $unit_capacity,
                            'room_capacity' => $unit['room_capacity'],
                            'url' => $unit['url'],
                            'capacity' => $unit_capacity,
                            'facility_units' => $fc,
                            // Tambahan data lainnya sesuai kebutuhan
                        ];

                        // Kurangi jumlah tamu yang tersisa dengan kapasitas unit yang dipilih
                        $totalPeople -= $unit_capacity;
                    } else {
                        // Jika tidak ada tamu yang tersisa atau kapasitas unit tidak mencukupi, keluar dari loop
                        break;
                    }
                }

                if (!empty($units_selected)) {
                    $response_data['houses'][] = [
                        'id' => $homestay_id,
                        'name' => $homestay['name'],
                        'gallery' => $homestay['gallery'],
                        'facilities' => $list_facility_rumah,
                        'units' => $units_selected,
                    ];
                }

                // Jika jumlah tamu sudah mencukupi, keluar dari loop homestay
                if ($totalPeople <= 0) {
                    break;
                }
            }

            // Respon API dengan unit-unit yang dipilih
            $response = [
                'status' => 200,
                'message' => 'Success',
                'datahome' => $response_data,

            ];
        }

        return $this->respond($response, $response['status']);
    }


    public function statistictersedia()
    {
        // Retrieve package information
        $checkInDate = '2024-04-28';
        $totalPeople = 5;

        $checkNormalOrRating = $this->detailReservationModel->get_normal_or_rating()->getResultArray();

        // Menghitung total elemen dalam array
        $total = count($checkNormalOrRating);
        $checkExistingData = $this->detailReservationModel->checkIfUnitReserved($checkInDate);

        if (!$checkExistingData) {

            $checkNormalOrRating = $this->detailReservationModel->get_normal_or_rating()->getResultArray();

            // Menghitung total elemen dalam array
            $total = count($checkNormalOrRating);

            // Mengecek apakah totalnya genap atau tidak
            if ($total % 2 == 0) {
                $tipe_pemilihan = "Normal";

                $list_homestay = $this->unitHomestayModel->get_available_units()->getResultArray();

                $homestays = array();
                foreach ($list_homestay as $homestay) {
                    $homestays[] = [
                        'homestay_id' => $homestay['homestay_id'],
                        'unit_type' => $homestay['unit_type'],
                        'unit_number' => $homestay['unit_number']
                    ];
                }

                $response_data = ['houses' => []];

                foreach ($homestays as $homestay) {
                    $homestay_id = $homestay['homestay_id'];
                    $unit_type = $homestay['unit_type'];
                    $unit_number = $homestay['unit_number'];

                    $homestayData = $this->homestayModel->get_homestay_by_id_simple($homestay_id)->getRowArray();

                    if (empty($homestayData)) {
                        continue;
                    }

                    // Retrieve facility and gallery details
                    $list_facility_rumah = $this->facilityHomestayDetailModel->get_detailFacilityHomestay_by_id($homestay_id)->getResultArray();
                    $list_gallery = $this->galleryHomestayModel->get_gallery($homestay_id)->getResultArray();
                    $galleries = array();
                    foreach ($list_gallery as $gallery) {
                        $galleries[] = $gallery['url'];
                    }
                    $homestayData['gallery'] = $galleries;

                    // Retrieve unit details with total reservations
                    $list_units = $this->unitHomestayModel->unit_tersedia($homestay_id, $unit_type, $unit_number, $checkInDate, $totalPeople)->getResultArray();

                    $units_selected = []; // Array untuk menyimpan unit yang dipilih di setiap homestay

                    foreach ($list_units as $unit) {
                        if ($totalPeople > 0 && $unit['capacity'] > 0) {
                            $unit_capacity = min($totalPeople, $unit['capacity']);

                            $facilities = array();
                            $unit_number = $unit['unit_number'];
                            $homestay_id = $unit['homestay_id'];
                            $unit_type = $unit['unit_type'];
                            $list_facility = $this->facilityUnitDetailModel->get_data_facility_unit_detail($unit_number, $homestay_id, $unit_type)->getResultArray();
                            $facilities[] = $list_facility;
                            $fc = $facilities;

                            $units_selected[] = [
                                'homestay_id' => $unit['homestay_id'],
                                'unit_type' => $unit['unit_type'],
                                'unit_number' => $unit['unit_number'],
                                'unit_name' => $unit['unit_name'],
                                'description' => $unit['description'],
                                // 'price' => $unit['price'],
                                // 'price' => $calculatePrice * $unit_capacity,
                                'room_capacity' => $unit['room_capacity'],
                                'url' => $unit['url'],
                                'capacity' => $unit_capacity,
                                'facility_units' => $fc,
                            ];

                            $totalPeople -= $unit_capacity;
                        } else {
                            break;
                        }
                    }

                    if (!empty($units_selected)) {
                        $response_data['houses'][] = [
                            'id' => $homestay_id,
                            'name' => $homestayData['name'],
                            'gallery' => $homestayData['gallery'],
                            'facilities' => $list_facility_rumah,
                            'units' => $units_selected,
                        ];
                    }

                    if ($totalPeople <= 0) {
                        break;
                    }
                }

                $response = [
                    'status' => 200,
                    'message' => 'Success',
                    'tipe' => $tipe_pemilihan,
                    'datahome' => $response_data,
                ];
            } else if ($total % 2 != 0) {
                $tipe_pemilihan = "Rating";

                $list_homestay = $this->unitHomestayModel->get_available_units_by_rating()->getResultArray();

                $homestays = array();
                foreach ($list_homestay as $homestay) {
                    $homestays[] = [
                        'homestay_id' => $homestay['homestay_id'],
                        'unit_type' => $homestay['unit_type'],
                        'unit_number' => $homestay['unit_number']
                    ];
                }

                $response_data = ['houses' => []];

                foreach ($homestays as $homestay) {
                    $homestay_id = $homestay['homestay_id'];
                    $unit_type = $homestay['unit_type'];
                    $unit_number = $homestay['unit_number'];

                    $homestayData = $this->homestayModel->get_homestay_by_id_simple($homestay_id)->getRowArray();

                    if (empty($homestayData)) {
                        continue;
                    }

                    // Retrieve facility and gallery details
                    $list_facility_rumah = $this->facilityHomestayDetailModel->get_detailFacilityHomestay_by_id($homestay_id)->getResultArray();
                    $list_gallery = $this->galleryHomestayModel->get_gallery($homestay_id)->getResultArray();
                    $galleries = array();
                    foreach ($list_gallery as $gallery) {
                        $galleries[] = $gallery['url'];
                    }
                    $homestayData['gallery'] = $galleries;

                    // Retrieve unit details with total reservations
                    // $list_units = $this->unitHomestayModel->unit_tersedia_by_rating($homestay_id, $unit_type, $unit_number, $checkInDate, $totalPeople)->getResultArray();
                    $list_units = $this->unitHomestayModel->unit_tersedia($homestay_id, $unit_type, $unit_number, $checkInDate, $totalPeople)->getResultArray();

                    $units_selected = []; // Array untuk menyimpan unit yang dipilih di setiap homestay

                    foreach ($list_units as $unit) {
                        if ($totalPeople > 0 && $unit['capacity'] > 0) {
                            $unit_capacity = min($totalPeople, $unit['capacity']);

                            $facilities = array();
                            $unit_number = $unit['unit_number'];
                            $homestay_id = $unit['homestay_id'];
                            $unit_type = $unit['unit_type'];
                            $list_facility = $this->facilityUnitDetailModel->get_data_facility_unit_detail($unit_number, $homestay_id, $unit_type)->getResultArray();
                            $facilities[] = $list_facility;
                            $fc = $facilities;

                            $units_selected[] = [
                                'homestay_id' => $unit['homestay_id'],
                                'unit_type' => $unit['unit_type'],
                                'unit_number' => $unit['unit_number'],
                                'unit_name' => $unit['unit_name'],
                                'description' => $unit['description'],
                                // 'price' => $unit['price'],
                                // 'price' => $calculatePrice * $unit_capacity,
                                'room_capacity' => $unit['room_capacity'],
                                'url' => $unit['url'],
                                'capacity' => $unit_capacity,
                                'facility_units' => $fc,
                            ];

                            $totalPeople -= $unit_capacity;
                        } else {
                            break;
                        }
                    }

                    if (!empty($units_selected)) {
                        $response_data['houses'][] = [
                            'id' => $homestay_id,
                            'name' => $homestayData['name'],
                            'gallery' => $homestayData['gallery'],
                            'facilities' => $list_facility_rumah,
                            'units' => $units_selected,
                        ];
                    }

                    if ($totalPeople <= 0) {
                        break;
                    }
                }

                $response = [
                    'status' => 200,
                    'message' => 'Success',
                    'tipe' => $tipe_pemilihan,
                    'datahome' => $response_data,
                ];
            }
        } else {
            // Retrieve list of homestays
            // $list_homestay = $this->unitHomestayModel->get_homestay_by_reserved($checkInDate)->getResultArray();
            // Retrieve list of homestays based on priority
            $list_homestay = $this->unitHomestayModel->get_homestay_by_prioritas_real($checkInDate)->getResultArray();

            $homestays = array();
            foreach ($list_homestay as $homestay) {
                $homestays[] = [
                    'homestay_id' => $homestay['homestay_id'],
                    'unit_type' => $homestay['unit_type'],
                    'unit_number' => $homestay['unit_number']
                ];
            }


            $response_data = ['houses' => []];

            foreach ($homestays as $homestay) {
                $homestay_id = $homestay['homestay_id'];
                $unit_type = $homestay['unit_type'];
                $unit_number = $homestay['unit_number'];

                $homestay = $this->homestayModel->get_homestay_by_id_simple($homestay_id)->getRowArray();

                if (empty($homestay)) {
                    continue;
                }

                // Retrieve facility and gallery details
                $list_facility_rumah = $this->facilityHomestayDetailModel->get_detailFacilityHomestay_by_id($homestay_id)->getResultArray();
                $list_gallery = $this->galleryHomestayModel->get_gallery($homestay_id)->getResultArray();
                $galleries = array();
                foreach ($list_gallery as $gallery) {
                    $galleries[] = $gallery['url'];
                }
                $homestay['gallery'] = $galleries;

                // Retrieve unit details with total reservations
                // $list_units = $this->unitHomestayModel->unit_tersedia($homestay_id, $unit_type, $unit_number, $checkInDate, $totalPeople)->getResultArray();
                $list_units = $this->unitHomestayModel->get_homestay_by_prioritas_real($checkInDate)->getResultArray();

                // $list_units = $this->unitHomestayModel->unit_tersedia($homestay_id, $checkInDate, $totalPeople)->getResultArray();

                $units_selected = []; // Inisialisasi ulang array untuk menyimpan unit yang dipilih di setiap homestay

                foreach ($list_units as $unit) {
                    // Cek jika kapasitas unit mencukupi untuk jumlah tamu yang tersisa
                    if ($totalPeople > 0 && $unit['unit_remaining'] > 0) {
                        // Tentukan kapasitas unit yang akan dipilih (maksimum antara kapasitas unit dan jumlah tamu yang tersisa)
                        $unit_capacity = min($totalPeople, $unit['unit_remaining']);

                        $facilities = array();
                        $unit_number = $unit['unit_number'];
                        $homestay_id = $unit['homestay_id'];
                        $unit_type = $unit['unit_type'];
                        $list_facility = $this->facilityUnitDetailModel->get_data_facility_unit_detail($unit_number, $homestay_id, $unit_type)->getResultArray();
                        $facilities[] = $list_facility;
                        $fc = $facilities;

                        // Tambahkan unit yang dipilih ke dalam array
                        $units_selected[] = [
                            'homestay_id' => $unit['homestay_id'],
                            'unit_type' => $unit['unit_type'],
                            'unit_number' => $unit['unit_number'],
                            'unit_name' => $unit['unit_name'],
                            'description' => $unit['description'],
                            'price' => $unit['price'],
                            'room_capacity' => $unit['room_capacity'],
                            'url' => $unit['url'],
                            'muatan' => $unit_capacity,
                            // 'facility_units' => $fc,
                            // Tambahan data lainnya sesuai kebutuhan
                        ];

                        // Kurangi jumlah tamu yang tersisa dengan kapasitas unit yang dipilih
                        $totalPeople -= $unit_capacity;
                    } else {
                        // Jika tidak ada tamu yang tersisa atau kapasitas unit tidak mencukupi, keluar dari loop
                        break;
                    }
                }

                if (!empty($units_selected)) {
                    $response_data['houses'][] = [
                        'id' => $homestay_id,
                        'name' => $homestay['name'],
                        // 'gallery' => $homestay['gallery'],
                        // 'facilities' => $list_facility_rumah,
                        'units' => $units_selected,
                    ];
                }

                // Jika jumlah tamu sudah mencukupi, keluar dari loop homestay
                if ($totalPeople <= 0) {
                    break;
                }
            }

            // Respon API dengan unit-unit yang dipilih
            $response = [
                'status' => 200,
                'message' => 'Success',
                // 'check' => $checkNormalOrRating,
                // 'dipilih' => $list_homestay,
                // 'tipe' => $tipe_pemilihan,
                'datahome' => $response_data,
            ];
        }


        return $this->respond($response, $response['status']);
    }



    //     public function statistictersedia2()
    // {
    //     $checkInDate = '2024-04-17';
    //     $totalPeople = 10;

    //     $list_homestay = $this->unitHomestayModel->get_available_units()->getResultArray();
    //     $response_data = ['houses' => []];

    //     foreach ($list_homestay as $homestay) {
    //         $homestay_id = $homestay['homestay_id'];
    //         $unit_type = $homestay['unit_type'];
    //         $unit_number = $homestay['unit_number'];

    //         $homestayData = $this->homestayModel->get_homestay_by_id_simple($homestay_id)->getRowArray();

    //         if (empty($homestayData)) {
    //             continue;
    //         }

    //         // Retrieve facility and gallery details
    //         $list_facility_rumah = $this->facilityHomestayDetailModel->get_detailFacilityHomestay_by_id($homestay_id)->getResultArray();
    //         $list_gallery = $this->galleryHomestayModel->get_gallery($homestay_id)->getResultArray();
    //         $galleries = [];
    //         foreach ($list_gallery as $gallery) {
    //             $galleries[] = $gallery['url'];
    //         }
    //         $homestayData['gallery'] = $galleries;

    //         // Retrieve unit details with total reservations
    //         $list_units = $this->unitHomestayModel->unit_tersedia2($homestay_id, $unit_type, $unit_number, $checkInDate, $totalPeople);

    //         // Initialize array to store selected units
    //         $units_selected = [];

    //         // Initialize variable to store totalPeople remaining
    //         $totalPeopleRemaining = $totalPeople;

    //         // Check if there are still totalPeople remaining
    //         if ($totalPeopleRemaining > 0) {
    //             // Iterate through available units to find units with total capacity equal to totalPeople
    //             foreach ($list_units as $unit) {
    //                 // Determine the capacity of the selected unit
    //                 $unit_capacity = $unit['capacity'];

    //                 // Check if the capacity of the unit matches the totalPeople remaining
    //                 if ($unit_capacity == $totalPeopleRemaining) {
    //                     // Add the selected unit to the array
    //                     $units_selected[] = [
    //                         'homestay_id' => $unit['homestay_id'],
    //                         'unit_type' => $unit['unit_type'],
    //                         'unit_number' => $unit['unit_number'],
    //                         'unit_name' => $unit['unit_name'],
    //                         'description' => $unit['description'],
    //                         'price' => $unit['price'],
    //                         'room_capacity' => $unit_capacity,
    //                         'totalPeopleRemaining' => 0, // No more people remaining
    //                         'url' => $unit['url'],
    //                         // Additional data as needed
    //                     ];

    //                     // Update totalPeopleRemaining to 0 as all people have been accommodated
    //                     $totalPeopleRemaining = 0;

    //                     // Exit the loop since the required units have been found
    //                     break;
    //                 }
    //             }
    //         }

    //         // Add the selected units to the response data
    //         if (empty($units_selected)) {
    //             $response_data['houses'][] = [
    //                 'id' => $homestay_id,
    //                 'name' => $homestayData['name'],
    //                 // 'gallery' => $homestayData['gallery'],
    //                 // 'facilities' => $list_facility_rumah,
    //                 'units' => $units_selected,
    //             ];
    //         }

    //         // If all totalPeople have been accommodated, exit the homestay loop
    //         if ($totalPeopleRemaining <= 0) {
    //             break;
    //         }
    //     }

    //     // API response with selected units
    //     $response = [
    //         'status' => 200,
    //         'message' => 'Success',
    //         'datahome' => $response_data,
    //     ];

    //     return $this->respond($response, $response['status']);
    // }

    // public function chooseCustomHome()
    // {
    //     $request = $this->request->getPost();
    //     $checkInDate = $request['checkInDate'];
    //     $totalPeople = $request['totalPeople'];

    //     $list_homestay = $this->unitHomestayModel->get_homestay_by_custom($totalPeople)->getResultArray();
    //     $homestays = array();
    //     foreach ($list_homestay as $homestay) {
    //         $homestays[] = $homestay['homestay_id'];
    //     }


    //     foreach ($homestays as $homestay_id) {
    //         $homestay = $this->homestayModel->get_homestay_by_id($homestay_id)->getRowArray();

    //         if (empty($homestay)) {
    //             continue;
    //         }

    //         $list_facility_rumah = $this->facilityHomestayDetailModel->get_detailFacilityHomestay_by_id($homestay_id)->getResultArray();
    //         $list_gallery = $this->galleryHomestayModel->get_gallery($homestay_id)->getResultArray();
    //         $galleries = array();
    //         foreach ($list_gallery as $gallery) {
    //             $galleries[] = $gallery['url'];
    //         }
    //         $homestay['gallery'] = $galleries;

    //         if ($totalPeople < 11) {
    //             $list_unit = $this->unitHomestayModel->get_unit_homestay_with_gallery_custom_medium($homestay_id, $checkInDate, $totalPeople)->getResultArray();
    //         } else {
    //             $list_unit = $this->unitHomestayModel->get_unit_homestay_with_gallery_large($homestay_id, $checkInDate, $totalPeople)->getResultArray();
    //         }


    //         // Add data for the current Homestay to the response
    //         $response_data['houses'][] = [
    //             'id' => $homestay_id,
    //             'name' => $homestay['name'],
    //             'gallery' => $homestay['gallery'],
    //             'facilities' => $list_facility_rumah,
    //             'units' => $list_unit,
    //         ];
    //     }

    //     $response = [
    //         'status' => 200,
    //         'message' => 'Success',
    //         'data' => $list_homestay,
    //         'datachoose' => $homestays,
    //         'datahome' => $response_data,
    //     ];

    //     return $this->respond($response, 200);
    // }

    public function chooseCustomHome()
    {
        $request = $this->request->getPost();
        $checkInDate = $request['checkInDate'];
        $totalPeople = $request['totalPeople'];

        $list_homestay = $this->unitHomestayModel->get_homestay_by_custom($totalPeople)->getResultArray();
        $homestays = array();
        foreach ($list_homestay as $homestay) {
            $homestays[] = $homestay['homestay_id'];
        }


        foreach ($homestays as $homestay_id) {
            $homestay = $this->homestayModel->get_homestay_by_id($homestay_id)->getRowArray();

            if (empty($homestay)) {
                continue;
            }

            $list_facility_rumah = $this->facilityHomestayDetailModel->get_detailFacilityHomestay_by_id($homestay_id)->getResultArray();
            $list_gallery = $this->galleryHomestayModel->get_gallery($homestay_id)->getResultArray();
            $galleries = array();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }
            $homestay['gallery'] = $galleries;

            if ($totalPeople < 11) {
                $list_unit = $this->unitHomestayModel->get_unit_homestay_with_gallery_custom_medium($homestay_id, $checkInDate, $totalPeople)->getResultArray();
            } else {
                $list_unit = $this->unitHomestayModel->get_unit_homestay_with_gallery_large($homestay_id, $checkInDate, $totalPeople)->getResultArray();
            }

            // Calculate the total capacity of all units
            $totalCapacity = array_sum(array_column($list_unit, 'capacity'));

            // Check if total capacity is sufficient for totalPeople
            if ($totalCapacity < $totalPeople) {
                // If totalPeople is greater than total capacity, continue to the next homestay
                continue;
            }

            // Add data for the current Homestay to the response
            $response_data['houses'][] = [
                'id' => $homestay_id,
                'name' => $homestay['name'],
                'gallery' => $homestay['gallery'],
                'facilities' => $list_facility_rumah,
                'units' => $list_unit,
            ];
        }

        $response = [
            'status' => 200,
            'message' => 'Success',
            'data' => $list_homestay,
            'datachoose' => $homestays,
            'datahome' => $response_data,
        ];

        return $this->respond($response, 200);
    }

    //apisb
    public function statisticbooking()
    {
        // Get list of homestays
        $list_homestay = $this->unitHomestayModel->get_homestay_by_statistic()->getResultArray();
        $homestays = array();
        foreach ($list_homestay as $homestay) {
            $homestays[] = $homestay['homestay_id'];
        }

        // Retrieve package information
        $id = 'P0026';
        $checkInDate = '2024-04-17';
        $totalPeople = '10';
        $package = $this->packageModel->get_package_by_id_custom($id)->getRowArray();

        // Initialize response data
        $response_data = ['houses' => []];

        foreach ($homestays as $homestay_id) {
            // Retrieve homestay details
            $homestay = $this->homestayModel->get_homestay_by_id($homestay_id)->getRowArray();

            if (empty($homestay)) {
                continue;
            }

            // Retrieve facility and gallery details
            $list_facility_rumah = $this->facilityHomestayDetailModel->get_detailFacilityHomestay_by_id($homestay_id)->getResultArray();
            $list_gallery = $this->galleryHomestayModel->get_gallery($homestay_id)->getResultArray();
            $galleries = array();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }
            $homestay['gallery'] = $galleries;

            // Choose unit based on totalPeople
            if ($totalPeople < 3) {
                $list_unit = $this->unitHomestayModel->get_unit_homestay_with_gallery_small($homestay_id, $checkInDate, $totalPeople)->getResultArray();
            } elseif ($totalPeople < 11) {
                $list_unit = $this->unitHomestayModel->get_unit_homestay_with_gallery_medium($homestay_id, $checkInDate, $totalPeople)->getResultArray();
            } else {
                $list_unit = $this->unitHomestayModel->get_unit_homestay_with_gallery_large($homestay_id, $checkInDate, $totalPeople)->getResultArray();
            }

            // Calculate the total capacity of all units
            $totalCapacity = array_sum(array_column($list_unit, 'capacity'));

            // Check if total capacity is sufficient for totalPeople
            if ($totalCapacity < $totalPeople) {
                // If totalPeople is greater than total capacity, continue to the next homestay
                continue;
            }

            // Add data for the current Homestay to the response
            $qualified_units = [];

            foreach ($list_unit as $unit) {
                if ($totalPeople > 0) {
                    // Choose the unit based on the remaining capacity and totalPeople
                    $unit_capacity = min($totalPeople, $unit['capacity']);
                    $qualified_units[] = [
                        'homestay_id' => $unit['homestay_id'],
                        'unit_type' => $unit['unit_type'],
                        'unit_number' => $unit['unit_number'],
                        'unit_name' => $unit['unit_name'],
                        'description' => $unit['description'],
                        'price' => $unit['price'],
                        'capacity' => $unit_capacity,
                        'id' => $unit['id'],
                        'name_type' => $unit['name_type'],
                        'url' => $unit['url'],
                    ];
                    $totalPeople -= $unit_capacity;
                } else {
                    break;
                }
            }

            // Tambahkan data untuk Homestay dan unit yang memenuhi syarat ke respons
            if (!empty($qualified_units)) {
                $response_data['houses'][] = [
                    'id' => $homestay_id,
                    'name' => $homestay['name'],
                    'facilities' => $list_facility_rumah,
                    'units' => $qualified_units,
                ];
            }
        }

        // Check if total capacity is still greater than 0
        if ($totalPeople > 0) {
            // If totalPeople is greater than remaining capacity, send an error response
            $response = [
                'status' => 400,
                'message' => 'Error',
                'error' => 'Not enough available units for the specified criteria.',
            ];
        } else {
            // If all criteria are met, send success response
            $response = [
                'status' => 200,
                'message' => 'Success',
                'data' => $list_homestay,
                'datachoose' => $homestays,
                'datapackage' => $package,
                'datahome' => $response_data,
            ];
        }

        return $this->respond($response, $response['status']);
    }


    public function tersedia()
    {
        // $homestays = ['HO001', 'HO002']; // Daftar homestay yang diminta
        $list_homestay = $this->unitHomestayModel->get_list_homestay()->getResultArray();
        $homestays = array();
        foreach ($list_homestay as $homestay) {
            $homestays[] = $homestay['homestay_id'];
        }
        $checkInDate = '2024-02-29';
        $totalPeople = '5';
        $units_selected = []; // Inisialisasi array untuk menyimpan unit yang dipilih

        foreach ($homestays as $homestay) {
            $homestay_id = $homestay['homestay_id'];
            $unit_type = $homestay['unit_type'];
            $unit_number = $homestay['unit_number'];

            $homestay = $this->homestayModel->get_homestay_by_id_simple($homestay_id)->getRowArray();

            if (empty($homestay)) {
                continue;
            }

            // Retrieve facility and gallery details
            $list_facility_rumah = $this->facilityHomestayDetailModel->get_detailFacilityHomestay_by_id($homestay_id)->getResultArray();
            $list_gallery = $this->galleryHomestayModel->get_gallery($homestay_id)->getResultArray();
            $galleries = array();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }
            $homestay['gallery'] = $galleries;

            // Retrieve unit details with total reservations
            $list_units = $this->unitHomestayModel->unit_tersedia($homestay_id, $unit_type, $unit_number, $checkInDate, $totalPeople)->getResultArray();

            foreach ($list_units as $unit) {
                // Cek jika kapasitas unit mencukupi untuk jumlah tamu yang tersisa
                if ($totalPeople > 0 && $unit['capacity'] > 0) {
                    // Tentukan kapasitas unit yang akan dipilih (maksimum antara kapasitas unit dan jumlah tamu yang tersisa)
                    $unit_capacity = min($totalPeople, $unit['capacity']);

                    // Tambahkan unit yang dipilih ke dalam array
                    $units_selected[] = [
                        'homestaydata' => $homestay,

                        'homestay_id' => $unit['homestay_id'],
                        'unit_type' => $unit['unit_type'],
                        'unit_number' => $unit['unit_number'],
                        'unit_name' => $unit['unit_name'],
                        'description' => $unit['description'],
                        'price' => $unit['price'],
                        'room_capacity' => $unit['room_capacity'],
                        'capacity' => $unit_capacity,
                        // Tambahan data lainnya sesuai kebutuhan
                    ];

                    // Kurangi jumlah tamu yang tersisa dengan kapasitas unit yang dipilih
                    $totalPeople -= $unit_capacity;
                } else {
                    // Jika tidak ada tamu yang tersisa atau kapasitas unit tidak mencukupi, keluar dari loop
                    break;
                }
            }

            // Jika jumlah tamu sudah mencukupi, keluar dari loop homestay
            if ($totalPeople <= 0) {
                break;
            }
        }

        // Respon API dengan unit-unit yang dipilih
        $response = [
            'status' => 200,
            'message' => 'Success',
            'datahome' => $units_selected,

        ];

        return $this->respond($response, $response['status']);
    }

    public function custombooking($id)
    {
        // Assume $id is an array containing Homestay IDs
        $homestay_ids = ['HO001', 'HO002', 'HO003'];

        $response_data = ['package' => [], 'houses' => []];

        foreach ($homestay_ids as $homestay_id) {
            // Fetch package data
            $package = $this->packageModel->get_package_by_id_custom($id)->getRowArray();

            // Fetch Homestay data
            $homestay = $this->homestayModel->get_homestay_by_id($homestay_id)->getRowArray();

            if (empty($homestay)) {
                // Skip to the next iteration if Homestay not found
                continue;
            }

            // Fetch facilities for Homestay
            $list_facility_rumah = $this->facilityHomestayDetailModel->get_detailFacilityHomestay_by_id($homestay_id)->getResultArray();

            // Fetch gallery for Homestay
            $list_gallery = $this->galleryHomestayModel->get_gallery($homestay_id)->getResultArray();
            $galleries = array();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }
            $homestay['gallery'] = $galleries;

            // Fetch units for Homestay
            $list_unit = $this->unitHomestayModel->get_unit_homestay($homestay_id)->getResultArray();
            // $list_unit = $this->unitHomestayModel->get_unit_homestay_with_gallery($homestay_id)->getResultArray();

            // Add data for the current Homestay to the response
            $response_data['houses'][] = [
                'id' => $homestay_id,
                'name' => $homestay['name'],
                // 'gallery' => $homestay['gallery'],
                'facilities' => $list_facility_rumah,
                'units' => $list_unit,
            ];
        }

        // Add package data to the response
        $response_data['package'] = $package;

        $response = [
            'status' => 200,
            'message' => 'Success',
            'data' => $response_data,
        ];

        return $this->respond($response, 200);
    }

    public function create()
    {
        $request = $this->request->getPost();

        $id = $this->reservationModel->get_new_id();
        $date = date('Y-m-d H:i');
        $requestData = [
            'id' => $id,
            'user_id' => user()->id,
            'package_id' => $request['package_id'],
            'request_date' => $date,
            'total_people' => $request['total_people'],
            'check_in' => $request['check_in'] . ' ' . $request['time_check_in'],
            'total_price' => $request['total_price'],
            'deposit' => $request['deposit'],
            'note' => $request['note'],
        ];

        // Remove empty values from the request data
        $requestData = array_filter($requestData);

        // Add reservation
        $addRe = $this->reservationModel->add_new_reservation($requestData);

        // Add detail reservation
        if (isset($request['homestays'])) {
            $homestays = $request['homestays'];
            $check_in = date('Y-m-d', strtotime($request['check_in']));
            $check_out = date('Y-m-d', strtotime($request['check_out']));

            $date_booking = array();
            $current_date = $check_in;

            while (strtotime($current_date) < strtotime($check_out)) {
                $date_booking[] = date('Y-m-d', strtotime($current_date));
                $current_date = date('Y-m-d', strtotime($current_date . " +1 day"));
            }

            foreach ($date_booking as $db) {
                foreach ($homestays as $homestay) {
                    $requestData = [
                        'date' => $db,
                        'homestay_id' => $homestay['homestay_id'],
                        'unit_type' => $homestay['unit_type'],
                        'unit_number' => $homestay['unit_number'],
                        'reservation_id' => $id,
                        'unit_guest' => $homestay['capacity'],
                        // 'accomodation_type' => $request['accomodationType'],
                    ];

                    // Add detail reservation
                    // $this->detailReservationModel->add_new_detail_reservation2($requestData);
                    $this->detailReservationModel->add_new_detail_reservation($requestData);
                }
            }
        }

        if ($addRe) {
            $customerName = user()->username;
            $customerEmail = user()->email;
            $reservation_id = $id;
            $package_id = $request['package_id'];
            $note = $request['note'];
            $reservation_date = date('Y-m-d');
            $reservation_time = date('H:i:s');

            $package = $this->packageModel->get_package_by_id($package_id)->getRowArray();
            $packageName = $package['name'];


            $villageEmailData = $this->sumpuModel->get_desa_wisata_info()->getRowArray();
            if ($villageEmailData) {
                $villageName = $villageEmailData['name'];

                // create new PDF document
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                // set document information
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Kampuang Minang Nagari Sumpu');
                $pdf->SetTitle('PDF Invoice Kampuang Minang Nagari Sumpu');
                $pdf->SetSubject('Kampuang Minang Nagari Sumpu');
                $pdf->SetKeywords('TCPDF, PDF, invoice, pesonasumpu.online');


                // set default header data
                $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
                $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

                // set header and footer fonts
                $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

                // set default monospaced font
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

                // set margins
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

                // set auto page breaks
                $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

                // set image scale factor
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

                // set default font subsetting mode
                $pdf->setFontSubsetting(true);

                // Set font
                // dejavusans is a UTF-8 Unicode font, if you only need to
                // print standard ASCII chars, you can use core fonts like
                // helvetica or times to reduce file size.
                $pdf->SetFont('dejavusans', '', 14, '', true);


                // Add a page
                // This method has several options, check the source code documentation for more information.
                $pdf->AddPage();

                $contents = $this->packageModel->get_list_package_distinct()->getResultArray();
                $datareservation = $this->reservationModel->get_reservation_by_id($id)->getRowArray();
                $package_id_reservation = $datareservation['package_id'];

                //detail package 
                $package = $this->packageModel->get_package_by_id($package_id_reservation)->getRowArray();
                $serviceinclude = $this->detailServicePackageModel->get_service_include_by_id($package_id_reservation)->getResultArray();
                $serviceexclude = $this->detailServicePackageModel->get_service_exclude_by_id($package_id_reservation)->getResultArray();
                $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($package_id_reservation)->getResultArray();
                $getday = $this->packageDayModel->get_day_by_package($package_id_reservation)->getResultArray();
                $combinedData = $this->detailPackageModel->getCombinedData($package_id_reservation);

                if (!empty($getday)) {
                    $day = max($getday);
                    $daypack = $day['day'];
                    $dayhome = $day['day'] - 1;
                } else {
                    $day = 1;
                    $daypack = 1;
                    $dayhome = 0;
                }

                //data homestay
                $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();
                if ($datareservation['cancel'] == '0') {
                    $booking_unit = $this->detailReservationModel->get_unit_homestay_bookingnya($id)->getResultArray();
                } else if ($datareservation['cancel'] == '1') {
                    $booking_unit = $this->backupDetailReservationModel->get_unit_homestay_bookingnya($id)->getResultArray();
                }

                // $unit_booking= $this->detailReservationModel->get_unit_homestay_dtbooking($id)->getResultArray();

                // dd($booking_unit);
                if (!empty($booking_unit)) {
                    $data_unit_booking = array();
                    $data_price = array();
                    foreach ($booking_unit as $booking) {
                        $date = $booking['date'];
                        $homestay_id = $booking['homestay_id'];
                        $unit_type = $booking['unit_type'];
                        $unit_number = $booking['unit_number'];
                        $reservation_id = $booking['reservation_id'];
                        $unit_guest = $booking['unit_guest'];


                        if ($datareservation['cancel'] == '0') {
                            $unit_booking[] = $this->detailReservationModel->get_unit_homestay_booking_data($date, $homestay_id, $unit_type, $unit_number, $unit_guest, $id)->getRowArray();
                            $total_price_homestay = $this->detailReservationModel->get_price_homestay_booking($homestay_id, $unit_type, $unit_number, $id)->getRow();
                        } else if ($datareservation['cancel'] == '1') {
                            $unit_booking[] = $this->backupDetailReservationModel->get_unit_homestay_booking_data($date, $homestay_id, $unit_type, $unit_number, $unit_guest, $reservation_id)->getRowArray();
                            $total_price_homestay = $this->backupDetailReservationModel->get_price_homestay_booking($homestay_id, $unit_type, $unit_number, $id)->getRow();
                        }

                        $total[] = $total_price_homestay->price;
                    }

                    $data_price = $total;
                    // dd($data_price);
                    // $accomodation_type = $accomodationType;

                    $tphom = array_sum($data_price);
                    $tph = $tphom * $dayhome;
                    // $tph = array_sum($data_price);
                    $data_unit_booking = $unit_booking;
                } else {
                    $data_unit_booking = [];
                    $tph = '0';
                }

                // $check_in = "2023-10-29 11:51:00";
                $check_in = $datareservation['check_in'];
                if (!empty($getday)) {
                    $totday = max($getday);
                    $day = $totday['day'] - 1;
                } else {
                    $totday = 1;
                    $day = $totday - 1;
                }

                // Ubah $check_in menjadi objek DateTime 
                $check_in_datetime = new DateTime($check_in);

                if ($day == '0') {
                    $check_out = $check_in_datetime->format('Y-m-d') . ' 18:00:00';
                } else {
                    // Tambahkan jumlah hari
                    $check_in_datetime->modify('+' . $day . ' days');
                    // Atur waktu selalu menjadi 12:00:00
                    $check_out = $check_in_datetime->format('Y-m-d') . ' 12:00:00';
                }


                $name_admin_confirm = $datareservation['admin_confirm'];
                $getAdminC = $this->accountModel->get_profil_admin($datareservation['admin_confirm'])->getRowArray();
                if ($getAdminC != null) {
                    $datareservation['name_admin_confirm'] = $getAdminC['username'];
                } else {
                    $datareservation['name_admin_confirm'] = 'adm';
                }

                $name_admin_refund = $datareservation['admin_refund'];
                $getAdminR = $this->accountModel->get_profil_admin($datareservation['admin_refund'])->getRowArray();
                if ($getAdminR != null) {
                    $datareservation['name_admin_refund'] = $getAdminR['username'];
                } else {
                    $datareservation['name_admin_refund'] = 'adm';
                }

                $admin_deposit_check = $datareservation['admin_deposit_check'];
                $getAdminDP = $this->accountModel->get_profil_admin($datareservation['admin_deposit_check'])->getRowArray();
                if ($getAdminDP != null) {
                    $datareservation['name_admin_deposit_check'] = $getAdminDP['username'];
                } else {
                    $datareservation['name_admin_deposit_check'] = 'adm';
                }

                $admin_payment_check = $datareservation['admin_payment_check'];
                $getAdminFP = $this->accountModel->get_profil_admin($datareservation['admin_payment_check'])->getRowArray();
                if ($getAdminFP != null) {
                    $datareservation['name_admin_payment_check'] = $getAdminFP['username'];
                } else {
                    $datareservation['name_admin_payment_check'] = 'adm';
                }

                if (empty($datareservation)) {
                    return redirect()->to('web/detailreservation');
                }
                $date = date('Y-m-d');

                $user_id = $datareservation['user_id'];
                $us = $this->userModel->get_users_by_id($user_id)->getRowArray();

                $data = [
                    //data package
                    'data_package' => $package,
                    'serviceinclude' => $serviceinclude,
                    'serviceexclude' => $serviceexclude,
                    'day' => $getday,
                    'daypack' => $daypack,
                    'activity' => $combinedData,
                    'detail' => $datareservation,
                    'customer' => $us,

                    //data homestay
                    'data' => $contents,
                    'list_unit' => $list_unit,
                    'date' => $date,
                    'dayhome' => $dayhome,
                    'check_out' => $check_out,
                    'data_unit' => $booking_unit,
                    'booking' => $data_unit_booking,
                    'price_home' => $tph,
                    // 'accomodation_type' => $accomodation_type,

                ];
                // return view('web/invoice', $data);

                //view mengarah ke invoice.php
                $html = view('web/invoice', $data);

                // Print text using writeHTMLCell()
                $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

                // Get the PDF content as a string
                $pdfContent = $pdf->Output('', 'S'); // 'S' to return as a string

                // Define the file path to save the PDF
                $pdfFilePath = WRITEPATH . 'uploads/invoice_' . date('YmdHis') . '.pdf'; // Adjust the file path as needed

                // Save the PDF to the server
                file_put_contents($pdfFilePath, $pdfContent);


                $email = \Config\Services::email();
                $email->setTo($customerEmail);
                $email->setSubject('Reservasi Anda di ' . $villageName);

                $message = "<p>Yth. $customerName,</p>";
                $message .= "<p>Terima kasih telah melakukan reservasi di $villageName!</p>";
                $message .= "<p>Reservasi Anda telah disimpan dengan detail sebagai berikut:</p><br>";
                $message .= "<p><span style='display: inline-block; width: 150px;'>ID Reservasi</span>: $reservation_id</p>";
                $message .= "<p><span style='display: inline-block; width: 150px;'>Nama Paket</span>: $packageName</p>";
                $message .= "<p><span style='display: inline-block; width: 150px;'>Tanggal Reservasi</span>: $reservation_date</p>";
                $message .= "<p><span style='display: inline-block; width: 150px;'>Waktu Reservasi</span>: $reservation_time WIB</p>";
                $message .= "<p><span style='display: inline-block; width: 150px;'>Note</span>: $note</p>";
                $message .= "<p><span style='display: inline-block; width: 150px;'>Status</span>: WAITING</p><br>";
                $message .= "<p>Konfirmasi atas reservasi ini akan segera diproses.</p><br>";
                $message .= "<p>Salam,</p>";
                $message .= "<p>Pokdarwis $villageName</p>";

                $email->setMessage($message);
                $email->setMailType('html');

                // Attach PDF to the email using the file path
                $email->attach($pdfFilePath, 'invoice.pdf', 'application/pdf');

                if ($email->send()) {

                    $villageName = $villageEmailData['name'];
                    $villageEmail = $villageEmailData['email'];

                    $email2 = \Config\Services::email();
                    $email2->setTo($villageEmail);
                    $email2->setSubject('Reservasi Baru ' . $villageName);

                    $message = "<p>Halo Admin,</p>";
                    $message .= "<p>Anda menerima notifikasi bahwa ada reservasi baru yang telah dibuat oleh pengguna. Berikut ini adalah rinciannya:</p><br>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>ID Reservasi</span>: $reservation_id</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Customer</span>: $customerName</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Nama Paket</span>: $packageName</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Tanggal Reservasi</span>: $reservation_date</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Waktu Reservasi</span>: $reservation_time WIB</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Note</span>: $note</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Status</span>: WAITING</p><br>";
                    $message .= "<p>Silakan segera melakukan tindak lanjut atas reservasi ini.</p>";
                    $message .= "<p>Terima kasih.</p>";

                    $email2->setMessage($message);
                    $email2->setMailType('html');
                    $email2->attach($pdfFilePath, 'invoice.pdf', 'application/pdf');
                    unlink($pdfFilePath); // Delete the PDF file from the server

                    if ($email2->send()) {
                        $response = ['message' => 'Both email notification sent successfully.'];
                        return $this->response->setJSON($response);
                    } else {
                        $response = ['message' => 'Failed to send email notification to admin.'];
                        return $this->response->setJSON($response);
                    }
                } else {
                    $response = ['message' => 'Failed to send email notification.'];
                    return $this->response->setJSON($response);
                }
            } else {
                $response = ['message' => 'Failed to get village information.'];
                return $this->response->setJSON($response);
            }
        }

        $package_id = $request['package_id'];

        // Check if the cart exists
        $array1 = array('package_id' => $package_id, 'user_id' => user()->id);
        $cart = $this->cartModel->where($array1)->find();

        if (!empty($cart)) {
            // Update the cart status
            $updateResult = $this->cartModel->updateCartStatus(user()->id, $package_id);

            if ($updateResult) {
                // Success message or further processing
                return $this->respond([
                    'status' => 200,
                    'message' => 'Cart status updated successfully.',
                ]);
            } else {
                // Error message
                return $this->respond([
                    'status' => 500,
                    'message' => 'Error updating cart status.',
                ]);
            }
        } else {
            // Cart not found
            return $this->respond([
                'status' => 404,
                'message' => 'Cart not found.',
            ]);
        }


        // // Mengirim respons ke klien dengan data reservation_id dan customer_email
        // return $this->respond([
        //     'status' => 200,
        //     'message' => 'Reservation created successfully.',
        //     'reservation_id' => $id,
        //     'customer_email' => user()->email,
        //     'package_id' => $request['package_id'],
        //     'reservation_date' => date('Y-m-d'),
        //     'reservation_time' => date('H:i:s'),
        // ]);
    }
}
