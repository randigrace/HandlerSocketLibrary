<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Result;

use HS\Error;
use HS\Errors\AuthenticationError;
use HS\Errors\ColumnParseError;
use HS\Errors\CommandError;
use HS\Errors\ComparisonOperatorError;
use HS\Errors\FilterColumnError;
use HS\Errors\FilterTypeError;
use HS\Errors\IndexOverFlowError;
use HS\Errors\InListSizeError;
use HS\Errors\InternalMysqlError;
use HS\Errors\KeyIndexError;
use HS\Errors\KeyLengthError;
use HS\Errors\LockTableError;
use HS\Errors\OpenTableError;
use HS\Errors\ReadOnlyError;
use HS\Query\OpenIndexQuery;
use HS\Query\QueryAbstract;
use HS\Query\QueryInterface;

abstract class ResultAbstract implements ResultInterface
{
    /** @var QueryInterface|null */
    protected $query = null;

    /** @var null|integer */
    protected $code = null;

    /** @var null|\Hs\Error */
    protected $error = null;

    /** @var array|null */
    protected $data = null;

    /** @var double */
    protected $time = 0;

    private $openIndexQuery = null;

    /**
     * @param QueryInterface      $query
     * @param array               $data
     * @param null|OpenIndexQuery $openIndexQuery
     */
    public function __construct(QueryInterface $query, &$data, $openIndexQuery = null)
    {
        $this->openIndexQuery = $openIndexQuery;
        $this->query = $query;
        $code = array_shift($data);
        $this->setCode($code);

        if ($this->code != 0) {
            /* inside data array with indexes:
                0 - always integer 1
                1 - human readable error message
            */
            $error = $data[1];
            switch ($error) {
                case 'cmd':
                case 'syntax':
                case 'notimpl':
                    $this->error = new CommandError($error);
                    break;
                case 'authtype':
                case 'unauth':
                    $this->error = new AuthenticationError($error);
                    break;
                case 'open_table':
                    $this->error = new OpenTableError($error);
                    break;
                case 'tblnum':
                case 'stmtnum':
                    $this->error = new IndexOverFlowError($error);
                    break;
                case 'invalueslen':
                    $this->error = new InListSizeError($error);
                    break;
                case 'filtertype':
                    $this->error = new FilterTypeError($error);
                    break;
                case 'filterfld':
                    $this->error = new FilterColumnError($error);
                    break;
                case 'lock_tables':
                    $this->error = new LockTableError($error);
                    break;
                case 'modop':
                    $this->error = new LockTableError($error);
                    break;
                case 'idxnum':
                    $this->error = new KeyIndexError($error);
                    break;
                case 'kpnum':
                case 'klen':
                    $this->error = new KeyLengthError($error);
                    break;
                case 'op':
                    $this->error = new ComparisonOperatorError($error);
                    break;
                case 'readonly':
                    $this->error = new ReadOnlyError($error);
                    break;
                case 'fld':
                    $this->error = new ColumnParseError($error);
                    break;
                case 'filterblob': // unknown error TODO
                default:
                    // Errors with wrong data
                    if (is_numeric($error)) {
                        $this->error = new InternalMysqlError($error);
                    } else {
                        $this->error = new Error($error);
                    }
                    break;
            }
        }
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function isSuccessfully()
    {
        if ($this->openIndexQuery !== null && !$this->openIndexQuery->getResult()->isSuccessfully()) {
            return false;
        }

        if ($this->code === 0) {
            return true;
        }

        return false;
    }

    /**
     * @return QueryAbstract
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return Error|null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param int $code
     */
    protected function setCode($code)
    {
        $this->code = (int)$code;
    }

    /**
     * @return null|string
     */
    public function getErrorMessage()
    {
        if ($this->error === null) {
            return null;
        }

        return $this->error->getMessage();
    }

    /**
     * @return null|array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param float $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return float
     */
    public function getTime()
    {
        return $this->time;
    }
} 