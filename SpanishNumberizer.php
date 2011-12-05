<?php

//include 'LanguageNumberizer.php';

class SpanishNumberizer extends LanguageNumberizer
{
    private $language = "Spanish";
    private $numbers = array("cero","uno","dos","tres","quatro","cinco","seis","siete","ocho","nueve","diez",
    "once","doce","trece","quatorce","quince","dieciseis", "diecisiete", "dieciocho",
    "diecinueve","veinte");

    protected function getLanguage() {
        return $this->$language;
    }
    
    protected function toText($number) {
        //$number = (string)$number;
        return $this->numbers[$number];
        
    }
    
}

?>
