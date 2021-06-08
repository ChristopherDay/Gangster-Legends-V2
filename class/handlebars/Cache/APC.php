<?php
/**
 *
 * @category  Xamin
 * @package   Handlebars
 * @author    Joey Baker <joey@byjoeybaker.com>
 * @author    Behrooz Shabani <everplays@gmail.com>
 * @copyright 2013 (c) Meraki, LLP
 * @copyright 2013 (c) Behrooz Shabani
 * @license   MIT
 * @link      http://voodoophp.org/docs/handlebars
 */

namespace Handlebars\Cache;
use Handlebars\Cache;

class APC implements Cache
{

    /**
     * Get cache for $name if exist.
     *
     * @param string $name Cache id
     *
     * @return mixed data on hit, boolean false on cache not found
     */
    public function get($name)
    {
        if (apc_exists($name)) {
            return apc_fetch($name);
        }
        return false;
    }

    /**
     * Set a cache
     *
     * @param string $name  cache id
     * @param mixed  $value data to store
     *
     * @return void
     */
    public function set($name, $value)
    {
        apc_store($name, $value);
    }

    /**
     * Remove cache
     *
     * @param string $name Cache id
     *
     * @return void
     */
    public function remove($name)
    {
        apc_delete($name);
    }

}
