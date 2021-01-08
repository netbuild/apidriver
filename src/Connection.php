<?php

/**
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU license
 * that is available through the world-wide-web at the following URI:
 * https://www.gnu.org/licenses/gpl-3.0.de.html
 *
 * @category   Development
 * @package    Apidriver
 * @author     Patrick Bohn <patrick.bohn@me.com>
 * @author     Dennis Müller <dm@netbuild.net>
 * @copyright  1998-2021 Net-Build GmbH
 * @license    https://www.gnu.org/licenses/gpl-3.0.de.html  GNU General Public License 3
 * @link       https://github.com/netbuild/apidriver
 *
 **/

namespace Netbuild\Apidriver;

use Netbuild\Apidriver\Client\Connector;
use Illuminate\Database\Connection as BaseConnection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class Connection extends BaseConnection
{
    protected $db;
    protected $connector;
    protected $model;

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * needed if you want to work with yajra's datatables editor, 
     * because the class does not support transactions
     */
    public function beginTransaction()
    {
        return null;        
    }

    /**
     * needed if you want to work with yajra's datatables editor, 
     * because the class does not support transactions
     */
    public function rollBack($toLevel = null)
    {
        return null;
    }

    /**
     * needed if you want to work with yajra's datatables editor, 
     * because the class does not support transactions
     */
    public function commit()
    {
        return null;
    }

    /**
     * Create a new database connection instance.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        // Create the connection
        $this->connector = $this->createConnection($config, []);

        $this->useDefaultPostProcessor();
        $this->useDefaultSchemaGrammar();
        $this->useDefaultQueryGrammar();
    }

    public function select($query, $bindings = [], $useReadPdo = false)
    {
        // Connect to api interface
        if(!is_null($this->model))
        {
            return $this->connector->get(
                $this,
                $query,
                $this->model,
                $this->config
            );
        }
    }

    public function delete($query, $bindings = [])
    {
        // Connect to api interface
        if(!is_null($this->model))
        {
            return $this->connector->remove(
                $this,
                $query,
                $this->model,
                $this->config
            );
        }
    }

    public function affectingStatement($query, $bindings = [])
    {
        // Connect to api interface
        if(!is_null($this->model))
        {
            return $this->connector->put(
                $this,
                $query,
                $this->model,
                $this->config
            );
        }
    }

    public function statement($query, $bindings = [])
    {
        // Connect to api interface
        if(!is_null($this->model))
        {
            return $this->connector->post(
                $this,
                $query,
                $this->model,
                $this->config
            );
        }
    }

    public function query(): QueryBuilder
    {
        return new QueryBuilder($this, $this->getQueryGrammar(), $this->getPostProcessor());
    }

    public function getSchemaBuilder()
    {
        return new Schema\Builder($this);
    }

    protected function createConnection(array $config, array $options)
    {
        return new Connector($config, $options);
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultPostProcessor()
    {
        return new Query\Processor();
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultQueryGrammar()
    {
        return new Query\Grammar();
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultSchemaGrammar()
    {
        return new Schema\Grammar();
    }

    /**
     * Dynamically pass methods to the connection.
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->db, $method], $parameters);
    }
}
