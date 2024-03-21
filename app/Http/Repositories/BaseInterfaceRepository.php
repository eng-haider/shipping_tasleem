<?php
namespace App\Http\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;

interface BaseInterfaceRepository
{
    public function getList($take = 10) : LengthAwarePaginator;
    public function show($id);
    public function check($id);
    public function create($data);
    public function insert($data);
    public function update($id, $values);
    public function delete($id);
 }