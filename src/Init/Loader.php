<?php
/**
 * Created by vim.
 * User: huguopeng
 * Date: 2018/09/03
 * Time: 21:00:12
 * By: Loader.php
 */
namespace framing\Init;

class Loader {

    public static function init(){
        $loader = new \Phalcon\Loader();
        $configApplication = \framing\Library\ConfigLibrary::get('config','application');
        /**
         * We're a registering a set of directories taken from the configuration file
         */
        $loader->registerDirs(
            [
                $configApplication->controllersDir,
                $configApplication->modelsDir
            ]
        )->register();
    }
}
