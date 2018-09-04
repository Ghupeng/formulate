<?php
/**
 * Created by vim.
 * User: huguopeng
 * Date: 2018/09/04
 * Time: 20:35:38
 * By: annotationsPlugin.php
 */
namespace framing\Plugin;
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
    public function beforeDispatch(Event $event, Dispatcher $dispatcher){
        echo "注解插件";
        return false;
    }
}
