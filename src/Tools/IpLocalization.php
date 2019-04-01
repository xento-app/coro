<?php
/**
 * @name IpLocalization
 * @version 1.0.0
 * @author Miroslaw Kukuryka
 * @copyright (c) 2018 (https://www.appsonline.eu)
 * @link https://www.appsonline.eu
 */
namespace Xento\Tools;

class IpLocalization
{

    /**
     *
     * @var string
     */
    private static $hostApi = 'https://ipapi.co/';

    /**
     *
     * @var string
     */
    private $format = 'json';

    /**
     *
     * @var array
     */
    private $errors = [];

    /**
     *
     * @name getHostApi
     *       @acces public
     * @return string
     */
    public static function getHostApi()
    {
        return self::$hostApi;
    }

    /**
     *
     * @name setFormat
     * @access public
     */
    public function setFormat($value)
    {
        $this->format = strtolower($value);
    }

    /**
     *
     * @name hasError
     * @access private
     * @return boolean
     */
    private function hasError()
    {
        if (sizeof($this->errors)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @name getErrors
     * @access private
     * @return array
     */
    private function getErrors()
    {
        return $this->errors;
    }

    /**
     *
     * @name getFormat
     * @access public
     * @return string|boolean
     */
    public function getFormat()
    {
        if (in_array($this->format, [
            'csv',
            'yaml',
            'xml',
            'json',
            'jsonp'
        ])) {
            return $this->format;
        } else {
            $this->errors[] = 'Invalid format ' . $this->format;
            return false;
        }
    }

    /**
     *
     * @name _csv
     * @access private
     * @return object
     */
    private function _csv($data)
    {
        $tmp = explode("\n", $data);

        $obj = new \stdClass();
        $keys = [];

        if (isset($tmp[0])) {
            $_keys = explode(',', $tmp[0]);
            foreach ($_keys as $p => $value) {
                $keys[$p] = $value;
            }
        }

        if (isset($tmp[1])) {
            $_data = explode(',', $tmp[1]);
            foreach ($_data as $p => $value) {
                $obj->{$keys[$p]} = $value;
            }
        }

        return $obj;
    }

    /**
     *
     * @name _yaml
     * @access private
     * @return object
     */
    private function _yaml($data)
    {
        $obj = new \stdClass();
        if (extension_loaded('yaml')) {
            $tmp = yaml_parse($data);
            foreach ($tmp as $key => $value) {
                $obj->$key = $value;
            }
        } else {
            $tmp = explode("\n", $data);
            foreach ($tmp as $item) {
                $value = explode(': ', $item);
                if (isset($value[0]) && isset($value[1])) {
                    $obj->{$value[0]} = $value[1];
                }
            }
        }
        return $obj;
    }

    /**
     *
     * @name _xml
     * @access private
     * @param string $data
     * @return object|boolean
     */
    private function _xml($data)
    {
        if (extension_loaded('simplexml')) {
            $xml = simplexml_load_string($data);
            return json_decode(json_encode($xml));
        } else {
            $this->errors[] = 'No instaled extension simplexml';
        }
        return false;
    }

    /**
     *
     * @name info
     * @access public
     * @param mixed $ip
     * @return object
     */
    public function info($ip = NULL)
    {
        if (is_null($ip)) {
            $ip = file_get_contents(self::getHostApi() . 'ip/');
        }

        if (! is_bool($this->getFormat())) {

            $data = false;

            switch ($this->getFormat()) {

                case 'csv':
                    $data = $this->_csv(file_get_contents(self::getHostApi() . $ip . '/csv/'));
                    break;

                case 'json':
                    $data = json_decode(file_get_contents(self::getHostApi() . $ip . '/json/'));
                    break;

                case 'jsonp':
                    $jsonp = file_get_contents(self::$hostApi . $ip . '/jsonp/');
                    if ($jsonp[0] !== '[' && $jsonp[0] !== '{') {
                        $jsonp = substr($jsonp, strpos($jsonp, '('));
                    }
                    $data = json_decode(trim($jsonp, '();'), false);
                    break;

                case 'yaml':
                    $data = $this->_yaml(file_get_contents(self::getHostApi() . $ip . '/yaml/'));
                    break;

                case 'xml':
                    $data = $this->_xml(file_get_contents(self::getHostApi() . $ip . '/xml/'));
                    break;

                default:
                    $data = json_decode(file_get_contents(self::getHostApi() . $ip . '/json/'));
                    break;
            }

            if ($this->hasError() !== false) {
                if (is_bool($data)) {
                    $data = new \stdClass();
                }
                if (isset($data->reason)) {
                    $this->errors[] = $data->reason;
                }
                $data->error = true;
                $data->reason = implode("\n", $this->getErrors());
            }
        } else {
            $data = new \stdClass();
            $data->error = true;
            $data->reason = implode("\n", $this->getErrors());
        }

        if (isset($data->error)) {
            if (! is_bool($data->error)) {
                $data->error = true;
            }
        }

        return $data;
    }
}
