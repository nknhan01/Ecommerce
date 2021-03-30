<?php

namespace Botble\CookieConsent\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class CookieConsentMiddleware
{
    /**
     * @param $request
     * @param Closure $next
     * @return Response|mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (!config('plugins.cookie-consent.general.enabled')) {
            return $response;
        }

        if (!$response instanceof Response) {
            return $response;
        }

        if (!$this->containsBodyTag($response)) {
            return $response;
        }

        return $this->addCookieConsentScriptToResponse($response);
    }

    /**
     * @param Response $response
     * @return bool
     */
    protected function containsBodyTag(Response $response): bool
    {
        return $this->getLastClosingBodyTagPosition($response->getContent()) !== false;
    }

    /**
     * @param string $content
     * @return false|int
     */
    protected function getLastClosingBodyTagPosition(string $content = '')
    {
        return strripos($content, '</body>');
    }

    /**
     * @param \Illuminate\Http\Response $response
     *
     * @return Response
     */
    protected function addCookieConsentScriptToResponse(Response $response)
    {
        $content = $response->getContent();

        $closingBodyTagPosition = $this->getLastClosingBodyTagPosition($content);

        $content = ''
            . substr($content, 0, $closingBodyTagPosition)
            . view('plugins/cookie-consent::index')->render()
            . substr($content, $closingBodyTagPosition);

        return $response->setContent($content);
    }
}
