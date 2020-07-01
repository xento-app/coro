<?php
/**
 * @name AppLogger
 * @version 1.0.0
 * @author Miroslaw Kukuryka
 * @copyright (c) 2019 (https://www.xento.app)
 * @link https://www.xento.app
 */
namespace Xento;

use Xento\Tools\Developer;

class AppLogger
{

    /**
     *
     * @var string
     */
    private static $_emailNotificationException = NULL;

    /**
     *
     * @var string
     */
    private static $_emailNotificationError = NULL;

    /**
     *
     * @name setEmailNotificationException
     * @access public
     * @return string
     */
    public static function setEmailNotificationException($value)
    {
        self::$_emailNotificationException = $value;
    }

    /**
     *
     * @name getEmailNotificationException
     * @access public
     * @return string
     */
    public static function getEmailNotificationException()
    {
        return self::$_emailNotificationException;
    }

    /**
     *
     * @name setEmailNotificationError
     * @access public
     * @return string
     */
    public static function setEmailNotificationError($value)
    {
        self::$_emailNotificationError = $value;
    }

    /**
     *
     * @name getEmailNotificationError
     * @access public
     * @return string
     */
    public static function getEmailNotificationError()
    {
        return self::$_emailNotificationError;
    }

    /**
     *
     * @name LogException
     * @access public
     * @param object $e
     * @param string $dir
     * @param string $message
     */
    public static function LogException($e, $dir = NULL, $message = NULL)
    {
        if (! is_null(\Xento\AppCore::registry('ipConnection'))) {
            $addressIp = \Xento\AppCore::registry('ipConnection');
        } else {
            $addressIp = '127.0.0.1';
        }

        if (! is_null($dir)) {
            $exceptionDir = $dir;
            if (is_bool(Developer::getInformation($exceptionDir))) {
                mkdir(getcwd() . '/data/logs/exceptions', '0777', true);
            }
        } else {
            $exceptionDir = getcwd() . '/data/logs/exceptions/';
            if (is_bool(Developer::getInformation($exceptionDir))) {
                mkdir(getcwd() . '/data/logs/exceptions', '0777', true);
                $exceptionDir = getcwd() . '/data/logs/exceptions/';
            }
        }

        $data = $addressIp . ' - [' . date('Y-m-d H:i:s') . '] ' . "\n";
        $data .= 'File : ' . $e->getFile() . "\n";
        $data .= 'Class : ' . get_class($e) . ' << >> ' . "\n";
        $data .= 'Code : ' . $e->getCode() . "\n";
        $data .= 'Message : ' . $e->getMessage() . "\n";
        $data .= 'Info : ' . $e->__toString() . "\n";

        if (! is_null($message)) {
            $data .= 'Info Extension Exception : ' . $message . "\n";
        }

        $data .= '--- ' . "\n";

        if (! is_null(self::getEmailNotificationException())) {

            $name = "Exception - Notification Application";
            $email = "support@epa24.pl";
            $recipient = self::getEmailNotificationException();
            $mail_body = var_export($data, true);
            $subject = "Exception - Notification";
            $header = "From: " . $name . " <" . $email . ">\r\n";

            mail($recipient, $subject, $mail_body, $header);
        }

        $fileException = 'log.exception.' . date('Y-m-d') . '.log';
        $fp = @fopen($exceptionDir . $fileException, 'a');
        if (! is_bool($fp)) {
            fwrite($fp, $data);
            fclose($fp);
        }
    }

    /**
     *
     * @name LogError
     * @access public
     * @param string $error
     * @param string $dir
     * @param string $critical
     */
    public static function LogError($error, $dir = NULL, $critical = false)
    {
        if (! is_null(\Xento\AppCore::registry('ipConnection'))) {
            $addressIp = \Xento\AppCore::registry('ipConnection');
        } else {
            $addressIp = '127.0.0.1';
        }

        if (! is_null($dir)) {
            $errorDir = $dir;
            if (is_boolf(Developer::getInformation($errorDir))) {
                mkdir(getcwd() . '/data/logs/errors', '0777', true);
            }
        } else {
            $errorDir = getcwd() . '/data/logs/errors/';
            if (is_bool(Developer::getInformation($errorDir))) {
                mkdir(getcwd() . '/data/logs/errors', '0777', true);
                $errorDir = getcwd() . '/data/logs/errors/';
            }
        }

        $criticalMsg = 'noCritacialError';
        if ($critical != false) {
            $criticalMsg = 'CriticalError';
        }

        $data = $addressIp . ';' . date('Y-m-d H:i:s') . ';' . $criticalMsg . ';';

        if (! empty($error)) {
            $data .= '-->|' . $error . '|<--' . "\n";
        } else {
            $data .= '-->|no_message_error|<--' . "\n";
        }

        // Send Mail
        if ($critical != false) {

            if (! is_null(self::getEmailNotificationError())) {

                $name = "Error - Notification Application";
                $email = "support@epa24.pl";
                $recipient = self::getEmailNotificationException();
                $mail_body = var_export($data, true);
                $subject = "Error - Notification";
                $header = "From: " . $name . " <" . $email . ">\r\n";

                mail($recipient, $subject, $mail_body, $header);
            }
        }

        $fileError = 'log.error.' . date('Y-m-d') . '.log';
        $fp = @fopen($errorDir . $fileError, 'a');
        if (! is_bool($fp)) {
            fwrite($fp, $data . "\n");
            fclose($fp);
        }
    }

    /**
     *
     * @name LogVar
     * @access public
     * @param mixed $value
     * @return string
     */
    public static function LogVar($value)
    {
        ob_start();
        if (is_bool($value)) {
            var_dump($value);
        } else {
            print_r($value);
        }
        $paramsLog = ob_get_contents();
        ob_end_clean();
        return $paramsLog;
    }
}
