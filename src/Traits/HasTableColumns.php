<?php
/**
 * @author Rafał Tadaszak <r.tadaszak@soit.pl>
 * @copyright (c) soIT.pl (2018-2019)
 * @url http://www.soit.pl
 */

namespace soIT\LaravelSeeders\Traits;

use Illuminate\Support\Facades\DB;
use soIT\LaravelSeeders\Containers\ModelContainer;

trait HasTableColumns
{
    /**
     * @var array Database table columns
     */
    protected $columns;

    /**
     * Set table columns
     */
    protected function getColumns(string $table)
    {
        return DB::getSchemaBuilder()->getColumnListing($table);
    }

    /**
     * Set model available columns
     *
     * @param array $columns
     *
     * @return $this
     */
    public function setColumns(string $table): self
    {
        $this->columns = $this->getColumns($table);

        return $this;
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
}
