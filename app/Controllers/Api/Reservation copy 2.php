<?php

namespace App\Controllers\Api;

use App\Models\ReservationModel;
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
use App\Models\PackageDayModel;
use App\Models\AccountModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Reservation extends ResourceController
{
    use ResponseTrait;

    protected $reservationModel;
    protected $detailReservationModel;
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


    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
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
        // $params = array(
        //     'transaction_details' => array(
        //         'order_id' => $datareservation['id'],
        //         'gross_amount' => $datareservation['deposit'],
        //     ),
        //     'customer_details' => array(
        //         'first_name' => $datareservation['username'],
        //         'last_name' => $datareservation['fullname'],
        //         'email' => $datareservation['email'],
        //         'phone' => $datareservation['phone'],
        //     ),
        // );

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
        // $params = array(
        //     'transaction_details' => array(
        //         'order_id' => $datareservation['id'],
        //         'gross_amount' => $datareservation['deposit'],
        //     ),
        //     'customer_details' => array(
        //         'first_name' => $datareservation['username'],
        //         'last_name' => $datareservation['fullname'],
        //         'email' => $datareservation['email'],
        //         'phone' => $datareservation['phone'],
        //     ),
        // );

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

        // $checkInDate = '2024-04-17';
        // $totalPeople = '10';

        $checkExistingData = $this->detailReservationModel->checkIfUnitReserved($checkInDate);

        if (!$checkExistingData) {
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
                            'price' => $unit['price'],
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
                'datahome' => $response_data,
            ];
        } else {
            $list_homestay = $this->unitHomestayModel->get_homestay_by_reserved($checkInDate)->getResultArray();

            $response_data = ['houses' => []];

            $totalRemaining = 0;
            foreach ($list_homestay as $homestay) {
                $homestay_id = $homestay['homestay_id'];
                $unit_type = $homestay['unit_type'];
                $unit_number = $homestay['unit_number'];
                $unit_remaining = $homestay['unit_remaining'];
                $name = $homestay['name'];

                // Retrieve facility and gallery details
                $list_facility_rumah = $this->facilityHomestayDetailModel->get_detailFacilityHomestay_by_id($homestay_id)->getResultArray();
                $list_gallery = $this->galleryHomestayModel->get_gallery($homestay_id)->getResultArray();
                $galleries = array();
                foreach ($list_gallery as $gallery) {
                    $galleries[] = $gallery['url'];
                }
                $homestayData['gallery'] = $galleries;

                $facilities = array();
                $list_facility = $this->facilityUnitDetailModel->get_data_facility_unit_detail($unit_number, $homestay_id, $unit_type)->getResultArray();
                $facilities[] = $list_facility;
                $fc = $facilities;

                $units_selected = [];


                $units_selected[] = [
                    'homestay_id' => $homestay_id,
                    'unit_type' => $unit_type,
                    'unit_number' => $unit_number,
                    'unit_name' => $homestay['unit_name'],
                    'description' => $homestay['description'],
                    'price' => $homestay['price'],
                    'room_capacity' => $homestay['capacity'],
                    'url' => $homestay['url'],
                    'capacity' => $unit_remaining,
                    'total_reservations' => $homestay['total_reservations'],
                    'unit_guest' => $homestay['unit_guest'],
                    'muatan2' => $unit_remaining,
                    'facility_units' => $fc,
                ];

                if ($totalRemaining + $unit_remaining > $totalPeople) {
                    break;
                }

                $response_data['houses'][] = [
                    'id' => $homestay_id,
                    'name' => $name,
                    'gallery' => $homestayData['gallery'],
                    'facilities' => $list_facility_rumah,
                    'units' => $units_selected,

                ];

                $totalRemaining += $unit_remaining;
            }

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
        $checkInDate = '2024-04-17';
        $totalPeople = 10;

        $checkNormalOrRating = $this->detailReservationModel->get_normal_or_rating()->getResultArray();

        // Menghitung total elemen dalam array
        $total = count($checkNormalOrRating);

        // Mengecek apakah totalnya genap atau tidak
        if ($total % 2 != 0) {
            $tipe_pemilihan = "Normal";
            $checkExistingData = $this->detailReservationModel->checkIfUnitReserved($checkInDate);

            if (!$checkExistingData) {
                // $list_homestay = $this->unitHomestayModel->get_list_homestay()->getResultArray();
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
                    // $list_units = $this->unitHomestayModel->unit_tersedia($homestay_id, $checkInDate, $totalPeople)->getResultArray();

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
                    'tipe' => $tipe_pemilihan,
                    'datahome' => $response_data,
                ];
            } else {
                // Retrieve list of homestays
                $list_homestay = $this->unitHomestayModel->get_homestay_by_reserved($checkInDate)->getResultArray();


                // Initialize response data
                $response_data = ['houses' => []];

                $totalRemaining = 0;

                foreach ($list_homestay as $homestay) {
                    // Retrieve homestay details
                    $homestay_id = $homestay['homestay_id'];
                    $unit_type = $homestay['unit_type'];
                    $unit_number = $homestay['unit_number'];
                    $unit_remaining = $homestay['unit_remaining'];
                    $name = $homestay['name'];

                    // Check if adding this unit will exceed totalPeople
                    if ($totalRemaining + $unit_remaining > $totalPeople) {
                        break; // Stop processing if adding this unit exceeds totalPeople
                    }

                    // Add homestay to response
                    $response_data['houses'][] = [
                        'id' => $homestay_id,
                        'name' => $name,
                        // 'gallery' => $homestay['gallery'],
                        // 'facilities' => $list_facility_rumah,
                        'units' => [
                            'homestay_id' => $homestay_id,
                            'unit_type' => $unit_type,
                            'unit_number' => $unit_number,
                            'total_reservations' => $homestay['total_reservations'],
                            'unit_guest' => $homestay['unit_guest'],
                            'muatan' => $unit_remaining,
                        ]

                    ];

                    // Update totalRemaining
                    $totalRemaining += $unit_remaining;
                }
                // Prepare response
                $response = [
                    'status' => 200,
                    'message' => 'Success',
                    // 'check' => $checkNormalOrRating,
                    'tipe' => $tipe_pemilihan,
                    // 'data' => $list_homestay,
                    'datahome' => $response_data,
                ];
            }
        }

        return $this->respond($response, $response['status']);
    }


  

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
                        // 'accomodation_type' => $request['accomodationType'],
                    ];

                    // Add detail reservation
                    // $this->detailReservationModel->add_new_detail_reservation2($requestData);
                    $this->detailReservationModel->add_new_detail_reservation($requestData);
                }
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


        // if ($addRe) {
        //     return redirect()->to(base_url('web/detailreservation/' . $id));
        // } else {
        //     return redirect()->back()->withInput();
        // }
    }
}
