<?php

declare(strict_types=1);

namespace SaeedVaziry\Payir\Facades;

use Illuminate\Support\Facades\Facade;
use SaeedVaziry\Payir\Exceptions\SendException;
use SaeedVaziry\Payir\Exceptions\VerifyException;
use SaeedVaziry\Payir\Http\Request;

/**
 * This is the payir facade class.
 */
class Payir extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'payir';
    }

    /**
     * Send data to pay.ir and init transaction
     *
     * @param $amount
     * @param $redirect
     * @param null $factorNumber
     * @param null $mobile
     * @param null $description
     * @return mixed
     * @throws SendException
     */
    public static function send($amount, $redirect = null, $factorNumber = null, $mobile = null, $description = null, $api = null, $validCardNumber = null)
    {
        $data = [
            'api' => $api ? $api : config('payir.api_key'),
            'redirect' => $redirect ? $redirect : url(config('payir.redirect')),
            'amount' => $amount,
            'factorNumber' => $factorNumber,
            'mobile' => $mobile,
            'description' => $description,
            'resellerId' => '1000000012'
        ];
        if ($validCardNumber) {
            $data['validCardNumber'] = $validCardNumber;
        }
        $send = Request::make('https://pay.ir/pg/send', $data);
        if (isset($send['status']) && isset($send['response'])) {
            if ($send['status'] == 200) {
                $send['response']['payment_url'] = 'https://pay.ir/pg/' . $send['response']['token'];

                return $send['response'];
            }

            throw new SendException($send['response']['errorMessage']);
        }

        throw new SendException('خطا در ارسال اطلاعات به Pay.ir. لطفا از برقرار بودن اینترنت و در دسترس بودن pay.ir اطمینان حاصل کنید');
    }

    /**
     * Send data to pay.ir and init transaction with options
     *
     * @param array $options
     * @return mixed
     * @throws SendException
     */
    public static function send2(array $options)
    {
        if (!isset($options['api'])) {
            $options['api'] = config('payir.api_key');
        }
        if (!isset($options['redirect'])) {
            url(config('payir.redirect'));
        }
        $options['resellerId'] = '1000000012';
        $send = Request::make('https://pay.ir/pg/send', $options);
        if (isset($send['status']) && isset($send['response'])) {
            if ($send['status'] == 200) {
                $send['response']['payment_url'] = 'https://pay.ir/pg/' . $send['response']['token'];

                return $send['response'];
            }

            throw new SendException($send['response']['errorMessage']);
        }

        throw new SendException('خطا در ارسال اطلاعات به Pay.ir. لطفا از برقرار بودن اینترنت و در دسترس بودن pay.ir اطمینان حاصل کنید');
    }

    /**
     * Verify transaction
     *
     * @param $token
     * @return mixed
     * @throws VerifyException
     */
    public static function verify($token, $api = null)
    {
        $verify = Request::make('https://pay.ir/pg/verify', [
            'api' => $api ? $api : config('payir.api_key'),
            'token' => $token,
        ]);
        if (isset($verify['status']) && isset($verify['response'])) {
            if ($verify['status'] == 200) {
                return $verify['response'];
            }

            throw new VerifyException($verify['response']['errorMessage']);
        }

        throw new VerifyException('خطا در ارسال اطلاعات به Pay.ir. لطفا از برقرار بودن اینترنت و در دسترس بودن pay.ir اطمینان حاصل کنید');
    }
}
