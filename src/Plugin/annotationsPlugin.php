<?php
/**
 * Created by vim.
 * User: huguopeng
 * Date: 2018/09/04
 * Time: 20:35:38
 * By: annotationsPlugin.php
 */
namespace framing\Plugin;
use framing\Base\BasePageInfo;
use framing\Library\Runmode;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

class annotationsPlugin extends Plugin{

    /**
     * This action is executed before execute any action in the application
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     *
     * @return bool
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher){
        $di = \Phalcon\DI::getDefault();
        $basePageInfo = new BasePageInfo();
        $di->set('basePageInfo',function() use($basePageInfo){
            return $basePageInfo;
        });
        $basePageInfo->runMode = Runmode::get();
        $basePageInfo->module = ucfirst($dispatcher->getControllerName());
        $basePageInfo->method = ucfirst($dispatcher->getActionName());

        // Possible controller class name
        $controllerName = $dispatcher->getControllerClass();
        // Possible method name
        $actionName = $dispatcher->getActiveMethod();

        // Get annotations in the controller class
        //        $annotationsController = $this->annotations->get($controllerName);
        //        if ($annotationsController->getClassAnnotations()->has("Auth")) {
        //            echo "该控制器需要授权";
        //        }
        // 解析目前访问的控制的方法的注释
        $annotations = $this->annotations->getMethod($controllerName,$actionName);
        $formCheck = $this->_getAnnotationValue($annotations,'formCheck');
        if($formCheck !== null){
            $basePageInfo->formCheck = $formCheck;
        }
        $format = $this->_getAnnotationValue($annotations,'format');
        if($format !== null){
            $basePageInfo->format = $format;
        }
        $cache =  $this->_getAnnotationValue($annotations,'Cache',true);
        if($cache !== null){
            $basePageInfo->cache = $cache;
        }
        $action = $this->_getAnnotationValue($annotations,'Action');
        if($action !== null) {
            $basePageInfo->method = trim($action);
        }
        $login = $this->_getAnnotationValue($annotations,'Login');
        if($login === false) {
            $basePageInfo->login = false;
        }
    }

    /**
     * This action is annotations value
     */
    private function _getAnnotationValue($annotations,$key,$array=false) {
        $value = null;
        if($annotations->has($key)) {
            $annotation = $annotations->get($key);
            $values = $annotation->getArguments();
            if($array === true){
                return $values;
            }
            if(count($values) > 0) {
                $value = $values[0];
            }
        }
        return $value;
    }
}
