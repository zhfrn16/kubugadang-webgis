<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class DetailServicePackageModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'detail_service_package';
    // protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['service_package_id', 'package_id', 'status', 'created_at', 'updated_at', 'status_created'];

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

    public function get_service($package_id = null)
    {
        $query = $this->db->table($this->table)
            ->select('service_package_id')
            ->where('package_id', $package_id)
            ->orderBy('service_package_id', 'ASC')
            ->get();
        return $query;
    }

    public function get_service_package_detail_by_id($id = null)
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->join('service_package', 'detail_service_package.service_package_id = service_package.id')
            ->where('detail_service_package.package_id', $id)
            ->get();
        return $query;
    }

    public function checkIfDataExistsHomestay($id)
    {
        return $this->table($this->table)
            ->join('service_package', 'detail_service_package.service_package_id = service_package.id')
            ->where('service_package.name', 'Homestay')
            ->where('detail_service_package.package_id', $id)
            ->get()
            ->getRow();
    }


    public function get_service_include_by_id($id = null)
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->join('service_package', 'detail_service_package.service_package_id = service_package.id')
            ->where('detail_service_package.package_id', $id)
            ->where('detail_service_package.status', '1')
            ->get();

        return $query;
    }

    public function get_service_exclude_by_id($id = null)
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->join('service_package', 'detail_service_package.service_package_id = service_package.id')
            ->where('detail_service_package.package_id', $id)
            ->where('detail_service_package.status', '0')
            ->get();

        return $query;
    }

    public function checkIfDataExists($requestData)
    {
        return $this->table($this->table)
            ->where('service_package_id', $requestData['service_package_id'])
            ->where('package_id', $requestData['package_id'])
            ->get()
            ->getRow();
    }

    public function add_new_detail_service_package($requestData = null)
    {
        $requestData['created_at'] = Time::now();
        $requestData['updated_at'] = Time::now();
        $insert = $this->db->table($this->table)
            ->insert($requestData);
        return $insert;
    }

    public function add_new_detail_service($id, $requestData)
    {
        
        $query = false;
        $content = [
            'service_package_id' =>  $requestData['service_package_id'],
            'package_id' => $id,
            'status' => $requestData['status'],
            'created_at' => Time::now(),
            'updated_at' => Time::now(),
            'status_created' => $requestData['status_created'],
            
        ];

        $query = $this->db->table($this->table)->insert($content);

        return $query;
    }

    public function delete_detail_service($id = null)
    {
        return $this->db->table($this->table)->delete(['package_id' => $id]);
    }

     public function getCombinedServicePrice($package_id = null, $package_min_capacity = null)
    {
        $servicePackageModel = new ServicePackageModel();
        $servicePackageData = $servicePackageModel->select('service_package.price as price, service_package.min_capacity as min_capacity, package.min_capacity as package_min_capacity')
            ->join('detail_service_package', 'detail_service_package.service_package_id=service_package.id')
            ->join('package', 'detail_service_package.package_id=package.id')
            ->where('detail_service_package.status', '1')
            ->where('detail_service_package.package_id', $package_id)
            ->get()->getResultArray();

        $packageDayModel = new PackageDayModel();
        $packageDayData = $packageDayModel->select('count(package_day.day) as day')
            ->where('package_day.package_id', $package_id)
            ->get()->getRowArray(); // Menggunakan getRowArray karena kita hanya mengambil satu baris

        // Inisialisasi variabel untuk total harga
        $combinedServicePrice = 0;

        // Iterasi melalui data service package
        foreach ($servicePackageData as $servicePackage) {
            // Periksa apakah 'price' dan 'min_capacity' ada dan memiliki nilai
            if (isset($servicePackage['price']) && is_numeric($servicePackage['price']) && isset($servicePackage['min_capacity']) && is_numeric($servicePackage['min_capacity'])) {
                // Kalkulasi jumlah item berdasarkan kapasitas paket dan layanan
                $capacity = $servicePackage['min_capacity'];
                $totalPeople = $servicePackage['package_min_capacity'];
                $day = $packageDayData['day'];

                // Menambahkan logika perhitungan totalDay
                if ($day > 2) {
                    $totalDay = $day - 1;
                } elseif ($day <= 2) {
                    $totalDay = 1;
                } else {
                    $totalDay = 0;
                }

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

                // Tambahkan harga layanan yang sudah dihitung ke total harga
                $combinedServicePrice += $servicePackage['price'] * $order * $totalDay;
            }
        }

        return $combinedServicePrice;
    }

    public function getCombinedServicePriceCustom($package_id = null, $package_min_capacity = null)
    {
        $servicePackageModel = new ServicePackageModel();
        $servicePackageData = $servicePackageModel->select('service_package.price as price, service_package.min_capacity as min_capacity, package.min_capacity as package_min_capacity')
            ->join('detail_service_package', 'detail_service_package.service_package_id=service_package.id')
            ->join('package', 'detail_service_package.package_id=package.id')
            ->where('detail_service_package.status', '1')
            ->where('detail_service_package.package_id', $package_id)
            ->get()->getResultArray();

        $packageDayModel = new PackageDayModel();
        $packageDayData = $packageDayModel->select('count(package_day.day) as day')
            ->where('package_day.package_id', $package_id)
            ->get()->getRowArray(); // Menggunakan getRowArray karena kita hanya mengambil satu baris

        // Inisialisasi variabel untuk total harga
        $combinedServicePrice = 0;

        // Iterasi melalui data service package
        foreach ($servicePackageData as $servicePackage) {
            // Periksa apakah 'price' dan 'min_capacity' ada dan memiliki nilai
            if (isset($servicePackage['price']) && is_numeric($servicePackage['price']) && isset($servicePackage['min_capacity']) && is_numeric($servicePackage['min_capacity'])) {
                // Kalkulasi jumlah item berdasarkan kapasitas paket dan layanan
                $capacity = $servicePackage['min_capacity'];
                $totalPeople = $package_min_capacity;
                $day = $packageDayData['day'];

                // Menambahkan logika perhitungan totalDay
                if ($day > 2) {
                    $totalDay = $day - 1;
                } elseif ($day <= 2) {
                    $totalDay = 1;
                } else {
                    $totalDay = 0;
                }

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

                // Tambahkan harga layanan yang sudah dihitung ke total harga
                $combinedServicePrice += $servicePackage['price'] * $order * $totalDay;
            }
        }

        return $combinedServicePrice;
    }

    
}
