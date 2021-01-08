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
			$response = Http::withHeaders([
			    'Accept' => 'application/json',
			    'Authorization' => sprintf('Bearer %s', $model->getApiToken()),
			    'User-Agent' => sprintf("%s %s", 
			    	config('app.client.user-agent') ?? 'Laravel Framework', 
			    	config('app.version') ?? '0.0.0'
			    )
			])->get(sprintf('%s/%s', 
				$model->getUrl(), 
				$model->getTableName()
			), [
			    'wheres' => $query['wheres']
			]);

			$response->throw();

			return $response->json();
		}
	}

	public function put($connection, $query, $model, $config)
	{
		if(is_string($model->getUrl()))
		{			
			$response = Http::withHeaders([
			    'Accept' => 'application/json',
			    'Authorization' => sprintf('Bearer %s', $model->getApiToken()),
			    'User-Agent' => sprintf("%s %s", 
			    	config('app.client.user-agent') ?? 'Laravel Framework', 
			    	config('app.version') ?? '0.0.0'
			    )
			])->put(sprintf('%s/%s/%s', 
				$model->getUrl(), 
				$model->getTableName(), 
				$model->id
			), 
		    	$model->getAttributes()
			);

			$response->throw();

			return $response->json();
		}
	}

	public function post($connection, $query, $model, $config)
	{
		if(is_string($model->getUrl()))
		{			
			$response = Http::withHeaders([
			    'Accept' => 'application/json',
			    'Authorization' => sprintf('Bearer %s', $model->getApiToken()),
			    'User-Agent' => sprintf("%s %s", 
			    	config('app.client.user-agent') ?? 'Laravel Framework', 
			    	config('app.version') ?? '0.0.0'
			    )
			])->post(sprintf('%s/%s', 
				$model->getUrl(), 
				$model->getTableName(), 
			), 
		    	$model->getAttributes()
			);

			$response->throw();	

			return $response->json();
		}
	}

	public function remove($connection, $query, $model, $config)
	{
		if(is_string($model->getUrl()))
		{			
			$response = Http::withHeaders([
			    'Accept' => 'application/json',
			    'Authorization' => sprintf('Bearer %s', $model->getApiToken()),
			    'User-Agent' => sprintf("%s %s", 
			    	config('app.client.user-agent') ?? 'Laravel Framework', 
			    	config('app.version') ?? '0.0.0'
			    )
			])->delete(sprintf('%s/%s/%s', 
				$model->getUrl(), 
				$model->getTableName(), 
				$model->id
			));

			$response->throw();

			return $response->json();
		}
	}
}