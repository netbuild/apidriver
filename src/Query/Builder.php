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

namespace Netbuild\Apidriver\Query;

use Illuminate\Database\Query\Builder as BaseBuilder;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;
use Netbuild\Apidriver\Connection;

class Builder extends BaseBuilder
{
    public function __construct(Connection $connection, Grammar $grammar, Processor $processor)
    {
        $this->grammar = $grammar;
        $this->connection = $connection;
        $this->processor = $processor;
    }
}