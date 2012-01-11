<?php

class JapaneseNumberizer extends LanguageNumberizer
{
    
    private $numbers = array("rei","ichi", "ni", "san", "shi", "go", "roku", "nana", "hachi", "kyuu", "juu");

    public function __construct() {
        $this->language = "Japanese";
        $this->language_local = "Nihongo";
    }

    
    protected function toText($number) {
        //$number = (string)$number;
        return $this->numbers[$number];
        
    }
    
}

?>
