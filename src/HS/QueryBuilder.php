<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS;

use HS\Builder\DecrementQueryBuilder;
use HS\Builder\DeleteQueryBuilder;
use HS\Builder\IncrementQueryBuilder;
use HS\Builder\InsertQueryBuilder;
use HS\Builder\SelectQueryBuilder;
use HS\Builder\UpdateQueryBuilder;

class QueryBuilder
{
    /**
     * @param $columns
     *        "id,text,varchar"
     *
     * @return SelectQueryBuilder
     */
    public static function select(array $columns)
    {
        return new SelectQueryBuilder($columns);
    }

    /**
     * @return DeleteQueryBuilder
     */
    public static function delete()
    {
        return new DeleteQueryBuilder();
    }

    /**
     * @param $columns
     *          ('id' => 1, 'text' => 'example')
     *
     * @return UpdateQueryBuilder
     */
    public static function update(array $columns)
    {
        return new UpdateQueryBuilder($columns);
    }

    /**
     * @return InsertQueryBuilder
     */
    public static function insert()
    {
        return new InsertQueryBuilder();
    }

    /**
     * @param $columns
     *          ('id' => 1, 'num')
     *
     * @return IncrementQueryBuilder
     */
    public static function increment(array $columns)
    {
        return new IncrementQueryBuilder($columns);
    }

    /**
     * @param $columns
     *          ('id' => 1, 'num')
     *
     * @return DecrementQueryBuilder
     */
    public static function decrement(array $columns)
    {
        return new DecrementQueryBuilder($columns);
    }
} 