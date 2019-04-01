<?php
/**
 * @name Server
 * @version 1.0.0
 * @author Miroslaw Kukuryka
 * @copyright (c) 2019 (https://www.xento.app)
 * @link https://www.xento.app
 */
namespace Xento\Tools;

use Xento\Tools\Developer;

class Server
{

    /**
     *
     * @name isExistExtension
     * @access public
     * @param string $name
     * @return boolean
     */
    public static function isExistExtension($name)
    {
        if (preg_match('/^[a-zA-z0-9\_\-\.\ ]{2,128}$/i', $name)) {
            if (extension_loaded($name)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     *
     * @name getIonCubeLoaderVersion
     * @access public
     * @return boolean|string
     */
    public static function getIonCubeLoaderVersion()
    {
        $result = false;
        if (self::isExistExtension('IonCube Loader') !== false) {

            ob_start();
            phpinfo(INFO_GENERAL);
            $aux = str_replace('&nbsp;', ' ', ob_get_clean());

            if ($aux !== false) {

                $pos = mb_stripos($aux, 'ionCube PHP Loader');

                if ($pos !== false) {

                    $aux = mb_substr($aux, $pos + 18);
                    $aux = mb_substr($aux, mb_stripos($aux, ' v') + 2);

                    $version = '';
                    $c = 0;
                    $char = mb_substr($aux, $c ++, 1);

                    while (mb_strpos('0123456789.', $char) !== false) {
                        $version .= $char;
                        $char = mb_substr($aux, $c ++, 1);
                    }

                    $result = $version;
                }
            }
        } else {
            return 'No Installed Extension IonCube Loader';
        }
        return $result;
    }

    /**
     *
     * @name checkIncludeFile
     * @access public
     * @param string $value
     * @return boolean
     */
    public static function checkIncludeFile($value)
    {
        $isExist = false;
        if (! empty($value)) {
            $includes = get_included_files();
            if (Developer::checkArray($includes)) {
                foreach ($includes as $item) {
                    if (preg_match('/' . $value . '/', $item)) {
                        $isExist = true;
                    }
                }
            }
        }
        return $isExist;
    }

    /**
     *
     * @name checkDeclaredClass
     * @access public
     * @param string $value
     * @return boolean
     */
    public static function checkDeclaredClass($value)
    {
        $isDeclarate = false;
        if (! empty($value)) {
            $declaretes = get_declared_classes();
            if (Developer::checkArray($declaretes)) {
                foreach ($declaretes as $item) {
                    if ($value == $item) {
                        $isDeclarate = true;
                        break;
                    }
                }
            }
        }
        return $isDeclarate;
    }

    /**
     *
     * @name folderSize
     * @access public
     * @param string $path
     * @return number
     */
    public static function folderSize($path)
    {
        $total_size = 0;
        $files = scandir($path);

        foreach ($files as $t) {
            if (is_dir(rtrim($path, '/') . '/' . $t)) {
                if ($t != "." && $t != "..") {
                    $size = self::folderSize(rtrim($path, '/') . '/' . $t);
                    $total_size += $size;
                }
            } else {
                $size = filesize(rtrim($path, '/') . '/' . $t);
                $total_size += $size;
            }
        }
        return $total_size;
    }

    /**
     *
     * @name changeDirPermission
     * @access public
     * @param string $src
     * @param string $mode
     * @return string|boolean
     */
    public static function changeDirPermission($src, $mode)
    {
        $result = false;
        if (preg_match('/^[0-9]{3,4}$/i', $mode) && $mode > 0) {

            if (strlen($mode) == 3) {
                $mode = '0' . $mode;
            }

            if (is_dir($src) !== false) {

                $_mode = substr(sprintf('%o', fileperms($src)), - 4);

                if ($mode != $_mode) {

                    $old = umask(0);
                    chmod($src, $mode);
                    umask($old);

                    $changeMode = substr(sprintf('%o', fileperms($src)), - 4);

                    if ($mode != $changeMode) {
                        return "No change directory permission [ src $src, oldPermission $_mode, mewPermission $mode ]";
                    }
                } else {
                    return "No change directory permission, identical permission [ src $src, oldPermission $_mode, mewPermission $mode ]";
                }
            } else {
                return "Object permission no directory [ src $src ]";
            }
            $result = true;
        } else {
            return "Valid variable $mode [ src $src, mode $mode ]";
        }
        return $result;
    }

    /**
     *
     * @name createFile
     * @access public
     * @param string $dir
     * @param string $file
     * @param mixed $content
     * @return boolean
     */
    public static function createFile($dir, $file, $content = null)
    {
        $result = false;
        $_dir = str_replace([
            '/',
            '\\',
            ':'
        ], [
            'p',
            'l',
            'd'
        ], $dir);
        $_dir = str_replace([
            'l'
        ], [
            'k'
        ], $_dir);

        $fp = fopen($dir . '/' . $file, 'w');
        if (! is_null($content)) {
            fwrite($fp, $content);
        }
        fclose($fp);
        if (file_exists($dir . '/' . $file)) {
            $result = true;
        }
        return $result;
    }

    /**
     *
     * @name deleteFile
     * @access public
     * @param string $dir
     * @param string $file
     * @return boolean
     */
    public static function deleteFile($dir, $file)
    {
        $result = false;
        if (file_exists($dir . '/' . $file)) {
            unlink($dir . '/' . $file);
        }
        if (! file_exists($dir . '/' . $file)) {
            $result = true;
        }
        return $result;
    }

    /**
     *
     * @name changeFilePermission
     * @access public
     * @param string $src
     * @param string $mode
     * @return string|boolean
     */
    public static function changeFilePermission($src, $mode)
    {
        $result = false;
        if (preg_match('/^[0-9]{3,4}$/i', $mode) && $mode > 0) {

            if (strlen($mode) == 3) {
                $mode = '0' . $mode;
            }

            if (is_file($src) !== false) {

                $_mode = substr(sprintf('%o', fileperms($src)), - 4);

                if ($mode != $_mode) {

                    $old = umask(0);
                    chmod($src, $mode);
                    umask($old);

                    $changeMode = substr(sprintf('%o', fileperms($src)), - 4);

                    if ($mode != $changeMode) {
                        return "No change file permission [ src $src, oldPermission $_mode, mewPermission $mode ]";
                    }
                } else {
                    return "No change file permission, identical permission [ src $src, oldPermission $_mode, mewPermission $mode ]";
                }
            } else {
                return "Object permission no file [ src $src ]";
            }
            $result = true;
        } else {
            return "Valid variable $mode [ src $src, mode $mode ]";
        }
        return $result;
    }

    /**
     *
     * @name createDir
     * @access public
     * @param string $dir
     * @param string $mode
     * @return boolean
     */
    public static function createDir($dir, $mode = '0777')
    {
        $result = false;
        $_dir = str_replace([
            '/',
            '\\',
            ':',
            '/',
            '//'
        ], [
            'p',
            'l',
            'd',
            'u',
            'k'
        ], $dir);
        if (preg_match('/^[a-zA-Z0-9\-\_]{2,}$/i', $_dir)) {
            $oldumask = umask(0);
            if (! mkdir($dir, $mode, true)) {
                $result = false;
            } else {
                umask($oldumask);
                $result = true;
            }
        }
        return $result;
    }

    /**
     *
     * @name removeDir
     * @access public
     * @param string $directory
     */
    public static function removeDir($directory)
    {
        if (is_dir($directory)) {
            $objects = scandir($directory);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (filetype($directory . '/' . $object) == 'dir') {
                        self::removeDir($directory . '/' . $object);
                    } else {
                        unlink($directory . '/' . $object);
                    }
                }
            }
            reset($objects);
            rmdir($directory);
        }
    }

    /**
     *
     * @name recursiveCopy
     * @access public
     * @param string $src
     * @param string $dest
     * @return boolean
     */
    public static function recursiveCopy($src, $dest)
    {
        if (! is_dir($src)) {
            return false;
        }

        if (! is_dir($dest)) {
            if (! self::createDir($dest)) {
                return false;
            }
        }

        $dir = new \DirectoryIterator($src);

        if (is_object($dir)) {
            foreach ($dir as $f) {
                if ($f->isFile()) {
                    copy($f->getRealPath(), "$dest/" . $f->getFilename());
                } else {
                    if (! $f->isDot() && $f->isDir()) {
                        self::recursiveCopy($f->getRealPath(), "$dest/$f");
                    }
                }
            }
        }
    }

    /**
     *
     * @name folderToZip
     * @access private
     * @param string $folder
     * @param string $zipFile
     * @param int $exclusiveLength
     */
    private static function folderToZip($folder, &$zipFile, $exclusiveLength)
    {
        $handle = opendir($folder);

        while (false !== $f = readdir($handle)) {

            if ($f != '.' && $f != '..') {

                $filePath = "$folder/$f";

                $localPath = substr($filePath, $exclusiveLength);

                if (is_file($filePath)) {
                    $zipFile->addFile($filePath, $localPath);
                } elseif (is_dir($filePath)) {
                    $zipFile->addEmptyDir($localPath);
                    self::folderToZip($filePath, $zipFile, $exclusiveLength);
                }
            }
        }
        closedir($handle);
    }

    /**
     *
     * @name zipDir
     * @access public
     * @param string $sourcePath
     * @param string $outZipPath
     * @return string|boolean
     */
    public static function zipDir($sourcePath, $outZipPath)
    {
        if (self::isExistExtension('zip') !== false) {
            return 'No Installed Extension ZIP';
        }

        $result = false;
        $pathInfo = pathInfo($sourcePath);
        $parentPath = $pathInfo['dirname'];
        $dirName = $pathInfo['basename'];

        $z = new \ZipArchive();
        $z->open($outZipPath, \ZIPARCHIVE::CREATE);
        $z->addEmptyDir($dirName);
        self::folderToZip($sourcePath, $z, strlen("$parentPath/"));
        $z->close();

        if (file_exists($outZipPath)) {
            $result = true;
        }
        return $result;
    }

    /**
     *
     * @name getDirListApplication
     * @access public
     * @param string $value
     * @param string $recursive
     * @return string|array
     */
    public static function getDirListApplication($value, $recursive = 'CATCH_GET_CHILD')
    {
        $result = false;

        if (is_dir($value) !== false) {

            $iterator = false;

            switch ($recursive) {

                case 'LEAVES_ONLY':

                    $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($value), \RecursiveIteratorIterator::LEAVES_ONLY);

                    break;

                case 'SELF_FIRST':

                    $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($value), \RecursiveIteratorIterator::SELF_FIRST);

                    break;

                case 'CHILD_FIRST':

                    $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($value), \RecursiveIteratorIterator::CHILD_FIRST);

                    break;

                case 'CATCH_GET_CHILD':

                    $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($value), \RecursiveIteratorIterator::CATCH_GET_CHILD);

                    break;

                default:
                    $iterator = "Valid param variable recursive [ data $value, recursive $recursive, onlyusetypes 'LEAVES_ONLY, SELF_FIRST, CHILD_FIRST, CATCH_GET_CHILD' ]";
                    break;
            }

            if (! is_bool($iterator)) {

                $dataSource = array();
                $_tmp = array();

                foreach ($iterator as $splFileInfo) {

                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['basename'] = $splFileInfo->getBaseName();

                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['prefix'] = sha1($splFileInfo->getRealPath());
                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['name'] = $splFileInfo->getFilename();
                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['basename'] = $splFileInfo->getBaseName();
                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['extension'] = $splFileInfo->getExtension();
                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['mimetype'] = Developer::fileMimeType($splFileInfo->getExtension());
                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['pathname'] = $splFileInfo->getPathname();
                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['path'] = $splFileInfo->getPath();
                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['realpath'] = $splFileInfo->getRealPath();
                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['type'] = $splFileInfo->getType();

                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['size'] = $splFileInfo->getSize();

                    if ($splFileInfo->getType() == 'dir') {
                        $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['size'] = self::folderSize($splFileInfo->getRealPath());
                    }

                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['group'] = $splFileInfo->getGroup();
                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['owner'] = $splFileInfo->getOwner();

                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['decimal'] = sprintf("%04o", $splFileInfo->getPerms());
                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['octal'] = substr(sprintf('%o', $splFileInfo->getPerms()), - 4);
                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['human'] = Developer::preparePermissionHuman($splFileInfo->getPerms());
                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['permission'] = $splFileInfo->getPerms();

                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['isreadable'] = $splFileInfo->isReadable();
                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['iswritable'] = $splFileInfo->isWritable();
                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['isexecutable'] = $splFileInfo->isExecutable();
                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['modtime'] = $splFileInfo->getMTime();
                    $_tmp[$splFileInfo->getType()][$splFileInfo->getRealPath()]['instime'] = $splFileInfo->getCTime();
                }

                if (sizeof($_tmp)) {

                    if (isset($_tmp['dir'])) {
                        sort($_tmp['dir']);
                    }

                    if (isset($_tmp['file'])) {
                        sort($_tmp['file']);
                    }

                    $_size = 0;
                    $_files = 0;
                    $_directories = 0;

                    foreach ($_tmp as $type => $itemType) {

                        if ($type == 'dir' || $type == 'file') {

                            foreach ($itemType as $item) {

                                if (isset($item['prefix'])) {

                                    $dataSource[$item['prefix']] = $item;

                                    if (isset($item['size'])) {
                                        if ($item['size'] > 0) {
                                            $_size += $item['size'];
                                        }
                                    }

                                    if ($type == 'dir') {
                                        if ($item['name'] != '.' && $item['name'] != '..') {
                                            $_directories += 1;
                                        }
                                    }

                                    if ($type == 'file') {
                                        $_files += 1;
                                    }
                                }
                            }
                        }
                    }

                    $_tmp = array();
                    $_info = array();

                    $_info['parent'] = basename($value);
                    $_info['size'] = $_size;
                    $_info['files'] = $_files;
                    $_info['directories'] = $_directories;

                    if (! isset($dataSource['info'])) {
                        $dataSource['info'] = $_info;
                    }
                }

                if (sizeof($dataSource)) {
                    $result = $dataSource;
                }
            }
        } else {
            return "Object no directory [ src $value ]";
        }
        return $result;
    }
}
