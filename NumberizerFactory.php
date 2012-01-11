<?php

class NumberizerFactory
{
    public static function build($language) {
        $class = $language.'Numberizer';
        if(!class_exists($class)) {
            throw new Exception('Missing class or language not supported.');
        }
        return new $class;
    }
}

?>
