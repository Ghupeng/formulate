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
            $attribute[$key]['name']   = trim($property->getName());
            $length = $this->_getCommentValue($property->getDocComment(),'length');
            if($length !== null){
                $attribute[$key]['length']   = $length;
            }
            $type = $this->_getCommentValue($property->getDocComment(),'type');
            if($type !== null){
                $attribute[$key]['type']   = $type;
            }
            $regex = $this->_getCommentValue($property->getDocComment(),'regex');
            if($regex !== null){
                $attribute[$key]['regex']   = $regex;
            }
            $default = $this->_getCommentValue($property->getDocComment(),'default');
            if($default !== null){
                $attribute[$key]['default']   = $default;
            }
            $filter = $this->_getCommentValue($property->getDocComment(),'filter');
            if($filter !== null){
                $attribute[$key]['filter']   = $filter;
            }
            $message = $this->_getCommentValue($property->getDocComment(),'message');
            if($message !== null){
                $attribute[$key]['message']   = $message;
            }
            $option = $this->_getCommentValue($property->getDocComment(),'option');
            if($option !== null){
                $attribute[$key]['option']   = (bool)$option;
            }
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
