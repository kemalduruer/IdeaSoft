<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{

    public function index()
    {
        try {
            $customers = Product::all();

            if ($customers->isEmpty()) {
                return response()->json([
                    'message' => 'No product found',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'message' => 'Products retrieved successfully',
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

            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'No product found',
                    'data' => [],
                ], 404); // 404: Not Found
            }

            return response()->json([
                'message' => 'Product retrieved successfully',
                'data' => $product,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
