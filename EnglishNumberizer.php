<?php

class EnglishNumberizer extends LanguageNumberizer
{
    private $numbers = array("zero", "one", "two", "three", "four", "five",
        "six", "seven", "eight", "nine", "ten", "eleven", "twelve",
         "thirteen", "fourteen", "fifteen", "sixteen", "seventeen",
         "eighteen", "nineteen", "twenty", 30=>"thirty", 40=>"forty",
          50=>"fifty", 60=>"sixty",70=>"seventy",80=>"eighty", 90=>"ninety");

    public function __construct() {
        $this->language = "English";
        $this->language_local = "English"; 
    }
    
    /*
     * input: an up-to-3-character string containing only digits "0"-"9"
     * output: a string of that number in English longform.
     */
    private function doNumber($number) {
        $num = ltrim($number, "0");
        switch(strlen($num)) {
            case 0:
                return $this->numbers[0];
            case 1:
                return $this->numbers[(int)$num];
            case 2:
                $tens = $this->numbers[(int)($num[0]."0")];
                $ones = "";
                if ($num[1] != "0") {
                    $ones = $this->numbers[(int)$num[1]];
                }
                return $tens." ".$ones;
            case 3:
                $hundreds = $this->numbers[(int)($num[0])] . " hundred";
                $tens = "";
                $ones = "";
                
                if ($num[1] != "0") {
                    $tens = " ".$this->numbers[(int)($num[1]."0")];
                }
                
                if ($num[2] != "0") {
                    $ones = " ".$this->numbers[(int)$num[2]];
                }
                
                return $hundreds.$tens.$ones;
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
            
            /* 
             * start with empty string, and concat from msd to lsd. Notice no breaks.
             */
            $ret = "";
            switch(count($groups)) {
                case 5:
                    if ($groups[4] != "zero") {
                        $ret .= $groups[4] . " trillion ";
                    }
                case 4:
                    if ($groups[3] != "zero") {
                        $ret .= $groups[3] . " billion ";
                    }
                case 3:
                    if ($groups[2] != "zero") {
                        $ret .= $groups[2] . " million ";
                    }
                case 2:
                    if ($groups[1] != "zero") {
                        $ret .= $groups[1] . " thousand ";
                    }
                case 1:
                    if ($groups[0] != "zero") {
                        $ret .= $groups[0];
                    }
            }
            return $ret;
        }
    }

}

?>
