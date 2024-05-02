<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'package';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'name', 'type_id', 'min_capacity', 'price', 'contact_person', 'description', 'video_url', 'custom', 'status'];

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

    public function get_list_package()
    {
        // $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.video_url,{$this->table}.min_capacity,{$this->table}.custom";
        $query = $this->db->table($this->table)
            ->select("{$columns}")
            ->join('package_type', 'package.type_id = package_type.id')
            // ->join('package_day', 'package.id = package_day.package_id')
            ->select('package_type.type_name')
            ->where('package.status', '1')
            ->orderBy('package.custom', 'ASC')
            ->orderBy('package.id', 'ASC')
            // ->groupby('package.id')
            ->get();
        return $query;
    }

    public function get_list_package_explore()
    {
        // $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.video_url,{$this->table}.min_capacity,{$this->table}.custom";
        $query = $this->db->table($this->table)
            ->select("{$columns}")
            ->join('package_type', 'package.type_id = package_type.id')
            // ->join('package_day', 'package.id = package_day.package_id')
            ->select('package_type.type_name')
            ->where('package.custom', '0')
            ->where('package.status', '1')
            ->orderBy('package.custom', 'ASC')
            ->orderBy('package.id', 'ASC')
            // ->groupby('package.id')
            ->get();
        return $query;
    }

    // //old
    // public function get_list_mypackage_explore($user_id)
    // {
    //     // $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
    //     $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.video_url,{$this->table}.min_capacity,{$this->table}.custom";
    //     $query = $this->db->table($this->table)
    //         ->select("{$columns}")
    //         // ->distinct() // Distinct agar tidak double
    //         ->join('reservation', 'package.id = reservation.package_id')
    //         ->join('package_type', 'package.type_id = package_type.id')
    //         ->select('package_type.type_name')
    //         ->where('reservation.user_id', $user_id)           
    //         ->where('reservation.admin_payment_check', '2')           
    //         ->orderBy('package.id', 'ASC')
    //         ->get();
    //     return $query;
    // }

    public function get_list_mypackage_explore($user_id)
    {
        $coords = "ST_Y(ST_Centroid(homestay.geom)) AS lat, ST_X(ST_Centroid(homestay.geom)) AS lng";
        $subQuery = $this->db->table('package')
            ->select("package.*, detail_reservation.*, homestay.name as homestay_name, reservation.check_in, reservation.payment_date, {$coords}")
            ->select('ROW_NUMBER() OVER(PARTITION BY detail_reservation.reservation_id ORDER BY detail_reservation.created_at) AS row_num')
            ->join('reservation', 'reservation.package_id = package.id')
            ->join('(SELECT *, ROW_NUMBER() OVER(PARTITION BY reservation_id ORDER BY created_at) AS detail_row_num FROM detail_reservation) AS detail_reservation', 'detail_reservation.reservation_id = reservation.id AND detail_reservation.detail_row_num = 1', 'left')
            ->join('homestay', 'homestay.id = detail_reservation.homestay_id', 'left')
            ->where('reservation.user_id', $user_id)
            ->where('reservation.cancel', '0')
            ->groupStart()
            ->where('reservation.payment_check', '200')
            ->orGroupStart()
            ->where('reservation.admin_payment_check', '2')
            ->groupEnd()
            ->groupEnd()
            ->orderBy('reservation.check_in', 'ASC') // Urutkan berdasarkan tanggal pembayaran
            ->get();

        // Periksa jika kueri berhasil dieksekusi
        if ($subQuery) {
            return $subQuery;
        } else {
            return []; // Mengembalikan array kosong jika terjadi kesalahan
        }
    }



    public function get_list_package_default()
    {
        // // $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        // $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.video_url,{$this->table}.min_capacity,{$this->table}.custom";
        // $query = $this->db->table($this->table)
        //     ->select("{$columns}")
        //     ->join('package_type', 'package.type_id = package_type.id')
        //     ->select('package_type.type_name')
        //     ->where('package.custom <>', '1')
        //     ->where('package.status', '1')
        //     ->orderby('package.id', 'DESC')
        //     // ->groupby('package.id')
        //     ->get();
        // return $query;

        $query = $this->db->table('package p')
            ->select('p.id, p.name, p.type_id, p.price, p.contact_person, p.description, p.video_url, p.min_capacity, p.custom, pt.type_name, COUNT(pd.day) AS days')
            ->join('package_type pt', 'p.type_id = pt.id')
            ->join('package_day pd', 'p.id = pd.package_id')
            ->where('p.custom <>', '1')
            ->where('p.status', '1')
            ->orderby('p.name', 'ASC')
            ->orderby('p.id', 'DESC')
            ->groupBy('p.id, p.name, p.type_id, p.price, p.contact_person, p.description, p.video_url, p.min_capacity, p.custom, pt.type_name')
            ->get();

        return $query;
    }

    // public function get_list_package_distinct()
    // {
    //     // $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
    //     $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.video_url,{$this->table}.min_capacity,{$this->table}.custom";
    //     $query = $this->db->table($this->table)
    //         ->select("max(day) as days, {$columns}")
    //         ->join('package_type', 'package.type_id = package_type.id')
    //         ->join('package_day', 'package.id = package_day.package_id')
    //         ->select('package_type.type_name, package_day.day')
    //         ->where('package.custom <>', '1')
    //         ->groupby('package.id')
    //         ->get();
    //     return $query;
    // }

    public function get_list_package_distinct()
    {
        $columns = "{$this->table}.id, {$this->table}.name, {$this->table}.type_id, {$this->table}.price, {$this->table}.contact_person, {$this->table}.description, {$this->table}.video_url, {$this->table}.min_capacity, {$this->table}.custom";

        $query = $this->db->table($this->table)
            ->select("MAX(package_day.day) AS days, {$columns}, package_type.type_name")
            ->join('package_type', 'package.type_id = package_type.id')
            ->join('package_day', 'package.id = package_day.package_id')
            ->where('package.custom <>', '1')
            ->groupBy('package.id')
            ->get();

        return $query;
    }



    public function get_package_by_id_custom($id = null)
    {
        // $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type_id,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.video_url,{$this->table}.min_capacity,{$this->table}.custom";
        // $query = $this->db->table($this->table)
        //     ->select("max(day) as days, {$columns}, package_type.type_name, package_day.day")
        //     ->join('package_type', 'package.type_id = package_type.id')
        //     ->join('package_day', 'package.id = package_day.package_id')
        //     ->where('package.id', $id)
        //     ->get();
        // return $query;

        // $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type_id,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.video_url,{$this->table}.min_capacity,{$this->table}.custom";
        // $query = $this->db->table($this->table)
        //     // ->select("max(package_day.day) as days, {$columns}, package_type.type_name, package_day.day")
        //     ->select("package.*")
        //     ->join('package_type', 'package.type_id = package_type.id')
        //     ->join('package_day', 'package.id = package_day.package_id')
        //     ->select("count(day) as days, package_type.type_name, package_day.day")            
        //     ->where('package.id', $id)
        //     ->groupBy("{$this->table}.id, {$this->table}.name, {$this->table}.type_id, {$this->table}.price, {$this->table}.contact_person, {$this->table}.description, {$this->table}.video_url, {$this->table}.min_capacity, {$this->table}.custom, package_type.type_name, package_day.day")
        //     ->get();

        // return $query;

        $query = $this->db->table('package p')
            ->select('p.id, p.name, p.type_id, p.price, p.contact_person, p.description, p.video_url, p.min_capacity, p.custom, pt.type_name, COUNT(pd.day) AS days')
            ->join('package_type pt', 'p.type_id = pt.id')
            ->join('package_day pd', 'p.id = pd.package_id')
            ->where('p.id', $id)
            ->groupBy('p.id, p.name, p.type_id, p.price, p.contact_person, p.description, p.video_url, p.min_capacity, p.custom, pt.type_name')
            ->get();

        return $query;
    }

    public function get_package_by_id($id = null)
    {
        // $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type_id,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.video_url,{$this->table}.min_capacity,{$this->table}.custom";
        // $geoJson = "ST_AsGeoJSON({$this->table}.geom) AS geoJson";
        $query = $this->db->table($this->table)
            ->select("{$columns}")
            ->where('package.id', $id)
            ->join('package_type', 'package.type_id = package_type.id')
            // ->join('package_day', 'package.id = package_day.package_id')
            // ->select('package_type.type_name, COUNT(package_day.day) AS days')
            ->select('package_type.type_name')
            ->get();
        return $query;
    }

    public function get_package_by_name($name = null)
    {
        // $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.video_url,{$this->table}.min_capacity,{$this->table}.custom";
        $query = $this->db->table($this->table)
            ->select("{$columns}")
            ->like("{$this->table}.name", $name)
            ->get();
        return $query;
    }

    public function get_package_by_type($type = null)
    {
        // $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.video_url,{$this->table}.min_capacity,{$this->table}.custom";
        $query = $this->db->table($this->table)
            ->select("{$columns}")
            ->like("{$this->table}.type_id", $type)
            ->get();
        return $query;
    }

    public function get_new_id()
    {

        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        if (empty($lastId)) {
            $id = 'P0001';
        } else {
            $count = (int)substr($lastId['id'], 2);
            $id = sprintf('P%04d', $count + 1);
        }
        return $id;
    }


    public function add_new_package($requestData = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($requestData);
        // $update = $this->db->table($this->table)
        //     ->set('geom', "ST_GeomFromText('{$geom}')", false)
        //     ->where('id', $requestData['id'])
        //     ->update();
        return $insert;
    }

    public function update_package($id = null, $package = null)
    {
        foreach ($package as $key => $value) {
            if (empty($value)) {
                unset($package[$key]);
            }
        }
        $query = $this->db->table($this->table)
            ->where('id', $id)
            ->update($package);
        return $query;
    }

    public function update_package_price($id = null, $requestDataPrice = null)
    {
        $package_id = $requestDataPrice['id'];
        $price = $requestDataPrice['price'];

        try {
            $query = $this->db->table($this->table)
                ->set('price', $price)
                ->where('id', $package_id)
                ->update();

            return $query;
        } catch (\Exception $e) {
            // Tangani kesalahan jika kueri pembaruan gagal
            return false;
        }
    }




    // public function update_geom($id = null, $geom = null)
    // {
    //     $query = $this->db->table($this->table)
    //         ->set('geom', "ST_GeomFromText('{$geom}')", false)
    //         ->where('id', $id)
    //         ->update();
    //     return $query;
    // }
}
