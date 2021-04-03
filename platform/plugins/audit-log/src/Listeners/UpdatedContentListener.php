<?php

namespace Platform\AuditLog\Listeners;

use Platform\AuditLog\Events\AuditHandlerEvent;
use Platform\Base\Events\UpdatedContentEvent;
use Exception;
use AuditLog;

class UpdatedContentListener
{

    /**
     * Handle the event.
     *
     * @param UpdatedContentEvent $event
     * @return void
     */
    public function handle(UpdatedContentEvent $event)
    {
        try {
            if ($event->data->id) {
                event(new AuditHandlerEvent(
                    $event->screen,
                    'updated',
                    $event->data->id,
                    AuditLog::getReferenceName($event->screen, $event->data),
                    'primary'
                ));
            }
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
