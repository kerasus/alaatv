<?php
/**
 * Created by PhpStorm.
 * User: sohrab
 * Date: 2019-03-14
 * Time: 13:27
 */

namespace App\Traits;

use App\Bon;
use App\Order;

trait HandleOrderPayment
{
    /**
     * @param $order
     *
     * @return array
     */
    protected function handleOrderSuccessPayment(Order $order): array
    {
        $order->closeWalletPendingTransactions();
        
        $updateOrderPaymentStatusResult = $this->updateOrderPaymentStatus($order);
        
        /** Attaching user bons for this order */
        if ($updateOrderPaymentStatusResult['paymentstatus_id'] == config('constants.PAYMENT_STATUS_PAID')) {
            $givesOrderBonsToUserResult     = $this->givesOrderBonsToUser($order);
            $updateOrderPaymentStatusResult = array_merge($updateOrderPaymentStatusResult, $givesOrderBonsToUserResult);
        }
        
        return $updateOrderPaymentStatusResult;
    }
    
    /**
     * @param  \App\Order  $order
     *
     * @return array
     */
    protected function updateOrderPaymentStatus(Order $order): array
    {
        $result           = [];
        $paymentstatus_id = (int) $order->totalPaidCost() < (int) $order->totalCost() ? config('constants.PAYMENT_STATUS_INDEBTED') : config('constants.PAYMENT_STATUS_PAID');
        
        $result['paymentstatus_id'] = $paymentstatus_id;

//        uncomment if you don't close order before redirecting to gateway
        if(in_array($order->orderstatus_id , Order::OPEN_ORDER_STATUSES))
            $order->close();

        $order->paymentstatus_id = $paymentstatus_id;

        $result['saveOrder'] = $order->update() ? 1 : 0;
        
        return $result;
    }
    
    /**
     * @param  \App\Order  $order
     *
     * @return array
     */
    private function givesOrderBonsToUser(Order $order): array
    {
        $result  = [];
        $bonName = config('constants.BON1');
        $bon     = Bon::ofName($bonName)
            ->first();
        
        if (!isset($bon)) {
            return $result;
        }
        
        list($givenBonNumber, $failedBonNumber) = $order->giveUserBons($bonName);
        
        $result['saveBon'] = $givenBonNumber;
        
        if ($givenBonNumber == 0) {
            $result['saveBon'] = $failedBonNumber > 0 ? -1 : 0;
        }
        
        $result['bonName'] = $bon->displayName;
        
        return $result;
    }
}
