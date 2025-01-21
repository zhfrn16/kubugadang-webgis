<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageTypeModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'package_type';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'type_name'];

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

    public function get_list_package_type()
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->orderBy('id', 'ASC')
            ->get();

        return $query;
    }

    public function get_package_type_by_id($id = null)
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('id', $id)
            ->get();
        return $query;
    }

    
    public function get_new_id()
    {
        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        if(empty($lastId)){
            $id='T0001';
        }else{
        $count = (int)substr($lastId['id'], 1);
        $id = sprintf('T0%03d', $count + 1);
        }
        return $id;
    }

    public function add_new_package_type($package_type = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($package_type);
        return $insert;
    }

    public function update_package_type($id = null, $package_type = null)
    {
        foreach ($package_type as $key => $value) {
            if (empty($value)) {
                unset($package_type[$key]);
            }
        }
        $query = $this->db->table($this->table)
            ->where('id', $id)
            ->update($package_type);
        return $query;
    }
}