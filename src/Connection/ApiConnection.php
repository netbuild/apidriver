<?php

namespace Netbuild\Apidriver\Connection;

use Illuminate\Database\Connection;
use Netbuild\Apiconnectionservice\Service;
use Netbuild\Apidriver\Grammar\ApiGrammar;
use Netbuild\Apidriver\Processor\ApiProcessor;

class ApiConnection extends Connection
{
    use Service;

    /**
     * Run a select statement against the api.
     *
     * @param  array  $query
     * @param  array  $bindings
     * @param  bool  $useReadPdo
     * @return array
     */
    public function select($query, $bindings = [], $useReadPdo = true)
    {
        if (empty($query) || empty($query['api'])) {
            return [];
        }

        $query['api_token'] = $this->getModel()->getApiToken();

        // Get api string from query and unset it from query
        $api = $query['api'];
        $url = $this->getModel()->getUrl();

        unset($query['api']);

        // Get flag for get metadata and unset it from query
        $isGetMetaData = ! empty($query['isGetMetaData']) && $query['isGetMetaData'] == 1 ? true : false;
        unset($query['isGetMetaData']);
        
        // Execute get request from api and receive response data
        $data = $this->get($api, $query, $isGetMetaData, $url);
        // Check flag for get metadata
        if ($isGetMetaData) {
            $res['total'] = $data['total'];
            $res['per_page'] = $data['per_page'];
            $res['current_page'] = $data['current_page'];
            $res['last_page'] = $data['last_page'];
            $res['next_page_url'] = $data['next_page_url'];
            $res['prev_page_url'] = $data['prev_page_url'];
            $res['from'] = $data['from'];
            $res['to'] = $data['to'];
            $data = $data['data'];
        }

        // Validate data and set index
        if (!empty($data)) 
        {
            $model = $this->getModel();

            foreach($data as $key => $record)
            {
                if(!is_null($record))
                {
                    $model->$key = $record;
                }
            }

            return $model->toArray();
        }
    }

    /**
     * Run an insert statement against the database.
     *
     * @param  array  $query
     * @param  array   $bindings
     * @return bool
     */
    public function insert($query, $bindings = [])
    {
        if (empty($query) || empty($query['api'])) {
            return [];
        }

        // Set api name then unset it from query array
        $api = $query['api'];
        $url = $this->getModel()->getUrl();
        unset($query['api']);

        // Execute post request and get response
        return $this->post($api, $query, $url) ?? [];
    }

      /**
     * Run an update statement against the database.
     *
     * @param  array  $query
     * @param  array   $bindings
     * @return int
     */
    public function update($query, $bindings = [])
    {
        if (empty($query) || empty($query['api']) || empty($query['id'])) {
            return 0;
        }

        $url = $this->getModel()->getUrl();

        // Get the api name from query, then unset it
        $api = $query['api'];
        unset($query['api']);

        // Get the id value from query, then unset it
        $id = $query['id'];
        unset($query['id']);

        // Execute put request and get response
        $res = $this->put($api, $id, $query, $url);

        return empty($res) ? 0 : 1;
    }

    /**
     * Execute mass update
     *
     * @param string $api
     * @param mixed $ids
     * @param array $values
     * @return void
     */
    public function massUpdate(string $api, $ids, array $values)
    {
        if (empty($api)) {
            return [];
        }

        $url = $this->getModel()->getUrl();
        
        $res = $this->put($api, $ids, $values);
        return $res ?? [];
    }

    /**
     * Execute put & post from incoming models
     *
     * @param string $api
     * @param array $models
     * @return void
     */
    public function batchUpdate(string $api, array $models)
    {
        if (empty($models) || empty($api)) {
            return [];
        }

        $putData = $models['putData'] ?? [];
        $ids = ids($putData);
        $postData = $models['postData'] ?? [];

        $res[] = $this->put($api, $ids, $putData);
        $res[] = $this->post($api, $postData);
        return $res ?? [];
    }

    /**
     * Run a delete statement against the database.
     *
     * @param  string  $query
     * @param  array   $bindings
     * @return int
     */
    public function delete($query, $bindings = [])
    {   
        if (empty($query) || empty($query['api']) || empty($query['id'])) {
            return 0;
        }

        $api = $query['api'];
        $id = $query['id'];

        $res = $this->deleteById($api, $id, $url);
        
        return empty($res) ? 0 : 1;
    }

    /**
     * Get a schema builder instance for the connection.
     *
     * @return \Illuminate\Database\Schema\Builder
     */
    public function getSchemaBuilder()
    {
        return null;
    }

    /**
     * Get the default query grammar instance.
     *
     * @return \App\Database\ApiGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new ApiGrammar);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \Illuminate\Database\Schema\Grammars\Grammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return null;
    }

    /**
     * Get the default post processor instance.
     *
     * @return \App\Database\ApiProcessor
     */
    protected function getDefaultPostProcessor()
    {
        return new ApiProcessor;
    }

    /**
     * Get the Doctrine DBAL driver.
     *
     * @return \Doctrine\DBAL\Driver\PDOMySql\Driver
     */
    protected function getDoctrineDriver()
    {
        return null;
    }
}
