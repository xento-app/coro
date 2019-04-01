<?php
/**
 * @name AppIp
 * @version 1.0.0
 * @author Miroslaw Kukuryka
 * @copyright (c) 2019 (https://www.xento.app)
 * @link https://www.xento.app
 */
namespace Xento;

class AppIp
{

    /**
     *
     * @var string
     */
    private $_addressIp;

    /**
     *
     * @name constructor
     * @access public
     */
    public function __construct()
    {
        $this->setAddressIp();
    }

    /**
     *
     * @name setAddressIp
     * @access private
     */
    private function setAddressIp()
    {
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $this->_addressIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $this->_addressIp = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                if (isset($_SERVER['REMOTE_ADDR'])) {
                    $this->_addressIp = $_SERVER['REMOTE_ADDR'];
                } else {
                    $this->_addressIp = '127.0.0.1';
                }
            }
        }

        if (! isset($_SERVER)) {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $this->_addressIp = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $this->_addressIp = getenv('HTTP_CLIENT_IP');
            } else {
                $this->_addressIp = getenv('REMOTE_ADDR');
            }
        }
    }

    /**
     *
     * @name getAddressIp
     * @access public
     * @return string
     */
    public function getAddressIp()
    {
        if ($this->_addressIp == '::1') {
            $this->_addressIp = '127.0.0.1';
        }
        return $this->_addressIp;
    }

    /**
     *
     * @name getServerAddressIp
     * @access public
     * @return string
     */
    public function getServerAddressIp()
    {
        if (! isset($_SERVER['SERVER_ADDR'])) {
            $_SERVER['SERVER_ADDR'] = '127.0.0.1';
        }
        if ($_SERVER['SERVER_ADDR'] == '::1') {
            return '127.0.0.1';
        }
        return $_SERVER['SERVER_ADDR'];
    }
}
