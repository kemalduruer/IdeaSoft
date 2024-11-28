<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\DiscountService;

class DiscountController extends Controller
{
    public function calculate(Request $request)
    {
        try {
            $validated = $request->validate([
                'orderId' => 'required|exists:orders,id',
            ]);

            $order = Order::with('items.product')->find($validated['orderId']);

            $items = $order->items->map(function ($item) {
                return [
                    'product_id' => $item->productId,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unitPrice,
                    'category' => $item->product->category,
                ];
            })->toArray();

            $discountService = new DiscountService();
            $discounts = $discountService->calculateDiscounts($items, $order->total);

            return response()->json([
                'orderId' => $order->id,
                'discounts' => $discounts['discounts'],
                'totalDiscount' => $discounts['totalDiscount'],
                'discountedTotal' => $discounts['discountedTotal'],
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
}
