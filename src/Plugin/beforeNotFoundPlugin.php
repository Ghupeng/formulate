<?php
/**
 * Created by vim.
 * User: huguopeng
 * Date: 2018/09/04
 * Time: 19:07:38
 * By: beforeNotFoundPlugin.php
 */
namespace framing\Plugin;
use Exception;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatchException;

class beforeNotFoundPlugin {

	public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher, Exception $exception)
	{
		// Default error action
		$action = 'show503';

		// Handle 404 exceptions
		if ($exception instanceof DispatchException) {
			$action = 'show404';
		}
		header("Location: https://www.baidu.com/");
		return false;
	}
}
