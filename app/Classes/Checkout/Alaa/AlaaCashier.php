<?php
/**
 * Created by PhpStorm.
 * User: mohamamad
 * Date: 10/11/2018
 * Time: 2:51 PM
 */

namespace App\Classes\Checkout\Alaa;

use App\Classes\Abstracts\Cashier;

class AlaaCashier Extends Cashier
{
    public function getPrice()
    {
        $priceInfo = [
            "order"=> $this->order ,
            "totalPriceInfo" => [
                    "totalRawPriceWhichHasDiscount"      => $this->totalRawPriceWhichHasDiscount,
                    "totalRawPriceWhichDoesntHaveDiscount"      => $this->totalRawPriceWhichDoesntHaveDiscount ,
                    "totalPriceWithDiscount"      => $this->totalPriceWithDiscount ,
                    "finalPrice"      =>  $this->finalPrice ,
            ],
            "orderproductsInfo" => [
                "rawOrderproductsToCalculateFromBase" => $this->rawOrderproductsToCalculateFromBase ,
                "rawOrderproductsToCalculateFromRecord" => $this->rawOrderproductsToCalculateFromRecord ,
                "calculatedOrderproducts" => $this->calculatedOrderproducts,
            ],
        ];
        return json_encode($priceInfo);
    }
}
