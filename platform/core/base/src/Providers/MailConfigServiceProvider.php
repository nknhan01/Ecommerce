<?php

namespace Platform\Base\Providers;

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
        config([
            'mail' => array_merge(config('mail'), [
                'default' => setting('email_driver', config('mail.default')),
                'from'    => [
                    'address' => setting('email_from_address', config('mail.from.address')),
                    'name'    => setting('email_from_name', config('mail.from.name')),
                ],
            ]),
        ]);

        switch (setting('email_driver', config('mail.default'))) {
            case 'smtp':
                config([
                    'mail.mailers.smtp' => array_merge(config('mail.mailers.smtp'), [
                        'transport'  => 'smtp',
                        'host'       => setting('email_host', config('mail.mailers.smtp.host')),
                        'port'       => (int)setting('email_port', config('mail.mailers.smtp.port')),
                        'encryption' => setting('email_encryption', config('mail.mailers.smtp.encryption')),
                        'username'   => setting('email_username', config('mail.mailers.smtp.username')),
                        'password'   => setting('email_password', config('mail.mailers.smtp.password')),
                    ]),
                ]);
                break;
            case 'mailgun':
                config([
                    'services.mailgun' => [
                        'domain'   => setting('email_mail_gun_domain', config('services.mailgun.domain')),
                        'secret'   => setting('email_mail_gun_secret', config('services.mailgun.secret')),
                        'endpoint' => setting('email_mail_gun_endpoint', config('services.mailgun.endpoint')),
                    ],
                ]);
                break;
            case 'sendmail':
                config([
                    'mail.mailers.sendmail.path' => setting('email_sendmail_path',
                        config('mail.mailers.sendmail.path')),
                ]);
                break;
            case 'postmark':
                config([
                    'services.postmark' => [
                        'token' => setting('email_postmark_token', config('services.postmark.token')),
                    ],
                ]);
                break;
            case 'ses':
                config([
                    'services.ses' => [
                        'key'    => setting('email_ses_key', config('services.ses.key')),
                        'secret' => setting('email_ses_secret', config('services.ses.secret')),
                        'region' => setting('email_ses_region', config('services.ses.region')),
                    ],
                ]);
                break;
            case 'log':
                config([
                    'mail.mailers.log.channel' => setting('email_log_channel',
                        config('mail.mailers.log.channel')),
                ]);
                break;
        }
    }
}
