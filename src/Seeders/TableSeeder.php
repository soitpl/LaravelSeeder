<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use soIT\LaravelSeeders\Enums\Duplicated;
use soIT\LaravelSeeders\Exceptions\ColumnNotFoundException;
use soIT\LaravelSeeders\Exceptions\SeedTargetFoundException;
use soIT\LaravelSeeders\Seeders\Features\SeederAdditionalPropertiesTrait;

class TableSeeder extends SeederAbstract
{
    use SeederTransformationsTrait, SeederTranslationsTrait, SeederAdditionalPropertiesTrait;

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
        if ($this->isTableExists($table)) {
            $this->tableName = $table;
            $this->setColumns();
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
    protected function isTableExists(string $table): bool
    {
        return in_array(
            $table,
            DB::connection()
                ->getDoctrineSchemaManager()
                ->listTableNames()
        );
    }

    /**
     * Set table columns
     */
    private function setColumns()
    {
        $this->columns = DB::getSchemaBuilder()->getColumnListing($this->tableName);
    }

    /**
     * Get array with defined unique columns
     *
     * @return array Array with defined unique columns
     */
    public function getUniqueColumns(): array
    {
        return $this->uniqueColumns;
    }

    /**
     * Set columns which will be unique in table and can be use to detect duplicated records
     *
     * @param string[] $keys
     *
     * @return TableSeeder
     * @throws ColumnNotFoundException
     */
    public function setUniqueKeys(array $keys): self
    {
        $this->uniqueColumns = [];

        foreach ($keys as $key) {
            $this->setUniqueColumn($key);
        }

        return $this;
    }

    /**
     * @param string $column
     *
     * @throws ColumnNotFoundException
     */
    private function setUniqueColumn(string $column)
    {
        if ($this->isColumnExistInTable($column)) {
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
    private function isColumnExistInTable(string $property): bool
    {
        return in_array($property, $this->columns);
    }

    /**
     * Get table name
     *
     * @return string Table name
     */
    public function getName(): string
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
        $insertData = $this->getInsertData();

        switch ($this->duplicated) {
            case Duplicated::IGNORE:
                return $this->saveAndIgnoreDuplicated($insertData);
                break;
            case Duplicated::UPDATE:
                return $this->saveOrUpdateDuplicated($insertData);
                break;
            default:
                return $this->create($insertData);
        }
    }

    /**
     * Make input data
     *
     * @return array
     */
    private function getInsertData(): array
    {
        $inputData = [];
        foreach ($this->data as $property => $value) {
            $targetProperty = $this->getTargetProperty($property);

            if ($targetProperty) {
                $inputData[$targetProperty] = $this->transformations
                    ? $this->transformations->getValue($property, $this->data[$property])
                    : $this->data[$property];
            }
        }

        return array_merge($inputData, $this->getAdditionalPropertiesInsertData());
    }

    /**
     * @param string $property
     *
     * @return string
     */
    private function getTargetProperty(string $property): string
    {
        return $this->isColumnExistInTable($property) ? $property : $this->getTranslations()->get($property);
    }

    /**
     * Save record and ignore save if record already exists
     *
     * @param array $data Data to save
     *
     * @return int|null
     */
    private function saveAndIgnoreDuplicated(array $data): ?int
    {
        if ($record = $this->getRecord($data)) {
            return $record->id ?? null;
        } else {
            return $this->create($data);
        }
    }

    /**
     * Get record identified by unique columns
     *
     * @param array $data
     *
     * @return Model|Builder|object|null
     */
    private function getRecord(array $data)
    {
        return $this->getQueryBuilder($data)->first();
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function getDataForSearch(array $data): array
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
    private function saveOrUpdateDuplicated(array $data): bool
    {
        return $this->getRecord($data) ? $this->update($data) : $this->create($data);
    }

    /**
     * Save record to database
     *
     * @param array $data Data to save
     *
     * @return int Record ID
     */
    private function create(array $data): int
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
    private function update(array $data): bool
    {
        return $this->getQueryBuilder($data)->update($data);
    }

    /**
     * Get query builder for record identified by unique keys
     *
     * @param array $data
     *
     * @return Builder
     */
    private function getQueryBuilder(array $data): Builder
    {
        return DB::table($this->tableName)->{$this->buildDynamicWhere()}(...$this->getDataForSearch($data));
    }

    /**
     * Build dynamic where for search record in database
     *
     * @return string
     */
    private function buildDynamicWhere(): string
    {
        return 'where' .
            implode(
                'And',
                array_map(function ($item) {
                    return ucfirst($item);
                }, $this->uniqueColumns)
            );
    }
}
