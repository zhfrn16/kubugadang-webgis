<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageReviewModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'package_review';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'package_id', 'rating', 'review_text', 'created_at', 'updated_at', 'is_approved'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    // No deletedField

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    // Get all reviews for a package
    public function getReviewsByPackage($package_id)
    {
        return $this->where('package_id', $package_id)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    // Get a review by user for a package
    public function getReviewByUser($package_id, $user_id)
    {
        return $this->where([
                'package_id' => $package_id,
                'user_id' => $user_id
            ])
            ->first();
    }

    // Create a new review
    public function createReview($data)
    {
        return $this->insert($data);
    }

    // Update a review
    public function update_review($id = null, $review = null)
    {
        foreach ($review as $key => $value) {
            if (empty($value)) {
                unset($review[$key]);
            }
        }
        $query = $this->db->table($this->table)
            ->where('id', $id)
            ->update($review);
        return $query;
    }

    // Approve or unapprove a review
    public function approveReview($id, $is_approved = true)
    {
        return $this->update($id, ['is_approved' => $is_approved]);
    }

    // Delete a review
    public function deleteReview($id)
    {
        return $this->delete($id);
    }
}
