<?php

namespace  App\PaymentModule\Controllers;

use App\PaymentModule\{OnlineGateWay, Responses, PaymentDriver};
use App\Repositories\TransactionRepo;
use App\Traits\HandleOrderPayment;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\{Cache, Request};

class PaymentVerifierController extends Controller
{
    use HandleOrderPayment;
    
    /**
     * @param  string  $paymentMethod
     * @param  string  $device
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(string $paymentMethod, string $device)
    {
        PaymentDriver::select($paymentMethod);
        $authority = OnlineGateWay::getAuthorityValue();

        $transaction = TransactionRepo::getTransactionByAuthority($authority)
            ->orFailWith([Responses::class, 'transactionNotFoundError']);

        /**
         * @var OnlinePaymentVerificationResponseInterface $verificationResult
         */
        $verificationResult = OnlineGateWay::verifyPayment(abs($transaction->cost), $authority);
        
        $transaction->order->detachUnusedCoupon();
        if ($verificationResult->isSuccessfulPayment()) {
            TransactionRepo::handleTransactionStatus(
                $transaction,
                $verificationResult->getRefId(),
                $verificationResult->getCardPanMask()
            );
            $this->handleOrderSuccessPayment($transaction->order);
        } else {
            $this->handleOrderCanceledPayment($transaction->order);
            $transaction->transactionstatus_id = config('constants.TRANSACTION_STATUS_UNSUCCESSFUL');
            $transaction->update();
        }
        /*
        if (isset($transaction->order_id)) {} else { if (isset($transaction->wallet_id)) { if ($result['status']) { $this->handleWalletChargingSuccessPayment($gatewayVerify['RefID'], $transaction, $gatewayVerify['cardPanMask']); } else { $this->handleWalletChargingCanceledPayment($transaction); } } } */
        
        Cache::tags('bon')->flush();
        
        Request::session()->flash('verifyResult', $verificationResult->getMessages());
        
        return redirect()->route('showOnlinePaymentStatus', [
            'status'        => ($verificationResult->isSuccessfulPayment()) ? 'successful' : 'failed',
            'paymentMethod' => $paymentMethod,
            'device'        => $device,
        ]);
    }
    
    /**
     * @param  \App\Order  $order
     *
     * @return array
     */
    private function handleOrderCanceledPayment(Order $order)
    {
        if ($order->orderstatus_id == config("constants.ORDER_STATUS_OPEN")) {
            $order->close(config('constants.PAYMENT_STATUS_UNPAID'), config('constants.ORDER_STATUS_CANCELED'));
            $order->updateWithoutTimestamp();
        }
        $order->refundWalletTransaction();
    }
    
    /*
     * private function handleWalletChargingCanceledPayment(Transaction $transaction)
    {
        $transaction->transactionstatus_id = config('constants.TRANSACTION_STATUS_UNSUCCESSFUL');
        $transaction->update();
    }

    private function handleWalletChargingSuccessPayment(string $refId, Transaction $transaction, string $cardPanMask = null)
    {
        $bankAccountId = null;
        if (isset($cardPanMask)) {
            $bankAccount = Bankaccount::firstOrCreate(['accountNumber' => $cardPanMask]);
            $bankAccountId = $bankAccount->id;
        }
        $this->changeTransactionStatusToSuccessful($refId, $transaction, $bankAccountId);
        $transaction->wallet->deposit($transaction->cost * (-1), true);
    }*/
}