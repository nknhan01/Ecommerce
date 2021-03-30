<?php

namespace Botble\Translation;

use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Events\Dispatcher;
use Botble\Translation\Models\Translation;
use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Lang;
use Symfony\Component\Finder\Finder;
use Symfony\Component\VarExporter\VarExporter;

class Manager
{
    const JSON_GROUP = '_json';

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;

    /**
     * @var array|\ArrayAccess
     */
    protected $config;

    /**
     * Manager constructor.
     * @param Application $app
     * @param Filesystem $files
     * @param Dispatcher $events
     */
    public function __construct(Application $app, Filesystem $files, Dispatcher $events)
    {
        $this->app = $app;
        $this->files = $files;
        $this->events = $events;
        $this->config = $app['config']['plugins.translation.general'];
    }

    /**
     * @param string $group
     * @param string $key
     */
    public function missingKey($group, $key)
    {
        if (!in_array($group, $this->config['exclude_groups'])) {
            Translation::firstOrCreate([
                'locale' => $this->app['config']['app.locale'],
                'group'  => $group,
                'key'    => $key,
            ]);
        }
    }

    /**
     * @param bool $replace
     * @return int
     */
    public function importTranslations($replace = false)
    {
        $counter = 0;

        foreach ($this->files->directories($this->app['path.lang']) as $langPath) {
            $locale = basename($langPath);
            foreach ($this->files->allfiles($langPath) as $file) {
                $info = pathinfo($file);
                $group = $info['filename'];
                if (in_array($group, $this->config['exclude_groups'])) {
                    continue;
                }
                $subLangPath = str_replace($langPath . DIRECTORY_SEPARATOR, '', $info['dirname']);
                $subLangPath = str_replace(DIRECTORY_SEPARATOR, '/', $subLangPath);
                $langDirectory = $group;
                if ($subLangPath != $langPath) {
                    $langDirectory = $subLangPath . '/' . $group;
                    $group = substr($subLangPath, 0, -3) . '/' . $group;
                }

                $translations = Lang::getLoader()->load($locale, $langDirectory);
                if ($translations && is_array($translations)) {
                    foreach (Arr::dot($translations) as $key => $value) {
                        $importedTranslation = $this->importTranslation($key, $value, ($locale != 'vendor' ? $locale : substr($subLangPath, -2)), $group, $replace);
                        $counter += $importedTranslation ? 1 : 0;
                    }
                }
            }
        }

        foreach ($this->files->files($this->app['path.lang']) as $jsonTranslationFile) {
            if (strpos($jsonTranslationFile, '.json') === false) {
                continue;
            }
            $locale = basename($jsonTranslationFile, '.json');
            $group = self::JSON_GROUP;
            $translations = Lang::getLoader()->load($locale, '*', '*'); // Retrieves JSON entries of the given locale only
            if ($translations && is_array($translations)) {
                foreach ($translations as $key => $value) {
                    $importedTranslation = $this->importTranslation($key, $value, $locale, $group, $replace);
                    $counter += $importedTranslation ? 1 : 0;
                }
            }
        }

        return $counter;
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $locale
     * @param string $group
     * @param bool $replace
     * @return bool
     */
    public function importTranslation($key, $value, $locale, $group, $replace = false)
    {
        // process only string values
        if (is_array($value)) {
            return false;
        }
        $value = (string)$value;
        $translation = Translation::firstOrNew([
            'locale' => $locale,
            'group'  => $group,
            'key'    => $key,
        ]);

        // Check if the database is different then the files
        $newStatus = $translation->value === $value ? Translation::STATUS_SAVED : Translation::STATUS_CHANGED;
        if ($newStatus !== (int)$translation->status) {
            $translation->status = $newStatus;
        }

        // Only replace when empty, or explicitly told so
        if ($replace || !$translation->value) {
            $translation->value = $value;
        }

        $translation->save();

        return true;
    }

    /**
     * @param null $path
     * @return int
     */
    public function findTranslations($path = null)
    {
        $path = $path ?: base_path();
        $groupKeys = [];
        $stringKeys = [];
        $functions = ['trans', 'trans_choice', 'Lang::get', 'Lang::choice', 'Lang::get', 'Lang::choice', '@lang', '@choice'];

        $groupPattern =                              // See http://regexr.com/392hu
            '[^\w|>]' .                          // Must not have an alphanum or _ or > before real method
            '(' . implode('|', $functions) . ')' .  // Must start with one of the functions
            '\(' .                               // Match opening parenthesis
            "[\'\"]" .                           // Match " or '
            '(' .                                // Start a new group to match:
            '[a-zA-Z0-9_-]+' .               // Must start with group
            "([.|\/][^\1)]+)+" .             // Be followed by one or more items/keys
            ')' .                                // Close group
            "[\'\"]" .                           // Closing quote
            '[\),]';                            // Close parentheses or new parameter

        $stringPattern =
            '[^\w|>]' .                                     // Must not have an alphanum or _ or > before real method
            '(' . implode('|', $functions) . ')' .             // Must start with one of the functions
            '\(' .                                          // Match opening parenthesis
            "(?P<quote>['\"])" .                            // Match " or ' and store in {quote}
            '(?P<string>(?:\\\k{quote}|(?!\k{quote}).)*)' . // Match any string that can be {quote} escaped
            '\k{quote}' .                                   // Match " or ' previously matched
            '[\),]';                                       // Close parentheses or new parameter

        // Find all PHP + Twig files in the app folder, except for storage
        $finder = new Finder();
        $finder->in($path)->exclude('storage')->name('*.php')->name('*.twig')->files();

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            // Search the current file for the pattern
            if (preg_match_all('/' . $groupPattern . '/siU', $file->getContents(), $matches)) {
                // Get all matches
                foreach ($matches[2] as $key) {
                    $groupKeys[] = $key;
                }
            }

            if (preg_match_all('/' . $stringPattern . '/siU', $file->getContents(), $matches)) {
                foreach ($matches['string'] as $key) {
                    if (preg_match("/(^[a-zA-Z0-9_-]+([.][^\1)\ ]+)+$)/siU", $key, $groupMatches)) {
                        // group{.group}.key format, already in $groupKeys but also matched here
                        // do nothing, it has to be treated as a group
                        continue;
                    }
                    //TODO: This can probably be done in the regex, but I couldn't do it.
                    //skip keys which contain namespace characters, unless they also contain a
                    //space, which makes it JSON.
                    if (!(Str::contains($key, '::') && Str::contains($key, '.')) || Str::contains($key, ' ')) {
                        $stringKeys[] = $key;
                    }
                }
            }
        }
        // Remove duplicates
        $groupKeys = array_unique($groupKeys);
        $stringKeys = array_unique($stringKeys);

        // Add the translations to the database, if not existing.
        foreach ($groupKeys as $key) {
            // Split the group and item
            try {
                [$group, $item] = explode('.', $key, 2);
            } catch (Exception $exception) {
                info($key);
            }
            $this->missingKey($group, $item);
        }

        foreach ($stringKeys as $key) {
            $group = self::JSON_GROUP;
            $item = $key;
            $this->missingKey($group, $item);
        }

        // Return the number of found translations
        return count($groupKeys + $stringKeys);
    }

    /**
     * @param null $group
     * @param bool $json
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     */
    public function exportTranslations($group = null, $json = false)
    {
        if (!empty($group) && !$json) {
            if (!in_array($group, $this->config['exclude_groups'])) {
                if ($group == '*') {
                    return $this->exportAllTranslations();
                }

                $tree = $this->makeTree(Translation::ofTranslatedGroup($group)->orderByGroupKeys(Arr::get($this->config, 'sort_keys', false))->get());

                foreach ($tree as $locale => $groups) {
                    if (isset($groups[$group])) {
                        $translations = $groups[$group];
                        $file = $locale . '/' . $group;
                        $groups = explode('/', $group);
                        if (count($groups) > 1) {
                            $folderName = Arr::last($groups);
                            Arr::forget($groups, count($groups) - 1);

                            $dir = 'vendor/' . implode('/', $groups) . '/' . $locale;
                            if (!$this->files->isDirectory($this->app->langPath() . '/' . $dir)) {
                                $this->files->makeDirectory($this->app->langPath() . '/' . $dir, 755, true);
                                system('find ' . $this->app->langPath() . '/' . $dir . ' -type d -exec chmod 755 {} \;');
                            }
                            $file = $dir . '/' . $folderName;
                        }
                        $path = $this->app['path.lang'] . '/' . $file . '.php';
                        $output = "<?php\n\nreturn " . VarExporter::export($translations) . ";\n";
                        $this->files->put($path, $output);
                    }
                }
                Translation::ofTranslatedGroup($group)->update(['status' => Translation::STATUS_SAVED]);
            }
        }

        if ($json) {
            $tree = $this->makeTree(Translation::ofTranslatedGroup(self::JSON_GROUP)->orderByGroupKeys(Arr::get($this->config, 'sort_keys', false))->get(), true);

            foreach ($tree as $locale => $groups) {
                if (isset($groups[self::JSON_GROUP])) {
                    $translations = $groups[self::JSON_GROUP];
                    $path = $this->app['path.lang'] . '/' . $locale . '.json';
                    $output = json_encode($translations, JSON_PRETTY_PRINT);
                    $this->files->put($path, $output);
                }
            }

            Translation::ofTranslatedGroup(self::JSON_GROUP)->update(['status' => Translation::STATUS_SAVED]);
        }
    }

    /**
     * @return bool
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     */
    public function exportAllTranslations()
    {
        $groups = Translation::whereNotNull('value')->selectDistinctGroup()->get('group');

        foreach ($groups as $group) {
            if ($group == self::JSON_GROUP) {
                $this->exportTranslations(null, true);
            } else {
                $this->exportTranslations($group->group);
            }
        }
        return true;
    }

    /**
     * @throws Exception
     */
    public function cleanTranslations()
    {
        Translation::whereNull('value')->delete();
    }

    public function truncateTranslations()
    {
        Translation::truncate();
    }

    /**
     * @param array $translations
     * @param bool $json
     * @return array
     */
    protected function makeTree(array $translations, $json = false)
    {
        $array = [];
        foreach ($translations as $translation) {
            if ($json) {
                $this->jsonSet($array[$translation->locale][$translation->group], $translation->key, $translation->value);
            } else {
                Arr::set($array[$translation->locale][$translation->group], $translation->key, $translation->value);
            }
        }
        return $array;
    }

    /**
     * @param null|string $key
     * @return mixed
     */
    public function getConfig($key = null)
    {
        if ($key == null) {
            return $this->config;
        }

        return $this->config[$key];
    }

    /**
     * @param array $array
     * @param string $key
     * @param string $value
     * @return mixed
     */
    public function jsonSet(&$array, $key, $value)
    {
        if (empty($key)) {
            return $array = $value;
        }

        $array[$key] = $value;

        return $array;
    }
}
