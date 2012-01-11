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
    
    private function doOnes($number) {
        return $this->numbers[(int)$number];
    }
    
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
        if (isset($this->numbers[(int)$number])) {
            return $this->numbers[(int)$number];
        } else {
            //$num = $number;//(string)$number;
            $len = strlen($number);

            //we want groupings of three starting from the right, so we reverse the string first
            $groups = str_split(strrev($number), 3);
            foreach ($groups as $index => $group) {
                $groups[$index] = strrev($group);
            }
            print_r($groups);
                       
            //now groups contains each set of three, with the highest index the msd
            //convert each "group" into Spanish longform.
            $textgroups = array();
            foreach ($groups as $index => $value) {
                $textgroups[$index] = $this->doNumber($value);
            }
            print_r($textgroups);

            //start with empty string, and concat from msd to lsd. Notice no breaks.
            //TODO: use a flag to catch the case "dos mil millones", where we have 0 millones
            $ret = "";
            $flag = false;
            switch(count($textgroups)) {
                case 5:
                    if ($textgroups[4] != "cero") {                    
                        if ($textgroups[4] == "uno") {
                            $ret .= "un billón ";
                        } else {
                            $ret .= $textgroups[4] . " billones ";
                        }
                    }
                case 4:
                    if ($textgroups[3] != "cero") {
                        $flag = true;
                        if ($textgroups[3] != "uno") {
                            $ret .= $textgroups[3] . " ";
                        }
                        $ret .= "mil ";
                    }
                case 3:
                    if ($textgroups[2] != "cero") {
                        if ($textgroups[2] == "uno") {
                            $ret .= "un millón ";
                        } else {
                            $ret .= $textgroups[2] . " millones ";
                        }
                    } else {
                        if ($flag) {
                            $ret .= " millones ";
                            $flag = false;
                        }
                    }

                case 2:
                    if ($textgroups[1] != "cero") {
                        if ($textgroups[1] != "uno") {
                            $ret .= $textgroups[1] . " ";
                        }
                        $ret .= "mil ";
                    }
                case 1:
                    if ($textgroups[0] != "cero") {
                        $ret .= $textgroups[0];
                    }
            }
            return $ret;
        }
    }
}

?>
