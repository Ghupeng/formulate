<?php
/**
 * Created by vim.
 * User: huguopeng
 * Date: 2018/09/04
 * Time: 19:07:38
 * By: beforeNotFoundPlugin.php
 */
namespace framing\Plugin;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

class beforeNotFoundPlugin extends Plugin{

    public function beforeNotFoundAction(Event $event, Dispatcher $dispatcher) {
        header("Location: https://www.baidu.com/");
        return false;
    }
}
