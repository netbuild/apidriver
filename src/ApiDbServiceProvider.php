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

namespace Netbuild\Apidriver;

use Illuminate\Support\ServiceProvider;
use Netbuild\Apidriver\Eloquent\Model;
use Netbuild\Apidriver\Connection;

class ApiDbServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        Model::setConnectionResolver($this->app['db']);
        Model::setEventDispatcher($this->app['events']);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        // Add database driver.
        $this->app->resolving('db', function ($db) {
            $db->extend('api', function ($config, $name) {
                $config['name'] = $name;
                return new Connection($config);
            });
        });
    }
}