<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $orders = Order::with('customer', 'items.product')->get();
            if ($orders->isEmpty()) {
                return response()->json([
                    'message' => 'No orders found in the database.',
                    'data' => [],
                ], 404);
            }
            return response()->json([
                'message' => 'Orders retrieved successfully',
                'data' => $orders
            ],200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ],422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred.',
                'error_details' => $e->getMessage(),
            ], 500);
        }
    }

    public function add(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'items' => 'required|array',
                'items.*.productId' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            $orderTotal = 0;
            $itemsData = [];

            foreach ($validatedData['items'] as $item) {
                $product = Product::find($item['productId']);

                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'message' => "Insufficient stock for product: {$product->name}",
                    ], 409);
                }

                $itemTotal = $product->price * $item['quantity'];
                $orderTotal += $itemTotal;

                $product->stock -= $item['quantity'];
                $product->save();

                $itemsData[] = [
                    'productId' => $product->id,
                    'quantity' => $item['quantity'],
                    'unitPrice' => $product->price,
                    'total' => $itemTotal
                ];
            }

            $order = Order::create([
                'customerId' => $validatedData['customer_id'],
                'total' => $orderTotal,
            ]);

            foreach ($itemsData as $itemData) {
                $order->items()->create($itemData);
            }

            return response()->json([
                'message' => 'Order created successfully',
                'data' => $order->load('items.product')
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ],422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred.',
                'error_details' => $e->getMessage(),
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
                ], 422);
            }
            $order = Order::find($id);

            if (!$order) {
                return response()->json([
                    'message ' => 'Order not found',
                ], 404);
            }

            return response()->json([
                'message ' => 'Order retrieved successfully',
                'data' => $order->load('items.product')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred.',
                'error_details' => $e->getMessage(),
            ], 500);
        }

    }

    public function delete($id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json([
                    'message' => 'ID needs to be numeric',
                    'errors' => 'The provided ID must be a numeric value'
                ], 422);
            }

            $order = Order::find($id);

            if (!$order) {
                return response()->json([
                    'message' => 'Order not found',
                ], 400);
            }

            $order->items()->delete();

            $order->delete();

            return response()->json([
                'message' => 'Order deleted successfully',

            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred.',
                'error_details' => $e->getMessage(),
            ], 500);
        }
    }
}
