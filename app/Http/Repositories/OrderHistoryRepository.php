<?php
namespace App\Http\Repositories;

use App\Models\OrderHistory;

class OrderHistoryRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new OrderHistory());
    }
}