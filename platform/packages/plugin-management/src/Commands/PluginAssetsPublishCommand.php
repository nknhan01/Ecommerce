<?php

namespace Botble\PluginManagement\Commands;

use Botble\PluginManagement\Services\PluginService;
use Illuminate\Console\Command;

class PluginAssetsPublishCommand extends Command
{
    /**
     * @var PluginService
     */
    public $pluginService;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'cms:plugin:assets:publish {name : The plugin that you want to publish assets}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish assets for a plugin';

    /**
     * PluginAssetsPublishCommand constructor.
     * @param PluginService $pluginService
     */
    public function __construct(PluginService $pluginService)
    {
        parent::__construct();

        $this->pluginService = $pluginService;
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle()
    {
        if (!preg_match('/^[a-z0-9\-]+$/i', $this->argument('name'))) {
            $this->error('Only alphabetic characters are allowed.');
            return false;
        }

        $plugin = strtolower($this->argument('name'));
        $result = $this->pluginService->publishAssets($plugin);

        if ($result['error']) {
            $this->error($result['message']);
            return false;
        }

        $this->info($result['message']);

        return true;
    }
}
