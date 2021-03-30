<?php

namespace Botble\Translation\Console;

use Botble\Translation\Manager;
use Illuminate\Console\Command;

class FindCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:translations:find';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find translations in blade files';

    /**
     * @var Manager
     */
    protected $manager;

    /**
     * FindCommand constructor.
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $counter = $this->manager->findTranslations(null);
        $this->info('Done importing, processed ' . $counter . ' items!');

        return $counter;
    }
}
