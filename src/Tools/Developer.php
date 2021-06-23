<?php
/**
 * @name Developer
 * @version 1.0.0
 * @author Miroslaw Kukuryka
 * @copyright (c) 2019 (https://www.xento.app)
 * @link https://www.xento.app
 */
namespace Xento\Tools;

use Xento\Tools\Server;

class Developer
{

    /**
     *
     * @name moduloPart
     * @access public
     * @param int $days
     * @param int $modulo
     * @return array
     */
    public static function moduloPart($days, $modulo = 7)
    {
        $data = [];

        // $days = date('j')
        if (! in_array($days, [
            28,
            30,
            31
        ])) {
            $days = date('t');
        }

        if (! preg_match('/^[0-9]{1,2}$/i', $modulo) && $modulo < 0) {
            $modulo = 7;
        }

        if ($days == $modulo) {
            $modulo = 7;
        }

        for ($d = 1; $d <= $days; $d ++) {
            for ($d = 1; $d <= $days; $d ++) {
                $p = (($d % $modulo) - 1);
                if ($p < 0) {
                    $p = 6;
                }
                $data[$d] = $p;
            }
            return $data;
        }
    }

    /**
     *
     * @name convertSize
     * @access public
     * @param int $mSize
     * @param int $decimals
     * @return string int
     */
    public static function convertByteFormat($size, $decimals = 2)
    {
        if (! empty($size) && (int) $size) {
            if ((int) $decimals && $decimals > 0 && $decimals < 4) {

                $units = [
                    'B' => 0,
                    'KB' => 1,
                    'MB' => 2,
                    'GB' => 3,
                    'TB' => 4,
                    'PB' => 5,
                    'EB' => 6,
                    'ZB' => 7,
                    'YB' => 8
                ];

                $powValue = floor(log($size) / log(1024));
                $unit = array_search($powValue, $units);
                $value = ($size / pow(1024, floor($units[$unit])));

                $size = sprintf('%.' . $decimals . 'f ' . $unit, $value);
                return $size;
            }
        }
        return $size;
    }

    /**
     *
     * @name arraySortByColumn
     * @access public
     * @param array $arr
     * @param string $col
     * @param string $dir
     */
    public static function arraySortByColumn(&$arr, $col, $dir = SORT_ASC)
    {
        $sort_col = [];
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }
        array_multisort($sort_col, $dir, $arr);
    }

    /**
     *
     * @name checkArray
     * @access public
     * @param array $value
     * @return bool
     */
    public static function checkArray($value)
    {
        $result = false;
        if (is_array($value)) {
            if (sizeof($value)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     *
     * @name getInformation
     * @access public
     * @param string $value
     * @return bool|array
     */
    public static function getInformation($value)
    {
        clearstatcache();
        if (empty($value)) {
            return false;
        }

        $isCheck = [];

        if (is_dir($value)) {
            $isCheck[] = true;
        }

        if (is_file($value)) {
            $isCheck[] = true;
        }

        if (! sizeof($isCheck)) {
            return false;
        }

        $info = [];
        $stat = stat($value);

        if (is_array($stat) && sizeof($stat)) {

            $ts = [
                0140000 => 'ssocket',
                0120000 => 'llink',
                0100000 => '-file',
                0060000 => 'bblock',
                0040000 => 'ddir',
                0020000 => 'cchar',
                0010000 => 'pfifo'
            ];

            $t = decoct($stat['mode'] & 0170000);

            $str = (array_key_exists(octdec($t), $ts)) ? $ts[octdec($t)][0] : 'u';
            $str .= (($stat['mode'] & 0x0100) ? 'r' : '-') . (($stat['mode'] & 0x0080) ? 'w' : '-');
            $str .= (($stat['mode'] & 0x0040) ? (($stat['mode'] & 0x0800) ? 's' : 'x') : (($stat['mode'] & 0x0800) ? 'S' : '-'));
            $str .= (($stat['mode'] & 0x0020) ? 'r' : '-') . (($stat['mode'] & 0x0010) ? 'w' : '-');
            $str .= (($stat['mode'] & 0x0008) ? (($stat['mode'] & 0x0400) ? 's' : 'x') : (($stat['mode'] & 0x0400) ? 'S' : '-'));
            $str .= (($stat['mode'] & 0x0004) ? 'r' : '-') . (($stat['mode'] & 0x0002) ? 'w' : '-');
            $str .= (($stat['mode'] & 0x0001) ? (($stat['mode'] & 0x0200) ? 't' : 'x') : (($stat['mode'] & 0x0200) ? 'T' : '-'));

            $info = [
                'dirname' => @dirname($value),
                'basename' => @basename($value),
                'filename' => $value,
                // 'realpath' => (@realpath($stat) != $stat) ? @realpath($stat)
                // : '',
                'human' => $str,
                'octal' => substr(sprintf("0%o", $stat['mode']), - 4),
                'decimal' => sprintf("%04o", $stat['mode']),
                'fileperms' => @fileperms($value),
                'mode' => $stat['mode'],
                'uid' => $stat['uid'],
                'gid' => $stat['gid'],
                'owner' => (function_exists('posix_getpwuid')) ? @posix_getpwuid($stat['uid']) : '',
                'group' => (function_exists('posix_getgrgid')) ? @posix_getgrgid($stat['gid']) : '',
                'mode' => $stat['mode'],
                'create' => $stat['atime'],
                'modified' => $stat['mtime'],
                'timecreate' => date('Y-m-d H:i:s', $stat['atime']),
                'timemodified' => date('Y-m-d H:i:s', $stat['mtime']),
                'size' => $stat['size'],
                'blocks' => $stat['blocks'],
                'block_size' => $stat['blksize'],
                'is_readable' => @is_readable($value),
                'is_writable' => @is_writable($value),
                'type' => substr($ts[octdec($t)], 1),
                'type_octal' => sprintf("%07o", octdec($t)),
                'is_file' => @is_file($value),
                'is_dir' => @is_dir($value),
                'is_link' => @is_link($value)
            ];
        }
        return $info;
    }

    /**
     *
     * @name arrayToYaml
     * @access public
     * @param array $data
     * @param string $output
     * @return bool
     */
    public static function arrayToYaml($data, $output)
    {
        $result = false;
        try {
            if (Server::isExistExtension('yaml') !== false) {
                if (self::checkArray($data) !== false) {
                    file_put_contents($output, yaml_emit($data));
                    if (! file_exists($output)) {
                        throw new \Xento\Exception\AppException('No exist file Yaml ' . var_dump($output));
                    }
                    $result = true;
                } else {
                    throw new \Xento\Exception\AppException('Valid element array ' . var_export([
                        'output' => $output,
                        'data' => $data
                    ], true));
                }
            } else {
                throw new \Xento\Exception\AppException('No instaled extension YAML');
            }
        } catch (\Xento\Exception\AppException $e) {
            \Xento\AppLogger::LogException($e);
            $result = false;
        }
        return $result;
    }

    /**
     *
     * @name yamlToArray
     * @access public
     * @param string $value
     * @return bool|array
     */
    public static function yamlToArray($value)
    {
        $result = false;
        try {
            if (Server::isExistExtension('yaml') !== false) {
                if (is_file($value)) {
                    $dataSource = yaml_parse(file_get_contents($value));
                    if (self::checkArray($dataSource) !== false) {
                        $result = $dataSource;
                    } else {
                        throw new \Xento\Exception\AppException('Valid element array ' . var_export([
                            'data' => $dataSource,
                            'file' => $value
                        ], true));
                    }
                }
            } else {
                throw new \Xento\Exception\AppException('No instaled extension YAML');
            }
        } catch (\Xento\Exception\AppException $e) {
            \Xento\AppLogger::LogException($e);
            $result = false;
        }
        return $result;
    }

    /**
     *
     * @name arrayToXml
     * @access public
     * @param array $data
     * @param string $output
     * @return bool
     */
    public function arrayToXml($data, $output)
    {
        $result = false;
        try {
            if (Server::isExistExtension('simplexml') !== false) {
                if (self::checkArray($data) !== false) {
                    // Todo
                } else {
                    throw new \Xento\Exception\AppException('Valid element array ' . var_export([
                        'output' => $output,
                        'data' => $data
                    ], true));
                }
            } else {
                throw new \Xento\Exception\AppException('Error Server No Installed Extension : SimpleXML');
            }
        } catch (\Xento\Exception\AppException $e) {
            \Xento\AppLogger::LogException($e);
            $result = false;
        }
        return $result;
    }

    /**
     *
     * @name xmlToArray
     * @access public
     * @param string $value
     * @return bool|array
     */
    public static function xmlToArray($value)
    {
        $result = false;
        try {
            if (is_file($value)) {
                $dataSource = [];
                if (self::checkArray($dataSource) !== false) {
                    $result = $dataSource;
                } else {
                    throw new \Xento\Exception\AppException('Valid element array ' . var_export([
                        'data' => $dataSource,
                        'file' => $value
                    ], true));
                }
            }
        } catch (\Xento\Exception\AppException $e) {
            \Xento\AppLogger::LogException($e);
            $result = false;
        }
        return $result;
    }

    /**
     *
     * @name fileMimeType
     * @access public
     * @param string $extension
     * @return boolean|string
     */
    public static function fileMimeType($extension)
    {
        $result = false;

        if (preg_match('/^[0-9a-zA-Z]{2,6}$/i', $extension)) {

            $mimeTypes = [
                // other'
                'txt' => 'text/plain',
                'htm' => 'text/html',
                'html' => 'text/html',
                'php' => 'text/html',
                'css' => 'text/css',
                'js' => 'application/javascript',
                'json' => 'application/json',
                'xml' => 'application/xml',
                'swf' => 'application/x-shockwave-flash',
                'flv' => 'video/x-flv',
                // images
                'png' => 'image/png',
                'jpe' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
                'gif' => 'image/gif',
                'bmp' => 'image/bmp',
                'ico' => 'image/vnd.microsoft.icon',
                'tiff' => 'image/tiff',
                'tif' => 'image/tiff',
                'svg' => 'image/svg+xml',
                'svgz' => 'image/svg+xml',
                // archives
                'zip' => 'application/zip',
                'rar' => 'application/x-rar-compressed',
                'exe' => 'application/x-msdownload',
                'msi' => 'application/x-msdownload',
                'cab' => 'application/vnd.ms-cab-compressed',
                // audio_video
                'mp3' => 'audio/mpeg',
                'qt' => 'video/quicktime',
                'mov' => 'video/quicktime',
                // adobe
                'pdf' => 'application/pdf',
                'psd' => 'image/vnd.adobe.photoshop',
                'ai' => 'application/postscript',
                'eps' => 'application/postscript',
                'ps' => 'application/postscript',
                // ms_office
                'doc' => 'application/msword',
                'rtf' => 'application/rtf',
                'xls' => 'application/vnd.ms-excel',
                'ppt' => 'application/vnd.ms-powerpoint',
                // open_office
                'odt' => 'application/vnd.oasis.opendocument.text',
                'ods' => 'application/vnd.oasis.opendocument.spreadsheet'
            ];

            if (isset($mimeTypes[strtolower($extension)])) {
                $result = $mimeTypes[strtolower($extension)];
            }
        }
        return $result;
    }

    /**
     *
     * @name preparePermissionHuman
     * @access public
     * @param string $value
     * @return string
     */
    public static function preparePermissionHuman($value)
    {
        $ts = [
            0140000 => 'ssocket',
            0120000 => 'llink',
            0100000 => '-file',
            0060000 => 'bblock',
            0040000 => 'ddir',
            0020000 => 'cchar',
            0010000 => 'pfifo'
        ];

        $t = decoct($value & 0170000);

        $str = (array_key_exists(octdec($t), $ts)) ? $ts[octdec($t)][0] : 'u';
        $str .= (($value & 0x0100) ? 'r' : '-') . (($value & 0x0080) ? 'w' : '-');
        $str .= (($value & 0x0040) ? (($value & 0x0800) ? 's' : 'x') : (($value & 0x0800) ? 'S' : '-'));
        $str .= (($value & 0x0020) ? 'r' : '-') . (($value & 0x0010) ? 'w' : '-');
        $str .= (($value & 0x0008) ? (($value & 0x0400) ? 's' : 'x') : (($value & 0x0400) ? 'S' : '-'));
        $str .= (($value & 0x0004) ? 'r' : '-') . (($value & 0x0002) ? 'w' : '-');
        $str .= (($value & 0x0001) ? (($value & 0x0200) ? 't' : 'x') : (($value & 0x0200) ? 'T' : '-'));
        return $str;
    }
}
