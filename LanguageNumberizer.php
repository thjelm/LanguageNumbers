<?php

abstract class LanguageNumberizer
{
    protected $language;
    protected $language_local;
    
    //constructor should set $language and $language_local
    abstract public function __construct();
    
    /*
     * toText()
     *
     * input: a string containing only digits ("0"-"9") 
     * output: a string of that number in text 
     */
    abstract protected function toText($number);
    
    public function getLanguage() {
        return $this->language;
    }
    
    public function getLocalLanguage() {
        return $this->language_local;
    }
    
    public function numberToText($number) {
        return $this->toText($number);
    }
}
?>
