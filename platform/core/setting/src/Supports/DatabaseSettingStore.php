<?php

namespace Botble\Setting\Supports;

use Botble\Base\Supports\Helper;
use Closure;
use Exception;
use File;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use UnexpectedValueException;

class DatabaseSettingStore extends SettingStore
{
    /**
     * The database connection instance.
     *
     * @var Connection
     */
    protected $connection;

    /**
     * The table to query from.
     *
     * @var string
     */
    protected $table;

    /**
     * The key column name to query from.
     *
     * @var string
     */
    protected $keyColumn;

    /**
     * The value column name to query from.
     *
     * @var string
     */
    protected $valueColumn;

    /**
     * Any query constraints that should be applied.
     *
     * @var Closure|null
     */
    protected $queryConstraint;

    /**
     * @var bool
     */
    protected $connectedDatabase = false;

    /**
     * Any extra columns that should be added to the rows.
     *
     * @var array
     */
    protected $extraColumns = [];

    /**
     * @param Connection $connection
     * @param string $table
     * @param null $keyColumn
     * @param null $valueColumn
     */
    public function __construct(Connection $connection, $table = null, $keyColumn = null, $valueColumn = null)
    {
        $this->connection = $connection;
        $this->table = $table ?: 'settings';
        $this->keyColumn = $keyColumn ?: 'key';
        $this->valueColumn = $valueColumn ?: 'value';
    }

    /**
     * Set the table to query from.
     *
     * @param string $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * Set the key column name to query from.
     *
     * @param string $key_column
     */
    public function setKeyColumn($keyColumn)
    {
        $this->keyColumn = $keyColumn;
    }

    /**
     * Set the value column name to query from.
     *
     * @param string $value_column
     */
    public function setValueColumn($valueColumn)
    {
        $this->valueColumn = $valueColumn;
    }

    /**
     * Set the query constraint.
     *
     * @param Closure $callback
     */
    public function setConstraint(Closure $callback)
    {
        $this->data = [];
        $this->loaded = false;
        $this->queryConstraint = $callback;
    }

    /**
     * Set extra columns to be added to the rows.
     *
     * @param array $columns
     */
    public function setExtraColumns(array $columns)
    {
        $this->extraColumns = $columns;
    }

    /**
     * {@inheritDoc}
     */
    public function forget($key): SettingStore
    {
        parent::forget($key);

        // because the database store cannot store empty arrays, remove empty
        // arrays to keep data consistent before and after saving
        $segments = explode('.', $key);
        array_pop($segments);

        while ($segments) {
            $segment = implode('.', $segments);

            // non-empty array - exit out of the loop
            if ($this->get($segment)) {
                break;
            }

            // remove the empty array and move on to the next segment
            $this->forget($segment);
            array_pop($segments);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function write(array $data)
    {
        $keys = $this->newQuery()->pluck($this->keyColumn);

        $insertData = Arr::dot($data);
        $updateData = [];
        $deleteKeys = [];

        foreach ($keys as $key) {
            if (isset($insertData[$key])) {
                $updateData[$key] = $insertData[$key];
            } else {
                $deleteKeys[] = $key;
            }
            unset($insertData[$key]);
        }

        foreach ($updateData as $key => $value) {
            $this->newQuery()
                ->where($this->keyColumn, '=', $key)
                ->update([$this->valueColumn => $value]);
        }

        if ($insertData) {
            $this->newQuery(true)
                ->insert($this->prepareInsertData($insertData));
        }

        if ($deleteKeys) {
            $this->newQuery()
                ->whereIn($this->keyColumn, $deleteKeys)
                ->delete();
        }

        if (config('core.setting.general.cache.enabled')) {
            try {
                $jsonSettingStore = new JsonSettingStore(new Filesystem);
                $jsonSettingStore->write($data);
            } catch (Exception $exception) {
                info($exception->getMessage());
            }
        }
    }

    /**
     * Transforms settings data into an array ready to be inserted into the
     * database. Call array_dot on a multidimensional array before passing it
     * into this method!
     *
     * @param array $data Call array_dot on a multidimensional array before passing it into this method!
     *
     * @return array
     */
    protected function prepareInsertData(array $data)
    {
        $dbData = [];

        if ($this->extraColumns) {
            foreach ($data as $key => $value) {
                $dbData[] = array_merge(
                    $this->extraColumns,
                    [$this->keyColumn => $key, $this->valueColumn => $value]
                );
            }
        } else {
            foreach ($data as $key => $value) {
                $dbData[] = [$this->keyColumn => $key, $this->valueColumn => $value];
            }
        }

        return apply_filters(SETTINGS_PREPARE_INSERT_DATA, $dbData);
    }

    /**
     * {@inheritDoc}
     * @throws FileNotFoundException
     */
    protected function read()
    {
        if (!$this->connectedDatabase) {
            $this->connectedDatabase = Helper::isConnectedDatabase();
        }

        if (!$this->connectedDatabase) {
            return [];
        }

        if (config('core.setting.general.cache.enabled')) {
            $jsonSettingStore = new JsonSettingStore(new Filesystem);
            if (File::exists($jsonSettingStore->getPath())) {
                $data = $jsonSettingStore->read();
                if (!empty($data)) {
                    return $data;
                }
            }
        }

        $data = $this->parseReadData($this->newQuery()->get());

        if (config('core.setting.general.cache.enabled')) {
            if (!isset($jsonSettingStore)) {
                $jsonSettingStore = new JsonSettingStore(new Filesystem);
            }

            $jsonSettingStore->write($data);
        }

        return $data;
    }

    /**
     * Parse data coming from the database.
     *
     * @param Collection $data
     *
     * @return array
     */
    public function parseReadData($data)
    {
        $results = [];

        foreach ($data as $row) {
            if (is_array($row)) {
                $key = $row[$this->keyColumn];
                $value = $row[$this->valueColumn];
            } elseif (is_object($row)) {
                $key = $row->{$this->keyColumn};
                $value = $row->{$this->valueColumn};
            } else {
                $msg = 'Expected array or object, got ' . gettype($row);
                throw new UnexpectedValueException($msg);
            }

            Arr::set($results, $key, $value);
        }

        return $results;
    }

    /**
     * Create a new query builder instance.
     *
     * @param  $insert  boolean  Whether the query is an insert or not.
     *
     * @return Builder
     */
    protected function newQuery($insert = false)
    {
        $query = $this->connection->table($this->table);

        if (!$insert) {
            foreach ($this->extraColumns as $key => $value) {
                $query->where($key, '=', $value);
            }
        }

        if ($this->queryConstraint !== null) {
            $callback = $this->queryConstraint;
            $callback($query, $insert);
        }

        return $query;
    }
}
