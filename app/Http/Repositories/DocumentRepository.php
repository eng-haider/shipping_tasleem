<?php
namespace App\Http\Repositories;

use App\Models\Document;

class DocumentRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new Document());
    }
}