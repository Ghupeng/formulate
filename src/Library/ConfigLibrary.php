<?php
/**
 * Created by vim.
 * User: huguopeng
 * Date: 2018/09/03
 * Time: 14:02:41
 * By: ConfigLibrary.php
 */
namespace framing\Library;
class ConfigLibrary {
    /**
     * @$param string $filename 文件名
     * @$param string $module   模块
     *
     */
    public static function get(string $filename,string $module) {
        $runmode = \framing\Library\Runmode::get();
        $filename = CONF_PATH . '/' . $runmode . '/' . $filename . '.ini';
        $config = self::getConfigFile($filename,$module);
        if($config === null) {
            echo "文件不存在";die;
        } else {
            return $config;
        }
    }

    /**
     * @param string $filename
     * @param string $module
     * @return null
     */
    public static function getConfigFile(string $filename='',string $module='') {
        if(!file_exists($filename)) {
            return null;
        }
        $config = new \Phalcon\Config\Adapter\Ini($filename);
        if(empty($config) || !isset($config->$module)) {
            return null;
        } else {
            return $config->$module;
        }
    }
}
