<?php


namespace SaeedVaziry\Payir;

use SaeedVaziry\Payir\Facades\Payir;

class PayirPG
{
    public $token;
    public $amount;
    public $redirect;
    public $factorNumber;
    public $mobile;
    public $description;
    public $paymentUrl;
    public $validCardNumber;

    /**
     * send
     *
     * @return mixed
     * @throws Exceptions\SendException
     */
    public function send()
    {
        try {
            $send = Payir::send($this->amount, $this->redirect, $this->factorNumber, $this->mobile, $this->description, $this->validCardNumber);

            $this->token = $send['token'];
            $this->paymentUrl = $send['payment_url'];
        } catch (Exceptions\SendException $e) {
            throw $e;
        }
    }

    /**
     * verify
     *
     * @return mixed
     * @throws Exceptions\VerifyException
     */
    public function verify()
    {
        try {
            return Payir::verify($this->token);
        } catch (Exceptions\VerifyException $e) {
            throw $e;
        }
    }
}