<?php

namespace App\Controllers\Web;

use App\Models\ReservationModel;
use App\Models\SumpuModel;
use App\Models\DetailReservationModel;
use App\Models\HomestayModel;
use App\Models\UnitHomestayModel;
use App\Models\PackageModel;
use App\Models\GalleryPackageModel;
use App\Models\BackupDetailReservationModel;
use App\Models\DetailServicePackageModel;
use App\Models\ServicePackageModel;

use App\Models\GalleryHomestayModel;
use App\Models\FacilityHomestayDetailModel;
use App\Models\GalleryUnitModel;
use App\Models\DetailPackageModel;
use App\Models\PackageDayModel;
use App\Models\AccountModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;
use App\Libraries\MY_TCPDF as TCPDF;
use Myth\Auth\Models\UserModel;
use DateTime;

class Reservation extends ResourcePresenter
{
    protected $reservationModel;
    protected $sumpuModel;
    protected $backupDetailReservationModel;
    protected $homestayModel;
    protected $unitHomestayModel;
    protected $packageModel;
    protected $galleryPackageModel;
    protected $detailServicePackageModel;
    protected $detailReservationModel;

    protected $galleryHomestayModel;
    protected $facilityHomestayDetailModel;
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
        $this->reservationModel = new ReservationModel();
        $this->sumpuModel = new SumpuModel();
        $this->detailReservationModel = new DetailReservationModel();
        $this->detailPackageModel = new DetailPackageModel();
        $this->homestayModel = new HomestayModel();
        $this->unitHomestayModel = new UnitHomestayModel();
        $this->facilityHomestayDetailModel = new FacilityHomestayDetailModel();
        $this->packageModel = new PackageModel();
        $this->galleryPackageModel = new GalleryPackageModel();
        $this->detailServicePackageModel = new DetailServicePackageModel();

        $this->galleryHomestayModel = new GalleryHomestayModel();
        $this->galleryUnitModel = new GalleryUnitModel();
        $this->packageDayModel = new PackageDayModel();
        $this->accountModel = new AccountModel();
        $this->backupDetailReservationModel = new BackupDetailReservationModel();
        $this->detailServicePackageModel = new DetailServicePackageModel();
        $this->userModel = new UserModel();

    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */

    // public function payMidtrans()
    // {

    //     // Set your Merchant Server Key
    //     \Midtrans\Config::$serverKey = 'SB-Mid-server-Of1IfaGcLxvAOT-blQIE63_G';
    //     // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
    //     \Midtrans\Config::$isProduction = false;
    //     // Set sanitization on (default)
    //     \Midtrans\Config::$isSanitized = true;
    //     // Set 3DS transaction for credit card to true
    //     \Midtrans\Config::$is3ds = true;

    //     $params = array(
    //         'transaction_details' => array(
    //             'order_id' => time(),
    //             'gross_amount' => 10000,
    //         ),
    //         'customer_details' => array(
    //             'first_name' => 'budi',
    //             'last_name' => 'pratama',
    //             'email' => 'budi.pra@example.com',
    //             'phone' => '08111222333',
    //         ),
    //     );

    //     $snapToken = \Midtrans\Snap::getSnapToken($params);
    //     // $data['token'] = $snapToken;
    //     // return view('web/pay_midtrans', $data);
    //     $response = [
    //         // 'datapackage' => $list_package,
    //         'data' => $snapToken,
    //         'status' => 200,
    //         'message' => [
    //             "Success get token"
    //         ]
    //     ];
    //     // return response(['token' => $snapToken]);
    //     return $this->respond($response);
    // }

    public function index()
    {
        $user = user()->username;
        $datareservation = $this->reservationModel->get_list_reservation_by_user($user)->getResultArray();
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        foreach ($datareservation as &$item) {

            $check_in = $item['check_in'];
            $getday = $this->packageDayModel->get_day_by_package($item['package_id'])->getResultArray();

            if (!empty($getday)) {
                $totday = max($getday);
                $day = $totday['day'] - 1;
            }
            // Ubah $check_in menjadi objek DateTime untuk mempermudah perhitungan
            $check_in_datetime = new DateTime($check_in);

            if ($day == '0') {
                $item['check_out'] = $check_in_datetime->format('Y-m-d') . ' 18:00:00';
            } else {
                // Tambahkan jumlah hari
                $check_in_datetime->modify('+' . $day . ' days');
                // Atur waktu selalu menjadi 12:00:00
                $item['check_out'] = $check_in_datetime->format('Y-m-d') . ' 12:00:00';
            }

            $name_admin_confirm = $item['admin_confirm'];
            $getAdminC = $this->accountModel->get_profil_admin($item['admin_confirm'])->getRowArray();
            if ($getAdminC != null) {
                $item['name_admin_confirm'] = $getAdminC['username'];
            } else {
                $item['name_admin_confirm'] = 'adm';
            }

            $name_admin_refund = $item['admin_refund'];
            $getAdminR = $this->accountModel->get_profil_admin($item['admin_refund'])->getRowArray();
            if ($getAdminR != null) {
                $item['name_admin_refund'] = $getAdminR['username'];
            } else {
                $item['name_admin_refund'] = 'adm';
            }

            $admin_deposit_check = $item['admin_deposit_check'];
            $getAdminDP = $this->accountModel->get_profil_admin($item['admin_deposit_check'])->getRowArray();
            if ($getAdminDP != null) {
                $item['name_admin_deposit_check'] = $getAdminDP['username'];
            } else {
                $item['name_admin_deposit_check'] = 'adm';
            }

            $admin_payment_check = $item['admin_payment_check'];
            $getAdminFP = $this->accountModel->get_profil_admin($item['admin_payment_check'])->getRowArray();
            if ($getAdminFP != null) {
                $item['name_admin_payment_check'] = $getAdminFP['username'];
            } else {
                $item['name_admin_payment_check'] = 'adm';
            }
        }

        $data = [
            'title' => 'Reservation',
            'data' => $datareservation,
            'data2' => $contents2,
        ];

        return view('web/reservation', $data);
    }

    public function report()
    {
        $datareservation_report = $this->reservationModel->get_list_reservation_report()->getResultArray();
        $deposit = $this->reservationModel->sum_done_deposit()->getRowArray();
        $total_price = $this->reservationModel->sum_done_total()->getRowArray();
        $dtrefund = $this->reservationModel->sum_done_refund()->getRowArray();
        $refund = $dtrefund['refund'] / 2;

        $data = [
            'title' => 'Report Reservation',
            'data' => $datareservation_report,
            'deposit' => $deposit['deposit'],
            'total_price' => $total_price['total_price'],
            'refund' => $refund
        ];
        // dd($data);
        return view('dashboard/reservation-report', $data);
    }

    public function show($id = null)
    {
        $detail_reservation = $this->reservationModel->get_reservation_by_id($id)->getRowArray();

        if (empty($detail_reservation)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $data = [
            'title' => $detail_reservation['username'],
            'data' => $detail_reservation,
        ];

        // if (url_is('*dashboard*')) {
        return view('web/detailreservation', $data);
        // }
    }

    /**
     * Present a view to present a new single resource object
     *
     * @return mixed
     */
    public function new()
    {
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $contents = $this->packageModel->get_list_package_distinct()->getResultArray();
        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();
        $id = $this->packageModel->get_new_id();
        $data = [
            'title' => 'New Reservation',
            'data' => $contents,
            'data2' => $contents2,
            'list_unit' => $list_unit,
            'custom_id' => $id
        ];

        return view('web/reservation-form', $data);
    }


    // public function custombooking($id)
    // {
    //     $package = $this->packageModel->get_package_by_id_custom($id)->getRowArray();

    //     $homestay_id = 'RG001';
    //     $homestay = $this->homestayModel->get_homestay_by_id($homestay_id)->getRowArray();
    //     if (empty($homestay)) {
    //         return redirect()->to(substr(current_url(), 0, -strlen($homestay_id)));
    //     }
    //     $list_facility_rumah = $this->facilityHomestayDetailModel->get_detailFacilityHomestay_by_id($homestay_id)->getResultArray();

    //     $list_gallery = $this->galleryHomestayModel->get_gallery($homestay_id)->getResultArray();
    //     $galleries = array();
    //     foreach ($list_gallery as $gallery) {
    //         $galleries[] = $gallery['url'];
    //     }
    //     $homestay['gallery'] = $galleries;

    //     $list_unit = $this->unitHomestayModel->get_unit_homestay_with_gallery($homestay_id)->getResultArray();       
    //     // $list_gallery_unit = $this->galleryUnitModel->get_gallery($homestay_id)->getResultArray();


    //     $data = [
    //         'title' => 'Reservation',
    //         'data' => $package,
    //         'datahome' => $homestay,
    //         'facilityhome' => $list_facility_rumah,
    //         'unit' => $list_unit,
    //         // 'gallery_unit' => $list_gallery_unit,
    //         'folderhome' => 'homestay',
    //         'folder' => 'package'
    //     ];


    //     return view('web/reservation-package-custom-form', $data);
    // }

    public function custombooking($id)
    {
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $list_homestay = $this->unitHomestayModel->get_homestay_by_statistic()->getResultArray();
        $homestays = array();
        foreach ($list_homestay as $homestay) {
            $homestays[] = $homestay['homestay_id'];
        }

        $package = $this->packageModel->get_package_by_id_custom($id)->getRowArray();
        $totaldays = $package['days'];

        $serviceinclude = $this->detailServicePackageModel->get_service_include_by_id($id)->getResultArray();
        $serviceexclude = $this->detailServicePackageModel->get_service_exclude_by_id($id)->getResultArray();
        // $serviceincludeHomestay = $this->detailServicePackageModel->checkIfDataExistsHomestay($id);
        $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($id)->getResultArray();
        $getday = $this->packageDayModel->get_list_package_day($id)->getResultArray();

        $dayTotal = $this->packageDayModel->get_list_package_day_total($id)->getRow();
        $combinedData = $this->detailPackageModel->getCombinedData($id);

        $data = [
            'title' => 'Reservation',
            'datapackage' => $package,
            'data2' => $contents2,
            'serviceinclude' => $serviceinclude,
            'serviceexclude' => $serviceexclude,
            // 'serviceincludeHomestay' => $serviceincludeHomestay,
            'totaldays' => $totaldays,
            'day' => $getday,
            'activity' => $combinedData,
            // 'datahome' => $response_data,
            'folder' => 'package',
            'folderhome' => 'homestay',
        ];


        if ($totaldays > 1) {
            return view('web/reservation-package-custom-form', $data);
        } else {
            return view('web/reservation-package-single-custom-form', $data);
        }
    }


    public function dataunithomestay()
    {
        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();

        // Periksa apakah ada hasil
        if ($list_unit > 0) {
            // Siapkan opsi untuk pilihan unit homestay
            $options = '<option value="">Select Unit Homestay</option>';
            while ($row = $list_unit->fetch_assoc()) {
                $homestay_id = $row['homestay_id'];
                $unit_type = $row['unit_type'];
                $unit_number = $row['unit_number'];
                $price = $row['price'];
                $capacity = $row['capacity'];

                // Tambahkan opsi ke variabel $options
                $options .= "<option value='$homestay_id' data-price='$price'>$unit_type - $unit_number (Capacity: $capacity)</option>";
            }
            echo $options;
        } else {
            echo '<option value="">No Units Available</option>';
        }

        // $data = [
        //     'title' => 'Homestay',
        //     'data' => $list_unit,
        // ];

        // // dd($data);
        // return view('dashboard/reservation-form', $data);
    }



    /**
     * Process the creation/insertion of a new resource object.
     * This should be a POST.
     *
     * @return mixed
     */
    // public function create()
    // {
    //     $request = $this->request->getPost();

    //     $id = $this->reservationModel->get_new_id();
    //     $date = date('Y-m-d H:i');
    //     $requestData = [
    //         'id' => $id,
    //         'user_id' => user()->id,
    //         'package_id' => $request['package'],
    //         'request_date' => $date,
    //         'total_people' => $request['total_people'],
    //         'check_in' => $request['check_in'] . ' ' . $request['time_check_in'],
    //         // 'check_out' => $request['check_out'].' '.$request['time_check_out'],
    //         'total_price' => $request['total_price'],
    //         'deposit' => $request['deposit'],
    //         'note' => $request['note']
    //     ];
    //     // dd($requestData);
    //     foreach ($requestData as $key => $value) {
    //         if (empty($value)) {
    //             unset($requestData[$key]);
    //         }
    //     }

    //     $addRe = $this->reservationModel->add_new_reservation($requestData);

    //     if ($addRe) {
    //         return redirect()->to(base_url('web/detailreservation/addhome/' . $id));
    //     } else {
    //         return redirect()->back()->withInput();
    //     }
    // }

    // public function create()
    // {
    //     $request = $this->request->getPost();

    //     $id = $this->reservationModel->get_new_id();
    //     $date = date('Y-m-d H:i');
    //     $requestData = [
    //         'id' => $id,
    //         'user_id' => user()->id,
    //         'package_id' => $request['package_id'],
    //         'request_date' => $date,
    //         'total_people' => $request['total_people'],
    //         'check_in' => $request['check_in'] . ' ' . $request['time_check_in'],
    //         'total_price' => $request['total_price'],
    //         'deposit' => $request['deposit'],
    //         'note' => $request['note']
    //     ];

    //     // Remove empty values from the request data
    //     $requestData = array_filter($requestData);

    //     // Add reservation
    //     $addRe = $this->reservationModel->add_new_reservation($requestData);

    //     // Add detail reservation
    //     if (isset($request['homestays'])) {
    //         $homestays = $request['homestays'];

    //         foreach ($homestays as $homestay) {
    //             $detailData = [
    //                 'reservation_id' => $id,
    //                 'homestay_id' => $homestay['homestay_id'],
    //                 'unit_type' => $homestay['unit_type'],
    //                 'unit_number' => $homestay['unit_number'],
    //             ];

    //             $this->detailReservationModel->addDetailReservation($detailData);
    //         }
    //     }

    //     if ($addRe) {
    //         return redirect()->to(base_url('web/detailreservation/' . $id));
    //     } else {
    //         return redirect()->back()->withInput();
    //     }
    // }

    public function edit($id = null)
    {
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $contents = $this->packageModel->get_list_package()->getResultArray();

        $datareservation = $this->reservationModel->get_reservation_by_id($id)->getRowArray();

        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();

        if (empty($datareservation)) {
            return redirect()->to('web/detailreservation');
        }
        $date = date('Y-m-d');

        $data = [
            'title' => 'Reservation Homestay',
            'data' => $contents,
            'data2' => $contents2,
            'detail' => $datareservation,
            'list_unit' => $list_unit,
            'date' => $date
        ];

        return view('web/reservation-form', $data);
    }

    // public function update($id = null)
    // {
    //     $request = $this->request->getPost();
    //     $requestData = [
    //         'id' => $id,
    //         'name' => $request['name'],
    //     ];
    //     foreach ($requestData as $key => $value) {
    //         if (empty($value)) {
    //             unset($requestData[$key]);
    //         }
    //     }

    //     $updateSP = $this->servicePackageModel->update_servicePackage($id, $requestData);

    //     if ($updateSP) {
    //         return redirect()->to(base_url('dashboard/servicepackage') . '/' . $id);
    //     } else {
    //         return redirect()->back()->withInput();
    //     }
    // }



    public function updateDepositCheck()
    {
        try {
            $res_id = $this->request->getPost('res_id');
            $res_id_deposit = $this->request->getPost('res_id_deposit');
            $myTokenDeposit = $this->request->getPost('myTokenDeposit');

            // Lakukan kodingan cURL untuk memeriksa status pembayaran
            $token = base64_encode("SB-Mid-server-Of1IfaGcLxvAOT-blQIE63_G");
            $url = "https://api.sandbox.midtrans.com/v2/" . $res_id_deposit . "/status";
            $header = array(
                'Accept: application/json',
                'Authorization: Basic ' . $token,
                'Content-Type: application/json'
            );
            $method = 'GET';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            $resultjson = json_decode($result, true);
            $result_caption = $resultjson['status_code'];
            $result_channel = $resultjson['payment_type'];
            $result_date = $resultjson['transaction_time'];

            // Tentukan deposit_date
            // $deposit_date = ($result_date) ? $result_date : date('Y-m-d H:i:s');

            // Lakukan pembaruan pada kolom deposit_check berdasarkan hasil dari cURL
            // $reservation_id = str_replace("D", "", $id);
            $reservation_id = $res_id;
            $token_of_deposit = $myTokenDeposit;
            $deposit_check = $result_caption;
            $deposit_channel = $result_channel;
            $deposit_date = $result_date;

            $updateDeposit = $this->reservationModel->updateDepositCheck($reservation_id, $token_of_deposit, $deposit_check, $deposit_channel, $deposit_date);

            // Kirim respons berdasarkan hasil pembaruan
            if ($updateDeposit) {
                $detail_reservation = $this->reservationModel->get_reservation_by_id($reservation_id)->getRowArray();
                $proof_of_deposit = $detail_reservation['proof_of_deposit'];
                if ($result_caption == 200 && $proof_of_deposit != 1) {
                    $customerName = $detail_reservation['username'];
                    $customerEmail = $detail_reservation['email'];
                    $amount = $detail_reservation['deposit'];
                    $packageName = $detail_reservation['package_name'];
                    $confirmation_date = strtotime($deposit_date);
                    $deposit_confirmation_date = date('Y-m-d', $confirmation_date);
                    $confirmation_time = strtotime($deposit_date);
                    $deposit_confirmation_time = date('H:i:s', $confirmation_time);

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

                        $id = $reservation_id;
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
                        $email->setSubject('Transaksi Pembayaran Deposit Reservasi ' . $reservation_id);

                        $message = "<p>Yth. $customerName,</p>";
                        $message .= "<p>Transaksi pembayaran deposit Anda telah berhasil dan dikonfirmasi dengan detail sebagai berikut:</p><br>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>ID Reservasi</span>: $reservation_id</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Nama Paket</span>: $packageName</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>ID Transaksi</span>: $res_id_deposit</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Saluran</span>: $deposit_channel</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Jumlah</span>: $amount</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Tanggal Transaksi</span>: $deposit_confirmation_date</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Waktu Transaksi</span>: $deposit_confirmation_time WIB</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Status Transaksi</span>: Sudah Bayar</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Status Reservasi</span>: PAY IN FULL</p><br>";
                        $message .= "<p>Silahkan lanjutkan pembayaran full pada website kami.</p><br>";
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
                            $email2->setSubject('Transaksi Pembayaran Deposit Reservasi ' . $reservation_id);

                            $message = "<p>Halo Admin,</p>";
                            $message .= "<p>Transaksi pembayaran deposit $reservation_id telah berhasil dan dikonfirmasi dengan detail sebagai berikut:</p><br>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>ID Reservasi</span>: $reservation_id</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>Nama Paket</span>: $packageName</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>ID Transaksi</span>: $res_id_deposit</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>Saluran</span>: $deposit_channel</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>Jumlah</span>: $amount</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>Tanggal Transaksi</span>: $deposit_confirmation_date</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>Waktu Transaksi</span>: $deposit_confirmation_time WIB</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>Status Transaksi</span>: Sudah Bayar</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>Status Reservasi</span>: PAY IN FULL</p><br>";
                            $message .= "<p>Silakan tunggu customer melakukan pembayaran full pada reservasi ini.</p>";
                            $message .= "<p>Terima kasih.</p>";

                            $email2->setMessage($message);
                            $email2->setMailType('html');

                            $email2->attach($pdfFilePath, 'invoice.pdf', 'application/pdf');
                            unlink($pdfFilePath); // Delete the PDF file from the server

                            if ($email2->send()) {
                                $requestData = [
                                    'proof_of_deposit' => 1,
                                ];

                                $updatePOD = $this->reservationModel->update_reservation($reservation_id, $requestData);
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
                return $this->response->setJSON(['message' => 'Deposit check berhasil diperbarui.']);
            } else {
                return $this->response->setStatusCode(500)->setJSON(['message' => 'Gagal memperbarui deposit check.']);
            }
        } catch (\Exception $e) {
            // Cetak informasi kesalahan ke log
            log_message('error', 'Error updating deposit check: ' . $e->getMessage());
            // Atau kembalikan pesan kesalahan yang lebih rinci
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Terjadi kesalahan dalam memperbarui deposit check: ' . $e->getMessage()]);
        }
    }



    public function updateFullCheck()
    {
        try {
            $res_id = $this->request->getPost('res_id');
            $res_id_full = $this->request->getPost('res_id_full');
            $myTokenFull = $this->request->getPost('myTokenFull');

            // Lakukan kodingan cURL untuk memeriksa status pembayaran
            $token = base64_encode("SB-Mid-server-Of1IfaGcLxvAOT-blQIE63_G");
            $url = "https://api.sandbox.midtrans.com/v2/" . $res_id_full . "/status";
            $header = array(
                'Accept: application/json',
                'Authorization: Basic ' . $token,
                'Content-Type: application/json'
            );
            $method = 'GET';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            $resultjson = json_decode($result, true);
            $result_caption = $resultjson['status_code'];
            $result_channel = $resultjson['payment_type'];
            $result_date = $resultjson['transaction_time'];

            // Tentukan payment_date
            // $payment_date = ($result_date) ? $result_date : date('Y-m-d H:i:s');

            // Lakukan pembaruan pada kolom payment_check berdasarkan hasil dari cURL
            // $reservation_id = str_replace("D", "", $id);
            $reservation_id = $res_id;
            $token_of_payment = $myTokenFull;
            $payment_check = $result_caption;
            $payment_channel = $result_channel;
            $payment_date = $result_date;

            $updatePayment = $this->reservationModel->updatePaymentCheck($reservation_id, $token_of_payment, $payment_check, $payment_channel, $payment_date);

            // Kirim respons berdasarkan hasil pembaruan
            if ($updatePayment) {
                $detail_reservation = $this->reservationModel->get_reservation_by_id($reservation_id)->getRowArray();
                $proof_of_payment = $detail_reservation['proof_of_payment'];
                $deposit_amount = $detail_reservation['deposit'];
                $total_price_amount = $detail_reservation['total_price'];
                $fullpayment =  $total_price_amount - $deposit_amount;
                if ($result_caption == 200 && $proof_of_payment != 1) {
                    $customerName = $detail_reservation['username'];
                    $customerEmail = $detail_reservation['email'];
                    $amount = $fullpayment;
                    $packageName = $detail_reservation['package_name'];
                    $confirmation_date = strtotime($payment_date);
                    $payment_confirmation_date = date('Y-m-d', $confirmation_date);
                    $confirmation_time = strtotime($payment_date);
                    $payment_confirmation_time = date('H:i:s', $confirmation_time);

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
                        $datareservation = $this->reservationModel->get_reservation_by_id($reservation_id)->getRowArray();
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
                            $booking_unit = $this->detailReservationModel->get_unit_homestay_bookingnya($reservation_id)->getResultArray();
                        } else if ($datareservation['cancel'] == '1') {
                            $booking_unit = $this->backupDetailReservationModel->get_unit_homestay_bookingnya($reservation_id)->getResultArray();
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
                                // $accomodationType = $booking['accomodation_type'];


                                if ($datareservation['cancel'] == '0') {
                                    $unit_booking[] = $this->detailReservationModel->get_unit_homestay_booking_data($date, $homestay_id, $unit_type, $unit_number, $reservation_id)->getRowArray();
                                    $total_price_homestay = $this->detailReservationModel->get_price_homestay_booking($homestay_id, $unit_type, $unit_number, $reservation_id)->getRow();
                                } else if ($datareservation['cancel'] == '1') {
                                    $unit_booking[] = $this->backupDetailReservationModel->get_unit_homestay_booking_data($date, $homestay_id, $unit_type, $unit_number, $reservation_id)->getRowArray();
                                    $total_price_homestay = $this->backupDetailReservationModel->get_price_homestay_booking($homestay_id, $unit_type, $unit_number, $reservation_id)->getRow();
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
                        $email->setSubject('Transaksi Pembayaran Full Payment Reservasi ' . $reservation_id);

                        $message = "<p>Yth. $customerName,</p>";
                        $message .= "<p>Transaksi pembayaran full payment Anda telah berhasil dan dikonfirmasi dengan detail sebagai berikut:</p><br>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>ID Reservasi</span>: $reservation_id</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Nama Paket</span>: $packageName</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>ID Transaksi</span>: $res_id_full</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Saluran</span>: $payment_channel</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Jumlah</span>: $amount</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Tanggal Transaksi</span>: $payment_confirmation_date</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Waktu Transaksi</span>: $payment_confirmation_time WIB</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Status Transaksi</span>: Sudah Bayar</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Status Reservasi</span>: ENJOY TRIP!</p><br>";
                        $message .= "<p>Terima kasih telah melakukan pembayaran reservasi, selamat menikmati paket wisata kami.</p><br>";
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
                            $email2->setSubject('Transaksi Pembayaran Full Payment Reservasi ' . $reservation_id);

                            $message = "<p>Halo Admin,</p>";
                            $message .= "<p>Transaksi pembayaran full payment $reservation_id telah berhasil dan dikonfirmasi dengan detail sebagai berikut:</p><br>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>ID Reservasi</span>: $reservation_id</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>Nama Paket</span>: $packageName</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>ID Transaksi</span>: $res_id_full</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>Saluran</span>: $payment_channel</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>Jumlah</span>: $amount</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>Tanggal Transaksi</span>: $payment_confirmation_date</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>Waktu Transaksi</span>: $payment_confirmation_time WIB</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>Status Transaksi</span>: Sudah Bayar</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>Status Reservasi</span>: ENJOY TRIP!</p><br>";
                            $message .= "<p>Terima kasih.</p>";

                            $email2->setMessage($message);
                            $email2->setMailType('html');

                            $email2->attach($pdfFilePath, 'invoice.pdf', 'application/pdf');
                            unlink($pdfFilePath); // Delete the PDF file from the server

                            if ($email2->send()) {
                                $requestData = [
                                    'proof_of_payment' => 1,
                                ];

                                $updatePOP = $this->reservationModel->update_reservation($reservation_id, $requestData);
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
                return $this->response->setJSON(['message' => 'Payment check berhasil diperbarui.']);
            } else {
                return $this->response->setStatusCode(500)->setJSON(['message' => 'Gagal memperbarui payment check.']);
            }
        } catch (\Exception $e) {
            // Cetak informasi kesalahan ke log
            log_message('error', 'Error updating payment check: ' . $e->getMessage());
            // Atau kembalikan pesan kesalahan yang lebih rinci
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Terjadi kesalahan dalam memperbarui payment check: ' . $e->getMessage()]);
        }
    }


    // public function updateDepositCheck() {
    //     // Terima data dari permintaan cURL
    //     $data = json_decode(file_get_contents('php://input'), true);

    //     // Cek apakah data yang diterima valid
    //     if (!empty($data['id']) && isset($data['deposit_check'])) {
    //         // Panggil model reservation
    //         // $this->load->model('Reservation_model');

    //         // Panggil metode untuk memperbarui deposit_check
    //         $reservation_id = $data['id'];
    //         $deposit_check = $data['deposit_check'];
    //         $result = $this->reservationModel->updateDepositCheck($reservation_id, $deposit_check);

    //         // Kirim respons berdasarkan hasil pembaruan
    //         if ($result) {
    //             http_response_code(200);
    //             echo json_encode(array("message" => "Deposit check berhasil diperbarui."));
    //         } else {
    //             http_response_code(500);
    //             echo json_encode(array("message" => "Gagal memperbarui deposit check."));
    //         }
    //     } else {
    //         // Kirim respons jika data tidak lengkap
    //         http_response_code(400);
    //         echo json_encode(array("message" => "Gagal memperbarui deposit check. Data tidak lengkap."));
    //     }
    // }


    public function uploaddeposit($id = null)
    {
        $request = $this->request->getPost();
        $date = date('Y-m-d H:i');

        $requestData = [
            'deposit_date' => $date,
            'deposit_check' => null,
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }
        $img = $this->request->getFile('proof_of_deposit');

        if (empty($_FILES['proof_of_deposit']['name'])) {
            $query = $this->reservationModel->upload_deposit($id, $requestData);
            if ($query) {
                $response = [
                    'status' => 200,
                    'message' => [
                        "Success upload deposit. Please wait, we will check your the deposit proof"
                    ]
                ];
                return redirect()->back();
            }
            $response = [
                'status' => 400,
                'message' => [
                    "Fail upload deposit"
                ]
            ];
            return $this->respond($response, 400);
        } else {

            $validationRule = [
                'proof_of_deposit' => [
                    'label' => 'proof_of_deposit File',
                    'rules' => 'uploaded[proof_of_deposit]'
                        . '|is_image[proof_of_deposit]'
                        . '|mime_in[proof_of_deposit,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                ],
            ];
            if (!$this->validate($validationRule) && !empty($_FILES['proof_of_deposit']['name'])) {
                $response = [
                    'status' => 400,
                    'message' => [
                        "Fail upload deposit "
                    ]
                ];
                return $this->respond($response, 400);
            }

            if ($img->isValid() && !$img->hasMoved()) {
                $filepath = WRITEPATH . 'uploads/' . $img->store();
                $user_image = new File($filepath);
                $user_image->move(FCPATH . 'media/photos/deposit');
                $requestData['proof_of_deposit'] = $user_image->getFilename();

                $query = $this->reservationModel->upload_deposit($id, $requestData);
                if ($query) {
                    $response = [
                        'status' => 200,
                        'message' => [
                            "Success upload deposit image. Please wait, we will check your the deposit proof"
                        ]
                    ];
                    return redirect()->back();
                }
                $response = [
                    'status' => 400,
                    'message' => [
                        "Fail upload deposit"
                    ]
                ];
                return $this->respond($response, 400);
            }
        }
        $response = [
            'status' => 400,
            'message' => [
                "Fail upload deposit."
            ]
        ];


        return $this->respond($response, 400);
    }

    public function uploadfullpayment($id = null)
    {
        $request = $this->request->getPost();
        $date = date('Y-m-d H:i');

        $requestData = [
            'payment_date' => $date,
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }
        $img = $this->request->getFile('proof_of_payment');

        if (empty($_FILES['proof_of_payment']['name'])) {
            $query = $this->reservationModel->upload_fullpayment($id, $requestData);
            if ($query) {
                $response = [
                    'status' => 200,
                    'message' => [
                        "Success upload full payment. Please wait, we will check your the payment proof"
                    ]
                ];
                return redirect()->back();
            }
            $response = [
                'status' => 400,
                'message' => [
                    "Fail upload full payment"
                ]
            ];
            return $this->respond($response, 400);
        } else {

            $validationRule = [
                'proof_of_payment' => [
                    'label' => 'proof_of_payment File',
                    'rules' => 'uploaded[proof_of_payment]'
                        . '|is_image[proof_of_payment]'
                        . '|mime_in[proof_of_payment,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                ],
            ];
            if (!$this->validate($validationRule) && !empty($_FILES['proof_of_payment']['name'])) {
                $response = [
                    'status' => 400,
                    'message' => [
                        "Fail upload full payment "
                    ]
                ];
                return $this->respond($response, 400);
            }

            if ($img->isValid() && !$img->hasMoved()) {
                $filepath = WRITEPATH . 'uploads/' . $img->store();
                $user_image = new File($filepath);
                $user_image->move(FCPATH . 'media/photos/fullpayment');
                $requestData['proof_of_payment'] = $user_image->getFilename();

                $query = $this->reservationModel->upload_fullpayment($id, $requestData);
                if ($query) {
                    $response = [
                        'status' => 200,
                        'message' => [
                            "Success upload full payment image. Please wait, we will check your the payment proof"
                        ]
                    ];
                    return redirect()->back();
                }
                $response = [
                    'status' => 400,
                    'message' => [
                        "Fail upload full payment"
                    ]
                ];
                return $this->respond($response, 400);
            }
        }
        $response = [
            'status' => 400,
            'message' => [
                "Fail upload fullpayment."
            ]
        ];
        return $this->respond($response, 400);
    }

    public function uploadrefund($id = null)
    {
        $request = $this->request->getPost();
        $date = date('Y-m-d H:i');

        $requestData = [
            'refund_date' => $date,
            'admin_refund' => $request['admin_refund'],
            'refund_check' => null,
        ];
        $img = $this->request->getFile('proof_refund');

        if (empty($_FILES['proof_refund']['name'])) {
            $query = $this->reservationModel->upload_refund($id, $requestData);
            if ($query) {
                $response = [
                    'status' => 200,
                    'message' => [
                        "Successful upload refund. Please wait, the customer will check your refund proof"
                    ]
                ];
                return redirect()->back();
            }
            $response = [
                'status' => 400,
                'message' => [
                    "Fail upload refund"
                ]
            ];
            return $this->respond($response, 400);
        } else {

            $validationRule = [
                'proof_refund' => [
                    'label' => 'proof_refund File',
                    'rules' => 'uploaded[proof_refund]'
                        . '|is_image[proof_refund]'
                        . '|mime_in[proof_refund,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                ],
            ];
            if (!$this->validate($validationRule) && !empty($_FILES['proof_refund']['name'])) {
                $response = [
                    'status' => 400,
                    'message' => [
                        "Fail upload refund "
                    ]
                ];
                return $this->respond($response, 400);
            }

            if ($img->isValid() && !$img->hasMoved()) {
                $filepath = WRITEPATH . 'uploads/' . $img->store();
                $user_image = new File($filepath);
                $user_image->move(FCPATH . 'media/photos/refund');
                $requestData['proof_refund'] = $user_image->getFilename();

                $query = $this->reservationModel->upload_refund($id, $requestData);
                if ($query) {

                    $villageEmailData = $this->sumpuModel->get_desa_wisata_info()->getRowArray();
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
                                // $accomodationType = $booking['accomodation_type'];


                                if ($datareservation['cancel'] == '0') {
                                    $unit_booking[] = $this->detailReservationModel->get_unit_homestay_booking_data($date, $homestay_id, $unit_type, $unit_number, $reservation_id)->getRowArray();
                                    $total_price_homestay = $this->detailReservationModel->get_price_homestay_booking($homestay_id, $unit_type, $unit_number, $reservation_id)->getRow();
                                } else if ($datareservation['cancel'] == '1') {
                                    $unit_booking[] = $this->backupDetailReservationModel->get_unit_homestay_booking_data($date, $homestay_id, $unit_type, $unit_number, $reservation_id)->getRowArray();
                                    $total_price_homestay = $this->backupDetailReservationModel->get_price_homestay_booking($homestay_id, $unit_type, $unit_number, $reservation_id)->getRow();
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
                        $email->setSubject('Pembayaran Refund Reservasi ' . $id);

                        $message = "<p>Yth. $customerName,</p>";
                        $message .= "<p>Refund Anda telah dibayar dengan detail sebagai berikut:</p><br>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>ID Reservasi</span>: $id</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Nama Paket</span>: $packageName</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Tanggal Refund</span>: $request_refund_date</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Waktu Refund</span>: $request_refund_time WIB</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Account Refund</span>: $account_refund</p>";
                        $message .= "<p><span style='display: inline-block; width: 150px;'>Status</span>: REFUND CHECK</p><br>";
                        $message .= "<p>Silakan submit check bukti refund pada halaman detail reservasi sebagai tindak lanjut atas pembayaran refund ini.</p><br>";
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
                            $email->setSubject('Pembayaran Refund Reservasi ' . $id);

                            $message = "<p>Halo Admin,</p>";
                            $message .= "<p>Refund Reservasi $id telah dibayar dengan detail sebagai berikut:</p><br>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>ID Reservasi</span>: $id</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>Nama Paket</span>: $packageName</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>Tanggal Refund</span>: $request_refund_date</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>Waktu Refund</span>: $request_refund_time WIB</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>Account Refund</span>: $account_refund</p>";
                            $message .= "<p><span style='display: inline-block; width: 150px;'>Status</span>: REFUND CHECK</p><br>";
                            $message .= "<p>Silakan tunggu refund check dari customer.</p>";
                            $message .= "<p>Terima kasih.</p>";

                            $email2->setMessage($message);
                            $email2->setMailType('html');

                            $email2->attach($pdfFilePath, 'invoice.pdf', 'application/pdf');
                            unlink($pdfFilePath); // Delete the PDF file from the server

                            if ($email2->send()) {
                            } else {
                            }
                        } else {
                        }


                        return redirect()->back();
                    } else {
                        return redirect()->back()->withInput();
                    }

                    $response = [
                        'status' => 200,
                        'message' => [
                            "Successful upload refund. Please wait, the customer will check your refund proof"
                        ]
                    ];
                    return redirect()->back();
                }
                $response = [
                    'status' => 400,
                    'message' => [
                        "Fail upload refund"
                    ]
                ];
                return $this->respond($response, 400);
            }
        }
        $response = [
            'status' => 400,
            'message' => [
                "Fail upload refund."
            ]
        ];
        return $this->respond($response, 400);
    }

    public function delete($id = null, $package_id = null, $user_id = null)
    {
        $request = $this->request->getPost();

        $id = $request['id'];
        $package_id = $request['package_id'];
        $user_id = $request['user_id'];

        $array1 = array('reservation_id' => $id);
        $detailReservation = $this->detailReservationModel->where($array1)->find();
        $deleteDR = $this->detailReservationModel->where($array1)->delete();

        if ($deleteDR) {
            //jika success
            $array2 = array('id' => $id, 'package_id' => $package_id, 'user_id' => $user_id);
            $reservation = $this->reservationModel->where($array2)->find();
            // dd($packageDay);
            $deleteRE = $this->reservationModel->where($array2)->delete();

            if ($deleteRE) {
                session()->setFlashdata('success', 'Reservation "' . $id . '" Deleted Successfully.');
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
}
