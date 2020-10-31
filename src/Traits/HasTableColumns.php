<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Traits;

use Illuminate\Support\Facades\DB;

trait HasTableColumns
{
    /**
     * @var string[] Database table columns
     */
    protected array $columns = [];

    /**
     * Set table columns
     * @codeCoverageIgnore
     */
    protected function getColumns(string $table)
    {
        return DB::getSchemaBuilder()->getColumnListing($table);
    }

    /**
     * Set model available columns
     *
     * @param string $table
     *
     * @return $this
     */
    public function setColumns(string $table):self
    {
        $this->columns = $this->getColumns($table) ?? [];

        return $this;
    }

    /**
     * Check is column name exist in set table
     *
     * @param string $property Property/column name
     *
     * @return bool
     */
    protected function isColumnExistInTable(string $property):bool
    {
        return in_array($property, $this->columns);
    }
}
