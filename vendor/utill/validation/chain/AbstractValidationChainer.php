<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */
namespace Utill\Validation\Chain;

use Iterator;
use ArrayAccess;
use Countable;
use Slim;


abstract class AbstractValidationChainer implements Iterator, Countable, ArrayAccess, 
                                        Slim\SlimAppInterface ,
                                        \Utill\Validation\ValidationInterface,
                                        \Utill\Validation\ValidationValueInterface,
                                        \Utill\Validation\ValidationChainInterface{
    
    
    protected $chainer;
    protected $slimApp;
    
    public function getSlimApp() {
        return $this->slimApp;
    }

    public function setSlimApp(Slim\Slim $slimApp) {
        $this->slimApp = $slimApp;
    }
    
    /**
     * returns the count of countable interface method
     * @return integer
     */
    public function count() {
        return count($this->chainer);
    }

    /**
     * returns the current object for iterator interface method
     * @return mixed any type 
     */
    public function current() {
        return current($this->chainer);
    }

    /**
     * returns the object determined by ket value for iterator interface method
     * @return mixed any type 
     */
    public function key() {
        return key($this->chainer);
    }
    
    /**
     * get next type for iterator interface method
     * @return mixed any type 
     */
    public function next() {
        return next($this->chainer);
    }
    
    /**
     * reset current iteartor for iterator interface method
     */
    public function rewind() {
        reset($this->chainer);
    }
    
    /**
     * is iterator type valid for iterator interface method
     * @return boolean
     */
    public function valid() {
        return (current($this->chainer) !== false);
    }

    /**
     * determine if type exists in iterator for arrayaccess interface method
     * @param mixed any type $offset 
     * @return type
     */
    public function offsetExists($offset) {
        return (isset($this->chainer[$offset])) ?  true : false;
    }

    /**
     * gets the type due to offset for arrayaccess interface method
     * @param mixed any type $offset
     * @return type
     */
    public function offsetGet($offset) {
        return (isset($this->chainer[$offset])) ?  $this->chainer[$offset] : null;
    }

    /**
     * unset any type due to offset for arrayaccess interface method
     * @param mixed any type $offset
     * @return \Utill\Strip\Chain\StripChainer
     */
    public function offsetUnset($offset) {
        unset($this->chainer[$offset]);
        return $this;
    }

}