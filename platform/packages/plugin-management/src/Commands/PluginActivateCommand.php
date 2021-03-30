<?php

namespace Botble\PluginManagement\Commands;

use Botble\PluginManagement\Services\PluginService;
use Illuminate\Console\Command;

class PluginActivateCommand extends Command
{
    /**
     * @var PluginService
     */
    public $pluginService;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cms:plugin:activate {name : The plugin that you want to activate}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate a plugin in /plugins directory';

    /**
     * PluginActivateCommand constructor.
     * @param PluginService $pluginService
     */
    public function __construct(PluginService $pluginService)
    {
        parent::__construct();

        $this->pluginService = $pluginService;
    }

    /**
     * @return boolean
     */
    public function handle()
    {
        if (!preg_match('/^[a-z0-9\-]+$/i', $this->argument('name'))) {
            $this->error('Only alphabetic characters are allowed.');
            return false;
        }

        $plugin = strtolower($this->argument('name'));

        $result = $this->pluginService->activate($plugin);

        if ($result['error']) {
            $this->error($result['message']);
            return false;
        }

        $this->info($result['message']);

        return true;
    }
}
