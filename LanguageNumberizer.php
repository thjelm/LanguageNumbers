<?php

abstract class LanguageNumberizer
{
    abstract protected function getLanguage();
    abstract protected function toText($number);
    
    public function displayLanguage() {
        return $this->getLanguage();
    }
    
    public function numberToText($number) {
        return $this->toText($number);
    }
}
?>
