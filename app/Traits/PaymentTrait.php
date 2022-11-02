<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait PaymentTrait
{
    private $dev_toyyibpay_uri = 'https://dev.toyyibpay.com';
    private $prod_toyyibpay_uri = 'https://toyyibpay.com';

    public function getUserSecretKey()
    {
        return env('TOYYIBPAY_USER_SECRET_KEY', '');
    }

    public function getURI()
    {
        return env('TOYYIBPAY_SANDBOX', true) ? $this->dev_toyyibpay_uri : $this->prod_toyyibpay_uri;
    }

    public function post($url, $data)
    {
        $response = Http::asForm()->post($url, $data);

        return (object) $response->json();
    }

    public function createCategory($name, $description)
    {
        $url = $this->getURI() . '/index.php/api/createCategory';

        $data = [
            'catname' => $name,
            'catdescription' => $description,
            'userSecretKey' => $this->getUserSecretKey()
        ];

        $res = $this->post($url, $data);
        return $res;
    }

    public function getCategory($code)
    {
        $url = $this->getURI() . '/index.php/api/getCategoryDetails';

        $data = [
            'categoryCode' => $code,
            'userSecretKey' => $this->getUserSecretKey(),
        ];

        $res = $this->post($url, $data);
        return $res;
    }

    public function createBill($code, $bill_object)
    {
        $url = $this->getURI() . '/index.php/api/createBill';

        $data = [
            'categoryCode' => $code,
            'userSecretKey' => $this->getUserSecretKey(),
            'billName' => $bill_object->billName,
            'billDescription' => $bill_object->billDescription,
            'billPriceSetting' => $bill_object->billPriceSetting,
            'billPayorInfo' => $bill_object->billPayorInfo,
            'billAmount' => $bill_object->billAmount,
            'billReturnUrl' => $bill_object->billReturnUrl ?? '',
            'billCallbackUrl' => $bill_object->billCallbackUrl ?? '',
            'billExternalReferenceNo' => $bill_object->billExternalReferenceNo,
            'billTo' => $bill_object->billTo,
            'billEmail' => $bill_object->billEmail,
            'billPhone' => $bill_object->billPhone,
            'billSplitPayment' => $bill_object->billSplitPayment ?? 0,
            'billSplitPaymentArgs' => $bill_object->billSplitPaymentArgs ?? '',
            'billPaymentChannel' => $bill_object->billPaymentChannel ?? '0',
            'billContentEmail' => $bill_object->billContentEmail ?? '',
            'billChargeToCustomer' => $bill_object->billChargeToCustomer ?? 1,
        ];

        $res = $this->post($url, $data);
        return $res;
    }

    public function getBill($code)
    {
        $url = $this->getURI() . '/index.php/api/getBillTransactions';

        $data = [
            'billCode' => $code,
        ];

        $res = $this->post($url, $data);
        return $res;
    }

    public function billPaymentLink($bill_code)
    {
        return $this->getURI() . '/' . $bill_code;
    }
}
