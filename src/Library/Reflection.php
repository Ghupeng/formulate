<?php
/**
 * Created by vim.
 * User: huguopeng
 * Date: 2018/09/03
 * Time: 19:02:41
 * By: Reflection.php
 */
namespace framing\Library;

class Reflection {

	public function getAttribute($className){
		
		$class = new \ReflectionClass($className);
		//Gets the attribute, regardless of the permissions modifier of the attribute.
		$properties = $class->getProperties();
		$attribute = [];
		foreach($properties as $key=> &$property) {
			$attribute[$key]['name'] = trim($property->getName());
			$attribute[$key]['length']   = $this->_getCommentValue($property->getDocComment(),'length');
			$attribute[$key]['type']     = $this->_getCommentValue($property->getDocComment(),'type');
			$attribute[$key]['min_max']  = $this->_getCommentValue($property->getDocComment(),'min_max');
			$attribute[$key]['regex']    = $this->_getCommentValue($property->getDocComment(),'regex');
			$attribute[$key]['default']  = $this->_getCommentValue($property->getDocComment(),'default');
			$attribute[$key]['filter']   = $this->_getCommentValue($property->getDocComment(),'filter');
			$attribute[$key]['message']  = $this->_getCommentValue($property->getDocComment(),'message');
		}
		return $attribute;
	}
	private function _getCommentValue(string $comment,string $key) {
		
		preg_match( "/@$key\((.*)\)/i", $comment, $matche);
		if(!empty($matche)){
			return $matche[1];
		}
		return null;
	}
}
