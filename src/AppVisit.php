<?php
/**
 * @name AppVisit
 * @version 1.0.0
 * @author Miroslaw Kukuryka
 * @copyright (c) 2018 (https://www.appsonline.eu)
 * @link https://www.appsonline.eu
 */
namespace Xento;

use Xento\Tools\Developer;

class AppVisit extends AppConnection
{

    /**
     *
     * @var string
     */
    private static $_dirLogVisit = false;

    /**
     *
     * @var mixed
     */
    private static $_fileLogVisit = false;

    /**
     *
     * @var string
     */
    private static $_sectionLogVisit = 'panel';

    /**
     *
     * @var string
     */
    private static $_separatorLogVisit = '#-#';

    /**
     *
     * @var array
     */
    private static $_separators = [
        '#-#',
        '---',
        '###',
        '-#-'
    ];

    /**
     *
     * @var int
     */
    private static $_schemaVisitType = 1;

    /**
     *
     * @var array
     */
    private static $_schemaVisit = [
        1 => [
            'datetime',
            'address_ip',
            'device_connection',
            'request_method_type',
            'request_uri_protocol',
            'request_uri_module',
            'request_uri_controller',
            'request_uri_action',
            'operation_system_name',
            'operation_system_version',
            'user_agent',
            'browser_type',
            'browser_name',
            'browser_version',
            'is_authorize',
            'account',
            'user',
            'request_uri',
            'request_header_code'
        ]
    ];

    /**
     *
     * @var array
     */
    private static $_paramsVisit = [];

    /**
     *
     * @name setDirLogVisit
     * @access public
     * @param string $value
     */
    public static function setDirLogVisit($value)
    {
        self::$_dirLogVisit = $value;
    }

    /**
     *
     * @name getDirLogVisit
     * @access public
     * @return string
     */
    public static function getDirLogVisit()
    {
        return self::$_dirLogVisit;
    }

    /**
     *
     * @name setFileLogVisit
     * @access public
     * @param string $value
     */
    public static function setFileLogVisit($value)
    {
        self::$_fileLogVisit = $value;
    }

    /**
     *
     * @name getFileLogVisit
     * @access public
     * @return mixed|boolean|string
     */
    public static function getFileLogVisit()
    {
        return self::$_fileLogVisit;
    }

    /**
     *
     * @name setSectionLogVisit
     * @access public
     * @param string $value
     */
    public static function setSectionLogVisit($value)
    {
        self::$_sectionLogVisit = $value;
    }

    /**
     *
     * @name getSectionLogVisit
     * @access public
     * @return string
     */
    public static function getSectionLogVisit()
    {
        if (! in_array(self::$_separatorLogVisit, [
            'panel',
            'admin',
            'install'
        ])) {
            return 'panel';
        }
        return self::$_separatorLogVisit;
    }

    /**
     *
     * @name setSeparatorLogVisit
     * @access public
     * @param string $value
     */
    public static function setSeparatorLogVisit($value)
    {
        self::$_separatorLogVisit = $value;
    }

    /**
     *
     * @name getSeparatorLogVisit
     * @access public
     * @return string
     */
    public static function getSeparatorLogVisit()
    {
        if (! in_array(self::$_separatorLogVisit, self::$_separators)) {
            self::$_separatorLogVisit = '#-#';
        }
        return self::$_separatorLogVisit;
    }

    /**
     *
     * @name setSchemaVisitType
     * @access public
     * @param int $value
     */
    public static function setSchemaVisitType($value)
    {
        self::$_schemaVisitType = (int) $value;
    }

    /**
     *
     * @name getSchemaVisitType
     * @access public
     * @return int
     */
    public static function getSchemaVisitType()
    {
        return self::$_schemaVisitType;
    }

    /**
     *
     * @name hasParamVisit
     * @access public
     * @param string $key
     * @return bool
     */
    private static function hasParamVisit($key)
    {
        if (isset(self::$_paramsVisit[$key])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @name getParamVisit
     * @access public
     * @param string $key
     * @return mixed|string
     */
    public static function getParamVisit($key)
    {
        if (self::hasParamVisit($key) !== false) {
            return self::$_paramsVisit[$key];
        } else {
            return 'none';
        }
    }

    /**
     *
     * @name getAllParamsVisit
     * @access public
     * @return array
     */
    public static function getAllParamsVisit()
    {
        return self::$_paramsVisit;
    }

    /**
     *
     * @name addParamVisit
     * @access public
     * @param string $key
     * @param string $value
     */
    public static function addParamVisit($key, $value)
    {
        if (self::hasParamVisit($key) !== true) {
            self::$_paramsVisit[$key] = $value;
        }
    }

    /**
     *
     * @name removeParamVisit
     * @access public
     * @param string $key
     */
    public static function removeParamVisit($key)
    {
        if (self::hasParamVisit($key) !== false) {
            unset(self::$_paramsVisit[$key]);
        }
    }

    /**
     *
     * @name check
     * @access private
     * @param string $value
     * @return boolean
     */
    private static function check($value)
    {
        $isExist = false;

        if (! file_exists(self::getDirLogVisit() . self::getFileLogVisit())) {
            $fp = fopen(self::getDirLogVisit() . self::getFileLogVisit(), 'a');
            fclose($fp);
        }

        $matches = [];
        $contents = file_get_contents(self::getDirLogVisit() . self::getFileLogVisit());
        $pattern = preg_quote($value, '/');
        $pattern = "/^.*$pattern.*\$/m";

        if (preg_match_all($pattern, $contents, $matches)) {
            $isExist = true;
        }

        return $isExist;
    }

    /**
     *
     * @name add
     * @access public
     */
    public static function add()
    {
        if (! is_bool(self::getDirLogVisit())) {
            if (is_bool(Developer::getInformation(self::getDirLogVisit()))) {
                mkdir(self::getDirLogVisit(), '0777', true);
            }
        }

        if (is_bool(self::getDirLogVisit())) {
            $sectionApplication = self::getSectionLogVisit();
            $visitDir = getcwd() . '/data/logs/visits/' . $sectionApplication . '/';
            if (is_bool(Developer::getInformation($visitDir))) {
                mkdir(getcwd() . '/data/logs/visits/' . $sectionApplication, '0777', true);
                $visitDir = getcwd() . '/data/logs/visits/' . $sectionApplication . '/';
            }
            self::setDirLogVisit($visitDir);
        }

        $paramsConnection = self::getAllParamsConnection();

        $visit = [];
        $schema = self::$_schemaVisit[self::getSchemaVisitType()];
        $visit[0] = self::getSchemaVisitType();
        foreach ($schema as $item) {
            if (isset($paramsConnection[$item])) {
                $visit[] = $paramsConnection[$item];
            } else {
                $visit[] = self::getParamVisit($item);
            }
        }
        $search = sha1(self::getSchemaVisitType() . self::getSeparatorLogVisit() . implode(self::getSeparatorLogVisit(), $visit));
        $visit[(sizeof($visit) + 1)] = $search;

        if (self::getFileLogVisit() !== true) {
            self::setFileLogVisit(date('Y_m_d') . '_visit.log');
        }

        if (! self::check($search)) {
            $fp = fopen(self::getDirLogVisit() . self::getFileLogVisit(), 'a');
            fwrite($fp, implode(self::getSeparatorLogVisit(), $visit) . "\n");
            fclose($fp);
        }
    }

    /**
     *
     * @name checkExistKeyValue
     * @access private
     * @param array $value
     * @return string
     */
    private static function checkExistKeyValue($value)
    {
        return ((isset($value)) ? $value : 'none');
    }

    /**
     *
     * @name toData
     * @access private
     * @param int $schema
     * @param array $data
     * @return array
     */
    private static function toData($schema, $data = [])
    {
        $dataSource = [];
        switch ($schema) {
            case 1:
                $dataSource['date'] = date('Y-m-d', strtotime($data[1]));
                $dataSource['datetime'] = self::checkExistKeyValue($data[1]);
                $dataSource['addressip'] = self::checkExistKeyValue($data[2]);
                $dataSource['isauthorize'] = self::checkExistKeyValue($data[15]);
                $dataSource['account'] = self::checkExistKeyValue($data[16]);
                $dataSource['user'] = self::checkExistKeyValue($data[17]);
                $dataSource['requesturi'] = self::checkExistKeyValue($data[18]);
                $dataSource['isssl'] = ((self::checkExistKeyValue($data[5]) == 'http') ? 'NONE' : 'YES');
                $dataSource['httpuseragent'] = self::checkExistKeyValue($data[11]);
                $dataSource['devicconnection'] = self::checkExistKeyValue($data[3]);
                $dataSource['module'] = self::checkExistKeyValue($data[6]);
                $dataSource['controller'] = self::checkExistKeyValue($data[7]);
                $dataSource['action'] = self::checkExistKeyValue($data[8]);
                $dataSource['method'] = self::checkExistKeyValue($data[4]);
                $dataSource['os'] = implode(' ', array(
                    $data[9],
                    $data[10]
                ));
                $dataSource['browsertype'] = ((isset($data[12])) ? $data[12] : 'none');
                $dataSource['browsername'] = ((isset($data[13])) ? $data[13] : 'none');
                $dataSource['browserversion'] = ((isset($data[14])) ? $data[14] : 'none');
                $dataSource['requestheadercode'] = ((isset($data[19])) ? $data[19] : 'none');
                $dataSource['prefix'] = ((isset($data[20])) ? $data[20] : 'no-exist-prefix');
                break;
        }
        return $dataSource;
    }

    /**
     *
     * @name getAllVisits
     * @access public
     * @return array|boolean
     */
    public static function getAllVisits($section = NULl)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $result = false;

        $visitsDir = $visitsDir = BASE_PATH . '/data/logs/visits/application';
        if (! is_null($section)) {
            $visitsDir = $visitsDir = BASE_PATH . '/data/logs/visits/' . $section;
        }

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($visitsDir), \RecursiveIteratorIterator::CHILD_FIRST);

        if (sizeof($iterator)) {

            $datResult = [];

            foreach ($iterator as $splFileInfo) {
                if (! $splFileInfo->isDir()) {
                    if (file_exists($splFileInfo->getPath() . '/' . $splFileInfo->getFilename())) {
                        $_visit = file($splFileInfo->getPath() . '/' . $splFileInfo->getFilename());
                        foreach ($_visit as $item) {
                            $value = explode(self::getSeparatorLogVisit(), $item);
                            $datResult[] = self::toData((int) $value[0], $value);
                        }
                    }
                }
            }

            if (sizeof($datResult)) {
                $result = $datResult;
            }
        }
        return $result;
    }
}
