<?php
/**
 *
 * @category  Xamin
 * @package   Handlebars
 * @author    fzerorubigd <fzerorubigd@gmail.com>
 * @author    Behrooz Shabani <everplays@gmail.com>
 * @copyright 2012 (c) ParsPooyesh Co
 * @copyright 2013 (c) Behrooz Shabani
 * @copyright 2014 (c) Mardix
 * @license   MIT
 * @link      http://voodoophp.org/docs/handlebars
 */

namespace Handlebars;

class Autoloader
{

    private $_baseDir;

    /**
     * Autoloader constructor.
     *
     * @param string $baseDir Handlebars library base directory default is
     *                        __DIR__.'/..'
     */
    protected function __construct($baseDir = null)
    {
        if ($baseDir === null) {
            $this->_baseDir = realpath(__DIR__ . '/..');
        } else {
            $this->_baseDir = rtrim($baseDir, '/');
        }
    }

    /**
     * Register a new instance as an SPL autoloader.
     *
     * @param string $baseDir Handlebars library base directory, default is
     *                        __DIR__.'/..'
     *
     * @return \Handlebars\Autoloader Registered Autoloader instance
     */
    public static function register($baseDir = null)
    {
        $loader = new self($baseDir);
        spl_autoload_register(array($loader, 'autoload'));

        return $loader;
    }

    /**
     * Autoload Handlebars classes.
     *
     * @param string $class class to load
     *
     * @return void
     */
    public function autoload($class)
    {
        if ($class[0] === '\\') {
            $class = substr($class, 1);
        }

        if (strpos($class, 'Handlebars') !== 0) {
            return;
        }

        $file = sprintf('%s/%s.php', $this->_baseDir, str_replace('\\', '/', $class));

        if (is_file($file)) {
            include $file;
        }
    }

}
