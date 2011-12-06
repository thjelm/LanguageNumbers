<?php

//include 'LanguageNumberizer.php';

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
    
    private function doThousands($number) {
        $hundreds = $number[1] . $number[2] . $number[3];
        $result = "mil " . $this->doHundreds($hundreds);
        if ((int)$number[0] > 1) {
            $result = $this->numbers[(int)$number[0]] . $result;
        }
        return $result;
    }
    
    
    protected function toText($number) {
        if (isset($this->numbers[$number])) {
            return $this->numbers[$number];
        } else {
            $num = (string)$number;
            $len = strlen($num);
            $answer = "";
            switch($len) {
                case 2:
                    return $this->doTens($num);
                case 3:
                    return $this->doHundreds($num);
                case 4:
                    $hundreds = $num[$len-3].$num[$len-2].$num[$len-1];
                    if ($num[0] > 1) {
                        return $this->doOnes($num[0]) . " mil " . $this->doHundreds($hundreds);
                    } else {
                        return "mil " . $this->doHundreds($hundreds);
                    }
                case 5:
                    $hundreds = $num[$len-3].$num[$len-2].$num[$len-1];
                    return $this->doTens($num[0].$num[1]) . " mil " . $this->doHundreds($hundreds);
                case 6:
                    $h1 = $num[$len-3].$num[$len-2].$num[$len-1];
                    $h2 = $num[0].$num[1].$num[2];
                    return $this->doHundreds($h2) . " mil " . $this->doHundreds($h1);       
            }
        }
    }
}

?>
