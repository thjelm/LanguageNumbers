<html>
<head><title>PHP TEST</title></head>
<body>
<h3>Spanish Numbers</h3>
<?php 

//include 'SpanishNumberizer.php';

spl_autoload_register(function($classname) {
    include $classname . '.php';
});


if ($_POST) {
    $spanish = new SpanishNumberizer();
    $japanese = new JapaneseNumberizer();
    $number = (int)$_POST['number'];
    $spanish_number = $spanish->numberToText($number);
    $japanese_number = $japanese->numberToText($number);

    echo "<h3>";
    echo $number . " = " . $spanish_number . " = " . $japanese_number;
    echo "</h3>";
}?>

<form action="" method="post">
    <p>Number: <input type="text" name="number" /></p>
    <p><input type="submit" value="Submit" /></p>
</form>


</body>
</html>
