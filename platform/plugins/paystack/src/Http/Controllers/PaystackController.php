<?php

namespace Platform\Paystack\Http\Controllers;

use Platform\Payment\Enums\PaymentStatusEnum;
use Platform\Base\Http\Controllers\BaseController;
use Platform\Base\Http\Responses\BaseHttpResponse;
use Platform\Payment\Services\Traits\PaymentTrait;
use OrderHelper;
use Illuminate\Http\Request;
use Paystack;
use Throwable;

class PaystackController extends BaseController
{
    use PaymentTrait;

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function getPaymentStatus(Request $request, BaseHttpResponse $response)
    {
        $result = Paystack::getPaymentData();

        $this->storeLocalPayment([
            'amount'          => $result['data']['amount'] / 100,
            'currency'        => $result['data']['currency'],
            'charge_id'       => $request->input('reference'),
            'payment_channel' => PAYSTACK_PAYMENT_METHOD_NAME,
            'status'          => $result['status'] ? PaymentStatusEnum::COMPLETED : PaymentStatusEnum::FAILED,
            'customer_id'     => auth('customer')->check() ? auth('customer')->user()->getAuthIdentifier() : null,
            'payment_type'    => 'direct',
            'order_id'        => $result['data']['metadata']['order_id'],
        ]);

        OrderHelper::processOrder($result['data']['metadata']['order_id'], $request->input('reference'));

        if (!$result['status']) {
            return $response
                ->setError()
                ->setNextUrl(route('public.checkout.success', session('tracked_start_checkout')))
                ->setMessage($result['message']);
        }

        return $response
            ->setNextUrl(route('public.checkout.success', session('tracked_start_checkout')))
            ->setMessage(__('Checkout successfully!'));
    }
}
