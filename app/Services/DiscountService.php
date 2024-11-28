<?php
namespace App\Services;

class DiscountService
{
    public function calculateDiscounts($items, $orderTotal)
    {
        $discounts = [];
        $totalDiscount = 0;
        $currentSubtotal = $orderTotal;

        $this->discountOver1000($discounts, $totalDiscount, $currentSubtotal, $orderTotal);
        $this->discountCategory2($discounts, $totalDiscount, $currentSubtotal, $items);
        $this->discountCategory1($discounts, $totalDiscount, $currentSubtotal, $items);
        return [
            'discounts' => array_map(function ($discount) {
                $discount['discountAmount'] = number_format($discount['discountAmount'], 2);
                $discount['subtotal'] = number_format($discount['subtotal'], 2);
                return $discount;
            }, $discounts),
            'totalDiscount' => number_format($totalDiscount, 2),
            'discountedTotal' => number_format($currentSubtotal, 2),
        ];
    }
    private function discountOver1000(&$discounts, &$totalDiscount, &$currentSubtotal, $orderTotal)
    {
        if ($orderTotal >= 1000) {
            $discountAmount = $orderTotal * 0.10;
            $this->addDiscount($discounts, $totalDiscount, $currentSubtotal, '10_PERCENT_OVER_1000', $discountAmount);
        }
    }

    private function discountCategory2(&$discounts, &$totalDiscount, &$currentSubtotal, $items)
    {
        foreach ($items as $item) {
            if ($item['category'] == 2 && $item['quantity'] >= 6) {
                $freeItems = floor($item['quantity'] / 6);
                $discountAmount = $freeItems * $item['unit_price'];
                $this->addDiscount($discounts, $totalDiscount, $currentSubtotal, 'BUY_5_GET_1', $discountAmount);
            }
        }
    }

    private function discountCategory1(&$discounts, &$totalDiscount, &$currentSubtotal, $items)
    {
        $category1Items = array_filter($items, function ($item) {
            return $item['category'] == 1;
        });

        if (count($category1Items) >= 2) {
            $cheapestItem = min(array_column($category1Items, 'unit_price'));
            $discountAmount = $cheapestItem * 0.20;
            $this->addDiscount($discounts, $totalDiscount, $currentSubtotal, '20_PERCENT_CHEAPEST_IN_CATEGORY_1', $discountAmount);
        }
    }

    private function addDiscount(&$discounts, &$totalDiscount, &$currentSubtotal, $reason, $amount)
    {
        $currentSubtotal -= $amount;
        $discounts[] = [
            'discountReason' => $reason,
            'discountAmount' => $amount,
            'subtotal' => $currentSubtotal,
        ];
        $totalDiscount += $amount;
    }
}
