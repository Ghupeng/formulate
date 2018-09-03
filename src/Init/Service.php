<?php
/**
 * Created by vim.
 * User: huguopeng
 * Date: 2018/09/03
 * Time: 21:04:02
 * By: Service.php
 */
namespace framing\Init;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;

class Service {
    /**
     * @param $di
     */
    public static function init(&$di) {
        /**
         * Setting up the view component
         */
        $di->setShared('view', function () {
            $configApplication = \framing\Library\ConfigLibrary::get('config','application');

            $view = new View();
            $view->setDI($this);
            $view->setViewsDir($configApplication->viewsDir);

            $view->registerEngines([
                '.volt' => function ($view) {
                    $configApplication = \framing\Library\ConfigLibrary::get('config','application');

                    $volt = new VoltEngine($view, $this);

                    $volt->setOptions([
                        'compiledPath' => $configApplication->cacheDir,
                        'compiledSeparator' => '_'
                    ]);
                    return $volt;
                },
                '.phtml' => PhpEngine::class
            ]);

            return $view;
        });

        /**
         * Database connection is created based in the parameters defined in the configuration file
         */
        $di->setShared('db', function () {
            $configDatabase = \framing\Library\ConfigLibrary::get('config','database');

            $class = 'Phalcon\Db\Adapter\Pdo\\' . $configDatabase->adapter;
            $params = [
                'host'     => $configDatabase->host,
                'username' => $configDatabase->username,
                'password' => $configDatabase->password,
                'dbname'   => $configDatabase->dbname,
                'charset'  => $configDatabase->charset
            ];

            if ($configDatabase->adapter == 'Postgresql') {
                unset($params['charset']);
            }

            $connection = new $class($params);

            return $connection;
        });

        /**
         * If the configuration specify the use of metadata adapter use it or use memory otherwise
         */
        $di->setShared('modelsMetadata', function () {
            return new MetaDataAdapter();
        });

        /**
         * Start the session the first time some component request the session service
         */
        $di->setShared('session', function () {
            $session = new SessionAdapter();
            $session->start();

            return $session;
        });

    }
}