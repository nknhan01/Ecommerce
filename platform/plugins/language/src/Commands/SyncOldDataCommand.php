<?php

namespace Botble\Language\Commands;

use DB;
use Illuminate\Console\Command;
use Language;
use Schema;

class SyncOldDataCommand extends Command
{

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cms:language:sync {table : The table need to set default language} {reference : screen name of that object}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set default language for old objects';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        if (!preg_match('/^[a-z\-_]+$/i', $this->argument('table'))) {
            $this->error('Only alphabetic characters are allowed.');
            return false;
        }

        if (!Schema::hasTable($this->argument('table'))) {
            $this->error('That table is not existed!');
            return false;
        }

        if (!Schema::hasColumn($this->argument('table'), 'id')) {
            $this->error('That table does not have ID column!');
            return false;
        }

        $data = DB::table($this->argument('table'))->get();
        foreach ($data as $item) {
            $existed = DB::table('language_meta')
                ->where([
                    'reference_id'   => $item->id,
                    'reference_type' => $this->argument('reference'),
                ])
                ->first();
            if (empty($existed)) {
                DB::table('language_meta')->insert([
                    'reference_id'     => $item->id,
                    'reference_type'   => $this->argument('reference'),
                    'lang_meta_code'   => Language::getDefaultLocaleCode(),
                    'lang_meta_origin' => md5($item->id . $this->argument('reference') . time()),
                ]);
            }
        }

        $this->info('Processed ' . count($data) . ' item(s)!');
        return true;
    }
}
