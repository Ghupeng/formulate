<?php
/**
 * Created by vim.
 * User: huguopeng
 * Date: 2018/09/05
 * Time: 20:02:52
 * By: BaseController.php
 */
namespace framing\Base;

class BaseController extends \ControllerBase {

	public function execute() {
		$basePageInfo = (\Phalcon\DI::getDefault())->get("basePageInfo");
		$class_name = "\\".APP_NAMESPACE."\\modules\\$basePageInfo->module\\Service\\".$basePageInfo->method;
		if(class_exists($class_name)) {
			$object = new $class_name();
			$object->execute();
		} else {
			echo  "不存在";// 抛出异常
		}

	}
}
