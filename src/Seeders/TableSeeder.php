<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl  2018-2019
 */

namespace soIT\LaravelSeeders\Seeders;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use soIT\LaravelSeeders\Enums\Duplicated;
use soIT\LaravelSeeders\Exceptions\ColumnNotFoundException;
use soIT\LaravelSeeders\Exceptions\SeedTargetFoundException;

class TableSeeder extends SeederAbstract
{
    use SeederTransformationsTrait, SeederTranslationsTrait;

    /**
     * @var array Database table columns
     */
    protected $columns;

    /**
     * @var array Unique columns in table
     */
    protected $uniqueColumns = [];

    /**
     * @var string Table name
     */
    protected $tableName;

    /**
     * ModelDispatcher constructor.
     *
     * @param string $table
     *
     * @throws SeedTargetFoundException
     */
    public function __construct(string $table)
    {
        $this->setTable($table);
    }

    /**
     * Set get table for seed operation
     *
     * @param string $table
     *
     * @throws SeedTargetFoundException
     */
    public function setTable(string $table)
    {
        if ($this->_isTableExists($table)) {
            $this->tableName = $table;
            $this->_setColumns();
        } else {
            throw new SeedTargetFoundException(sprintf("%s table was't found for seed", ucfirst($table)));
        }
    }

    /**
     * Check is table exists in database
     *
     * @param string $table Table to check
     *
     * @return bool
     */
    private function _isTableExists(string $table):bool
    {
        switch (DB::connection()->getDriverName()) {
            case 'sqlite':
                $tables = array_map('current', DB::select("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;"));
            break;
            default:
                $tables = array_map('current', DB::select('SHOW TABLES'));
        }

        return in_array($table, $tables);
    }

    /**
     * Set table columns
     */
    private function _setColumns()
    {
        $this->columns = DB::getSchemaBuilder()->getColumnListing($this->tableName);
    }

    /**
     * Get array with defined unique columns
     *
     * @return array Array with defined unique columns
     */
    public function getUniqueColumns():array
    {
        return $this->uniqueColumns;
    }

    /**
     * Set columns which will be unique in table and can be use to detect duplicated records
     *
     * @param string[] $keys
     *
     * @return TableSeeder
     * @throws SeedTargetFoundException
     */
    public function setUniqueKeys(array $keys):self
    {
        $this->uniqueColumns = [];

        foreach ($keys as $key) {
            $this->_setUniqueColumn($key);
        }

        return $this;
    }

    /**
     * @param string $column
     *
     * @throws ColumnNotFoundException
     */
    private function _setUniqueColumn(string $column)
    {
        if ($this->_isColumnExistInTable($column)) {
            array_push($this->uniqueColumns, $column);
        } else {
            throw new ColumnNotFoundException(sprintf("%s column was't found for seed", ucfirst($column)));
        }
    }

    /**
     * Check is column name exist in set table
     *
     * @param string $property Property/column name
     *
     * @return bool
     */
    private function _isColumnExistInTable(string $property):bool
    {
        return in_array($property, $this->columns);
    }

    /**
     * Get table name
     *
     * @return string Table name
     */
    public function getName():string
    {
        return $this->tableName;
    }

    /**
     * Create and save new model in database
     *
     * @return bool
     */
    public function save()
    {
        $insertData = $this->_getInsertData();

        switch ($this->duplicated) {
            case Duplicated::IGNORE:
                return $this->_saveAndIgnoreDuplicated($insertData);
            break;
            case Duplicated::UPDATE:
                return $this->_saveOrUpdateDuplicated($insertData);
            break;
            default:
                return $this->_create($insertData);
        }
    }

    /**
     * Make input data
     *
     * @return array
     */
    private function _getInsertData():array
    {
        $inputData = [];
        foreach ($this->data as $property => $value) {
            $targetProperty = $this->_getTargetProperty($property);

            if ($targetProperty) {
                $inputData[$targetProperty] = $this->transformations ? $this->transformations->getValue($property, $this->data[$property]) : $this->data[$property];
            }
        }

        return $inputData;
    }

    /**
     * @param string $property
     *
     * @return string
     */
    private function _getTargetProperty(string $property):string
    {
        return $this->_isColumnExistInTable($property) ? $property : $this->getTranslations()->get($property);
    }

    /**
     * Save record and ignore save if record already exists
     *
     * @param array $data Data to save
     *
     * @return int|null
     */
    private function _saveAndIgnoreDuplicated(array $data):?int
    {
        if ($record = $this->_getRecord($data)) {
            return $record->id ?? null;
        } else {
            return $this->_create($data);
        }
    }

    /**
     * Get record identified by unique columns
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model|Builder|object|null
     */
    private function _getRecord(array $data)
    {
        return $this->_getQueryBuilder($data)->first();
    }

    private function _getDataForSearch(array $data):array
    {
        $out = [];
        foreach ($this->uniqueColumns as $column) {
            $out[] = $data[$column];
        }

        return $out;
    }

    /**
     * Save record or edit if already exist in database
     *
     * @param array $data
     *
     * @return bool
     */
    private function _saveOrUpdateDuplicated(array $data):bool
    {
        if ($record = $this->_getRecord($data)) {
            return $this->_update($data);
        } else {
            return $this->_create($data);
        }
    }

    /**
     * Save record to database
     *
     * @param array $data Data to save
     *
     * @return int Record ID
     */
    private function _create(array $data):int
    {
        return DB::table($this->tableName)->insertGetId($data);
    }

    /**
     * Update record in database
     *
     * @param array $data
     *
     * @return bool
     */
    private function _update(array $data):bool
    {
        return $this->_getQueryBuilder($data)->update($data);
    }

    /**
     * Get query builder for record identified by unique keys
     *
     * @param array $data
     *
     * @return Builder
     */
    private function _getQueryBuilder(array $data):Builder
    {
        return DB::table($this->tableName)->{$this->_buildDynamicWhere()}(...$this->_getDataForSearch($data));
    }

    /**
     * Build dynamic where for search record in database
     *
     * @return string
     */
    private function _buildDynamicWhere():string
    {
        return 'where'.implode('And', array_map(function ($item) {
                return ucfirst($item);
            }, $this->uniqueColumns));
    }
}
