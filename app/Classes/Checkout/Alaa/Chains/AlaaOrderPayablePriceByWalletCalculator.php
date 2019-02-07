<?php
/**
 * Created by PhpStorm.
 * User: mohamamad
 * Date: 12/20/2018
 * Time: 12:31 PM
 */

namespace App\Classes\Checkout\Alaa\Chains;

use App\Classes\Abstracts\Checkout\OrderPayablePriceByWalletCalculator;
use App\Order;
use App\Product;
use App\User;

class AlaaOrderPayablePriceByWalletCalculator extends OrderPayablePriceByWalletCalculator
{
    protected function calculateAmountPaidByWallet(Order $order, $finalPrice)
    {
        $user = $order->user;
        $donateOrderProducts = $order->normalOrderproducts;


        $donateCost = 0;
        if ($donateOrderProducts->isNotEmpty()) {
            $donateCost = $donateOrderProducts->sum("cost");
        }

        $credit = optional($user)->getTotalWalletBalance();
        $costWithWallet = $finalPrice - $donateCost;
        $payableByWallet = min($costWithWallet, $credit);
        $priceToPay = max($finalPrice - $payableByWallet, 0);

        return [
          "payableAmountByWallet" => $payableByWallet ,
          "priceToPay" => $priceToPay
        ];
    }
}