<?php

namespace App\Controllers\Web;

use App\Models\BackupDetailReservationModel;
use App\Models\DetailReservationModel;
use App\Models\ReservationModel;
use App\Models\UnitHomestayModel;
use App\Models\PackageModel;
use App\Models\PackageDayModel;
use App\Models\DetailPackageModel;
use App\Models\DetailServicePackageModel;
use App\Models\AccountModel;
use App\Models\GalleryPackageModel;

use App\Models\CulinaryPlaceModel;
use App\Models\WorshipPlaceModel;
use App\Models\FacilityModel;
use App\Models\SouvenirPlaceModel;
use App\Models\AttractionModel;
use App\Models\EventModel;
use App\Models\HomestayModel;
use App\Models\KubuGadangModel;
use App\Models\ServicePackageModel;

use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;
use DateTime;

class DetailReservation extends ResourcePresenter
{
    protected $backupDetailReservationModel;
    protected $detailReservationModel;
    protected $reservationModel;
    protected $galleryPackageModel;
    protected $unitHomestayModel;
    protected $packageModel;
    protected $packageDayModel;
    protected $detailPackageModel;
    protected $servicePackageModel;
    protected $detailServicePackageModel;
    protected $culinaryPlaceModel;
    protected $worshipPlaceModel;
    protected $facilityModel;
    protected $souvenirPlaceModel;
    protected $attractionModel;
    protected $eventModel;
    protected $homestayModel;
    protected $KubuGadangModel;
    protected $accountModel;


    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];
    protected $db, $builder;

    public function __construct()
    {
        $this->backupDetailReservationModel = new BackupDetailReservationModel();
        $this->detailReservationModel = new DetailReservationModel();
        $this->reservationModel = new ReservationModel();
        $this->unitHomestayModel = new UnitHomestayModel();
        $this->packageModel = new PackageModel();
        $this->packageDayModel = new PackageDayModel();
        $this->detailPackageModel = new DetailPackageModel();
        $this->servicePackageModel = new ServicePackageModel();
        $this->detailServicePackageModel = new DetailServicePackageModel();
        $this->culinaryPlaceModel = new CulinaryPlaceModel();
        $this->worshipPlaceModel = new WorshipPlaceModel();
        $this->facilityModel = new FacilityModel();
        $this->souvenirPlaceModel = new SouvenirPlaceModel();
        $this->attractionModel = new AttractionModel();
        $this->eventModel = new EventModel();
        $this->homestayModel = new HomestayModel();
        $this->KubuGadangModel = new KubuGadangModel();
        $this->accountModel = new AccountModel();
        $this->galleryPackageModel = new GalleryPackageModel();


        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('package');;
    }

    public function addcustom()
    {
        $id = $this->packageModel->get_new_id();

        $date = date('Y-m-d H:i');
        $user = user()->username;
        $requestData = [
            'id' => $id,
            'name' => 'Custom by ' . $user . ' at ' . $date,
            'type_id' => 'T0000',
            'min_capacity' => '5',
            'price' => null,
            'description' => 'This tour package is customized by the user',
            'contact_person' => null,
            'custom' => '1'
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $geom = null;
        $geojson = null;

        if (isset($request['video'])) {
            $folder = $request['video'];
            $filepath = WRITEPATH . 'uploads/' . $folder;
            $filenames = get_filenames($filepath);
            $vidFile = new File($filepath . '/' . $filenames[0]);
            $vidFile->move(FCPATH . 'media/videos');
            delete_files($filepath);
            rmdir($filepath);
            $requestData['video_url'] = $vidFile->getFilename();
        }

        $addPA = $this->packageModel->add_new_package($requestData);
        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
                $fileImg->move(FCPATH . 'media/photos/package');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
            $this->galleryPackageModel->add_new_gallery($id, $gallery);
        }


        if ($addPA) {
            // return redirect()->to(base_url('web/detailreservation/packagecustom/') . $id);
            return redirect()->to(base_url('web/package/custom/') . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function packagecustom($id)
    {
        $package = $this->packageModel->get_package_by_id($id)->getRowArray();
        $package_id = $package['id'];
        $packageDay = $this->packageDayModel->get_package_day_by_id($package_id)->getResultArray();

        $culinary = $this->culinaryPlaceModel->get_list_cp()->getResultArray();
        $worship = $this->worshipPlaceModel->get_list_wp()->getResultArray();
        $facility = $this->facilityModel->get_list_facility()->getResultArray();
        $souvenir = $this->souvenirPlaceModel->get_list_sp()->getResultArray();
        $attraction = $this->attractionModel->get_list_attraction()->getResultArray();
        $event = $this->eventModel->get_list_event()->getResultArray();
        $homestay = $this->homestayModel->get_list_homestay_homestay()->getResultArray();

        $data_object = array_merge($culinary, $worship, $facility, $souvenir, $attraction, $event, $homestay);
        $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($package_id)->getResultArray();
        $combinedData = $this->detailPackageModel->getCombinedData($package_id);

        $object = [
            'culinary' => $culinary,
            'worship' => $worship,
            'facility' => $facility,
            'souvenir' => $souvenir,
            'attraction' => $attraction,
            'event' => $event,
            'homestay' => $homestay
        ];

        $servicelist = $this->servicePackageModel->get_list_service_package()->getResultArray();

        $this->builder->select('service_package.id, service_package.name');
        $this->builder->join('detail_service_package', 'detail_service_package.package_id = package.id');
        $this->builder->join('service_package', 'service_package.id = detail_service_package.service_package_id', 'right');
        $this->builder->where('package.id', $id);
        $query = $this->builder->get();
        $datase['package'] = $query->getResult();
        $datases = $datase['package'];
        $package['datase'] = $datases;

        $servicepackage = $this->detailServicePackageModel->get_service_package_detail_by_id($id)->getResultArray();

        $data = [
            'title' => 'Detail Package ' . $package['name'],
            'id' => $id,
            'data' => $package,
            'day' => $packageDay,
            'activity' => $detailPackage,
            'data_package' => $combinedData,
            'object' => $object,
            'detailservice' => $servicepackage,
            'service' => $package['datase'],
            'servicelist' => $servicelist
        ];
        // dd( $data);


        return view('web/custom-package-form', $data, $object);
    }

    public function addextend($base_id)
    {
        $id = $this->packageModel->get_new_id();
        $package = $this->packageModel->get_package_by_id($base_id)->getRowArray();

        $name_package = $package['name'];
        $date = date('Y-m-d H:i');
        $user = user()->username;
        $requestData = [
            'id' => $id,
            'name' => $name_package . ' extend by ' . $user . ' at ' . $date,
            'type_id' => $package['type_id'],
            'min_capacity' => $package['min_capacity'],
            'price' => $package['price'],
            'description' => 'This tour package is extend from ' . $name_package . ' .' . $package['description'],
            'contact_person' => $package['contact_person'],
            'custom' => '1'
        ];
        $addPA = $this->packageModel->add_new_package($requestData);

        $packageDay = $this->packageDayModel->get_package_day_by_id($base_id)->getResultArray();
        foreach ($packageDay as $data) {
            $newData = array(
                'day' => $data['day'],
                'package_id' => $id,
                'description' => $data['description'],
                'status' => 0
            );
            $addPD = $this->packageDayModel->add_new_packageDay($newData);
        }

        $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($base_id)->getResultArray();
        foreach ($detailPackage as $data) {
            $newData = array(
                'activity' => $data['activity'],
                'day' => $data['day'],
                'package_id' => $id,
                'activity_type' => $data['activity_type'],
                'object_id' => $data['object_id'],
                'description' => $data['description'],
                'status' => 0

            );
            $addDP = $this->detailPackageModel->add_new_packageActivity($newData);
        }

        $servicepackage = $this->detailServicePackageModel->get_service_package_detail_by_id($base_id)->getResultArray();
        foreach ($servicepackage as $data) {
            $newData = array(
                'service_package_id' => $data['service_package_id'],
                'package_id' => $id,
                'status' => $data['status'],
                'status_created' => 0
            );
            $addSP = $this->detailServicePackageModel->add_new_detail_service_package($newData);
        }

        // if ($addSP) {
        return redirect()->to(base_url('web/package/extend/') . $id);
        // } else {
        // return redirect()->back()->withInput();
        // } 

    }

    public function createday($id)
    {

        $request = $this->request->getPost();

        $requestData = [
            'package_id' => $id,
            'day' => $request['day'],
            'description' => $request['description'],
            'status' => 1

        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $addPD = $this->packageDayModel->add_new_packageDay($requestData);

        if ($addPD) {
            // return view('dashboard/detail-package-form');
            $package = $this->packageModel->get_package_by_id($id)->getRowArray();

            $combinedDataPrice = $this->detailPackageModel->getCombinedDataPrice($id);
            $combinedServicePrice = $this->detailServicePackageModel->getCombinedServicePrice($id);

            $requestDataPrice = [
                'id' => $id,
                'price' => $combinedDataPrice + $combinedServicePrice,
            ];
            $updatePA = $this->packageModel->update_package($id, $requestDataPrice);

            $id = $package['id'];
            $data = [
                'title' => 'New Detail Package',
                'data' => $package
            ];

            // return view('dashboard/detail-package-form', $data);

            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function createactivity($id)
    {
        $request = $this->request->getPost();

        $requestData = [
            'package_id' => $id,
            'day' => $request['day'],
            'activity' => $request['activity'],
            'activity_type' => $request['activity_type'],
            'object_id' => $request['object'],
            'description' => $request['description_activity'],
            'status' => 1
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $checkExistingData = $this->detailPackageModel->checkIfDataExists($requestData);

        if ($checkExistingData) {
            // Data sudah ada, set pesan error flash data
            session()->setFlashdata('failed', 'The sequence of activities already exists.');

            return redirect()->back()->withInput();
        } else {
            // Data belum ada, jalankan query insert
            $addPA = $this->detailPackageModel->add_new_packageActivity($requestData);



            if ($addPA) {
                // $package_min_capacity = $request['package_min_capacity'];
                $combinedDataPrice = $this->detailPackageModel->getCombinedDataPrice($id);
                $combinedServicePrice = $this->detailServicePackageModel->getCombinedServicePrice($id);

                $requestDataPrice = [
                    'id' => $id,
                    'price' => $combinedDataPrice + $combinedServicePrice,
                ];
                $updatePAA = $this->packageModel->update_package_price($id, $requestDataPrice);

                if ($updatePAA) {
                    // return view('dashboard/detail-package-form');
                    session()->setFlashdata('success', 'The activity data was added successfully.');

                    return redirect()->back();
                }
            } else {
                return redirect()->back()->withInput();
            }
        }
    }


    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    // public function addhome($id = null)
    // {
    //     $contents = $this->packageModel->get_list_package_distinct()->getResultArray();
    //     $datareservation = $this->reservationModel->get_reservation_by_id($id)->getRowArray();
    //     $datatourismvillage = $this->KubuGadangModel->get_sumpu()->getResultArray();
    //     $package_id_reservation = $datareservation['package_id'];

    //     //detail package 
    //     $package = $this->packageModel->get_package_by_id($package_id_reservation)->getRowArray();
    //     $serviceinclude = $this->detailServicePackageModel->get_service_include_by_id($package_id_reservation)->getResultArray();
    //     $serviceexclude = $this->detailServicePackageModel->get_service_exclude_by_id($package_id_reservation)->getResultArray();
    //     $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($package_id_reservation)->getResultArray();
    //     $getday = $this->packageDayModel->get_day_by_package($package_id_reservation)->getResultArray();
    //     $combinedData = $this->detailPackageModel->getCombinedData($package_id_reservation);

    //     //data homestay homestay
    //     $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();
    //     if ($datareservation['cancel'] == '0') {
    //         $booking_unit = $this->detailReservationModel->get_unit_homestay_bookingnya($id)->getResultArray();
    //     } else if ($datareservation['cancel'] == '1') {
    //         $booking_unit = $this->backupDetailReservationModel->get_unit_homestay_bookingnya($id)->getResultArray();
    //     }

    //     if (!empty($getday)) {
    //         $day = max($getday);
    //         $daypack = $day['day'];
    //         $dayhome = $day['day'] - 1;
    //     } else {
    //         $day = 1;
    //         $daypack = 1;
    //         $dayhome = 0;
    //     }

    //     if (!empty($booking_unit)) {
    //         $data_unit_booking = array();
    //         $data_price = array();
    //         foreach ($booking_unit as $booking) {
    //             $date = $booking['date'];
    //             $homestay_id = $booking['homestay_id'];
    //             $unit_type = $booking['unit_type'];
    //             $unit_number = $booking['unit_number'];
    //             $reservation_id = $booking['reservation_id'];

    //             if ($datareservation['cancel'] == '0') {
    //                 $unit_booking[] = $this->detailReservationModel->get_unit_homestay_booking_data($date, $homestay_id, $unit_type, $unit_number, $id)->getRowArray();
    //                 $total_price_homestay = $this->detailReservationModel->get_price_homestay_booking($homestay_id, $unit_type, $unit_number, $id)->getRow();
    //             } else if ($datareservation['cancel'] == '1') {
    //                 $unit_booking[] = $this->backupDetailReservationModel->get_unit_homestay_booking_data($date, $homestay_id, $unit_type, $unit_number, $id)->getRowArray();
    //                 $total_price_homestay = $this->backupDetailReservationModel->get_price_homestay_booking($homestay_id, $unit_type, $unit_number, $id)->getRow();
    //             }

    //             $total[] = $total_price_homestay->price;
    //         }

    //         $data_price = $total;
    //         $tphom = array_sum($data_price);
    //         $tph = $tphom;
    //         $data_unit_booking = $unit_booking;
    //     } else {
    //         $data_unit_booking = [];
    //         $tph = '0';
    //     }

    //     if (empty($datareservation)) {
    //         return redirect()->to('web/detailreservation');
    //     }
    //     $date = date('Y-m-d');

    //     // $check_in = "2023-10-29 11:51:00";
    //     $check_in = $datareservation['check_in'];

    //     if (!empty($getday)) {
    //         $totday = max($getday);
    //         $day = $totday['day'] - 1;
    //     } else {
    //         $totday = 1;
    //         $day = $totday - 1;
    //     }

    //     // Ubah $check_in menjadi objek DateTime untuk mempermudah perhitungan
    //     $check_in_datetime = new DateTime($check_in);
    //     if ($day == '0') {
    //         $check_out = $check_in_datetime->format('Y-m-d') . ' 18:00:00';
    //     } else {
    //         // Tambahkan jumlah hari
    //         $check_in_datetime->modify('+' . $day . ' days');
    //         // Atur waktu selalu menjadi 12:00:00
    //         $check_out = $check_in_datetime->format('Y-m-d') . ' 12:00:00';
    //     }


    //     $requestDate = $datareservation['request_date'];
    //     $request_date_datetime = new DateTime($requestDate);

    //     $timeDifference = $check_in_datetime->diff($request_date_datetime);
    //     $days = $timeDifference->d;

    //     if ($days >= 3) {
    //         $batas_dp_dt = $check_in_datetime->modify('-' . '3' . ' days');
    //         $batas_dp = $batas_dp_dt->format('Y-m-d H:i:s');

    //         $batas_cancel_dt = $check_in_datetime->modify('-' . '3' . ' days');
    //         $batas_cancel = $batas_cancel_dt->format('Y-m-d H:i:s');
    //     } elseif ($days < 3) {
    //         $batas_dp_dt = $request_date_datetime->modify('+12 hours');
    //         $batas_dp = $batas_dp_dt->format('Y-m-d H:i:s');

    //         $batas_cancel_dt = $request_date_datetime->modify('+12 hours');
    //         $batas_cancel = $batas_cancel_dt->format('Y-m-d H:i:s');
    //     }

    //     $data = [
    //         //data package
    //         'data_package' => $package,
    //         'serviceinclude' => $serviceinclude,
    //         'serviceexclude' => $serviceexclude,
    //         'day' => $getday,
    //         'daypack' => $daypack,
    //         'activity' => $combinedData,
    //         'detail' => $datareservation,

    //         //data homestay homestay
    //         'title' => 'Reservation Homestay',
    //         'data' => $contents,
    //         'datatourismvillage' => $datatourismvillage,
    //         'list_unit' => $list_unit,
    //         'date' => $date,
    //         'dayhome' => $dayhome,
    //         'check_out' => $check_out,
    //         'data_unit' => $booking_unit,
    //         'booking' => $data_unit_booking,
    //         'price_home' => $tph,
    //         'batas_dp' => $batas_dp,
    //     ];
    //     // dd($data);
    //     return view('web/detail-reservation-form', $data);
    // }

    public function choosehome()
    {
        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();
    }



    public function findhome()
    {
        $request = $this->request->getPost();
        $contents = $this->unitHomestayModel->get_unit_homestay($request)->getResult();

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success find unit homestay by id"
            ]
        ];
        return $this->respond($response);
    }


    public function show($id = null)
    {
        $contents = $this->packageModel->get_list_package_distinct()->getResultArray();
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();

        $datareservation = $this->reservationModel->get_reservation_by_id($id)->getRowArray();
        $datatourismvillage = $this->KubuGadangModel->get_sumpu()->getResultArray();
        $package_reservation = $datareservation['package_id'];

        //detail package 
        $package = $this->packageModel->get_package_by_id($package_reservation)->getRowArray();
        $serviceinclude = $this->detailServicePackageModel->get_service_include_by_id($package_reservation)->getResultArray();
        $serviceexclude = $this->detailServicePackageModel->get_service_exclude_by_id($package_reservation)->getResultArray();
        $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($package_reservation)->getResultArray();
        $getday = $this->packageDayModel->get_day_by_package($package_reservation)->getResultArray();
        $combinedData = $this->detailPackageModel->getCombinedData($package_reservation);

        if (!empty($getday)) {
            $day = max($getday);
            $daypack = $day['day'];
            $dayhome = $day['day'] - 1;
        } else {
            $day = 1;
            $daypack = 1;
            $dayhome = 0;
        }

        //data homestay homestay
        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();

        if ($datareservation['cancel'] == '0') {
            $booking_unit = $this->detailReservationModel->get_unit_homestay_bookingnya($id)->getResultArray();
        } else if ($datareservation['cancel'] == '1') {
            $booking_unit = $this->backupDetailReservationModel->get_unit_homestay_bookingnya($id)->getResultArray();
        }


        if (!empty($booking_unit)) {
            $data_unit_booking = array();
            $data_price = array();
            foreach ($booking_unit as $booking) {
                $date = $booking['date'];
                $homestay_id = $booking['homestay_id'];
                $unit_type = $booking['unit_type'];
                $unit_number = $booking['unit_number'];
                $reservation_id = $booking['reservation_id'];
                // $accomodationType = $booking['accomodation_type'];


                if ($datareservation['cancel'] == '0') {
                    $unit_booking[] = $this->detailReservationModel->get_unit_homestay_booking_data($date, $homestay_id, $unit_type, $unit_number, $id)->getRowArray();
                    $total_price_homestay = $this->detailReservationModel->get_price_homestay_booking($homestay_id, $unit_type, $unit_number, $id)->getRow();
                } else if ($datareservation['cancel'] == '1') {
                    $unit_booking[] = $this->backupDetailReservationModel->get_unit_homestay_booking_data($date, $homestay_id, $unit_type, $unit_number, $id)->getRowArray();
                    $total_price_homestay = $this->backupDetailReservationModel->get_price_homestay_booking($homestay_id, $unit_type, $unit_number, $id)->getRow();
                }

                $total[] = $total_price_homestay->price;
            }

            // $accomodation_type = $accomodationType;

            $data_price = $total;
            $tphom = array_sum($data_price);
            $tph = $tphom * $dayhome;
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

        // $token_of_deposit = $datareservation['token_of_deposit'];

        $datareservation['fullpayment'] = $datareservation['total_price'] - $datareservation['deposit'];


        // Ubah $check_in menjadi objek DateTime untuk mempermudah perhitungan
        $check_in_datetime = new DateTime($check_in);

        if ($day == '0') {
            $check_out = $check_in_datetime->format('Y-m-d') . ' 18:00:00';
        } else {
            // Tambahkan jumlah hari
            $check_in_datetime->modify('+' . $day . ' days');
            // Atur waktu selalu menjadi 12:00:00
            $check_out = $check_in_datetime->format('Y-m-d') . ' 12:00:00';
        }

        if (empty($datareservation)) {
            return redirect()->to('web/detailreservation');
        }
        $date = date('Y-m-d');

        $requestDate = $datareservation['request_date'];
        $request_date_datetime = new DateTime($requestDate);

        $timeDifference = $check_in_datetime->diff($request_date_datetime);
        $days = $timeDifference->d;

        if ($days >= 3) {
            $batas_dp_dt = $check_in_datetime->modify('-' . '3' . ' days');
            $batas_dp = $batas_dp_dt->format('Y-m-d H:i:s');

            $batas_cancel_dt = $check_in_datetime->modify('-' . '3' . ' days');
            $batas_cancel = $batas_cancel_dt->format('Y-m-d H:i:s');
        } elseif ($days < 3) {
            $batas_dp_dt = $request_date_datetime->modify('+12 hours');
            $batas_dp = $batas_dp_dt->format('Y-m-d H:i:s');

            $batas_cancel_dt = $request_date_datetime->modify('+12 hours');
            $batas_cancel = $batas_cancel_dt->format('Y-m-d H:i:s');
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

        $data = [
            //data package
            'data_package' => $package,
            'serviceinclude' => $serviceinclude,
            'serviceexclude' => $serviceexclude,
            'day' => $getday,
            'daypack' => $daypack,
            'activity' => $combinedData,

            //data homestay homestay
            'title' => 'Reservation Homestay',
            'data' => $contents,
            'data2' => $contents2,
            'detail' => $datareservation,
            'datatourismvillage' => $datatourismvillage,
            'list_unit' => $list_unit,
            'date' => $date,
            'dayhome' => $dayhome,
            'check_out' => $check_out,
            'batas_dp' => $batas_dp,
            'batas_cancel' => $batas_cancel,
            'data_unit' => $booking_unit,
            'booking' => $data_unit_booking,
            'price_home' => $tph,
            // 'accomodation_type' => $accomodation_type,

        ];
        // dd($data);
        if ($datareservation['type_of_payment'] == 1) {
            return view('web/detail-reservation-form-manual', $data);
        } else {
            return view('web/detail-reservation-form', $data);
        }
    }

    public function confirm($id = null)
    {
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();

        $contents = $this->packageModel->get_list_package_distinct()->getResultArray();
        $datareservation = $this->reservationModel->get_reservation_by_id($id)->getRowArray();
        // dd($id);
        $package_reservation = $datareservation['package_id'];

        //detail package 
        $package = $this->packageModel->get_package_by_id($package_reservation)->getRowArray();
        $serviceinclude = $this->detailServicePackageModel->get_service_include_by_id($package_reservation)->getResultArray();
        $serviceexclude = $this->detailServicePackageModel->get_service_exclude_by_id($package_reservation)->getResultArray();
        $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($package_reservation)->getResultArray();
        $getday = $this->packageDayModel->get_day_by_package($package_reservation)->getResultArray();
        $combinedData = $this->detailPackageModel->getCombinedData($package_reservation);

        if (!empty($getday)) {
            $day = max($getday);
            $daypack = $day['day'];
            $dayhome = $day['day'] - 1;
        } else {
            $day = 1;
            $daypack = 1;
            $dayhome = 0;
        }

        //data homestay homestay
        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();
        if ($datareservation['cancel'] == '0') {
            $booking_unit = $this->detailReservationModel->get_unit_homestay_bookingnya($id)->getResultArray();
        } else if ($datareservation['cancel'] == '1') {
            $booking_unit = $this->backupDetailReservationModel->get_unit_homestay_bookingnya($id)->getResultArray();
        }

        if (!empty($booking_unit)) {
            $data_unit_booking = array();
            $data_price = array();
            foreach ($booking_unit as $booking) {
                $date = $booking['date'];
                $homestay_id = $booking['homestay_id'];
                $unit_type = $booking['unit_type'];
                $unit_number = $booking['unit_number'];
                $reservation_id = $booking['reservation_id'];
                // $accomodationType = $booking['accomodation_type'];


                if ($datareservation['cancel'] == '0') {
                    $unit_booking[] = $this->detailReservationModel->get_unit_homestay_booking_data($date, $homestay_id, $unit_type, $unit_number, $id)->getRowArray();
                    $total_price_homestay = $this->detailReservationModel->get_price_homestay_booking($homestay_id, $unit_type, $unit_number, $id)->getRow();
                } else if ($datareservation['cancel'] == '1') {
                    $unit_booking[] = $this->backupDetailReservationModel->get_unit_homestay_booking_data($date, $homestay_id, $unit_type, $unit_number, $id)->getRowArray();
                    $total_price_homestay = $this->backupDetailReservationModel->get_price_homestay_booking($homestay_id, $unit_type, $unit_number, $id)->getRow();
                }

                $total[] = $total_price_homestay->price;
            }

            $data_price = $total;

            // $accomodation_type = $accomodationType;

            $tphom = array_sum($data_price);
            $tph = $tphom;
            $data_unit_booking = $unit_booking;
        } else {
            $data_unit_booking = [];
            $tph = '0';
        }

        // dd($booking_unit);
        if (empty($datareservation)) {
            return redirect()->to('web/detailreservation');
        }
        $date = date('Y-m-d');

        $check_in = $datareservation['check_in'];

        if (!empty($getday)) {
            $totday = max($getday);
            $day = $totday['day'] - 1;
        } else {
            $totday = 1;
            $day = $totday - 1;
        }

        $datareservation['fullpayment'] = $datareservation['total_price'] - $datareservation['deposit'];


        // Ubah $check_in menjadi objek DateTime untuk mempermudah perhitungan
        $check_in_datetime = new DateTime($check_in);

        if ($day == '0') {
            $check_out = $check_in_datetime->format('Y-m-d') . ' 18:00:00';
        } else {
            // Tambahkan jumlah hari
            $check_in_datetime->modify('+' . $day . ' days');
            // Atur waktu selalu menjadi 12:00:00
            $check_out = $check_in_datetime->format('Y-m-d') . ' 12:00:00';
        }

        $requestDate = $datareservation['request_date'];
        $request_date_datetime = new DateTime($requestDate);

        $timeDifference = $check_in_datetime->diff($request_date_datetime);
        $days = $timeDifference->d;

        // dd($days);

        if ($days > 3) {
            $batas_dp_dt = $check_in_datetime->modify('-' . '3' . ' days');
            $batas_dp = $batas_dp_dt->format('Y-m-d H:i:s');

            $batas_cancel_dt = $check_in_datetime->modify('-' . '2' . ' days');
            $batas_cancel = $batas_cancel_dt->format('Y-m-d H:i:s');
        } elseif ($days <= 3) {
            $batas_dp_dt = $request_date_datetime->modify('+' . '12' . ' hours');
            $batas_dp = $batas_dp_dt->format('Y-m-d H:i:s');

            $batas_cancel_dt = $request_date_datetime->modify('+' . '12' . ' hours');
            $batas_cancel = $batas_cancel_dt->format('Y-m-d H:i:s');
        };

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


        $data = [
            //data package
            'data_package' => $package,
            'serviceinclude' => $serviceinclude,
            'serviceexclude' => $serviceexclude,
            'day' => $getday,
            'daypack' => $daypack,
            'activity' => $combinedData,

            //data homestay homestay
            'title' => 'Reservation Homestay',
            'data' => $contents,
            'data2' => $contents2,
            'detail' => $datareservation,
            'list_unit' => $list_unit,
            'date' => $date,
            'check_out' => $check_out,
            'batas_dp' => $batas_dp,
            'batas_cancel' => $batas_cancel,
            'dayhome' => $dayhome,
            'data_unit' => $booking_unit,
            'booking' => $data_unit_booking,
            'price_home' => $tph,
            // 'accomodation_type' => $accomodation_type,

        ];
        // dd($data);
        return view('dashboard/detail-reservation-confirm', $data);
    }

    public function saveconfirm($id = null)
    {
        $request = $this->request->getPost();
        $date = date('Y-m-d H:i');

        $requestData = [
            'status' => $request['status'],
            'confirmation_date' => $date,
            'feedback' => $request['feedback'],
            'admin_confirm' => $request['admin_confirm'],
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $updateDR = $this->reservationModel->update_reservation($id, $requestData);

        $requestData2 = [
            'unit_status' => $request['status'],           
        ];

        foreach ($requestData2 as $key => $value) {
            if (empty($value)) {
                unset($requestData2[$key]);
            }
        }

        $updateDDR = $this->reservationModel->update_detail_reservation($id, $requestData2);

        if ($updateDR) {
            $reservation_id = $id;
            $reservation = $this->reservationModel->get_reservation_by_id($reservation_id)->getRowArray();
            $customerName = $reservation['username'];
            $customerEmail = $reservation['email'];
            $packageName = $reservation['package_name'];
            $confirmation_date = date('Y-m-d');
            $confirmation_time = date('H:i:s');
            $feedback = $request['feedback'];

            $statusreservasi = $request['status'];
            if ($statusreservasi == 1) {
                $villageEmailData = $this->KubuGadangModel->get_desa_wisata_info()->getRowArray();
                if ($villageEmailData) {
                    $villageName = $villageEmailData['name'];

                    $email = \Config\Services::email();
                    $email->setTo($customerEmail);
                    $email->setSubject('Konfirmasi Reservasi Anda di ' . $villageName);

                    $message = "<p>Yth. $customerName,</p>";
                    $message .= "<p>Reservasi Anda telah diterima dan dikonfirmasi dengan detail sebagai berikut:</p><br>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>ID Reservasi</span>: $reservation_id</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Nama Paket</span>: $packageName</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Tanggal Konfirmasi</span>: $confirmation_date</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Waktu Konfirmasi</span>: $confirmation_time WIB</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Feedback Admin</span>: $feedback</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Status</span>: PAY DEPOSIT</p><br>";
                    $message .= "<p>Silahkan lakukan pembayaran deposit pada website kami.</p><br>";
                    $message .= "<p>Salam,</p>";
                    $message .= "<p>Pokdarwis $villageName</p>";

                    $email->setMessage($message);
                    $email->setMailType('html');


                    $email->setMessage($message);
                    $email->setMailType('html');

                    if ($email->send()) {

                        $villageName = $villageEmailData['name'];
                        $villageEmail = $villageEmailData['email'];

                        $email2 = \Config\Services::email();
                        $email2->setTo($villageEmail);
                        $email2->setSubject('Konfirmasi Reservasi ' . $villageName);

                        $message = "<p>Halo Admin,</p>";
                        $message .= "<p>Reservasi $reservation_id telah diterima dan dikonfirmasi dengan detail sebagai berikut:</p><br>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>ID Reservasi</span>: $reservation_id</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Nama Paket</span>: $packageName</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Tanggal Konfirmasi</span>: $confirmation_date</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Waktu Konfirmasi</span>: $confirmation_time WIB</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Feedback Admin</span>: $feedback</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Status</span>: PAY DEPOSIT</p><br>";
                        $message .= "<p>Silakan tunggu customer melakukan pembayaran deposit pada reservasi ini.</p>";
                        $message .= "<p>Terima kasih.</p>";

                        $email2->setMessage($message);
                        $email2->setMailType('html');

                        if ($email2->send()) {
                            return redirect()->back();
                        } else {
                            return redirect()->back();
                        }
                    } else {
                        return redirect()->back();
                    }
                } else {
                    return redirect()->back();
                }
            } else if ($statusreservasi == 2) {
                $villageEmailData = $this->KubuGadangModel->get_desa_wisata_info()->getRowArray();
                if ($villageEmailData) {
                    $villageName = $villageEmailData['name'];

                    $email = \Config\Services::email();
                    $email->setTo($customerEmail);
                    $email->setSubject('Konfirmasi Reservasi Anda di ' . $villageName);

                    $message = "<p>Yth. $customerName,</p>";
                    $message .= "<p>Reservasi Anda telah ditolak dan dikonfirmasi dengan detail sebagai berikut:</p><br>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>ID Reservasi</span>: $reservation_id</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Nama Paket</span>: $packageName</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Tanggal Konfirmasi</span>: $confirmation_date</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Waktu Konfirmasi</span>: $confirmation_time WIB</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Feedback Admin</span>: $feedback</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Status</span>: REJECTED</p><br>";
                    $message .= "<p>Salam,</p>";
                    $message .= "<p>Pokdarwis $villageName</p>";

                    $email->setMessage($message);
                    $email->setMailType('html');


                    $email->setMessage($message);
                    $email->setMailType('html');

                    if ($email->send()) {

                        $villageName = $villageEmailData['name'];
                        $villageEmail = $villageEmailData['email'];

                        $email2 = \Config\Services::email();
                        $email2->setTo($villageEmail);
                        $email2->setSubject('Konfirmasi Reservasi ' . $villageName);

                        $message = "<p>Halo Admin,</p>";
                        $message .= "<p>Reservasi $reservation_id telah ditolak dan dikonfirmasi dengan detail sebagai berikut:</p><br>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>ID Reservasi</span>: $reservation_id</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Nama Paket</span>: $packageName</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Tanggal Konfirmasi</span>: $confirmation_date</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Waktu Konfirmasi</span>: $confirmation_time WIB</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Feedback Admin</span>: $feedback</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Status</span>: REJECTED</p><br>";
                        $message .= "<p>Terima kasih.</p>";

                        $email2->setMessage($message);
                        $email2->setMailType('html');

                        if ($email2->send()) {
                            return redirect()->back();
                        } else {
                            return redirect()->back();
                        }
                    } else {
                        return redirect()->back();
                    }
                } else {
                    return redirect()->back();
                }
            }

            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function savecancel($id = null)
    {
        $booking_unit = $this->detailReservationModel->get_unit_homestay_bookingnya($id)->getResultArray();

        if ($booking_unit != null) {
            foreach ($booking_unit as $data_unit) {
                $date = $data_unit['date'];
                $unit_number = $data_unit['unit_number'];
                $homestay_id = $data_unit['homestay_id'];
                $unit_type = $data_unit['unit_type'];
                $reservation_id = $data_unit['reservation_id'];
                // $accomodation_type = $data_unit['accomodation_type'];
                // $array = array('date' => $date, 'unit_number' => $unit_number, 'homestay_id' => $homestay_id, 'unit_type' => $unit_type, 'reservation_id' => $reservation_id, 'accomodation_type' => $accomodation_type);
                $array = array('date' => $date, 'unit_number' => $unit_number, 'homestay_id' => $homestay_id, 'unit_type' => $unit_type, 'reservation_id' => $reservation_id);

                $addBDE = $this->backupDetailReservationModel->add_backup($array);
                $deletefromDE = $this->detailReservationModel->where($array)->delete();
            }
        }
        $date = date('Y-m-d H:i');

        $requestData = [
            'cancel' =>  '1',
            'cancel_date' => $date,
        ];
        $updateDR = $this->reservationModel->update_cancel($id, $requestData);

        if ($updateDR) {
            session()->setFlashdata('success', 'Reservation has been canceled');

            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function saverefund($id = null)
    {
        $booking_unit = $this->detailReservationModel->get_unit_homestay_bookingnya($id)->getResultArray();

        if ($booking_unit != null) {
            foreach ($booking_unit as $data_unit) {
                $date = $data_unit['date'];
                $unit_number = $data_unit['unit_number'];
                $homestay_id = $data_unit['homestay_id'];
                $unit_type = $data_unit['unit_type'];
                $reservation_id = $data_unit['reservation_id'];
                // $accomodation_type = $data_unit['accomodation_type'];
                // $array = array('date' => $date, 'unit_number' => $unit_number, 'homestay_id' => $homestay_id, 'unit_type' => $unit_type, 'reservation_id' => $reservation_id, 'accomodation_type' => $accomodation_type);
                $array = array('date' => $date, 'unit_number' => $unit_number, 'homestay_id' => $homestay_id, 'unit_type' => $unit_type, 'reservation_id' => $reservation_id);

                $addBDE = $this->backupDetailReservationModel->add_backup($array);
                $deletefromDE = $this->detailReservationModel->where($array)->delete();
            }
        }
        $request = $this->request->getPost();

        $date = date('Y-m-d H:i');

        $datareservation = $this->reservationModel->get_reservation_by_id($id)->getRowArray();

        $check_in = strtotime($datareservation['check_in']); // Konversi string tanggal ke timestamp
        $check_in_min_3_days = strtotime('-3 days', $check_in); // Tambahkan 2 hari
        $batasdp = date('Y-m-d H:i', $check_in_min_3_days);

        if ($datareservation['deposit_check'] == 200 && $datareservation['payment_check'] == null) {
            $refund_amount = $datareservation['deposit'] / 2;
        } elseif ($date < $batasdp && $datareservation['deposit_check'] == 200 && $datareservation['payment_check'] == 200) {
            $refund_amount = ($datareservation['deposit'] / 2) + ($datareservation['total_price'] - $datareservation['deposit']);
        } elseif ($date > $batasdp && $datareservation['deposit_check'] == 200 && $datareservation['payment_check'] == 200) {
            $refund_amount = $datareservation['total_price'] - $datareservation['deposit'];
        } else {
            $refund_amount = 0;
        }

        $requestData = [
            'cancel' =>  $request['cancel'],
            'account_refund' => $request['account_refund'],
            'cancel_date' => $date,
            'refund_amount' => $refund_amount,
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }
        $updateR = $this->reservationModel->update_cancel($id, $requestData);

        if ($updateR) {
            $villageEmailData = $this->KubuGadangModel->get_desa_wisata_info()->getRowArray();
            if ($villageEmailData) {
                $villageName = $villageEmailData['name'];
                $account_refund = $request['account_refund'];
                $detail_reservation = $this->reservationModel->get_reservation_by_id($id)->getRowArray();
                $datecancel = $detail_reservation['cancel_date'];
                $customerName = $detail_reservation['username'];
                $customerEmail = $detail_reservation['email'];
                $packageName = $detail_reservation['package_name'];
                $cancel_date = strtotime($datecancel);
                $request_cancel_date = date('Y-m-d', $cancel_date);
                $cancel_time = strtotime($datecancel);
                $request_cancel_time = date('H:i:s', $cancel_time);

                $email = \Config\Services::email();
                $email->setTo($customerEmail);
                $email->setSubject('Pengajuan Cancel dan Refund Reservasi ' . $id);

                $message = "<p>Yth. $customerName,</p>";
                $message .= "<p>Pengajuan Cancel dan Refund Anda telah disimpan dengan detail sebagai berikut:</p><br>";
                $message .= "<p><span style='display: inline-block; width: 150px;'>ID Reservasi</span>: $id</p>";
                $message .= "<p><span style='display: inline-block; width: 150px;'>Nama Paket</span>: $packageName</p>";
                $message .= "<p><span style='display: inline-block; width: 150px;'>Tanggal Cancel</span>: $request_cancel_date</p>";
                $message .= "<p><span style='display: inline-block; width: 150px;'>Waktu Cancel</span>: $request_cancel_time WIB</p>";
                $message .= "<p><span style='display: inline-block; width: 150px;'>Account Refund</span>: $account_refund</p>";
                $message .= "<p><span style='display: inline-block; width: 150px;'>Refund Amount</span>: $refund_amount</p>";
                $message .= "<p><span style='display: inline-block; width: 150px;'>Status</span>: CANCEL AND REFUND</p><br>";
                $message .= "<p>Refund akan segera kami proses.</p><br>";
                $message .= "<p>Salam,</p>";
                $message .= "<p>Pokdarwis $villageName</p>";

                $email->setMessage($message);
                $email->setMailType('html');


                $email->setMessage($message);
                $email->setMailType('html');

                if ($email->send()) {

                    $villageName = $villageEmailData['name'];
                    $villageEmail = $villageEmailData['email'];

                    $email2 = \Config\Services::email();
                    $email2->setTo($villageEmail);
                    $email->setSubject('Pengajuan Cancel dan Refund Reservasi ' . $id);

                    $message = "<p>Halo Admin,</p>";
                    $message .= "<p>Pengajuan Cancel dan Refund Reservasi $id telah disimpan dengan detail sebagai berikut:</p><br>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>ID Reservasi</span>: $id</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Nama Paket</span>: $packageName</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Tanggal Cancel</span>: $request_cancel_date</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Waktu Cancel</span>: $request_cancel_time WIB</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Account Refund</span>: $account_refund</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Refund Amount</span>: $refund_amount</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Status</span>: CANCEL AND REFUND</p><br>";
                    $message .= "<p>Silakan upload bukti pembayaran refund sebagai tindak lanjut atas pengajuan cancel dan refund reservasi ini.</p>";
                    $message .= "<p>Terima kasih.</p>";

                    $email2->setMessage($message);
                    $email2->setMailType('html');

                    if ($email2->send()) {
                    } else {
                    }
                } else {
                }


                session()->setFlashdata('success', 'Reservation has been canceled and refund requested. Please wait the refund from us');

                return redirect()->back();
            } else {
                return redirect()->back()->withInput();
            }
        }
    }

    public function saveresponse($id = null)
    {
        $request = $this->request->getPost();

        $requestData = [
            'response' =>  $request['response'],
        ];
        $updateDR = $this->reservationModel->update_response($id, $requestData);

        if ($updateDR) {
            session()->setFlashdata('success', 'Response already sent');

            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function savecheckdeposit($id = null)
    {
        $request = $this->request->getPost();

        $requestData = [
            'deposit_check' =>  $request['deposit_check'],
            'admin_deposit_check' => $request['admin_deposit_check'],
        ];

        $updateDR = $this->reservationModel->update_reservation($id, $requestData);

        if ($updateDR) {
            session()->setFlashdata('success', 'Proof of deposit has been checked');

            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function savecheckpayment($id = null)
    {
        $request = $this->request->getPost();

        $requestData = [
            'payment_check' =>  $request['payment_check'],
            'admin_payment_check' => $request['admin_payment_check'],
        ];

        $updateDR = $this->reservationModel->update_reservation($id, $requestData);

        if ($updateDR) {
            session()->setFlashdata('success', 'Proof of payment has been checked');

            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function savecheckrefund($id = null)
    {
        $request = $this->request->getPost();

        $requestData = [
            'refund_check' =>  $request['refund_check'],
        ];

        $updateDR = $this->reservationModel->update_reservation($id, $requestData);

        if ($updateDR) {

            $refund_check = $request['refund_check'];
            if ($refund_check == 1) {
                $villageEmailData = $this->KubuGadangModel->get_desa_wisata_info()->getRowArray();
                if ($villageEmailData) {
                    $villageName = $villageEmailData['name'];
                    $detail_reservation = $this->reservationModel->get_reservation_by_id($id)->getRowArray();
                    $account_refund = $detail_reservation['account_refund'];
                    $daterefund = $detail_reservation['refund_date'];
                    $customerName = $detail_reservation['username'];
                    $customerEmail = $detail_reservation['email'];
                    $packageName = $detail_reservation['package_name'];
                    $refund_date = strtotime($daterefund);
                    $request_refund_date = date('Y-m-d', $refund_date);
                    $refund_time = strtotime($daterefund);
                    $request_refund_time = date('H:i:s', $refund_time);

                    $email = \Config\Services::email();
                    $email->setTo($customerEmail);
                    $email->setSubject('Pengecekan Refund Reservasi ' . $id);

                    $message = "<p>Yth. $customerName,</p>";
                    $message .= "<p>Pengecekan bukti refund Anda telah disimpan dengan detail sebagai berikut:</p><br>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>ID Reservasi</span>: $id</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Nama Paket</span>: $packageName</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Tanggal Refund</span>: $request_refund_date</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Waktu Refund</span>: $request_refund_time WIB</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Account Refund</span>: $account_refund</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Status</span>: REFUND SUCCESS</p><br>";
                    $message .= "<p>Salam,</p>";
                    $message .= "<p>Pokdarwis $villageName</p>";

                    $email->setMessage($message);
                    $email->setMailType('html');


                    $email->setMessage($message);
                    $email->setMailType('html');

                    if ($email->send()) {

                        $villageName = $villageEmailData['name'];
                        $villageEmail = $villageEmailData['email'];

                        $email2 = \Config\Services::email();
                        $email2->setTo($villageEmail);
                        $email->setSubject('Pengecekan Refund Reservasi ' . $id);

                        $message = "<p>Halo Admin,</p>";
                        $message .= "<p>Pengecekan bukti refund Reservasi $id telah dengan detail sebagai berikut:</p><br>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>ID Reservasi</span>: $id</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Nama Paket</span>: $packageName</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Tanggal Refund</span>: $request_refund_date</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Waktu Refund</span>: $request_refund_time WIB</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Account Refund</span>: $account_refund</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Status</span>: REFUND SUCCESS</p><br>";
                        $message .= "<p>Terima kasih.</p>";

                        $email2->setMessage($message);
                        $email2->setMailType('html');

                        if ($email2->send()) {
                        } else {
                        }
                    } else {
                    }
                    return redirect()->back();
                } else {
                    return redirect()->back()->withInput();
                }
            } else if ($refund_check == 0) {
                $villageEmailData = $this->KubuGadangModel->get_desa_wisata_info()->getRowArray();
                if ($villageEmailData) {
                    $villageName = $villageEmailData['name'];
                    $detail_reservation = $this->reservationModel->get_reservation_by_id($id)->getRowArray();
                    $account_refund = $detail_reservation['account_refund'];
                    $daterefund = $detail_reservation['refund_date'];
                    $customerName = $detail_reservation['username'];
                    $customerEmail = $detail_reservation['email'];
                    $packageName = $detail_reservation['package_name'];
                    $refund_date = strtotime($daterefund);
                    $request_refund_date = date('Y-m-d', $refund_date);
                    $refund_time = strtotime($daterefund);
                    $request_refund_time = date('H:i:s', $refund_time);

                    $email = \Config\Services::email();
                    $email->setTo($customerEmail);
                    $email->setSubject('Pengecekan Refund Reservasi ' . $id);

                    $message = "<p>Yth. $customerName,</p>";
                    $message .= "<p>Pengecekan bukti refund Anda telah disimpan dengan detail sebagai berikut:</p><br>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>ID Reservasi</span>: $id</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Nama Paket</span>: $packageName</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Tanggal Refund</span>: $request_refund_date</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Waktu Refund</span>: $request_refund_time WIB</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Account Refund</span>: $account_refund</p>";
                    $message .= "<p><span style='display: inline-block; width: 150px;'>Status</span>: REFUND INCORRECT</p><br>";
                    $message .= "<p>Refund akan kami proses kembali.</p><br>";
                    $message .= "<p>Salam,</p>";
                    $message .= "<p>Pokdarwis $villageName</p>";


                    $email->setMessage($message);
                    $email->setMailType('html');


                    $email->setMessage($message);
                    $email->setMailType('html');

                    if ($email->send()) {

                        $villageName = $villageEmailData['name'];
                        $villageEmail = $villageEmailData['email'];

                        $email2 = \Config\Services::email();
                        $email2->setTo($villageEmail);
                        $email->setSubject('Pengecekan Refund Reservasi ' . $id);

                        $message = "<p>Halo Admin,</p>";
                        $message .= "<p>Pengecekan bukti refund Reservasi $id telah disimpan dengan detail sebagai berikut:</p><br>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>ID Reservasi</span>: $id</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Nama Paket</span>: $packageName</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Tanggal Refund</span>: $request_refund_date</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Waktu Refund</span>: $request_refund_time WIB</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Account Refund</span>: $account_refund</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Status</span>: REFUND INCORRECT</p><br>";
                        $message .= "<p>Bukti refund incorrect. Silakan upload kembali bukti pembayaran refund yang benar.</p>";
                        $message .= "<p>Terima kasih.</p>";

                        $email2->setMessage($message);
                        $email2->setMailType('html');

                        if ($email2->send()) {
                        } else {
                        }
                    } else {
                    }
                    return redirect()->back();
                } else {
                    return redirect()->back()->withInput();
                }
            }


            session()->setFlashdata('success', 'Proof of refund has been checked');

            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function create()
    {
        $request = $this->request->getPost();
        $date = date('Y-m-d');

        $reservation_id = $request['reservation_id'];
        $pk_unit = $request['pk_unit'];
        $array = explode("-", $pk_unit);

        $check_in = date('Y-m-d', strtotime($request['check_in_timestamp']));
        $check_out = date('Y-m-d', strtotime($request['check_out_timestamp']));

        $date_booking = array(); // Array untuk menyimpan tanggal-tanggal booking
        $current_date = $check_in;
        while (strtotime($current_date) < strtotime($check_out)) {
            $date_booking[] = date('Y-m-d', strtotime($current_date)); // Menambahkan tanggal ke dalam array
            $current_date = date('Y-m-d', strtotime($current_date . " +1 day")); // Menambah 1 hari ke tanggal saat ini
        }

        foreach ($date_booking as $db) {
            $requestData = [
                'date' => $db,
                'homestay_id' => $array[0],
                'unit_type' => $array[1],
                'unit_number' => $array[2],
                'reservation_id' => $reservation_id,
                'unit_guest' => $array[7],
            ];

            foreach ($requestData as $key => $value) {
                if (empty($value)) {
                    unset($requestData[$key]);
                }
            }

            $checkExistingData = $this->detailReservationModel->checkIfDataExists($requestData);

            if (!$checkExistingData) {
                $addDR = $this->detailReservationModel->add_new_detail_reservation($requestData);

                $data_unit = $this->unitHomestayModel->get_unit_homestay_selected($requestData['unit_number'], $requestData['homestay_id'], $requestData['unit_type'])->getRowArray();
                $datareservation = $this->reservationModel->get_reservation_by_id($requestData['reservation_id'])->getRowArray();
                $getday = $this->packageDayModel->get_day_by_package($datareservation['package_id'])->getResultArray();


                if (!empty($getday)) {
                    $day = max($getday);
                    $daypack = $day['day'];
                    $dayhome = $day['day'] - 1;
                } else {
                    $day = 1;
                    $daypack = 1;
                    $dayhome = 0;
                }
                $tph = $data_unit['price'];

                $new_price = $datareservation['total_price'] + $tph;
                $new_deposit = $new_price * 0.2;

                $id = $requestData['reservation_id'];
                $requestData = [
                    'total_price' => $new_price,
                    'deposit' => $new_deposit,
                ];

                // dd($id, $requestData);
                $updateR = $this->reservationModel->update_reservation($id, $requestData);
            } else {
                $addDR = null;
            }
        }

        if ($addDR == null) {
            session()->setFlashdata('failed', 'The Homestay unit has been booked.');

            return redirect()->back()->withInput();
        } elseif ($addDR) {
            session()->setFlashdata('success', 'The Homestay unit was successfully added.');


            return redirect()->back();
        }
    }


    public function update($id = null)
    {
        $request = $this->request->getPost();
        $requestData = [
            'id' => $id,
            'name' => $request['name'],
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $updateDR = $this->detailReservationModel->add_new_detail_reservation($id, $requestData);

        if ($updateDR) {
            return redirect()->to(base_url('web/reservation') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }


    public function deleteday($package_id = null, $day = null, $description = null)
    {
        $request = $this->request->getPost();

        $package_id = $request['package_id'];
        $day = $request['day'];
        $description = $request['description'];

        $array1 = array('package_id' => $package_id, 'day' => $day);
        $detailPackage = $this->detailPackageModel->where($array1)->find();
        $deleteDP = $this->detailPackageModel->where($array1)->delete();

        if ($deleteDP) {
            //jika success
            $array2 = array('package_id' => $package_id, 'day' => $day, 'description' => $description);
            $packageDay = $this->packageDayModel->where($array2)->find();
            // dd($packageDay);
            $deletePD = $this->packageDayModel->where($array2)->delete();

            if ($deletePD) {
                session()->setFlashdata('pesan', 'Activity "' . $description . '" successfully deleted.');

                $package = $this->packageModel->get_package_by_id($package_id)->getRowArray();
                $package_id = $package['id'];
                $packageDay = $this->packageDayModel->get_package_day_by_id($package_id)->getResultArray();
                $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($package_id, $packageDay)->getResultArray();

                $combinedDataPrice = $this->detailPackageModel->getCombinedDataPrice($package_id);
                $combinedServicePrice = $this->detailServicePackageModel->getCombinedServicePrice($package_id);

                $requestDataPrice = [
                    'id' => $package_id,
                    'price' => $combinedDataPrice + $combinedServicePrice,
                ];
                $updatePA = $this->packageModel->update_package($package_id, $requestDataPrice);

                $data = [
                    'title' => 'New Detail Package',
                    'data' => $package,
                    'day' => $packageDay,
                    'activity' => $detailPackage
                ];

                return redirect()->back();
            }
        } else {
            $response = [
                'status' => 404,
                'message' => [
                    "Package not found"
                ]
            ];
            return $this->failNotFound($response);
        }
    }


    public function delete($package_id = null, $day = null, $activity = null, $description = null)
    {
        $request = $this->request->getPost();

        $day = $request['day'];
        $activity = $request['activity'];
        $description = $request['description'];

        $array = array('package_id' => $package_id, 'day' => $day, 'activity' => $activity);
        $detailPackage = $this->detailPackageModel->where($array)->find();
        $deleteDP = $this->detailPackageModel->where($array)->delete();

        if ($deleteDP) {
            session()->setFlashdata('pesan', 'Activity "' . $description . '" successfully deleted.');
            //jika success
            $package = $this->packageModel->get_package_by_id($package_id)->getRowArray();

            // $package_id=$package['id'];

            $packageDay = $this->packageDayModel->get_package_day_by_id($package_id)->getResultArray();

            // dd($packageDay);
            // foreach ($packageDay as $item):
            // $dayp=$item['day'];
            $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($package_id, $packageDay)->getResultArray();

            // $package_min_capacity = $request['package_min_capacity'];
            // $combinedDataPrice = $this->detailPackageModel->getCombinedDataPrice($package_id, $package_min_capacity);
            $combinedDataPrice = $this->detailPackageModel->getCombinedDataPrice($package_id);
            $combinedServicePrice = $this->detailServicePackageModel->getCombinedServicePrice($package_id);

            $requestDataPrice = [
                'id' => $package_id,
                'price' => $combinedDataPrice + $combinedServicePrice,
            ];
            $updatePAA = $this->packageModel->update_package_price($package_id, $requestDataPrice);

            // $updatePA = $this->packageModel->update_package($package_id, $requestDataPrice);

            $data = [
                'title' => 'New Detail Package',
                'data' => $package,
                'day' => $packageDay,
                'activity' => $detailPackage
            ];

            // endforeach;
            return redirect()->back();

            // return view('dashboard/detail-package-form', $data, $package, $packageDay, $detailPackage);

        } else {
            $response = [
                'status' => 404,
                'message' => [
                    "Package not found"
                ]
            ];
            return $this->failNotFound($response);
        }


        // return redirect()->to('/packageday/P0014');
    }


    public function deleteunit($homestay_id = null, $unit_type = null, $unit_number = null, $reservation_id = null)
    {
        $request = $this->request->getPost();

        $date = $request['date'];
        $homestay_id = $request['homestay_id'];
        $unit_type = $request['unit_type'];
        $unit_number = $request['unit_number'];
        $reservation_id = $request['reservation_id'];
        $description = $request['description'];

        $data_unit = $this->unitHomestayModel->get_unit_homestay_selected($unit_number, $homestay_id, $unit_type)->getRowArray();

        $array = array('date' => $date, 'unit_number' => $unit_number, 'homestay_id' => $homestay_id, 'unit_type' => $unit_type);
        $bookingunit = $this->detailReservationModel->where($array)->find();
        $deleteBU = $this->detailReservationModel->where($array)->delete();

        if ($deleteBU) {
            session()->setFlashdata('pesan', 'Unit Deleted Successfully.');

            $data_unit = $this->unitHomestayModel->get_unit_homestay_selected($unit_number, $homestay_id, $unit_type)->getRowArray();
            $datareservation = $this->reservationModel->get_reservation_by_id($reservation_id)->getRowArray();
            $getday = $this->packageDayModel->get_day_by_package($datareservation['package_id'])->getResultArray();


            if (!empty($getday)) {
                $day = max($getday);
                $daypack = $day['day'];
                $dayhome = $day['day'] - 1;
            } else {
                $day = 1;
                $daypack = 1;
                $dayhome = 0;
            }
            $tph = $data_unit['price'];

            $new_price = $datareservation['total_price'] - $tph;
            $new_deposit = $new_price * 0.2;

            $id = $reservation_id;
            $requestData = [
                'total_price' => $new_price,
                'deposit' => $new_deposit,
            ];

            // dd($id, $requestData);
            $updateR = $this->reservationModel->update_reservation($id, $requestData);

            return redirect()->back();
        } else {
            $response = [
                'status' => 404,
                'message' => [
                    "Package not found"
                ]
            ];
            return $this->failNotFound($response);
        }
    }

    public function review($id = null)
    {
        $datareservation = $this->reservationModel->get_reservation_by_id($id)->getRowArray();
        $package_reservation = $datareservation['package_id'];

        //detail package 
        $package = $this->packageModel->get_package_by_id($package_reservation)->getRowArray();
        $getday = $this->packageDayModel->get_day_by_package($package_reservation)->getResultArray();

        //data homestay homestay
        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();
        $booking_unit = $this->detailReservationModel->get_unit_homestay_booking($id)->getResultArray();

        if (!empty($booking_unit)) {
            foreach ($booking_unit as $booking) {
                $homestay_id = $booking['homestay_id'];
                $unit_type = $booking['unit_type'];
                $unit_number = $booking['unit_number'];
                $reservation_id = $booking['reservation_id'];

                $data_unit_booking = $this->detailReservationModel->get_unit_homestay_booking_data_reservation($homestay_id, $unit_type, $unit_number, $reservation_id)->getResultArray();
            }
        } else {
            $data_unit_booking = [];
        }

        // dd($booking_unit);
        if (empty($datareservation)) {
            return redirect()->to('web/detailreservation');
        }
        $date = date('Y-m-d');

        $check_in = $datareservation['check_in'];

        if (!empty($getday)) {
            $totday = max($getday);
            $day = $totday['day'] - 1;
        } else {
            $totday = 1;
            $day = $totday - 1;
        }


        // Ubah $check_in menjadi objek DateTime untuk mempermudah perhitungan
        $check_in_datetime = new DateTime($check_in);
        if ($day == '0') {
            $check_out = $check_in_datetime->format('Y-m-d') . ' 18:00:00';
        } else {
            // Tambahkan jumlah hari
            $check_in_datetime->modify('+' . $day . ' days');
            // Atur waktu selalu menjadi 12:00:00
            $check_out = $check_in_datetime->format('Y-m-d') . ' 12:00:00';
        }

        $data = [
            'title' => 'Review Package and Homestay',
            'data_package' => $package,
            'detail' => $datareservation,
            'check_out' => $check_out,
            'data_unit' => $booking_unit,
            'booking' => $data_unit_booking,
        ];
        // dd($data);
        return view('web/review-reservation-form', $data);
    }

    public function savereview($id = null)
    {
        $request = $this->request->getPost();
        $requestData = [
            'rating' => $request['rating'],
            'review' => $request['review'],
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $updateR = $this->reservationModel->update_reservation($id, $requestData);

        if ($updateR) {
            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function savereviewunit($reservation_id = null)
    {
        $request = $this->request->getPost();
        // $date=$request['date'];
        $reservation_id = $request['reservation_id'];
        $unit_number = $request['unit_number'];
        $homestay_id = $request['homestay_id'];
        $unit_type = $request['unit_type'];

        $requestData = [
            'rating' => $request['rating'],
            'review' => $request['review'],
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $updateDR = $this->detailReservationModel->update_detailreservation($reservation_id, $unit_number, $homestay_id, $unit_type, $requestData);

        if ($updateDR) {
            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }
}
