<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

class UpdateQuery extends ModifyQueryAbstract
{
    public function getModificator()
    {
        return 'U';
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryParameters()
    {
        $parameters = parent::getQueryParameters();

        $valueList = $this->getParameter('valueList', array());

        return array_merge($parameters, $valueList);
    }
}