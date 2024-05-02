<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;


class DetailPackageModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'detail_package';
    // protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['package_id', 'day', 'activity', 'activity_type', 'object_id', 'description', 'status', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function get_detailPackage_by_id($package_id)
    {

        $query = $this->db->table($this->table)
            ->select("*")
            ->where('package_id', $package_id)
            ->get();

        return $query;
    }

    public function getCombinedData($package_id)
    {
        $culinaryPlaceModel = new CulinaryPlaceModel();
        $traditionalHouseModel = new TraditionalHouseModel();
        $souvenirPlaceModel = new SouvenirPlaceModel();
        $worshipPlaceModel = new WorshipPlaceModel();
        $facilityModel = new FacilityModel();
        $attractionModel = new AttractionModel();
        $eventModel = new EventModel();
        $homestayModel = new HomestayModel();

        $culinaryData = $culinaryPlaceModel->select('package_id, day, activity, activity_type, object_id, detail_package.status, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
            ->join('detail_package', 'detail_package.object_id=culinary_place.id')
            ->where('detail_package.package_id', $package_id)
            ->get()->getResultArray();

        $traditionalData = $traditionalHouseModel->select('package_id, day, ticket_price as traditional_house_price, activity, activity_type, object_id, detail_package.status, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
            ->join('detail_package', 'detail_package.object_id=traditional_house.id')
            ->where('detail_package.package_id', $package_id)
            ->get()->getResultArray();

        $souvenirData = $souvenirPlaceModel->select('package_id, day, activity, activity_type, object_id, detail_package.status, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
            ->join('detail_package', 'detail_package.object_id=souvenir_place.id')
            ->where('detail_package.package_id', $package_id)
            ->get()->getResultArray();

        $worshipData = $worshipPlaceModel->select('package_id, day, activity, activity_type, object_id, detail_package.status, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
            ->join('detail_package', 'detail_package.object_id=worship_place.id')
            ->where('detail_package.package_id', $package_id)
            ->get()->getResultArray();

        $facilityData = $facilityModel->select('package_id, day, activity, activity_type, object_id, detail_package.status, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
            ->join('detail_package', 'detail_package.object_id=facility.id')
            ->where('detail_package.package_id', $package_id)
            ->get()->getResultArray();

        $attractionData = $attractionModel->select('package_id, day, activity, price as attraction_price, activity_type, object_id, detail_package.status, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
            ->join('detail_package', 'detail_package.object_id=attraction.id')
            ->where('detail_package.package_id', $package_id)
            ->get()->getResultArray();

        $eventData = $eventModel->select('package_id, day, activity, object_id, detail_package.status, activity_type, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
            ->join('detail_package', 'detail_package.object_id=event.id')
            ->where('detail_package.package_id', $package_id)
            ->get()->getResultArray();

        $homestayData = $homestayModel->select('package_id, day, activity, object_id, detail_package.status, activity_type, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
            ->join('detail_package', 'detail_package.object_id=homestay.id')
            ->where('detail_package.package_id', $package_id)
            ->get()->getResultArray();

        // Gabungkan hasil dari kedua model
        $combinedData = array_merge($culinaryData, $traditionalData, $souvenirData, $worshipData, $facilityData, $attractionData, $eventData, $homestayData);

        usort($combinedData, function ($a, $b) {
            $dayComparison = strcmp($a['day'], $b['day']);
            if ($dayComparison === 0) {
                // Jika 'day' sama, bandingkan berdasarkan 'activity'
                return strcmp($a['activity'], $b['activity']);
            }
            // Urutkan berdasarkan 'day' terlebih dahulu
            return $dayComparison;
        });

        return $combinedData;
    }

    // public function getCombinedDataPrice($package_id)
    // {
    //     $attractionModel = new AttractionModel();
    //     $traditionalHouseModel = new TraditionalHouseModel();
    
    //     // Ambil data harga atraksi dan harga tiket rumah tradisional
    //     $attractionData = $attractionModel->select('attraction.price as attraction_price')
    //         ->join('detail_package', 'detail_package.object_id = attraction.id')
    //         ->where('detail_package.package_id', $package_id)
    //         ->get()->getResultArray();
    
    //     $traditionalHouseData = $traditionalHouseModel->select('traditional_house.ticket_price as traditional_house_price')
    //         ->join('detail_package', 'detail_package.object_id = traditional_house.id')
    //         ->where('detail_package.package_id', $package_id)
    //         ->get()->getResultArray();
    
    //     // Inisialisasi variabel untuk total harga
    //     $combinedDataPrice = 0;
    
    //     // Iterasi melalui data atraksi
    //     foreach ($attractionData as $attraction) {
    //         // Periksa apakah 'attraction_price' ada dan memiliki nilai
    //         if (isset($attraction['attraction_price']) && is_numeric($attraction['attraction_price'])) {
    //             // Tambahkan 'attraction_price' ke total harga
    //             $combinedDataPrice += $attraction['attraction_price'];
    //         }
    //     }
    
    //     // Iterasi melalui data tiket rumah tradisional
    //     foreach ($traditionalHouseData as $traditionalHouse) {
    //         // Periksa apakah 'traditional_house_price' ada dan memiliki nilai
    //         if (isset($traditionalHouse['traditional_house_price']) && is_numeric($traditionalHouse['traditional_house_price'])) {
    //             // Tambahkan 'traditional_house_price' ke total harga
    //             $combinedDataPrice += $traditionalHouse['traditional_house_price'];
    //         }
    //     }
    
    //     return $combinedDataPrice;
    // }


    public function getCombinedDataPrice($package_id = null, $package_min_capacity = null)
    {
        $attractionModel = new AttractionModel();
        $traditionalHouseModel = new TraditionalHouseModel();

        // Ambil data harga atraksi dan harga tiket rumah tradisional
        $attractionData = $attractionModel->select('attraction.price as attraction_price, attraction.min_capacity as min_capacity, package.min_capacity as package_min_capacity')
            ->join('detail_package', 'detail_package.object_id = attraction.id')
            ->join('package', 'detail_package.package_id = package.id')
            ->where('detail_package.package_id', $package_id)
            ->get()->getResultArray();

        $traditionalHouseData = $traditionalHouseModel->select('traditional_house.ticket_price as traditional_house_price, traditional_house.min_capacity as min_capacity, package.min_capacity as package_min_capacity')
            ->join('detail_package', 'detail_package.object_id = traditional_house.id')
            ->where('detail_package.package_id', $package_id)
            ->join('package', 'detail_package.package_id = package.id')
            ->get()->getResultArray();

        // Inisialisasi variabel untuk total harga
        $combinedDataPriceAT = 0;
        $combinedDataPriceTH = 0;
        $combinedDataPriceATTH = 0;

        // Iterasi melalui data atraksi
        foreach ($attractionData as $attraction) {
            // Periksa apakah 'attraction_price' ada dan memiliki nilai
            if (isset($attraction['attraction_price']) && is_numeric($attraction['attraction_price']) && isset($attraction['min_capacity']) && is_numeric($attraction['min_capacity'])) {
                // Tambahkan 'traditional_house_price' ke total harga
                $capacity = $attraction['min_capacity'];
                $totalPeople = $attraction['package_min_capacity'];


                $numberOfServices = floor($totalPeople / $capacity);
                $remainder = $totalPeople % $capacity;

                if ($numberOfServices !== 0) {
                    $add = 0;
                    if ($remainder !== 0 && $remainder < 5) {
                        $add = 1;
                    } elseif ($remainder >= 5) {
                        $add = 1;
                    }

                    $order = $numberOfServices + $add;
                } else {
                    $order = 1;
                }

                $combinedDataPriceAT += $attraction['attraction_price'] * $order;
            }
        }

        // Iterasi melalui data tiket rumah tradisional
        foreach ($traditionalHouseData as $traditionalHouse) {
            // Periksa apakah 'traditional_house_price' ada dan memiliki nilai
            if (isset($traditionalHouse['traditional_house_price']) && is_numeric($traditionalHouse['traditional_house_price']) && isset($traditionalHouse['min_capacity']) && is_numeric($traditionalHouse['min_capacity'])) {
                // Tambahkan 'traditional_house_price' ke total harga
                $capacity = $traditionalHouse['min_capacity'];
                $totalPeople = $traditionalHouse['package_min_capacity'];


                $numberOfServices = floor($totalPeople / $capacity);
                $remainder = $totalPeople % $capacity;

                if ($numberOfServices !== 0) {
                    $add = 0;
                    if ($remainder !== 0 && $remainder < 5) {
                        $add = 1;
                    } elseif ($remainder >= 5) {
                        $add = 1;
                    }

                    $order = $numberOfServices + $add;
                } else {
                    $order = 1;
                }

                $combinedDataPriceTH += $traditionalHouse['traditional_house_price'] * $order;
            }
        }

        $combinedDataPriceATTH = $combinedDataPriceAT + $combinedDataPriceTH;
        return $combinedDataPriceATTH;
    }
    public function getCombinedDataPriceCustom($package_id = null, $package_min_capacity = null)
    {
        $attractionModel = new AttractionModel();
        $traditionalHouseModel = new TraditionalHouseModel();

        // Ambil data harga atraksi dan harga tiket rumah tradisional
        // $attractionData = $attractionModel->select('attraction.price as attraction_price, attraction.min_capacity as min_capacity, package.min_capacity as package_min_capacity')
        $attractionData = $attractionModel->select('attraction.price as attraction_price, attraction.min_capacity as min_capacity')
            ->join('detail_package', 'detail_package.object_id = attraction.id')
            ->where('detail_package.package_id', $package_id)
            ->get()->getResultArray();

        // $traditionalHouseData = $traditionalHouseModel->select('traditional_house.ticket_price as traditional_house_price, traditional_house.min_capacity as min_capacity, package.min_capacity as package_min_capacity')
        $traditionalHouseData = $traditionalHouseModel->select('traditional_house.ticket_price as traditional_house_price, traditional_house.min_capacity as min_capacity')
            ->join('detail_package', 'detail_package.object_id = traditional_house.id')
            ->where('detail_package.package_id', $package_id)
            ->get()->getResultArray();

        // Inisialisasi variabel untuk total harga
        $combinedDataPriceAT = 0;
        $combinedDataPriceTH = 0;
        $combinedDataPriceATTH = 0;

        // Iterasi melalui data atraksi
        foreach ($attractionData as $attraction) {
            // Periksa apakah 'attraction_price' ada dan memiliki nilai
            if (isset($attraction['attraction_price']) && is_numeric($attraction['attraction_price']) && isset($attraction['min_capacity']) && is_numeric($attraction['min_capacity'])) {
                // Tambahkan 'traditional_house_price' ke total harga
                $capacity = $attraction['min_capacity'];
                $totalPeople = $package_min_capacity;


                $numberOfServices = floor($totalPeople / $capacity);
                $remainder = $totalPeople % $capacity;

                if ($numberOfServices !== 0) {
                    $add = 0;
                    if ($remainder !== 0 && $remainder < 5) {
                        $add = 1;
                    } elseif ($remainder >= 5) {
                        $add = 1;
                    }

                    $order = $numberOfServices + $add;
                } else {
                    $order = 1;
                }

                $combinedDataPriceAT += $attraction['attraction_price'] * $order;
            }
        }

        // Iterasi melalui data tiket rumah tradisional
        foreach ($traditionalHouseData as $traditionalHouse) {
            // Periksa apakah 'traditional_house_price' ada dan memiliki nilai
            if (isset($traditionalHouse['traditional_house_price']) && is_numeric($traditionalHouse['traditional_house_price']) && isset($traditionalHouse['min_capacity']) && is_numeric($traditionalHouse['min_capacity'])) {
                // Tambahkan 'traditional_house_price' ke total harga
                $capacity = $traditionalHouse['min_capacity'];
                $totalPeople = $package_min_capacity;


                $numberOfServices = floor($totalPeople / $capacity);
                $remainder = $totalPeople % $capacity;

                if ($numberOfServices !== 0) {
                    $add = 0;
                    if ($remainder !== 0 && $remainder < 5) {
                        $add = 1;
                    } elseif ($remainder >= 5) {
                        $add = 1;
                    }

                    $order = $numberOfServices + $add;
                } else {
                    $order = 1;
                }

                $combinedDataPriceTH += $traditionalHouse['traditional_house_price'] * $order;
            }
        }

        $combinedDataPriceATTH = $combinedDataPriceAT + $combinedDataPriceTH;
        return $combinedDataPriceATTH;
    }



    //   ---  
    public function get_day_by_package($package_id)
    {
        $query = $this->db->table($this->table)
            ->select("day")
            ->where('package_id', $package_id)
            ->distinct()
            ->get();

        return $query;
    }

    public function get_activity_day($day, $package_id)
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('package_id', $package_id)
            ->where('day', $day)
            ->get();

        return $query;
    }

    public function culinary_place($package_id)
    {
        $culinaryPlaceModel = new CulinaryPlaceModel();

        $culinary_place = $culinaryPlaceModel->select('package_id, day, activity, activity_type, object_id, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
            ->join('detail_package', 'detail_package.object_id=culinary_place.id')
            ->where('detail_package.package_id', $package_id)
            ->get()->getResultArray();

        return $culinary_place;
    }

    public function traditional_house($package_id)
    {
        $traditionalHouseModel = new TraditionalHouseModel();

        $traditional_house = $traditionalHouseModel->select('package_id, day, activity, activity_type, object_id, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
            ->join('detail_package', 'detail_package.object_id=traditional_house.id')
            ->where('detail_package.package_id', $package_id)
            ->get()->getResultArray();

        return $traditional_house;
    }

    public function worship_place($package_id)
    {
        $worshipPlaceModel = new WorshipPlaceModel();

        $worship_place = $worshipPlaceModel->select('package_id, day, activity, activity_type, object_id, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
            ->join('detail_package', 'detail_package.object_id=worship_place.id')
            ->where('detail_package.package_id', $package_id)
            ->get()->getResultArray();

        return $worship_place;
    }

    public function souvenir_place($package_id)
    {
        $souvenirPlaceModel = new SouvenirPlaceModel();

        $souvenir_place = $souvenirPlaceModel->select('package_id, day, activity, activity_type, object_id, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
            ->join('detail_package', 'detail_package.object_id=souvenir_place.id')
            ->where('detail_package.package_id', $package_id)
            ->get()->getResultArray();

        return $souvenir_place;
    }

    public function attraction($package_id)
    {
        $attractionModel = new AttractionModel();

        $attraction = $attractionModel->select('package_id, day, activity, activity_type, object_id, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
            ->join('detail_package', 'detail_package.object_id=attraction.id')
            ->where('detail_package.package_id', $package_id)
            ->get()->getResultArray();

        return $attraction;
    }

    public function checkIfDataExists($requestData)
    {
        return $this->table($this->table)
            ->where('package_id', $requestData['package_id'])
            ->where('day', $requestData['day'])
            ->where('activity', $requestData['activity'])
            ->get()
            ->getRow();
    }

    public function add_new_packageActivity($packageActivity = null)
    {

        $packageActivity['created_at'] = Time::now();
        $packageActivity['updated_at'] = Time::now();

        $insert = $this->db->table($this->table)
            ->insert($packageActivity);
        return $insert;

        // $insert = $this->db->table($this->table)
        //     ->insert($packageActivity);
        // return $insert;
    }

    function get_object()
    {
        $query = $this->db->get('attraction');
        return $query;
    }

    // public function add_new_detail_service($id, array $detailService)
    // {
    //     // dd($detailService);
    //     $query = false;
    //     foreach ($detailService as $ds) {
    //         $content = [
    //             'service_package_id' => $ds,
    //             'package_id' => $id,
    //             'status' => '1',
    //         ];
    //         $query = $this->db->table($this->table)->insert($content);
    //     }
    //     return $query;
    // }

    // public function delete_detail_service($id = null)
    // {
    //     return $this->db->table($this->table)->delete(['package_id' => $id]);
    // }

}
