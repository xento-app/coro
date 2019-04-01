<?php
/**
 * @name AppConnection
 * @version 1.0.0
 * @author Miroslaw Kukuryka
 * @copyright (c) 2019 (https://www.xento-app)
 * @link https://www.xento.app
 */
namespace Xento;

class AppConnection
{

    /**
     *
     * @var string
     */
    private static $_addressIp = '127.0.0.1';

    /**
     *
     * @var string
     */
    private static $_deviceConnection = 'none';

    /**
     *
     * @var string
     */
    private static $_requestUri;

    /**
     *
     * @var string
     */
    private static $_requestUriProtocol = 'http';

    /**
     *
     * @var string
     */
    private static $_userAgent;

    /**
     *
     * @var int
     */
    private static $_requestHeaderCode = 200;

    /**
     *
     * @var string
     */
    private static $_requestMethodType = 'POST';

    /**
     *
     * @var string
     */
    private static $_requestUriModule = 'application';

    /**
     *
     * @var string
     */
    private static $_requestUriController = 'index';

    /**
     *
     * @var string
     */
    private static $_requestUriAction = 'index';

    /**
     *
     * @var string
     */
    private static $_operationSystemName = 'undefined';

    /**
     *
     * @var string
     */
    private static $_operationSystemVersion = 'undefined';

    /**
     *
     * @var array
     */
    private static $_requestParamsPost = [];

    /**
     *
     * @var array
     */
    private static $_requestParamsGet = [];

    /**
     *
     * @var array
     */
    private static $_paramsConnection = [];

    /**
     *
     * @name setAddressIp
     * @access public
     * @param string $value
     */
    public static function setAddressIp($value)
    {
        self::$_addressIp = $value;
    }

    /**
     *
     * @name getAddressIp
     * @access public
     * @return mixed
     */
    public static function getAddressIp()
    {
        return self::$_addressIp;
    }

    /**
     *
     * @name setDeviceConnection
     * @access public
     * @param string $value
     */
    public static function setDeviceConnection($value)
    {
        self::$_deviceConnection = $value;
    }

    /**
     *
     * @name getDeviceConnection
     * @access public
     * @return string
     */
    public static function getDeviceConnection()
    {
        return self::$_deviceConnection;
    }

    /**
     *
     * @name setRequestUri
     * @access public
     * @param string $value
     */
    public static function setRequestUri($value)
    {
        self::$_requestUri = $value;
    }

    /**
     *
     * @name getRequestUri
     * @access public
     * @return string
     */
    public static function getRequestUri()
    {
        return self::$_requestUri;
    }

    /**
     *
     * @name setRequestUriProtocol
     * @access public
     * @param string $value
     */
    public static function setRequestUriProtocol($value)
    {
        self::$_requestUriProtocol = strtolower($value);
    }

    /**
     *
     * @name getRequestUriProtocol
     * @access public
     * @return string
     */
    public static function getRequestUriProtocol()
    {
        return self::$_requestUriProtocol;
    }

    /**
     *
     * @name setUserAgent
     * @access public
     * @param string $value
     */
    public static function setUserAgent($value)
    {
        self::$_userAgent = $value;
    }

    /**
     *
     * @name getUserAgent
     * @access public
     * @return string
     */
    public static function getUserAgent()
    {
        return self::$_userAgent;
    }

    /**
     *
     * @name setRequestHeaderCode
     * @access public
     * @param int $value
     */
    public static function setRequestHeaderCode($value)
    {
        self::$_requestHeaderCode = (int) $value;
    }

    /**
     *
     * @name getRequestHeaderCode
     * @access public
     * @return int
     */
    public static function getRequestHeaderCode()
    {
        return self::$_requestHeaderCode;
    }

    /**
     *
     * @name setRequestMethodType
     * @access public
     * @param string $value
     */
    public static function setRequestMethodType($value)
    {
        self::$_requestMethodType = strtoupper($value);
    }

    /**
     *
     * @name getRequestMethodType
     * @access public
     * @return string
     */
    public static function getRequestMethodType()
    {
        return self::$_requestMethodType;
    }

    /**
     *
     * @name setRequestUriModule
     * @access public
     * @param string $value
     */
    public static function setRequestUriModule($value)
    {
        self::$_requestUriModule = strtolower($value);
    }

    /**
     *
     * @name getRequestUriModule
     * @access public
     * @return string
     */
    public static function getRequestUriModule()
    {
        return self::$_requestUriModule;
    }

    /**
     *
     * @name setRequestUriController
     * @access public
     * @param string $value
     */
    public static function setRequestUriController($value)
    {
        self::$_requestUriController = $value;
    }

    /**
     *
     * @name getRequestUriController
     * @access public
     * @return string
     */
    public static function getRequestUriController()
    {
        return self::$_requestUriController;
    }

    /**
     *
     * @name setRequestUriAction
     * @access public
     * @param string $value
     */
    public static function setRequestUriAction($value)
    {
        self::$_requestUriController = $value;
    }

    /**
     *
     * @name getRequestUriAction
     * @access public
     * @return string
     */
    public static function getRequestUriAction()
    {
        return self::$_requestUriController;
    }

    /**
     *
     * @name setOperationSystemName
     * @access public
     * @param string $value
     */
    public static function setOperationSystemName($value)
    {
        self::$_operationSystemName = $value;
    }

    /**
     *
     * @name getOperationSystemName
     * @access public
     * @return string
     */
    public static function getOperationSystemName()
    {
        return self::$_operationSystemName;
    }

    /**
     *
     * @name setOperationSystemVersion
     * @access public
     * @param string $value
     */
    public static function setOperationSystemVersion($value)
    {
        self::$_operationSystemVersion = $value;
    }

    /**
     *
     * @name getOperationSystemVersion
     * @access public
     * @return string
     */
    public static function getOperationSystemVersion()
    {
        return self::$_operationSystemVersion;
    }

    /**
     *
     * @name hasRequestParamPost
     * @access private
     * @param string $key
     * @return bool
     */
    private static function hasRequestParamPost($key)
    {
        if (isset(self::$_requestParamsPost[$key])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @name addRequestParamPost
     * @access public
     * @param string $key
     * @param
     *            $value
     */
    public static function addRequestParamPost($key, $value)
    {
        if (self::hasRequestParamPost($key) !== true) {
            self::$_requestParamsPost[$key] = $value;
        }
    }

    /**
     *
     * @name getRequestParamsPost
     * @access public
     * @return array
     */
    public static function getRequestParamsPost()
    {
        return self::$_requestParamsGet;
    }

    /**
     *
     * @name getRequestParamPost
     * @access public
     * @param string $key
     * @return array
     */
    public static function getRequestParamPost($key)
    {
        if (self::hasRequestParamPost($key) !== true) {
            return self::$_requestParamsGet;
        }
    }

    /**
     *
     * @name hasRequestParamGet
     * @access private
     * @param string $key
     * @return bool
     */
    private static function hasRequestParamGet($key)
    {
        if (isset(self::$_requestParamsGet[$key])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @name addRequestParamGet
     * @access public
     * @param string $key
     * @param string $value
     */
    public static function addRequestParamGet($key, $value)
    {
        if (self::hasRequestParamGet($key) !== true) {
            self::$_requestParamsGet[$key] = $value;
        }
    }

    /**
     *
     * @name getRequestParamsGet
     * @access public
     * @return array
     */
    public static function getRequestParamsGet()
    {
        return self::$_requestParamsGet;
    }

    /**
     *
     * @access public
     * @param string $key
     * @return mixed
     */
    public static function getRequestParamGet($key)
    {
        if (self::hasRequestParamGet($key) !== true) {
            return self::$_requestParamsGet[$key];
        }
    }

    /**
     *
     * @name prepareParamsConnection
     * @access private
     */
    private static function prepareParamsConnection()
    {
        self::addParamConnection('datetime', date('Y-m-d H:i:s'));
        self::addParamConnection('address_ip', self::getAddressIp());
        self::addParamConnection('device_connection', self::getDeviceConnection());
        self::addParamConnection('request_uri', self::getRequestUri());
        self::addParamConnection('request_uri_protocol', self::getRequestUriProtocol());
        self::addParamConnection('user_agent', self::getUserAgent());
        self::addParamConnection('request_header_code', self::getRequestHeaderCode());
        self::addParamConnection('request_method_type', self::getRequestMethodType());
        self::addParamConnection('request_uri_module', self::getRequestUriModule());
        self::addParamConnection('request_uri_controller', self::getRequestUriController());
        self::addParamConnection('request_uri_action', self::getRequestUriAction());
        self::addParamConnection('operation_system_name', self::getOperationSystemName());
        self::addParamConnection('operation_system_version', self::getOperationSystemVersion());
        self::addParamConnection('request_params_post', self::getRequestParamsPost());
        self::addParamConnection('request_params_get', self::getRequestParamsGet());
    }

    /**
     *
     * @name hasParamConnection
     * @access public
     * @param string $key
     * @return bool
     */
    public static function hasParamConnection($key)
    {
        if (isset(self::$_paramsConnection[$key])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @name addParamConnection
     * @access public
     * @param string $key
     * @param string $value
     */
    public static function addParamConnection($key, $value)
    {
        if (self::hasParamConnection($key) !== true) {
            self::$_paramsConnection[$key] = $value;
        }
    }

    /**
     *
     * @name removeParamConnection
     * @access public
     * @param string $key
     */
    public static function removeParamConnection($key)
    {
        if (self::hasParamConnection($key) !== false) {
            unset(self::$_paramsConnection[$key]);
        }
    }

    /**
     *
     * @name removeAllParamsConnection
     * @access public
     */
    public static function removeAllParamsConnection()
    {
        if (sizeof(self::$_paramsConnection)) {
            self::$_paramsConnection = [];
        }
    }

    /**
     *
     * @name getParamConnection
     * @access public
     * @param string $key
     * @return mixed
     */
    public static function getParamConnection($key)
    {
        if (self::hasParamConnection($key) !== false) {
            return self::$_paramsConnection[$key];
        }
    }

    /**
     *
     * @name getAllParamsConnection
     * @access public
     * @return array
     */
    public static function getAllParamsConnection()
    {
        self::prepareParamsConnection();
        return self::$_paramsConnection;
    }

    /**
     *
     * @name getallheaders
     * @access public
     * @link http://php.net/manual/pl/function.getallheaders.php
     * @return array
     */
    public static function getallheaders()
    {
        if (! function_exists('getallheaders')) {
            $headers = [];
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
            return $headers;
        } else {
            return getallheaders();
        }
    }
}
