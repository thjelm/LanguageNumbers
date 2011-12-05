<?php

//include 'LanguageNumberizer.php';

class JapaneseNumberizer extends LanguageNumberizer
{
    private $language = "Japanese";
    private $numbers = array("rei","ichi", "ni", "san", "shi", "go", "roku", "nana", "hachi", "kyuu", "juu");

    protected function getLanguage() {
        return $this->$language;
    }
    
    protected function toText($number) {
        //$number = (string)$number;
        return $this->numbers[$number];
        
    }
    
}

?>
