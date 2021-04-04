<?php

namespace Platform\Payment\Services\Gateways;

use Platform\Payment\Enums\PaymentMethodEnum;
use Platform\Payment\Enums\PaymentStatusEnum;
use Platform\Payment\Services\Traits\PaymentTrait;
use Platform\Support\Services\ProduceServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CodPaymentService implements ProduceServiceInterface
{
    use PaymentTrait;

    /**
     * @param Request $request
     * @return mixed|void
     */
    public function execute(Request $request)
    {
        $chargeId = Str::upper(Str::random(10));

        $this->storeLocalPayment([
            'amount'          => $request->input('amount'),
            'currency'        => $request->input('currency'),
            'charge_id'       => $chargeId,
            'order_id'        => $request->input('order_id'),
            'payment_channel' => PaymentMethodEnum::COD,
            'status'          => PaymentStatusEnum::PENDING,
        ]);

        return $chargeId;
    }
}
