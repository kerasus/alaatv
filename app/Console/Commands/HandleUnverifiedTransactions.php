<?php

namespace App\Console\Commands;

use App\Transaction;
use AlaaTV\Gateways\Money;
use App\Transactiongateway;
use App\Http\Requests\Request;
use Illuminate\Console\Command;
use AlaaTV\Gateways\PaymentDriver;

class HandleUnverifiedTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alaaTv:unverifiedTransactions:handle';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Confirm Unverified Transactions';
    
    /**
     * @var Request $request
     */
    private $request;
    
    /**
     * Create a new command instance.
     *
     * @param  Request  $request
     */
    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //ToDo : At this time this only works for Zarinpal
        $paymentMethod = 'zarinpal';
        
        $transactiongateway = Transactiongateway::where('name', $paymentMethod)
            ->first();
        $data['merchantID'] = $transactiongateway->merchantNumber;
        $paymentClient = PaymentDriver::select($paymentMethod);

        $notExistTransactions             = [];
        $unverifiedTransactionsDueToError = [];
        
        $this->info('getting data from zarinpal ...');
        $result = $this->getUnverifiedTransactions();

        if ($result['Status'] != 'success') {
            $this->info('There is a problem with receiving unverified transactions with Status: '.$result['Status']);
            return null;
        }

        $this->info('Untrusted transactions received.');
        $transactions = $result['Authorities'];
        foreach ($transactions as $transaction) {

            /*$result = [
                'sendSMS' => false,
                'Status' => 'error'
            ];*/
            $this->request->offsetSet('Authority', $transaction['Authority']);
            $this->request->offsetSet('Status', 'OK');
            /*$data = [
                'request' => $this->request,
                'result' => $result
            ];*/
            $authority = $transaction['Authority'];
            $this->info($authority);

            $transaction = Transaction::authority($authority)->first();

            if (is_null($transaction)) {
                array_push($notExistTransactions, $transaction);
            } else {
                $transaction['Status'] = 'OK';
                array_push($unverifiedTransactionsDueToError, $transaction);
                $gateWayVerify = $paymentClient->verifyPayment(Money::fromTomans($transaction->cost), $authority);

                if (!$gateWayVerify->isSuccessfulPayment()) {
                    array_push($unverifiedTransactionsDueToError, $transaction);
                }
            }
        }

        if (count($unverifiedTransactionsDueToError) > 0) {
            $this->info('Unverified Transactions Due To Error:');
            $this->logError($unverifiedTransactionsDueToError);
        }

        if (count($notExistTransactions) <= 0) {
            return null;
        }
        $this->logError($notExistTransactions);

        if ($this->confirm('The above transactions are not available. \n\rDo you wish to force verify?', true)) {
            foreach ($notExistTransactions as $item) {
                $gateWayVerify = $paymentClient->verifyPayment(Money::fromTomans($item['Amount']), $item['Authority']);
            }
        }
    }
    
    private function getUnverifiedTransactions()
    {
        return (new Zarinpal(['merchantID' => $this->merchantNumber]))->getUnverifiedTransactions();
    }

    /**
     * @param array $r
     * @return mixed
     */
    private function logError(array $r)
    {
        foreach ($r as $item) {
            $authority = $item['Authority'];
            $amount = $item['Amount'];
            $channel = $item['Channel'];
            $cellPhone = $item['CellPhone'];
            $date = $item['Date'];
            $this->info('authority: {'.$authority.'} amount: {'.$amount.'} channel: {'.$channel.'} cellPhone: {'.$cellPhone.'} date: {'.$date.'}');
        }
    }
}
