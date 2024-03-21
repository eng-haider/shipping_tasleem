<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\Customer\Create;
use App\Http\Requests\Customer\Update;
use App\Http\Requests\Index\Pagination;
use App\Http\Repositories\CustomerRepository;

class CustomerController extends Controller
{
    public function __construct(private CustomerRepository $CustomerRepo)
    {
        $this->middleware(['permissions:get-customer'])->only(['index']);
        $this->middleware(['permissions:store-customer'])->only(['store']);
        $this->middleware(['permissions:show-customer'])->only(['show']);
        $this->middleware(['permissions:update-customer'])->only(['update']);
        $this->middleware(['permissions:delete-customer'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pagination $request)
    {
        $request->validated();
        return $this->CustomerRepo->getList($request->take);
        
    }

    /**
     * Display the Customer.
     *
     * @param  \App\Models\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->CustomerRepo->show($id);
        return Helper::responseSuccess('Get customer successfully', $response);
    }

    /**
     * Update the Customer in storage.
     *
     * @param  \Illuminate\Customer\Update  $request
     * @param  \App\Models\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function store(Create $request)
    {
        $customer = $request->validated();
        $response = $this->CustomerRepo->create($customer);
        return Helper::responseSuccess('Update customer successfully', $response);
    }

    /**
     * Update the Customer in storage.
     *
     * @param  \Illuminate\Customer\Update  $request
     * @param  \App\Models\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $id)
    {
        $customer = $request->validated();
        $response = $this->CustomerRepo->update($id, $customer);
        return Helper::responseSuccess('Update customer successfully', $response);
    }
}
