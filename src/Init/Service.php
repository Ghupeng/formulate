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
use Phalcon\Mvc\Router\Annotations as RouterAnnotations;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;

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

        /**
         * Open custom annotation routing
         */
        $di->setShared('router', function () {
            // Use the annotations router. We're passing false as we don't want the router to add its default patterns
            $router = new RouterAnnotations(false);
            $configRouter = \framing\Library\ConfigLibrary::get('config','router');
            $modules = explode(',',$configRouter['list']);
            // Read the annotations from ProductsController if the URI starts with /api/products
            foreach ($modules as &$module) {
                $router->addResource(ucfirst($module), '/'.lcfirst($module));
            }

            return $router;
        });

        $di->set('dispatcher', function () {
            // Create an event manager
            $eventsManager = new EventsManager();
            // Attach a listener for type 'dispatch'
            $eventsManager->attach('dispatch:beforeNotFoundAction',new beforeNotFoundPlugin());

            $dispatcher = new MvcDispatcher();

            // Bind the eventsManager to the view component
            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
        },true);

    }
}