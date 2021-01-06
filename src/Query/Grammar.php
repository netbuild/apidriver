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

namespace Netbuild\Apidriver\Query;

use Illuminate\Database\Query\Grammars\Grammar as BaseGrammar;
use Illuminate\Database\Query\Builder;

class Grammar extends BaseGrammar
{
     /**
     * The components that make up a select clause.
     *
     * @var array
     */
    protected $selectComponents = [
        'aggregate',
        'columns',
        'from',
        'joins',
        'wheres',
        'groups',
        'havings',
        'orders',
        'limit',
        'offset',
        'lock',
    ];

    /**
     * Compile a select query into api condition.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string
     */
    public function compileSelect(Builder $query)
    {
        return [ 
            'from'   => $query->from ?? null,
            'wheres' => $query->wheres ?? null,
            'limit'  => $query->limit ?? null 
        ];
    }

     /**
     * Compile an insert and get ID statement into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array   $values
     * @param  string  $sequence
     * @return array
     */
    public function compileInsertGetId(Builder $query, $values, $sequence)
    {
        if (empty($query) || empty($values) || empty($query->from)) {
            return [];
        }

        // Set api name from query builder
        $conditions['api'] = $query->from;

        // Set attributes for insert
        foreach ($values as $key => $value) {
            $conditions[$key] = $value;
        }

        return $conditions ?? [];
    }

     /**
     * Compile an update statement into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $values
     * @return array
     */
    public function compileUpdate(Builder $query, $values)
    {
        if (empty($query) || empty($values)) {
            return [];
        }
        
        // Get condition for update
        $conditions = $this->compileSelect($query);

        // Get query
        foreach ($values as $key => $value) {
           $conditions[$key] = $value; 
        }

        return $conditions ?? [];
    }

    /**
     * Compile a delete statement into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string
     */
    public function compileDelete(Builder $query)
    {
        if (empty($query)) {
            return [];
        }

        // Get condition for delete
        $conditions = $this->compileSelect($query);

        return $conditions ?? [];
    }
}