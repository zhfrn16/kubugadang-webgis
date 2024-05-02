<?php

namespace App\Models;

use CodeIgniter\I18n\Time;
use CodeIgniter\Model;

class CartModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'cart';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'user_id', 'package_id', 'status', 'created_at', 'updated_at'];

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

    public function get_list_cart()
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->orderBy('id', 'ASC')
            ->get();

        return $query;
    }

    public function get_cart_by_id($id = null)
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('id', $id)
            ->get();
        return $query;
    }

    public function get_list_cart_by_user_id_api($user_id = null)
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('user_id', $user_id)
            ->where('status', '1')
            ->get();
        return $query;
    }


    public function get_list_cart_by_user_id($user = null)
    {
        $query = $this->db->table($this->table)
            // ->select("*, package.name as package_name, gallery_package.url as url")
            ->select("*, package.name as package_name, package_id as package_id")
            ->join('package', 'cart.package_id = package.id')
            // ->join('gallery_package', 'gallery_package.package_id = package.id')
            ->join('users', 'cart.user_id = users.id')
            // ->where('users.username', $user)
            ->where('users.id', $user)
            ->orderBy('cart.created_at', 'DESC')
            ->where('cart.status', '1')
            ->get();
        return $query;
    }

    public function get_total_cart_by_user_id($user = null)
    {
        $query = $this->db->table($this->table)
            ->select("count(cart.package_id) as total_cart")
            ->join('users', 'cart.user_id = users.id')
            ->where('users.username', $user)
            ->where('cart.status', '1')
            ->get();
        return $query;
    }


    public function get_new_id()
    {
        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        if (empty($lastId)) {
            $id = 'C0001';
        } else {
            $count = (int)substr($lastId['id'], 1);
            $id = sprintf('C0%03d', $count + 1);
        }
        return $id;
    }

    public function checkIfDataExists($requestData)
    {
        return $this->table($this->table)
            ->where('user_id', $requestData['user_id'])
            ->where('package_id', $requestData['package_id'])
            ->where('status', $requestData['status'])
            ->get()
            ->getRow();
    }

    public function add_new_cart($cart = null)
    {
        $cart['created_at'] = Time::now();
        $cart['updated_at'] = Time::now();
        $insert = $this->db->table($this->table)
            ->insert($cart);
        return $insert;
    }

    // public function add_new_cart($requestData)
    // {
    //     $query = false;
    //     $content = [
    //         'user_id' => $requestData['user_id'],
    //         'package_id' => $requestData['package_id'],
    //         'status' => $requestData['status']
    //     ];

    //     $insert = $this->db->table($this->table)
    //         ->insert($content);
    //     return $insert;
    // }

    // public function update_cart($user_id = null, $package_id = null, $cart = null)
    // {
    //     foreach ($cart as $key => $value) {
    //         if (empty($value)) {
    //             unset($cart[$key]);
    //         }
    //     }
    //     $query = $this->db->table($this->table)
    //         // ->where('id', $id)
    //         ->where('user_id', $user_id)
    //         ->where('package_id', $package_id)
    //         ->where('status', '1')
    //         ->update($cart);
    //     return $query;
    // }

    public function updateCartStatus($user_id = null, $package_id = null)
    {
        $updated_at = Time::now();
        $updateData = [
            'status' => 0,
            'updated_at' => $updated_at
        ];

        $query = $this->db->table($this->table)
            ->where('user_id', $user_id)
            ->where('package_id', $package_id)
            ->where('status', '1')
            ->update($updateData);

        return $query;
    }
}
