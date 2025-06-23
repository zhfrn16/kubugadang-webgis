<?php

namespace App\Models;

use CodeIgniter\Model;

class HomestayModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'homestay';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = [
        'id', 'name', 'address', 'contact_person', 'open', 'close', 'description', 'status', 'homestay_status', 'video_url', 'geom', 'price'
    ];

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

    public function get_list_homestay()
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.address,{$this->table}.contact_person,{$this->table}.open,{$this->table}.close,{$this->table}.video_url,{$this->table}.homestay_status,{$this->table}.description,{$this->table}.price";
        $query = $this->db->table($this->table)
        ->select("{$columns}, {$coords}")
        ->get();

        return $query;
    }

    public function get_list_homestay_homestay()
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.address,{$this->table}.contact_person,{$this->table}.open,{$this->table}.close,{$this->table}.video_url,{$this->table}.homestay_status,{$this->table}.description,{$this->table}.price";
        $query = $this->db->table($this->table)
        ->select("{$columns}, {$coords}")
        ->where('homestay_status', '1')
        ->get();

        return $query;
    }

    public function get_list_homestay_recommendation()
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('homestay_id', 'HO001')
            ->get();

        return $query;
    }

    public function get_count_homestay()
    {
        $query = $this->db->table($this->table)
            ->selectCount("id")
            ->get();
        return $query;
    }

    public function get_object($id=null)
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $query = $this->db->table($this->table)
            ->select("id, name, {$coords}")
            ->where('id', $id)
            ->get();
        return $query;
    }

   

    public function get_geoJson($id = null)
    {
        $geoJson = "ST_AsGeoJSON({$this->table}.geom) AS geoJson";
        $query = $this->db->table($this->table)
            ->select("{$geoJson}")
            ->where('id', $id)
            ->get();
        return $query;
    }

    // public function get_homestay_by_id($id = null)
    // {
    //     $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
    //     $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.address,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description";
    //     $query = $this->db->table($this->table)
    //         ->select("{$columns}, {$coords}")
    //         ->where('id', $id)
    //         ->get();

    //     return $query;
    // }


    public function get_homestay_by_id($id = null)
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.address,{$this->table}.contact_person,{$this->table}.open,{$this->table}.close,{$this->table}.video_url,{$this->table}.homestay_status,{$this->table}.description,{$this->table}.price";
        $geoJson = "ST_AsGeoJSON({$this->table}.geom) AS geoJson";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}, {$geoJson}")
            ->where('id', $id)
            ->get();
        return $query;
    }

    public function get_homestay_by_id_simple($id = null)
    {
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.address,{$this->table}.contact_person,{$this->table}.open,{$this->table}.close,{$this->table}.video_url,{$this->table}.homestay_status";
        $query = $this->db->table($this->table)
            ->select("{$columns}")
            ->where('id', $id)
            ->get();
        return $query;
    }

    public function get_homestay_by_id_custom($id = null)
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.address,{$this->table}.contact_person,{$this->table}.open,{$this->table}.close,{$this->table}.video_url,{$this->table}.homestay_status,{$this->table}.description,{$this->table}.price";
        // $geoJson = "ST_AsGeoJSON({$this->table}.geom) AS geoJson";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}, unit_homestay.price")
            ->join('unit_homestay', 'homestay.id = unit_homestay.homestay_id')
            ->where('homestay.id', $id)
            ->groupBy("unit_homestay.price, {$this->table}.id,{$this->table}.name,{$this->table}.address,{$this->table}.contact_person,{$this->table}.open,{$this->table}.close,{$this->table}.video_url,{$this->table}.homestay_status,{$this->table}.description,{$this->table}.price")
            ->get();
        return $query;
    }
 

    public function get_homestay_by_radius($data = null)
    {
        $radius = (int)$data['radius'] / 1000;
        $lat = $data['lat'];
        $long = $data['long'];
        $distance = "(6371 * acos(cos(radians({$lat})) * cos(radians(ST_Y(ST_CENTROID({$this->table}.geom)))) 
                    * cos(radians(ST_X(ST_CENTROID({$this->table}.geom))) - radians({$long})) 
                    + sin(radians({$lat}))* sin(radians(ST_Y(ST_CENTROID({$this->table}.geom))))))";
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.address,{$this->table}.contact_person,{$this->table}.open,{$this->table}.close,{$this->table}.video_url,{$this->table}.homestay_status,{$this->table}.description,{$this->table}.price";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}, {$distance} as distance")
            ->having(['distance <=' => $radius])
            ->get();
        return $query;
    }


    public function get_new_id()
    {
        
        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        if(empty($lastId)){
            $id='HO001';
        }else{
        $count = (int)substr($lastId['id'], 3);
        $id = sprintf('HO%03d', $count + 1);
        }
        return $id;
    }

    public function add_new_homestay($homestay = null, $geom = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($homestay);
        $update = $this->db->table($this->table)
            ->set('geom', "ST_GeomFromText('{$geom}')", false)
            ->where('id', $homestay['id'])
            ->update();
        return $insert && $update;
    }

    public function update_homestay($id = null, $homestay = null)
    {
        foreach ($homestay as $key => $value) {
            if (empty($value)) {
                unset($homestay[$key]);
            }
        }
        $query = $this->db->table($this->table)
            ->where('id', $id)
            ->update($homestay);
        return $query;
    }

    public function update_geom($id = null, $geom = null)
    {
        $query = $this->db->table($this->table)
            ->set('geom', "ST_GeomFromText('{$geom}')", false)
            ->where('id', $id)
            ->update();
        return $query;
    }

    public function get_list_hm_apii() {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.address,{$this->table}.contact_person,{$this->table}.open,{$this->table}.close,{$this->table}.video_url,{$this->table}.homestay_status,{$this->table}.description,{$this->table}.price";
        $vilGeom = "village.id = '1' AND ST_Contains(village.geom, {$this->table}.geom)";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->from('village')
            ->where($vilGeom)
            ->get();
        return $query;
    }

    public function get_list_hm_api() {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.address,{$this->table}.contact_person,{$this->table}.open,{$this->table}.close,{$this->table}.video_url,{$this->table}.homestay_status,{$this->table}.description,{$this->table}.price";
        // $vilGeom = "village.id = '1' AND ST_Contains(village.geom, {$this->table}.geom)";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->from('village')
            // ->where($vilGeom)
            ->distinct()
            ->get();
        return $query;
    }

}
