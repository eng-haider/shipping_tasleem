<?php
namespace App\Http\Repositories;

use App\Models\Governorate;

class GovernorateRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new Governorate());
    }
    public function CreateGovernorateToCompany($data)
    {
        $governorate = $this->model->find($data['governorate_id']);
        $governorate->company()->attach([
            'company_id' => $data['company_id'],
        ]);
        return $governorate;
    }
}