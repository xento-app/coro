<?php
/**
 * @name AppCore
 * @version 1.0.0
 * @author Miroslaw Kukuryka
 * @copyright (c) 2019 (https://www.xento.app)
 * @link https://www.xento.app
 */
namespace Xento;

class AppCore
{

    /**
     *
     * @var string
     */
    private static $_version = '1.0.0';

    /**
     *
     * @var string
     */
    private static $_versionInfo = 'Xento Application';

    /**
     *
     * @var array
     */
    private static $_registry = [];

    /**
     *
     * @name getVersion
     * @access public
     * @return string
     */
    public static function getVersion()
    {
        return self::$_version;
    }

    /**
     *
     * @name getVersionInfo
     * @access public
     * @return string
     */
    public static function getVersionInfo()
    {
        return self::$_versionInfo . ' ' . self::$_version;
    }

    /**
     *
     * @name register
     * @access public
     * @param string $key
     * @param mixed $value
     */
    public static function register($key, $value)
    {
        if (! isset(self::$_registry[$key])) {
            self::$_registry[$key] = serialize($value);
        }
    }

    /**
     *
     * @name unregister
     * @access public
     * @param string $key
     */
    public static function unregister($key)
    {
        if (isset(self::$_registry[$key])) {
            if (is_object(self::$_registry[$key])) {
                self::$_registry[$key]->__destruct();
            }
            unset(self::$_registry[$key]);
        }
    }

    /**
     *
     * @name registry
     * @access public
     * @param string $key
     * @return array|NULL
     */
    public static function registry($key)
    {
        if (isset(self::$_registry[$key])) {
            return unserialize(self::$_registry[$key]);
        }
        return null;
    }

    /**
     *
     * @name registryAll
     * @access public
     * @return array|NULL
     */
    public static function registryAll()
    {
        if (sizeof(self::$_registry)) {
            return self::$_registry;
        }
        return null;
    }

    /**
     *
     * @name getHostProtocol
     * @access public
     * @return string
     */
    public static function getHostProtocol()
    {
        return isset($_SERVER['HTTPS']) ? 'https' : 'http';
    }

    /**
     *
     * @name getHost
     * @access public
     * @return string
     */
    public static function getHost()
    {
        $server = $_SERVER['HTTP_HOST'];
        $path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
        return self::getHostProtocol() . '://' . $server . $path;
    }

    /**
     *
     * @name isSsl
     * @access public
     * @return boolean
     */
    public static function isSsl()
    {
        if (isset($_SERVER['HTTPS'])) {
            return true;
        }
        return false;
    }

    /**
     *
     * @name getHostTld
     * @access public
     * @return string
     */
    public static function getHostTld()
    {
        $_host = explode('.', self::getHost());
        if (is_array($_host)) {
            if (sizeof($_host) > 1) {
                return trim(str_replace('/', '', end($_host)));
            }
        }
        return 'localhost';
    }

    /**
     *
     * @name IsOnline
     * @access public
     * @param string $value
     * @return bool
     */
    public static function IsOnline($value)
    {
        if (! parse_url($value)) {
            return false;
        }
    }
    
    /**
     * @name isSendMail
     * @access public
     * @return boolean
     */
    public static function isSendMail():bool
    {
        $isSendMail = true;
        if (ini_get('SMTP') == 'localhost') {
            if (self::getHostTld() == 'localhost') {
                $isSendMail = false;
            }
        }
        
        return $isSendMail;
    }
}
