<?php

namespace App\Controllers\Web;

use App\Models\PackageModel;
use App\Models\KubuGadangModel;
use App\Models\PackageDayModel;
use App\Models\DetailPackageModel;
use App\Models\GalleryPackageModel;
use App\Models\PackageTypeModel;
use App\Models\ServicePackageModel;
use App\Models\DetailServicePackageModel;
use App\Models\BackupDetailReservationModel;
use App\Models\DetailReservationModel;
use App\Models\ReservationModel;
use App\Models\UnitHomestayModel;
use App\Models\TraditionalHouseModel;
use App\Models\CulinaryPlaceModel;
use App\Models\WorshipPlaceModel;
use App\Models\FacilityModel;
use App\Models\SouvenirPlaceModel;
use App\Models\AttractionModel;
use App\Models\EventModel;
use App\Models\HomestayModel;
use CodeIgniter\CLI\Console;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class Package extends ResourcePresenter
{
    protected $packageModel;
    protected $KubuGadangModel;
    protected $detailPackageModel;
    protected $packageDayModel;
    protected $galleryPackageModel;
    protected $packageTypeModel;
    protected $servicePackageModel;
    protected $detailServicePackageModel;
    protected $backupDetailReservationModel;
    protected $detailReservationModel;
    protected $reservationModel;
    protected $unitHomestayModel;
    protected $traditionalHouseModel;
    protected $culinaryPlaceModel;
    protected $worshipPlaceModel;
    protected $facilityModel;
    protected $souvenirPlaceModel;
    protected $attractionModel;
    protected $eventModel;
    protected $homestayModel;

    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;
    protected $db, $builder;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->packageModel = new PackageModel();
        $this->KubuGadangModel = new KubuGadangModel();
        $this->packageDayModel = new PackageDayModel();
        $this->detailPackageModel = new DetailPackageModel();
        $this->galleryPackageModel = new GalleryPackageModel();
        $this->packageTypeModel = new PackageTypeModel();
        $this->servicePackageModel = new ServicePackageModel();
        $this->detailServicePackageModel = new DetailServicePackageModel();
        $this->backupDetailReservationModel = new BackupDetailReservationModel();
        $this->detailReservationModel = new DetailReservationModel();
        $this->reservationModel = new ReservationModel();
        $this->unitHomestayModel = new UnitHomestayModel();
        $this->traditionalHouseModel = new TraditionalHouseModel();
        $this->culinaryPlaceModel = new CulinaryPlaceModel();
        $this->worshipPlaceModel = new WorshipPlaceModel();
        $this->facilityModel = new FacilityModel();
        $this->souvenirPlaceModel = new SouvenirPlaceModel();
        $this->attractionModel = new AttractionModel();
        $this->eventModel = new EventModel();
        $this->homestayModel = new HomestayModel();

        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('package');;
    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */

    public function index()
    {
        $contents = $this->packageModel->get_list_package_default()->getResultArray();
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();

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
        $idnew = $this->packageModel->get_new_id();

        $data = [
            'title' => 'Package',
            'data' => $contents,
            'data2' => $contents2,
            'idnew' => $idnew
        ];
        // dd($data);
        return view('web/list_package', $data);
    }

    /**
     * Present a view to present a specific resource object
     *
     * @param mixed $id
     *
     * @return mixed
     */



    public function show($id = null)
    {
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();
        $review = $this->reservationModel->getReview($id)->getResultArray();

        $package = $this->packageModel->get_package_by_id($id)->getRowArray();
        if (empty($package)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $list_gallery = $this->galleryPackageModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $package['gallery'] = $galleries;

        $serviceinclude = $this->detailServicePackageModel->get_service_include_by_id($id)->getResultArray();
        $serviceexclude = $this->detailServicePackageModel->get_service_exclude_by_id($id)->getResultArray();
        $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($id)->getResultArray();
        $getday = $this->packageDayModel->get_list_package_day($id)->getResultArray();
        $combinedData = $this->detailPackageModel->getCombinedData($id);
        $review = $this->reservationModel->getReview($id)->getResultArray();
        $rating = $this->reservationModel->getRating($id)->getRowArray();

        $data = [
            'title' => $package['name'],
            'data' => $package,
            'data2' => $contents2,
            'reviews' => $review,
            'serviceinclude' => $serviceinclude,
            'serviceexclude' => $serviceexclude,
            'day' => $getday,
            'activity' => $combinedData,
            'review' => $review,
            'rating' => $rating,
            'folder' => 'package'
        ];

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_package', $data);
        }
        return view('web/detail_package', $data);
    }

    public function detailpackage($id = null)
    {
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();
        $review = $this->reservationModel->getReview($id)->getResultArray();

        $package = $this->packageModel->get_package_by_id($id)->getRowArray();
        if (empty($package)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $list_gallery = $this->galleryPackageModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $package['gallery'] = $galleries;

        $serviceinclude = $this->detailServicePackageModel->get_service_include_by_id($id)->getResultArray();
        $serviceexclude = $this->detailServicePackageModel->get_service_exclude_by_id($id)->getResultArray();
        $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($id)->getResultArray();
        $getday = $this->packageDayModel->get_list_package_day($id)->getResultArray();
        $combinedData = $this->detailPackageModel->getCombinedData($id);
        $review = $this->reservationModel->getReview($id)->getResultArray();
        $rating = $this->reservationModel->getRating($id)->getRowArray();

        $data = [
            'title' => $package['name'],
            'data' => $package,
            'data2' => $contents2,
            'reviews' => $review,
            'serviceinclude' => $serviceinclude,
            'serviceexclude' => $serviceexclude,
            'day' => $getday,
            'activity' => $combinedData,
            'review' => $review,
            'rating' => $rating,
            'folder' => 'package'
        ];

        // if (url_is('*dashboard*')) {
        //     return view('dashboard/detail_package_detail', $data);
        // }
        return view('web/detail_package_detail', $data);
    }

    /**
     * Present a view to present a new single resource object
     *
     * @return mixed
     */
    public function new()
    {
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();

        $type = $this->packageTypeModel->get_list_package_type()->getResultArray();
        $servicelist = $this->servicePackageModel->get_list_service_package()->getResultArray();
        $id = $this->packageModel->get_new_id();
        $package = array();
        $package['custom'] = 'P0001';

        $data = [
            'title' => 'New Package',
            'type' => $type,
            'data' => $package,
            'data2' => $contents2,
            'servicelist' => $servicelist,
            'id' => $id
        ];

        // dd($data);
        return view('dashboard/package-form', $data);
    }

    /**
     * Process the creation/insertion of a new resource object.
     * This should be a POST.
     *
     * @return mixed
     */
    public function create()
    {
        $request = $this->request->getPost();

        $id = $this->packageModel->get_new_id();

        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'type_id' => $request['type'],
            'min_capacity' => $request['min_capacity'],
            'price' => $request['price'],
            'description' => $request['description'],
            'contact_person' => $request['contact_person'],
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }
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
        // Handle gallery files
        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);

                // Remove old file with the same name, if exists
                $existingFile = FCPATH . 'media/photos/package/' . $fileImg->getFilename();
                if (file_exists($existingFile)) {
                    unlink($existingFile);
                }

                $fileImg->move(FCPATH . 'media/photos/package');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }

            // Update or add gallery data
            if ($this->galleryPackageModel->isGalleryExist($id)) {
                // Update gallery with the new or existing file names
                $this->galleryPackageModel->update_gallery($id, $gallery);
            } else {
                // Add new gallery if it doesn't exist
                $this->galleryPackageModel->add_new_gallery($id, $gallery);
            }
        } else {
            // Delete gallery if no files are uploaded
            $this->galleryPackageModel->delete_gallery($id);

            // Set default gallery image
            $defaultImageName = 'default.jpg';
            $defaultImage = FCPATH . 'media/photos/package/' . $defaultImageName;

            $gallery = [$defaultImageName];

            // Add or update gallery with default image
            if ($this->galleryPackageModel->isGalleryExist($id)) {
                // Update gallery with the default image
                $this->galleryPackageModel->update_gallery($id, $gallery);
            } else {
                // Add new gallery with the default image
                $this->galleryPackageModel->add_new_gallery($id, $gallery);
            }
        }

        if ($addPA) {
            return redirect()->to(base_url('dashboard/package/edit') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id = null)
    {
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();

        $package = $this->packageModel->get_package_by_id($id)->getRowArray();
        if (empty($package)) {
            return redirect()->to('dashboard/package');
        }

        $list_gallery = $this->galleryPackageModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $package['gallery'] = $galleries;

        $type = $this->packageTypeModel->get_list_package_type()->getResultArray();

        $servicelist = $this->servicePackageModel->get_list_service_package()->getResultArray();
        // $detailservice = $this->detailServicePackageModel->get_detailServicePackage_by_id($id)->getRowArray();

        $this->builder->select('service_package.id, service_package.name');
        $this->builder->join('detail_service_package', 'detail_service_package.package_id = package.id');
        $this->builder->join('service_package', 'service_package.id = detail_service_package.service_package_id', 'right');
        $this->builder->where('package.id', $id);
        $query = $this->builder->get();
        $datase['package'] = $query->getResult();
        $datases = $datase['package'];
        $package['datase'] = $datases;

        $servicepackage = $this->detailServicePackageModel->get_service_package_detail_by_id($id)->getResultArray();

        $packageDay = $this->packageDayModel->get_package_day_by_id($id)->getResultArray();
        $culinary = $this->culinaryPlaceModel->get_list_cp()->getResultArray();
        $traditional = $this->traditionalHouseModel->get_list_th()->getResultArray();
        $worship = $this->worshipPlaceModel->get_list_wp()->getResultArray();
        $facility = $this->facilityModel->get_list_facility()->getResultArray();
        $souvenir = $this->souvenirPlaceModel->get_list_sp()->getResultArray();
        $attraction = $this->attractionModel->get_list_attraction()->getResultArray();
        $event = $this->eventModel->get_list_event()->getResultArray();
        $homestay = $this->homestayModel->get_list_homestay_homestay()->getResultArray();

        $data_object = array_merge($culinary, $worship, $facility, $souvenir, $attraction, $event, $homestay);

        $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($id)->getResultArray();

        $combinedData = $this->detailPackageModel->getCombinedData($id);
        $combinedDataPrice = $this->detailPackageModel->getCombinedDataPrice($id);

        $object = [
            'culinary' => $culinary,
            'traditional' => $traditional,
            'worship' => $worship,
            'facility' => $facility,
            'souvenir' => $souvenir,
            'attraction' => $attraction,
            'event' => $event,
            'homestay' => $homestay
        ];

        $data = [
            'title' => 'Package',
            'id' => $id,
            'data' => $package,
            'data2' => $contents2,
            'type' => $type,
            'day' => $packageDay,
            'activity' => $combinedData,
            'data_package' => $combinedData,
            'expectedPrice' => $combinedDataPrice,
            'object' => $object,
            'detailservice' => $servicepackage,
            'service' => $package['datase'],
            'servicelist' => $servicelist
        ];
        return view('dashboard/package-form', $data);
    }

    /**
     * Process the updating, full or partial, of a specific resource object.
     * This should be a POST.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $request = $this->request->getPost();
        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'type_id' => $request['type'],
            'min_capacity' => $request['min_capacity'],
            'price' => $request['price'],
            'description' => $request['description'],
            'contact_person' => $request['contact_person']
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        // Handle video file
        if (isset($request['video'])) {
            $folder = $request['video'];
            $filepath = WRITEPATH . 'uploads/' . $folder;
            $filenames = get_filenames($filepath);
            $vidFile = new File($filepath . '/' . $filenames[0]);
            $vidFile->move(FCPATH . 'media/videos');
            delete_files($filepath);
            rmdir($filepath);
            $requestData['video_url'] = $vidFile->getFilename();
        } else {
            $requestData['video_url'] = null;
        }

        // Update package data
        $updatePA = $this->packageModel->update_package($id, $requestData);

        $combinedDataPrice = $this->detailPackageModel->getCombinedDataPrice($id);
        $combinedServicePrice = $this->detailServicePackageModel->getCombinedServicePrice($id);

        $requestDataPrice = [
            'id' => $id,
            'price' => $combinedDataPrice + $combinedServicePrice
        ];
        $updatePAA = $this->packageModel->update_package_price($id, $requestDataPrice);

        // Handle gallery files
        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);

                // Remove old file with the same name, if exists
                $existingFile = FCPATH . 'media/photos/package/' . $fileImg->getFilename();
                if (file_exists($existingFile)) {
                    unlink($existingFile);
                }

                $fileImg->move(FCPATH . 'media/photos/package');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }

            // Update or add gallery data
            if ($this->galleryPackageModel->isGalleryExist($id)) {
                // Update gallery with the new or existing file names
                $this->galleryPackageModel->update_gallery($id, $gallery);
            } else {
                // Add new gallery if it doesn't exist
                $this->galleryPackageModel->add_new_gallery($id, $gallery);
            }
        } else {
            // Delete gallery if no files are uploaded
            $this->galleryPackageModel->delete_gallery($id);

            // Set default gallery image
            $defaultImageName = 'default.jpg';
            $defaultImage = FCPATH . 'media/photos/package/' . $defaultImageName;

            $gallery = [$defaultImageName];

            // Add or update gallery with default image
            if ($this->galleryPackageModel->isGalleryExist($id)) {
                // Update gallery with the default image
                $this->galleryPackageModel->update_gallery($id, $gallery);
            } else {
                // Add new gallery with the default image
                $this->galleryPackageModel->add_new_gallery($id, $gallery);
            }
        }


        if ($updatePA) {
            return redirect()->to(base_url('dashboard/package') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function updatecapacity()
    {
        $request = $this->request->getPost();
        $requestData = [
            'id' => $request['id'],
            'min_capacity' => $request['min_capacity'],
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $id = $request['id'];
        $package_min_capacity = $request['min_capacity'];

        $combinedDataPrice = $this->detailPackageModel->getCombinedDataPriceCustom($id, $package_min_capacity);
        $combinedServicePrice = $this->detailServicePackageModel->getCombinedServicePriceCustom($id, $package_min_capacity);

        $requestDataPrice = [
            'id' => $id,
            'price' => $combinedDataPrice + $combinedServicePrice
        ];
        $updatePAA = $this->packageModel->update_package_price($id, $requestDataPrice);
        $updatePA = $this->packageModel->update_package($id, $requestData);

        // Update package data

        if ($updatePAA) {
            $response = [
                'status' => 'success',
                'message' => 'Minimal capacity has been successfully updated.'
            ];
            return $this->response->setJSON($response);
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Failed to update minimal capacity.'
            ];
            return $this->response->setJSON($response);
        }
    }

    public function createReview()
    {
        $request = $this->request->getPost();
        $id = $request['id'];
        $user_id = user()->id;
        $review = [
            'package_id' => $id,
            'user_id' => $user_id,
            'rating' => $request['rating'],
            'review' => $request['review']
        ];

        // Check if the user has already reviewed this package
        if ($this->reservationModel->getReviewByUser($id, $user_id)) {
            return redirect()->back()->with('error', 'You have already reviewed this package.');
        }

        // Insert the review
        if ($this->reservationModel->createReview($review)) {
            return redirect()->back()->with('success', 'Review submitted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to submit review.');
        }
    }

    public function updateReview($id = null)
    {
        $request = $this->request->getPost();
        $review = [
            'rating' => $request['rating'],
            'review' => $request['review']
        ];

        // Update the review
        if ($this->reservationModel->update_review($id, $review)) {
            return redirect()->back()->with('success', 'Review updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update review.');
        }
    }

    public function deleteReview($id = null)
    {
        // Delete the review
        if ($this->reservationModel->delete_review($id)) {
            return redirect()->back()->with('success', 'Review deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete review.');
        }
    }


    public function updatecustom($id = null)
    {
        $request = $this->request->getPost();
        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'type_id' => $request['type'],
            'min_capacity' => $request['min_capacity'],
            'price' => $request['price'],
            'description' => $request['description'],
            'contact_person' => $request['contact_person']
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        // $geom = $request['multipolygon'];
        // $geojson = $request['geo-json'];

        if (isset($request['video'])) {
            $folder = $request['video'];
            $filepath = WRITEPATH . 'uploads/' . $folder;
            $filenames = get_filenames($filepath);
            $vidFile = new File($filepath . '/' . $filenames[0]);
            $vidFile->move(FCPATH . 'media/videos');
            delete_files($filepath);
            rmdir($filepath);
            $requestData['video_url'] = $vidFile->getFilename();
        } else {
            $requestData['video_url'] = null;
        }
        $updatePA = $this->packageModel->update_package($id, $requestData);
        // $updateGeom = $this->packageModel->update_geom($id, $geom);

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
            $this->galleryPackageModel->update_gallery($id, $gallery);
        } else {
            $this->galleryPackageModel->delete_gallery($id);
        }

        //data reservation
        $dataRC = $this->reservationModel->get_package_reservation_by_idp($id)->getRowArray();
        $idr = $dataRC['id'];

        //data home
        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();


        if ($dataRC['cancel'] == '0') {
            $booking_unit = $this->detailReservationModel->get_unit_homestay_bookingnya($idr)->getResultArray();
        } else if ($dataRC['cancel'] == '1') {
            $booking_unit = $this->backupDetailReservationModel->get_unit_homestay_bookingnya($idr)->getResultArray();
        }
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

                if ($dataRC['cancel'] == '0') {
                    $unit_booking[] = $this->detailReservationModel->get_unit_homestay_booking_data($date, $homestay_id, $unit_type, $unit_number, $idr)->getRowArray();
                    $total_price_homestay = $this->detailReservationModel->get_price_homestay_booking($homestay_id, $unit_type, $unit_number, $idr)->getRow();
                } else if ($dataRC['cancel'] == '1') {
                    $unit_booking[] = $this->backupDetailReservationModel->get_unit_homestay_booking_data($date, $homestay_id, $unit_type, $unit_number, $idr)->getRowArray();
                    $total_price_homestay = $this->backupDetailReservationModel->get_price_homestay_booking($homestay_id, $unit_type, $unit_number, $idr)->getRow();
                }

                $total[] = $total_price_homestay->price;
            }

            $data_price = $total;
            $tphom = array_sum($data_price);
            $tph = $tphom;
            $data_unit_booking = $unit_booking;
        } else {
            $data_unit_booking = [];
            $tph = '0';
        }

        //update data biaya reservasi dan deposit
        $capacity = $request['min_capacity'];
        $price = $request['price'];
        $totalPeople = $dataRC['total_people'];
        $idr = $dataRC['id'];

        $numberOfPackages = floor($totalPeople / $capacity);
        $remainder = $totalPeople % $capacity; // Hitung sisa hasil bagi
        $batas = ceil($capacity / 2);

        if ($numberOfPackages != 0) {
            if ($remainder != 0 && $remainder < $batas) {
                $add = 0.5;
                $order = $numberOfPackages + $add; // Tambahkan 0.5 jika sisa kurang dari 5
                $totalPrice = $price * $order;
                $deposit = $totalPrice * 0.2;
            } else if ($remainder >= $batas) {
                $add = 1;
                $order = $numberOfPackages + $add; // Tambahkan 1 jika sisa lebih dari atau sama dengan 5
                $totalPrice = $price * $order;
                $deposit = $totalPrice * 0.2;
            } else if ($remainder == 0) {
                $add = 0;
                $order = $numberOfPackages + $add;
                $totalPrice = $price * $order;
                $deposit = $totalPrice * 0.2;
            }
        } else {
            $add = 1;
            $order = $numberOfPackages + $add;
            $totalPrice = $price * $order;
            $deposit = $totalPrice * 0.2;
        }

        $rekaptotalPrice = $totalPrice + $tph;
        $rekaptotalPricedeposit = $rekaptotalPrice * 0.2;

        $requestData1 = [
            'total_price' => $rekaptotalPrice,
            'deposit' => $rekaptotalPricedeposit
        ];

        // dd($requestData1);
        $updateRA = $this->reservationModel->update_reservation($idr, $requestData1);

        if ($updatePA && $updateRA) {
            return redirect()->to(base_url('dashboard/detailreservation/confirm/') . '/' . $idr);
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function delete($id = null)
    {
        $request = $this->request->getPost();

        $id = $request['id'];
        $name = $request['name'];

        $array1 = array('package_id' => $id);
        $deleteDP = $this->detailPackageModel->where($array1)->delete();
        $deletePD = $this->packageDayModel->where($array1)->delete();
        $deleteDSP = $this->detailServicePackageModel->where($array1)->delete();

        $array = array('id' => $id, 'name' => $name);
        $package = $this->packageModel->where($array)->find();
        $deleteP = $this->packageModel->where($array)->delete();

        if ($deleteP) {
            session()->setFlashdata('success', 'Package "' . $name . '" yang di custom berhasil dibatalkan.');

            return redirect()->to(base_url('web/package'));

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
    }

    public function extend($id = null)
    {
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();

        $package = $this->packageModel->get_package_by_id($id)->getRowArray();
        if (empty($package)) {
            return redirect()->to('web/package');
        }

        $type = $this->packageTypeModel->get_list_package_type()->getResultArray();

        $servicelist = $this->servicePackageModel->get_list_service_package()->getResultArray();
        // $detailservice = $this->detailServicePackageModel->get_detailServicePackage_by_id($id)->getRowArray();

        $this->builder->select('service_package.id, service_package.name');
        $this->builder->join('detail_service_package', 'detail_service_package.package_id = package.id');
        $this->builder->join('service_package', 'service_package.id = detail_service_package.service_package_id', 'right');
        $this->builder->where('package.id', $id);
        $query = $this->builder->get();
        $datase['package'] = $query->getResult();
        $datases = $datase['package'];

        $servicepackage = $this->detailServicePackageModel->get_service_package_detail_by_id($id)->getResultArray();

        // ---------data activity-------------------
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

        $data = [
            'title' => 'Detail Package ' . $package['name'],
            'id' => $id,
            'data' => $package,
            'data2' => $contents2,
            'type' => $type,
            // 'day' => $packageDay,
            // 'activity' => $detailPackage,
            // 'data_package' => $combinedData,
            'day' => $packageDay,
            'activity' => $combinedData,
            'data_package' => $combinedData,
            'object' => $object,
            'detailservice' => $servicepackage,
            'service' => $datases,
            'servicelist' => $servicelist
        ];
        // dd($data);
        return view('web/extend-package-form', $data, $object);
    }

    public function custompackage($id = null)
    {
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();

        $package = $this->packageModel->get_package_by_id($id)->getRowArray();
        if (empty($package)) {
            return redirect()->to('web/package');
        }

        $type = $this->packageTypeModel->get_list_package_type()->getResultArray();

        $servicelist = $this->servicePackageModel->get_list_service_package()->getResultArray();
        // $detailservice = $this->detailServicePackageModel->get_detailServicePackage_by_id($id)->getRowArray();

        $this->builder->select('service_package.id, service_package.name');
        $this->builder->join('detail_service_package', 'detail_service_package.package_id = package.id');
        $this->builder->join('service_package', 'service_package.id = detail_service_package.service_package_id', 'right');
        $this->builder->where('package.id', $id);
        $query = $this->builder->get();
        $datase['package'] = $query->getResult();
        $datases = $datase['package'];

        $servicepackage = $this->detailServicePackageModel->get_service_package_detail_by_id($id)->getResultArray();

        // ---------data activity-------------------
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

        $data = [
            'title' => 'Detail Package ' . $package['name'],
            'id' => $id,
            'data' => $package,
            'data2' => $contents2,
            'type' => $type,
            // 'day' => $packageDay,
            // 'activity' => $detailPackage,
            // 'data_package' => $combinedData,
            'day' => $packageDay,
            'activity' => $combinedData,
            'data_package' => $combinedData,
            'object' => $object,
            'detailservice' => $servicepackage,
            'service' => $datases,
            'servicelist' => $servicelist
        ];
        // dd($data);
        return view('web/custom-package-form', $data, $object);
    }

    public function custom($id = null)
    {
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();

        $package = $this->packageModel->get_package_by_id($id)->getRowArray();
        if (empty($package)) {
            return redirect()->to('web/package');
        }

        $type = $this->packageTypeModel->get_list_package_type()->getResultArray();

        $servicelist = $this->servicePackageModel->get_list_service_package()->getResultArray();
        // $detailservice = $this->detailServicePackageModel->get_detailServicePackage_by_id($id)->getRowArray();

        $this->builder->select('service_package.id, service_package.name');
        $this->builder->join('detail_service_package', 'detail_service_package.package_id = package.id');
        $this->builder->join('service_package', 'service_package.id = detail_service_package.service_package_id', 'right');
        $this->builder->where('package.id', $id);
        $query = $this->builder->get();
        $datase['package'] = $query->getResult();
        $datases = $datase['package'];

        $servicepackage = $this->detailServicePackageModel->get_service_package_detail_by_id($id)->getResultArray();

        // ---------data activity-------------------
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

        $data = [
            'title' => 'Detail Package ' . $package['name'],
            'id' => $id,
            'data' => $package,
            'data2' => $contents2,
            'type' => $type,
            // 'day' => $packageDay,
            // 'activity' => $detailPackage,
            // 'data_package' => $combinedData,
            'day' => $packageDay,
            'activity' => $combinedData,
            'data_package' => $combinedData,
            'object' => $object,
            'detailservice' => $servicepackage,
            'service' => $datases,
            'servicelist' => $servicelist
        ];
        // dd($data);
        return view('web/new-custom-package-form', $data, $object);
    }

    public function listmobile()
    {
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();

        $list_package = $this->packageModel->get_list_package_default_mobile()->getResultArray();
        $packages = [];

        foreach ($list_package as $package) {
            $id = $package['id'];
            $package = $this->packageModel->get_package_by_id($id)->getRowArray();
            if (empty($package)) {
                return redirect()->to(substr(current_url(), 0, -strlen($id)));
            }

            $list_gallery = $this->galleryPackageModel->get_gallery($id)->getResultArray();
            $galleries = array();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }
            $package['gallery'] = $galleries;

            $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($id)->getResultArray();
            $getday = $this->packageDayModel->get_list_package_day($id)->getResultArray();
            $combinedData = $this->detailPackageModel->getCombinedData($id);

            $datapackage = [
                'title' => $package['name'],
                'type_name' => $package['type_name'],
                'price' => $package['price'],
                'data' => $package,
                'data2' => $contents2,
                'day' => $getday,
                'activity' => $combinedData,
                'folder' => 'package'
            ];

            $packages[] = $datapackage;
        }

        $data = [
            'title' => 'Explore Sumpu',
            'data2' => $contents2,
            'datapackage' => $packages,

        ];
        return view('maps/list_package', $data);
    }

    public function deleteobject($id = null)
    {
        $request = $this->request->getPost();

        $id = $request['id'];
        $array1 = array('id' => $id);
        $deleteHM = $this->packageModel->where($array1)->delete();

        if ($deleteHM) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete Package"
                ]
            ];
            session()->setFlashdata('success', 'Package "' . $id . '" Deleted Successfully.');

            return redirect()->to(base_url('dashboard/package'));
        } else {
            $response = [
                'status' => 404,
                'message' => [
                    "Package failed to delete"
                ]
            ];
            return $this->failNotFound($response);
        }
    }
}
