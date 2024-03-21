<?php
namespace App\Http\Repositories;

use App\Helper\Helper;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use App\Services\CDCIntegrateService;

class CompanyRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new Company());
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            $governorates = $data['governorates'];
            unset($data['governorates']);
            $company_cdc_id = $this->getCompanyId($data['name']);
            if(!$company_cdc_id){
                return Helper::responseError('Company not found in CDC', [], 400);
            }
            $data['company_cdc_id'] = $company_cdc_id;
            $company = $this->model->create($data);
            foreach ($governorates as $governorate) {
                DB::table('company_governorates')->insert([
                    'company_id' => $company->id,
                    'governorate_id' => $governorate
                ]);
            }
            DB::commit();
            return Helper::responseSuccess('Create company cuccessfully', $company);
        } catch (\Exception $e) {
            DB::rollBack();
            return Helper::responseError($e->getMessage(), [], 400);
        }
    }
    public function getCompanyId($name)
    {
        $service = new CDCIntegrateService();
        $companies = $service->getCompaniesFromCDC();
        foreach ($companies as $company) {
            if ($company['title'] == $name) {
                return $company['id'];
            }
        }
    }
}