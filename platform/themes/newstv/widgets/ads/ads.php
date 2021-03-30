<?php

use Botble\Widget\AbstractWidget;

class AdsWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $frontendTemplate = 'frontend';

    /**
     * @var string
     */
    protected $backendTemplate = 'backend';

    /**
     * @var string
     */
    protected $widgetDirectory = 'ads';

    /**
     * Widget constructor.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __construct()
    {
        parent::__construct([
            'name'          => 'Ads',
            'description'   => __('Widget to show image ads on sidebar.'),
            'image_url'     => null,
            'image_link'    => null,
            'image_new_tab' => 0,
        ]);
    }
}
