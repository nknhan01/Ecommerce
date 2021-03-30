<?php

namespace Botble\Captcha\Utilities;

use Botble\Captcha\Contracts\Utilities\RequestContract;
use Botble\Captcha\Exceptions\InvalidUrlException;

class Request implements RequestContract
{

    /**
     * Run the request and get response.
     *
     * @param  string $url
     * @param  bool $curled
     * @throws InvalidUrlException
     * @return array
     */
    public function send($url, $curled = true)
    {
        if (!is_string($url)) {
            throw new InvalidUrlException('The url must be a string value, ' . gettype($url) . ' given');
        }

        $url = trim($url);

        if (empty($url)) {
            throw new InvalidUrlException('The url must not be empty');
        }

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidUrlException('The url [' . $url . '] is invalid');
        }

        if (function_exists('curl_version') && $curled === true) {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
            ]);
            $result = curl_exec($curl);
            curl_close($curl);
        } else {
            $result = file_get_contents($url);
        }

        return is_string($result) && !empty($result) ? json_decode($result, true) : [];
    }
}
