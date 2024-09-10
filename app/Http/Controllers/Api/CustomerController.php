<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Requests\UpdateCustomerRequest;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user_id = $request->headers->get('id');
            $customers = Customer::where('user_id', $user_id)->get();
            return $this->sendSuccess("Customer list", $customers);
        } catch (Exception $e) {
            return $this->sendError("Failed to get Customer list", 200, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $user_id = $request->headers->get('id');
            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'user_id' => $user_id,
            ]);
            return $this->sendSuccess("Customer Created", $customer, 201);
        } catch (Exception $e) {
            return $this->sendError("Failed to Create Customer", 200, $e->getMessage());
        }
    }

    public function show($customer, Request $request)
    {
        try {
            $user_id = $request->headers->get('id');
            $customer = Customer::where('user_id', $user_id)->findOrFail($customer);
            return $this->sendSuccess("Customer Details", $customer);
        } catch (Exception $e) {
            return $this->sendError("Failed to get Customer Details", 200, $e->getMessage());
        }
    }

    public function update(Request $request, $customer)
    {
        try {
            $user_id = $request->headers->get('id');
            $customer = Customer::where('user_id', $user_id)->findOrFail($customer)->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'user_id' => $user_id,
            ]);
            return $this->sendSuccess("Customer Updated", $customer);
        } catch (Exception $e) {
            return $this->sendError("Failed to Update Customer", 200, $e->getMessage());
        }
    }

    public function destroy(Request $request, $customer)
    {
        try {
            $user_id = $request->headers->get('id');
            $customer = Customer::where('user_id', $user_id)->findOrFail($customer);
            $customer->delete();
            return $this->sendSuccess("Customer Deleted", []);
        } catch (Exception $e) {
            return $this->sendError("Failed to Delete Customer", 200, $e->getMessage());
        }
    }
}