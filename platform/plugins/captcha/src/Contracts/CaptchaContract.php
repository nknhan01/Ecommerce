<?php

namespace Botble\Captcha\Contracts;

use Botble\Captcha\Contracts\Utilities\AttributesContract;
use Botble\Captcha\Contracts\Utilities\RequestContract;

interface CaptchaContract
{
    /**
     * Set HTTP Request Client.
     *
     * @param  RequestContract $request
     *
     * @return self
     */
    public function setRequestClient(RequestContract $request);

    /**
     * Set noCaptcha Attributes.
     *
     * @param  AttributesContract $attributes
     *
     * @return self
     */
    public function setAttributes(AttributesContract $attributes);

    /**
     * Display Captcha.
     *
     * @param  string|null $name
     * @param  array $attributes
     *
     * @return string
     */
    public function display($name, array $attributes = []);

    /**
     * Display image Captcha.
     *
     * @param  string|null $name
     * @param  array $attributes
     *
     * @return string
     */
    public function image($name, array $attributes = []);

    /**
     * Display audio Captcha.
     *
     * @param  string|null $name
     * @param  array $attributes
     *
     * @return string
     */
    public function audio($name, array $attributes = []);

    /**
     * Verify Response.
     *
     * @param  string $response
     * @param  string $clientIp
     *
     * @return bool
     */
    public function verify($response, $clientIp = null);

    /**
     * Get script tag.
     *
     * @return string
     */
    public function script();

    /**
     * Get script tag with a callback function.
     *
     * @param  array $captcha
     * @param  string $callbackName
     *
     * @return string
     */
    public function scriptWithCallback(array $captcha, $callbackName = 'captchaRenderCallback');
}
