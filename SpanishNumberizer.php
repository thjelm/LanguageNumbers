<?php

class SpanishNumberizer extends LanguageNumberizer
{
    private $language = "Spanish";
    private $numbers = array("cero","uno","dos","tres","cuatro","cinco","seis","siete","ocho","nueve","diez",
    "once","doce","trece","catorce","quince","dieciséis", "diecisiete", "dieciocho",
    "diecinueve","veinte","veintiuno", "veintidos", "veintitrés", "veinticuatro",
    "veinticinco", "veintiséis", "veintisiete", "veintiocho", "veintinueve", "treinta",
    40=>"cuarenta",50=>"cincuenta",60=>"sesenta",70=>"setenta",80=>"ochenta",
    90=>"noventa", 100=>"cien", 500=>"quinientos", 700=>"setecientos", 900=>"novecientos", 1000=>"mil", 1000000=>"un millón");

    protected function getLanguage() {
        return $this->$language;
    }
    
    
    /*
     * input: a 1-character string containing a digit "0"-"9"
     * output: a string of that number in Spanish longform
     */
    private function doOnes($number) {
        return $this->numbers[(int)$number];
    }
    
    /*
     * input: a 2-character string containing only digits "0"-"9"
     * output: a string of that number in Spanish longform
     */
    private function doTens($number) {
        if ($number[0] == "0") {
            if($number[1] != "0") {
                return $this->doOnes($number[1]);
            }
        } elseif ((int)$number[0] < 3) {
            return $this->numbers[(int)$number];
        } else {
            $index = (int)($number[0] . "0");
            $result = $this->numbers[$index];
            if ($number[1] != "0") {
                $result .= " y " . $this->doOnes($number[1]);
            }
            return $result;
        }
    }
    
    /*
     * input: a 3-character string containing only digits "0"-"9"
     * output: a string of that number in Spanish longform
     */
    private function doHundreds($number) {
        if ($number[0] == "0") {
            return $this->doTens($number[1].$number[2]);
        } elseif ($number[0] == "1") {
            return "ciento " . $this->doTens($number[1].$number[2]);
        } else {
            $tens = $number[1] . $number[2];
            $index = (int)($number[0] . "00");
            if (isset($this->numbers[$index])) {
                return $this->numbers[$index] . " " . $this->doTens($tens);
            } else {
                $index = (int)$number[0];
                return $this->numbers[$index] . "cientos " . $this->doTens($tens);
            }
        }
    }
    
    /*
     * input: an up-to-3-character string containing only digits "0"-"9"
     * output: a string of that number in Spanish longform.
     */
    private function doNumber($number) {
        $num = ltrim($number, "0");
        switch(strlen($num)) {
            case 0:
                return $this->doOnes("0");
            case 1:
                return $this->doOnes($num);
            case 2:
                return $this->doTens($num);
            case 3:
                return $this->doHundreds($num);
        }
    }
    
    
    protected function toText($number) {
        //first check if it is in our numbers array, which holds exceptions to the rules.
        if (isset($this->numbers[(int)$number])) {
            return $this->numbers[(int)$number];
        } else {

            /*
             * We want groupings of three starting from the right, 
             * so we reverse the string first.  Then we split the reversed
             * string into an array of 3-character strings.
             *
             * Ex: 4519224 -> "422", "915", "4"
             *
             * Now $groups contains each set of three digits, but 
             * each set is in reverse order.
             * The highest index has the most significant digits.
             * We convert each set of three into Spanish longform by first reversing
             * it (since it is backwards) and then calling our doNumber() method.
             */
            $groups = str_split(strrev($number), 3);
            foreach ($groups as $index => $group) {
                $groups[$index] = $this->doNumber(strrev($group));
            }
            
            // start with empty string, and concat from msd to lsd. Notice no breaks.
            //
            // $flag is there to cover cases like 2000000000 "dos mil millones",
            // where "000" is the set for "millones", yet it must be written.
            $ret = "";
            $flag = false;
            switch(count($groups)) {
                case 5:
                    if ($groups[4] != "cero") {                    
                        if ($groups[4] == "uno") {
                            $ret .= "un billón ";
                        } else {
                            $ret .= $groups[4] . " billones ";
                        }
                    }
                case 4:
                    if ($groups[3] != "cero") {
                        $flag = true;
                        if ($groups[3] != "uno") {
                            $ret .= $groups[3] . " ";
                        }
                        $ret .= "mil ";
                    }
                case 3:
                    if ($groups[2] != "cero") {
                        if ($groups[2] == "uno") {
                            $ret .= "un millón ";
                        } else {
                            $ret .= $groups[2] . " millones ";
                        }
                    } else {
                        if ($flag) {
                            $ret .= " millones ";
                            $flag = false;
                        }
                    }

                case 2:
                    if ($groups[1] != "cero") {
                        if ($groups[1] != "uno") {
                            $ret .= $groups[1] . " ";
                        }
                        $ret .= "mil ";
                    }
                case 1:
                    if ($groups[0] != "cero") {
                        $ret .= $groups[0];
                    }
            }
            return $ret;
        }
    }
}

?>
