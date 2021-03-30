<?php

namespace Botble\Base\Providers;

use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $config = [
            'default' => setting('email_driver', config('mail.default')),
            'mailers' => array_merge(config('mail.mailers'), [
                'smtp'     => [
                    'transport'  => 'smtp',
                    'host'       => setting('email_host', config('mail.mailers.smtp.host')),
                    'port'       => (int)setting('email_port', config('mail.mailers.smtp.port')),
                    'encryption' => setting('email_encryption', config('mail.mailers.smtp.encryption')),
                    'username'   => setting('email_username', config('mail.mailers.smtp.username')),
                    'password'   => setting('email_password', config('mail.mailers.smtp.password')),
                ],
                'sendmail' => [
                    'transport' => 'sendmail',
                    'path'      => setting('email_sendmail_path', config('mail.mailers.sendmail.path')),
                ],
            ]),
            'from'    => [
                'address' => setting('email_from_address', config('mail.from.address')),
                'name'    => setting('email_from_name', config('mail.from.name')),
            ],
        ];

        config(['mail' => array_merge(config('mail'), $config)]);

        if (setting('email_driver', config('mail.default')) === 'mailgun') {
            config([
                'services.mailgun' => [
                    'domain' => setting('email_mail_gun_domain', config('services.mailgun.domain')),
                    'secret' => setting('email_mail_gun_secret', config('services.mailgun.secret')),
                ],
            ]);
        }
    }
}
