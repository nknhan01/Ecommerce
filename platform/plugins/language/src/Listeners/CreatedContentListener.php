<?php

namespace Platform\Language\Listeners;

use Platform\Base\Events\CreatedContentEvent;
use Exception;
use Language;

class CreatedContentListener
{

    /**
     * Handle the event.
     *
     * @param CreatedContentEvent $event
     * @return void
     */
    public function handle(CreatedContentEvent $event)
    {
        try {
            Language::saveLanguage($event->screen, $event->request, $event->data);
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
