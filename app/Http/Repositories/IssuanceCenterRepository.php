<?php
namespace App\Http\Repositories;

use App\Models\IssuanceCenter;

class IssuanceCenterRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new IssuanceCenter());
    }
}