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
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.video_url,{$this->table}.min_capacity,{$this->table}.custom";
        $query = $this->db->table($this->table)
            ->select("{$columns}")
            ->join('package_type', 'package.type_id = package_type.id')
            ->select('package_type.type_name')
            ->where('package.status', '1')
            ->orderBy('package.custom', 'ASC')
            ->orderBy('package.id', 'ASC')
            ->get();
        return $query;
    }

    public function get_list_package_explore()
    {
        // $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.video_url,{$this->table}.min_capacity,{$this->table}.custom";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.min_capacity,{$this->table}.custom";
        $query = $this->db->table($this->table)
            ->select("{$columns},type_name")
            ->join('package_type', 'package.type_id = package_type.id')
            ->select('package_type.type_name')
            ->where('package.custom', '0')
            ->where('package.status', '1')
            ->orderBy('package.custom', 'ASC')
            ->orderBy('package.id', 'DESC')
            ->get();
        return $query;
    }


    public function get_list_mypackage_explore($user_id)
    {
        $coords = "ST_Y(ST_Centroid(homestay.geom)) AS lat, ST_X(ST_Centroid(homestay.geom)) AS lng";
        $subQuery = $this->db->table('package')
            ->select("package.*, detail_reservation.*, reservation.id as reservation_id, homestay.name as homestay_name, reservation.id as reservation_id, reservation.check_in, reservation.payment_date, {$coords}")
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
            // ->orderBy('reservation.check_in', 'ASC') // Urutkan berdasarkan tanggal pembayaran
            ->orderBy('reservation.check_in', 'DESC') // Urutkan berdasarkan tanggal pembayaran
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
        $query = $this->db->table('package p')
            ->select('p.id, p.name, p.type_id, p.price, p.contact_person, p.description, p.video_url, p.min_capacity, p.custom, pt.type_name, COUNT(pd.day) AS days')
            ->join('package_type pt', 'p.type_id = pt.id')
            ->join('package_day pd', 'p.id = pd.package_id')
            ->where('p.custom <>', '1')
            ->where('p.status', '1')
            ->orderby('p.id', 'DESC')
            ->orderby('p.name', 'ASC')
            ->groupBy('p.id, p.name, p.type_id, p.price, p.contact_person, p.description, p.video_url, p.min_capacity, p.custom, pt.type_name')
            ->get();
        return $query;
    }
    

    public function get_list_package_default_mobile()
    {      
        $query = $this->db->table('package p')
            ->select('p.id, p.name, p.type_id, p.price, p.contact_person, p.description, p.video_url, p.min_capacity, p.custom, pt.type_name, COUNT(pd.day) AS days')
            ->join('package_type pt', 'p.type_id = pt.id')
            ->join('package_day pd', 'p.id = pd.package_id')
            ->where('p.custom <>', '1')
            ->where('p.status', '1')
            ->orderby('p.id', 'DESC')
            ->orderby('p.name', 'ASC')
            ->groupBy('p.id, p.name, p.type_id, p.price, p.contact_person, p.description, p.video_url, p.min_capacity, p.custom, pt.type_name')
            ->get();
        return $query;
    }

   
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
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type_id,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.video_url,{$this->table}.min_capacity,{$this->table}.custom";
        $query = $this->db->table($this->table)
            ->select("{$columns}")
            ->where('package.id', $id)
            ->join('package_type', 'package.type_id = package_type.id')           
            ->select('package_type.type_name')
            ->get();
        return $query;
    }

    public function get_package_by_name($name = null)
    {
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.video_url,{$this->table}.min_capacity,{$this->table}.custom";
        $query = $this->db->table($this->table)
            ->select("{$columns}")
            ->like("{$this->table}.name", $name)
            ->get();
        return $query;
    }

    public function get_package_by_type($type = null)
    {
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
            $count = (int)substr($lastId['id'], 1);
            $id = sprintf('P%04d', $count + 1);
        }
        return $id;
    }


    public function add_new_package($requestData = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($requestData);        
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
            return false;
        }
    }


  
}
