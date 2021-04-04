<?php

namespace Platform\Slug\Listeners;

use Platform\Base\Events\DeletedContentEvent;
use Platform\Slug\Repositories\Interfaces\SlugInterface;
use Exception;
use SlugHelper;

class DeletedContentListener
{

    /**
     * @var SlugInterface
     */
    protected $slugRepository;

    /**
     * DeletedContentListener constructor.
     * @param SlugInterface $slugRepository
     */
    public function __construct(SlugInterface $slugRepository)
    {
        $this->slugRepository = $slugRepository;
    }

    /**
     * Handle the event.
     *
     * @param DeletedContentEvent $event
     * @return void
     */
    public function handle(DeletedContentEvent $event)
    {
        if (SlugHelper::isSupportedModel(get_class($event->data))) {
            try {
                $this->slugRepository->deleteBy([
                    'reference_id'   => $event->data->id,
                    'reference_type' => get_class($event->data),
                ]);
            } catch (Exception $exception) {
                info($exception->getMessage());
            }
        }
    }
}
