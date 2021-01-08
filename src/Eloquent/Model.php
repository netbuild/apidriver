<?php

/**
 *
 * LICENSE: This source file is subject to version 3.01 of the GNU license
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

namespace Netbuild\Apidriver\Eloquent;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Netbuild\Apidriver\Query\Builder as QueryBuilder;
use Netbuild\Apidriver\Eloquent\Builder;

abstract class Model extends BaseModel
{
    protected $guarded = [];

    /**
     * @inheritDoc Illuminate\Database\Eloquent\Concerns\HasTimestamps
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @inheritdoc
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    /**
     * @inheritdoc
     */
    protected function newBaseQueryBuilder()
    {
        $connection = $this->getConnection();
        $connection->setModel($this);

        return new QueryBuilder(
            $connection, 
            $connection->getQueryGrammar(),
            $connection->getPostProcessor()
        );
    }

    /**
     * Validate attributes before return it via toArray method or create a model instance of it
     *
     * @return bool
     */
    public function validate() : bool
    {
        return (empty($this->is_valid)) ? true : $this->is_valid == 1;
    }

    /**
     * Get the table qualified key name.
     *
     * @return string
     */
    public function getQualifiedKeyName()
    {
        return $this->getKeyName();
    }

    /**
     * @inheritdoc
     */
    public function __call($method, $parameters)
    {
        // Unset method
        if ($method == 'unset') 
        {
            return call_user_func_array([$this, 'drop'], $parameters);
        }

        return parent::__call($method, $parameters);
    }

    public function getApiToken()
    {
        return $this->api_token;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getTableName()
    {
        return $this->table;
    }

    public function setApiToken($api_token)
    {
        $this->api_token = $api_token;

        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
}