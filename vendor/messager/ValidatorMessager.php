<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */
namespace Messager;

/**
 * concrete error messager class
 * @author Okan CIRAN
 * @since 14/01/2016
 */
class ValidatorMessager extends AbstractMessager implements 
                                \Messager\Validator\ValidationMessagerInterface,
                                \Messager\FilterMessagerBaseKeyInterface{
    
    
    /**
     * validation operations holder
     * @var string
     */
    protected $validationMessage;
    
    /**
     * basekey for validated values to be arranged with a key name
     * @var string
     */
    protected $baseKey;
    
    /**
     * method overridden
     * @param mixed array | null $validationMessage
     * @author Okan CIRAN
     * @since 19/01/2016
     */
    public function addValidationMessage($validationMessageArray = null) {
        if(!is_array($validationMessageArray)) throw new \Exception ('array expected in \Messager\ValidatorMesseger!!');
        if(isset($this->baseKey)) {
            $this->validationMessage[$this->baseKey] = $validationMessageArray;
        } else {
            $this->validationMessage = $validationMessageArray;
        }
    }
    
    /**
     * returns validation operations message
     * @return string
     */
    public function getValidationMessage() {
        return $this->validationMessage;
    }
    
    /**
     * set validation operations message
     * @param type $validationMessage
     */
    public function setValidationMessage($validationMessage = null) {
        $this->validationMessage = $validationMessage;
    }
    
    /**
     * gets base key, implemented from \Messager\Filter\FilterMessagerBaseKeyInterface
     * @return string
     */
    public function getBaseKey() {
        return $this->baseKey;
    }

    /**
     * set base key, implemented from \Messager\Filter\FilterMessagerBaseKeyInterface
     * @param string $baseKey
     */
    public function setBaseKey($baseKey = null) {
        $this->baseKey = $baseKey;
    }
}

