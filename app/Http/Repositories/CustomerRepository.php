<?php
namespace App\Http\Repositories;

use App\Models\Customer;

class CustomerRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new Customer());
    }
}