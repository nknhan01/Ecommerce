<?php

namespace Platform\Payment\Providers;

use Platform\Base\Supports\Helper;
use Platform\Base\Traits\LoadAndPublishDataTrait;
use Platform\Payment\Enums\PaymentMethodEnum;
use Platform\Payment\Models\Payment;
use Platform\Payment\Services\Gateways\PayPalPaymentService;
use Platform\Payment\Services\Gateways\StripePaymentService;
use Event;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
use Platform\Payment\Repositories\Caches\PaymentCacheDecorator;
use Platform\Payment\Repositories\Eloquent\PaymentRepository;
use Platform\Payment\Repositories\Interfaces\PaymentInterface;
use Laravel\Cashier\Cashier;

class PaymentServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->singleton(PaymentInterface::class, function () {
            return new PaymentCacheDecorator(new PaymentRepository(new Payment));
        });

        Helper::autoload(__DIR__ . '/../../helpers');

        if (class_exists('Laravel\Cashier\Cashier')) {
            Cashier::ignoreMigrations();
        }
    }

    public function boot()
    {
        $this->setNamespace('plugins/payment')
            ->loadAndPublishConfigurations(['payment', 'permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web'])
            ->loadMigrations()
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-plugins-payments',
                    'priority'    => 800,
                    'parent_id'   => null,
                    'name'        => 'plugins/payment::payment.name',
                    'icon'        => 'fas fa-credit-card',
                    'url'         => route('payment.index'),
                    'permissions' => ['payment.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-payments-all',
                    'priority'    => 0,
                    'parent_id'   => 'cms-plugins-payments',
                    'name'        => 'plugins/payment::payment.transactions',
                    'icon'        => null,
                    'url'         => route('payment.index'),
                    'permissions' => ['payment.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-payment-methods',
                    'priority'    => 1,
                    'parent_id'   => 'cms-plugins-payments',
                    'name'        => 'plugins/payment::payment.payment_methods',
                    'icon'        => null,
                    'url'         => route('payments.methods'),
                    'permissions' => ['payments.methods'],
                ]);
        });

        add_shortcode('payment-form', 'Payment form', 'Payment form', function ($shortCode) {
            $data = [
                'name'        => $shortCode->name,
                'currency'    => $shortCode->currency,
                'amount'      => $shortCode->amount,
                'returnUrl'   => $shortCode->return_url,
                'callbackUrl' => $shortCode->callback_url,
            ];

            $view = 'plugins/payment::partials.form';

            if ($shortCode->view && view()->exists($shortCode->view)) {
                $view = $shortCode->view;
            }

            return view($view, $data);
        });

        shortcode()->setAdminConfig('payment-form', view('plugins/payment::partials.shortcode-admin-config')->render());

        add_shortcode('payment-info', 'Payment info', 'Payment info', function ($shortCode) {
            $payment = app(PaymentInterface::class)->getFirstBy(['charge_id' => $shortCode->charge_id]);

            if (!$payment) {
                return trans('plugins/payment::payment.payment_not_found');
            }

            $detail = null;
            switch ($payment->payment_channel) {
                case PaymentMethodEnum::PAYPAL:
                    $paymentDetail = (new PayPalPaymentService)->getPaymentDetails($payment->charge_id);
                    $detail = view('plugins/payment::paypal.detail', ['payment' => $paymentDetail])->render();
                    break;
                case PaymentMethodEnum::STRIPE:
                    $paymentDetail = (new StripePaymentService)->getPaymentDetails($payment->charge_id);
                    $detail = view('plugins/payment::stripe.detail', ['payment' => $paymentDetail])->render();
                    break;
                case PaymentMethodEnum::COD:
                case PaymentMethodEnum::BANK_TRANSFER:
                    break;
                default:
                    $detail = apply_filters(PAYMENT_FILTER_PAYMENT_INFO_DETAIL, null, $payment);
                    break;
            }

            $view = 'plugins/payment::partials.info';

            if ($shortCode->view && view()->exists($shortCode->view)) {
                $view = $shortCode->view;
            }

            return view($view, compact('payment', 'detail'));
        });

    }
}
