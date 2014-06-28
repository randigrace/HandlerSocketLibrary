<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\HSReader;

use HS\Driver;
use HS\Exceptions\WrongParameterException;
use HS\Tests\TestCommon;
use HS\Reader;
use Stream\Stream;

class OpenIndexTest extends TestCommon
{

    public function testStringIndexId()
    {
        $reader = $this->getReader();
        try {
            $reader->openIndex("1", $this->getDatabase(), $this->getTableName(), '', array('text'));
        } catch (WrongParameterException $e) {
            return;
        }

        $this->fail("Not fall string set as indexId.");
    }

    public function testZeroIndexId()
    {
        $reader = $this->getReader();
        try {
            $reader->openIndex(0, $this->getDatabase(), $this->getTableName(), '', array('text'));
        } catch (WrongParameterException $e) {
            return;
        }

        $this->fail("Not fall 0 set as indexId.");
    }

    public function testNegativeIndexId()
    {
        $reader = $this->getReader();
        try {
            $reader->openIndex(-99, $this->getDatabase(), $this->getTableName(), '', array('text'));
        } catch (WrongParameterException $e) {
            return;
        }

        $this->fail("Not fall negative int set as indexId.");
    }

    public function testEmptyDatabase()
    {
        $reader = $this->getReader();
        try {
            $reader->openIndex(1, Stream::STR_EMPTY, $this->getTableName(), '', array('text'));
        } catch (WrongParameterException $e) {
            return;
        }

        $this->fail("Not fall empty string set as databaseName.");
    }

    public function testNullDatabase()
    {
        $reader = $this->getReader();
        try {
            $reader->openIndex(1, null, $this->getTableName(), '', array('text'));
        } catch (WrongParameterException $e) {
            return;
        }

        $this->fail("Not fall null set as databaseName.");
    }

    public function testEmptyTableName()
    {
        $reader = $this->getReader();
        try {
            $reader->openIndex(1, $this->getDatabase(), Stream::STR_EMPTY, '', array('text'));
        } catch (WrongParameterException $e) {
            return;
        }

        $this->fail("Not fall empty string set as tableName.");
    }

    public function testNullTableName()
    {
        $reader = $this->getReader();
        try {
            $reader->openIndex(1, $this->getDatabase(), null, '', array('text'));
        } catch (WrongParameterException $e) {
            return;
        }

        $this->fail("Not fall null set as tableName.");
    }

    public function testIntTableName()
    {
        $reader = $this->getReader();
        try {
            $reader->openIndex(1, $this->getDatabase(), 23, '', array('text'));
        } catch (WrongParameterException $e) {
            return;
        }

        $this->fail("Not fall int set as tableName.");
    }

    public function testNullIndexName()
    {
        $reader = $this->getReader();
        try {
            $reader->openIndex(1, $this->getDatabase(), $this->getTableName(), null, array('text'));
        } catch (WrongParameterException $e) {
            $this->fail("Fall null set as indexName.");
        }
    }

    public function testEmptyIndexName()
    {
        $reader = $this->getReader();
        try {
            $reader->openIndex(1, $this->getDatabase(), $this->getTableName(), Stream::STR_EMPTY, array('text'));
        } catch (WrongParameterException $e) {
            $this->fail("Fall empty string set as indexName.");
        }

    }

    public function testIntIndexName()
    {
        $reader = $this->getReader();
        try {
            $reader->openIndex(1, $this->getDatabase(), $this->getTableName(), 22, array('text'));
        } catch (WrongParameterException $e) {
            return;
        }

        $this->fail("Not fall int set as indexName.");
    }

    public function testStringColumns()
    {
        $reader = $this->getReader();
        try {
            $reader->openIndex(1, $this->getDatabase(), $this->getTableName(), '', "columns");
        } catch (WrongParameterException $e) {
            return;
        }

        $this->fail("Not fall string set as columns.");
    }

    public function testNullColumns()
    {
        $reader = $this->getReader();
        try {
            $reader->openIndex(1, $this->getDatabase(), $this->getTableName(), '', null);
        } catch (WrongParameterException $e) {
            return;
        }

        $this->fail("Not fall null set as columns.");
    }

    public function testEmptyColumns()
    {
        $reader = $this->getReader();
        try {
            $reader->openIndex(1, $this->getDatabase(), $this->getTableName(), '', array());
        } catch (WrongParameterException $e) {
            return;
        }

        $this->fail("Not fall empty array set as columns.");
    }

    public function testColumnsContainObject()
    {
        $reader = $this->getReader();
        try {
            $reader->openIndex(1, $this->getDatabase(), $this->getTableName(), '', array("text", new OpenIndexTest()));
        } catch (WrongParameterException $e) {
            return;
        }

        $this->fail("Not fall object added to array of columns.");
    }

    public function testColumnsContainArray()
    {
        $reader = $this->getReader();
        try {
            $reader->openIndex(1, $this->getDatabase(), $this->getTableName(), '', array("text", array("test")));
        } catch (WrongParameterException $e) {
            return;
        }

        $this->fail("Not fall array added to array of columns.");
    }

    public function testMissedDatabaseSuccessfully()
    {
        $reader = $this->getReader();
        $openIndex = null;
        try {
            $openIndex = $reader->openIndex(1, "randomdatabase", $this->getTableName(), '', array("text"));
        } catch (WrongParameterException $e) {
            $this->fail("Fall with valid parameters.");
        }

        $reader->getResponses();
        $this->assertFalse(
            $openIndex->getResponse()->isSuccessfully(),
            "Successfully openIndex on not existed database."
        );

    }

    public function testMissedDatabaseError()
    {
        $reader = $this->getReader();
        $openIndex = null;
        try {
            $openIndex = $reader->openIndex(1, "randomdatabase", $this->getTableName(), '', array("text"));
        } catch (WrongParameterException $e) {
            $this->fail("Fall with valid parameters.");
        }

        $reader->getResponses();
        $this->assertEquals(
            'HS\Errors\OpenTableError',
            get_class($openIndex->getResponse()->getError()),
            "Error object os not instance of OpenTableError"
        );

    }

    public function testReopenIndex()
    {
        $reader = $this->getReader();
        $openIndex = null;
        $openIndexSecond = null;
        try {
            $openIndex = $reader->openIndex(1, $this->getDatabase(), $this->getTableName(), '', array("text"));
            $openIndexSecond = $reader->openIndex(1, $this->getDatabase(), $this->getTableName(), '', array("text"));
        } catch (WrongParameterException $e) {
            $this->fail("Fall with valid parameters.");
        }

        $reader->getResponses();
        $this->assertTrue($openIndexSecond->getResponse()->isSuccessfully(), "Fall reopen index");

    }
} 