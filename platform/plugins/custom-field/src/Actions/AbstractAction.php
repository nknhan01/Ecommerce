<?php

namespace Botble\CustomField\Actions;

abstract class AbstractAction
{
    /**
     * @param $message
     * @param array|null $data
     * @return array
     */
    protected function error($message = null, array $data = null): array
    {
        if (!$message) {
            $message = __('Error occurred');
        }
        return response_with_messages($message, true, 500, $data);
    }

    /**
     * @param $message
     * @param array|null $data
     * @return array
     */
    protected function success($message = null, array $data = null): array
    {
        if (!$message) {
            $message = __('Request completed');
        }
        return response_with_messages(
            $message,
            false,
            !$data ? 200 : 201,
            $data
        );
    }
}
