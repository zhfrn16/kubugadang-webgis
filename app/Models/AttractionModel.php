<?php

namespace App\Models;

use CodeIgniter\Model;

class AttractionModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'attraction';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'name', 'type', 'price', 'description', 'video_url', 'geom', 'geom_area'];

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

   

    public function get_list_attraction()
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type,{$this->table}.price,{$this->table}.category,{$this->table}.min_capacity,{$this->table}.description,{$this->table}.video_url";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->get();
        return $query;
    }

    public function get_list_attraction_without_lsa()
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type,{$this->table}.price,{$this->table}.category,{$this->table}.min_capacity,{$this->table}.description,{$this->table}.video_url";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->where('type !=', 'Lake')
            ->get();
        return $query;
    }

    public function get_list_attractionLSA()
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type,{$this->table}.price,{$this->table}.category,{$this->table}.min_capacity,{$this->table}.description,{$this->table}.video_url";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->where('type', 'Lake')
            ->get();
        return $query;
    }
    public function get_list_attractionNT()
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type,{$this->table}.price,{$this->table}.category,{$this->table}.min_capacity,{$this->table}.description,{$this->table}.video_url";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->where('type', 'Nature')
            ->get();
        return $query;
    }
    public function get_list_attractionCT()
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type,{$this->table}.price,{$this->table}.category,{$this->table}.min_capacity,{$this->table}.description,{$this->table}.video_url";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->where('type', 'Culture')
            ->get();
        return $query;
    }
    public function get_list_attractionET()
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type,{$this->table}.price,{$this->table}.category,{$this->table}.min_capacity,{$this->table}.description,{$this->table}.video_url";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->where('type', 'Education')
            ->get();
        return $query;
    }

    public function get_list_attractionSLA()
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type,{$this->table}.price,{$this->table}.category,{$this->table}.min_capacity,{$this->table}.description,{$this->table}.video_url";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->where('type', 'Lake')
            ->get();
        return $query;
    }

    public function get_lsa_by_radius($data = null)
    {
        $radius = (int)$data['radius'] / 1000;
        $lat = $data['lat'];
        $long = $data['long'];
        $distance = "(6371 * acos(cos(radians({$lat})) * cos(radians(ST_Y(ST_CENTROID({$this->table}.geom)))) 
                    * cos(radians(ST_X(ST_CENTROID({$this->table}.geom))) - radians({$long})) 
                    + sin(radians({$lat}))* sin(radians(ST_Y(ST_CENTROID({$this->table}.geom))))))";
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type,{$this->table}.price,{$this->table}.category,{$this->table}.min_capacity,{$this->table}.description,{$this->table}.video_url";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}, {$distance} as distance")
            ->where('type', 'Lake')
            ->having(['distance <=' => $radius])
            ->get();
        return $query;
    }

    public function get_attraction_by_id($id = null)
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type,{$this->table}.price,{$this->table}.description,{$this->table}.video_url";
        $geoJson = "ST_AsGeoJSON({$this->table}.geom) AS geoJson";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}, {$geoJson}")
            ->where('id', $id)
            ->get();
        return $query;
    }

    public function get_geoJson($id = null)
    {
        $geoJson = "ST_AsGeoJSON({$this->table}.geom_area) AS geoJson";
        $query = $this->db->table($this->table)
            ->select("{$geoJson}")
            ->where('id', $id)
            ->get();
        return $query;
    }

    public function get_attraction2_by_id($id = null)
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom_area)) AS lat, ST_X(ST_Centroid({$this->table}.geom_area)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type,{$this->table}.price,{$this->table}.description,{$this->table}.video_url";
        $geoJson = "ST_AsGeoJSON({$this->table}.geom_area) AS geoJson";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}, {$geoJson}")
            ->where('id', $id)
            ->get();
        return $query;
    }

    public function get_new_id()
    {
        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        if(empty($lastId)){
            $id='AT001';
        }else{
        $count = (int)substr($lastId['id'], 3);
        $id = sprintf('AT%03d', $count + 1);
        }
        return $id;
    }

    public function add_new_attraction($attraction = null, $geom = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($attraction);
        $update = $this->db->table($this->table)
            ->set('geom', "ST_GeomFromText('{$geom}')", false)
            ->where('id', $attraction['id'])
            ->update();
        return $insert && $update;
    }

    public function update_attraction($id = null, $attraction = null)
    {
        foreach ($attraction as $key => $value) {
            if (empty($value)) {
                unset($attraction[$key]);
            }
        }
        $query = $this->db->table($this->table)
            ->where('id', $id)
            ->update($attraction);
        return $query;
    }

    public function update_geom($id = null, $geom = null)
    {
        $query = $this->db->table($this->table)
            ->set('geom_area', "ST_GeomFromText('{$geom}')", false)
            ->where('id', $id)
            ->update();
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


    function get_attraction(){

        $object="<option value='0'>--pilih--</pilih>";
        
        $this->db->order_by('name','ASC');
        $ob= $this->db->get();
        
        foreach ($ob->result_array() as $data ){
        $object.= "<option value='$data[id]'>$data[name]</option>";
        }
        
        return $object;
        
    }
    
    public function get_list_attraction_api() {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type,{$this->table}.price,{$this->table}.description,{$this->table}.video_url";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->get();
        return $query;
    }

        public function get_silek()
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type,{$this->table}.price,{$this->table}.description,{$this->table}.video_url,{$this->table}.category";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->where('id', 'AT004')
            ->get();
        return $query;
    }
}
