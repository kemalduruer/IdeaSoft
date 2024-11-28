<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        try {
            $customers = Customer::all();
            if ($customers->isEmpty()) {
                return response()->json([
                    'message' => 'No customers found',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'message' => 'Customers retrieved successfully',
                'data' => $customers,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json([
                    'message' => 'ID needs to be numeric',
                    'errors' => 'The provided ID must be a numeric value'
                ], 400);
            }
            $customer = Customer::find($id);

            if (!$customer) {
                return response()->json([
                    'message' => 'Customer not found',
                    'errors' => "No customer found with ID $id.",
                ], 404);
            }

            return response()->json([
                'message' => 'Customer found',
                'data' => $customer,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ]);
        }
    }

}
