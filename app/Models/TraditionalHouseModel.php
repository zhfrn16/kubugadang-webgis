<?php

namespace App\Models;

use CodeIgniter\Model;

class TraditionalHouseModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'traditional_house';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = [
        'id', 'name', 'address', 'ticket_price', 'contact_person', 'open', 'close', 'description', 'status', 'geom', 'video_url'
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

    public function get_list_th()
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.address,{$this->table}.contact_person,{$this->table}.open,{$this->table}.close,{$this->table}.ticket_price,{$this->table}.category,{$this->table}.description,{$this->table}.status";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->get();
        return $query;
    }

    public function get_object_package()
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->get();
        return $query;
    }

    public function get_new_id()
    {
        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        if (empty($lastId)) {
            $id = 'TH001';
        } else {
            $count = (int)substr($lastId['id'], 3);
            $id = sprintf('TH%03d', $count + 1);
        }
        return $id;
    }

    public function add_new_th($traditionalhouse = null, $geom = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($traditionalhouse);
        $update = $this->db->table($this->table)
            ->set('geom', "ST_GeomFromText('{$geom}')", false)
            ->where('id', $traditionalhouse['id'])
            ->update();
        return $insert && $update;
    }

    public function get_object($id = null)
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

    public function update_th($id = null, $traditionalhouse = null)
    {
        foreach ($traditionalhouse as $key => $value) {
            if (empty($value)) {
                unset($traditionalhouse[$key]);
            }
        }
        $query = $this->db->table($this->table)
            ->where('id', $id)
            ->update($traditionalhouse);
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

    public function get_th_by_id($id = null)
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.address,{$this->table}.contact_person,{$this->table}.open,{$this->table}.close,{$this->table}.ticket_price,{$this->table}.description,{$this->table}.status";
        $geoJson = "ST_AsGeoJSON({$this->table}.geom) AS geoJson";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}, {$geoJson}")
            ->where('id', $id)
            ->get();
        return $query;
    }


    public function get_th_by_radius($data = null)
    {
        $radius = (int)$data['radius'] / 1000;
        $lat = $data['lat'];
        $long = $data['long'];
        $distance = "(6371 * acos(cos(radians({$lat})) * cos(radians(ST_Y(ST_CENTROID({$this->table}.geom)))) 
                    * cos(radians(ST_X(ST_CENTROID({$this->table}.geom))) - radians({$long})) 
                    + sin(radians({$lat}))* sin(radians(ST_Y(ST_CENTROID({$this->table}.geom))))))";
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.address,{$this->table}.contact_person,{$this->table}.open,{$this->table}.close,{$this->table}.ticket_price,{$this->table}.description,{$this->table}.status";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}, {$distance} as distance")
            ->having(['distance <=' => $radius])
            ->get();
        return $query;
    }
}
