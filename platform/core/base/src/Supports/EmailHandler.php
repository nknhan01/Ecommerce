<?php

namespace Botble\Base\Supports;

use Botble\Base\Events\SendMailEvent;
use Botble\Base\Jobs\SendMailJob;
use Exception;
use MailVariable;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Throwable;
use URL;

class EmailHandler
{

    /**
     * @param string $view
     */
    public function setEmailTemplate(string $view)
    {
        config()->set('core.base.general.email_template', $view);
    }

    /**
     * @param string $content
     * @param string $title
     * @param string $to
     * @param array $args
     * @param bool $debug
     * @throws Throwable
     */
    public function send(string $content, string $title, $to = null, $args = [], $debug = false)
    {
        try {
            if (empty($to)) {
                $to = setting('admin_email', setting('email_from_address', config('mail.from.address')));
            }

            $content = MailVariable::prepareData($content);
            $title = MailVariable::prepareData($title);

            if (config('core.base.general.send_mail_using_job_queue')) {
                dispatch(new SendMailJob($content, $title, $to, $args, $debug));
            } else {
                event(new SendMailEvent($content, $title, $to, $args, $debug));
            }
        } catch (Exception $exception) {
            if ($debug) {
                throw $exception;
            }
            info($exception->getMessage());
            $this->sendErrorException($exception);
        }
    }

    /**
     * Sends an email to the developer about the exception.
     *
     * @param Exception|\Throwable $exception
     * @return void
     *
     * @throws Throwable
     */
    public function sendErrorException(Exception $exception)
    {
        try {
            $ex = FlattenException::create($exception);

            $url = URL::full();
            $error = $this->renderException($exception);

            $this->send(
                view('core/base::emails.error-reporting', compact('url', 'ex', 'error'))->render(),
                $exception->getFile(),
                !empty(config('core.base.general.error_reporting.to')) ?
                    config('core.base.general.error_reporting.to') :
                    setting('admin_email')
            );
        } catch (Exception $ex) {
            info($ex->getMessage());
        }
    }

    /**
     * @param Throwable $exception
     * @return string
     */
    protected function renderException($exception)
    {
        $renderer = new HtmlErrorRenderer(true);

        $exception = $renderer->render($exception);

        if (!headers_sent()) {
            http_response_code($exception->getStatusCode());

            foreach ($exception->getHeaders() as $name => $value) {
                header($name . ': ' . $value, false);
            }
        }

        return $exception->getAsString();
    }
}
