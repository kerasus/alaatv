<?php
/**
 * Created by PhpStorm.
 * User: Alaaa
 * Date: 1/21/2019
 * Time: 1:39 PM
 */

namespace App\Classes\Payment\GateWay;


use App\Http\Controllers\TransactionController;

class GateWay
{
    private $transactionController;

    /**
     * @var GateWayAbstract $gateWayClass
     */
    private $gateWayClass;

    public function __construct(TransactionController $transactionController)
    {
        $this->transactionController = $transactionController;
    }

    /**
     * @param string $gateWay
     * @return GateWay
     */
    public function setGateWay(string $gateWay)
    {
        $className = __NAMESPACE__ . '\\' . ucfirst($gateWay) . '\\' . ucfirst($gateWay);
        if (class_exists($className)) {
            $this->gateWayClass = new $className($this->transactionController);
        } else {
            throw new Exception('GateWay {' . $className . '} not found.');
        }
        return $this;
    }

    public function redirect(array $data) {
        return $this->gateWayClass->loadForRedirect($data)
            ->redirect();
    }

    public function verify(array $data) {
        return $this->gateWayClass->loadForVerify($data)
            ->verify();
    }
}