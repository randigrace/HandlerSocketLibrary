<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

use HS\Result\UpdateResult;
use \HS\Tests\TestCommon;

class UpdateTest extends TestCommon
{
    public function testSingleUpdate()
    {
        $writer = $this->getWriter();

        $indexId = $writer->getIndexId(
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'text')
        );
        $updateRequest = $writer->updateByIndex($indexId, '=', array(2), array(2, 'new'));

        $selectQuery = $writer->selectByIndex($indexId, '=', array(2));
        $writer->getResults();

        /** @var UpdateResult $updateResult */
        $updateResult = $updateRequest->getResult();
        $this->assertTrue($updateResult->isSuccessfully(), "Fall updateQuery return bad status.");
        $this->assertTrue($selectQuery->getResult()->isSuccessfully(), "Fall selectQuery return bad status.");

        $this->assertTrue($updateResult->getNumberModifiedRows() > 0, "Fall updateQuery didn't modified rows.");

        $data = $selectQuery->getResult()->getData();

        $this->assertEquals('new', $data[0]['text']);
    }
} 