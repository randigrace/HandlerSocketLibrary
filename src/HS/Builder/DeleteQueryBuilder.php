<?php
namespace HS\Builder;

use HS\HSInterface;
use HS\Query\DeleteQuery;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class DeleteQueryBuilder extends AbstractBuilder
{
    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        return $this->constructArray;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery($indexId, $openIndexQuery = null)
    {
        return new DeleteQuery(
            $indexId,
            $this->comparisonOperation,
            $this->where,
            $this->limit,
            $this->offset,
            $openIndexQuery
        );
    }
} 