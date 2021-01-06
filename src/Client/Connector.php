<?php

/**
 *
 * LICENSE: This source file is subject to version 3.01 of the GNU license
 * that is available through the world-wide-web at the following URI:
 * https://www.gnu.org/licenses/gpl-3.0.de.html.  If you did not receive 
 * a copy of the PHP License and are unable to obtain it through the web, 
 * please send a note to license@php.net so we can mail you a copy immediately.
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

namespace Netbuild\Apidriver\Client;

use Illuminate\Database\Connection as BaseConnection;
use Illuminate\Support\Collection;
use Netbuild\Apidriver\Grammar\ApiGrammar;
use Netbuild\Apidriver\Processor\ApiProcessor;
use Illuminate\Support\Facades\Http;

class Connector extends BaseConnection
{
	public function get($connection, $query, $model, $config)
	{	
		if(is_string($model->getUrl()))
		{			
			$response = Http::get($model->getUrl() . $model->getTableName(), [
			    'api_token' => $model->getApiToken(),
			    'wheres' => $query['wheres'],
			]);

			return $response->json();
		}
	}

	public function put($connection, $query, $model, $config)
	{
		if(is_string($model->getUrl()))
		{			
				$response = Http::put($model->getUrl() . $model->getTableName() . '/' . $model->id, [
				    'api_token' => $model->getApiToken(),
				    'wheres' => $query['wheres'],
				]);

				return true;
		}
	}

	public function post($connection, $query, $model, $config)
	{
		// TODO: must be completed
		if(is_string($model->getUrl()))
		{			
				$response = Http::post($model->getUrl() . $model->getTableName(), [
				    'api_token' => $model->getApiToken(),
				    'id' => $model->id
				]);

				return true;
		}
	}

	public function remove($connection, $query, $model, $config)
	{
		// TODO: must be completed
		if(is_string($model->getUrl()))
		{			
				$response = Http::delete($model->getUrl() . $model->getTableName(), [
				    'api_token' => $model->getApiToken(),
				    'id' => $model->id
				]);

				return true;
		}
	}
}