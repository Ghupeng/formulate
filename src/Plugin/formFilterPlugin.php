<?php
/**
 * Created by vim.
 * User: huguopeng
 * Date: 2018/09/05
 * Time: 23:35:38
 * By: formFilterPlugin.php
 */
namespace framing\Plugin;

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

class formFilterPlugin extends Plugin{

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
		$basePageInfo = $di->get('basePageInfo');
		$request      = $di->getRequest();

		$class_name = "\\".APP_NAMESPACE."\\modules\\$basePageInfo->module\\Param\\".$basePageInfo->method."Param";
		if(class_exists($class_name)){
			$class = new $class_name();
			$basePageInfo->params = $class->vaild($basePageInfo,$request);
		} else {
			echo  "文件不存在".$class_name;die;
		}

	}


}
