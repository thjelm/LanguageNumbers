<html>
<head>
<meta charset='utf-8'>
<title>Language Numbers</title>
</head>
<body>
<h3>Language Numbers</h3>
<?php 

error_reporting(E_ALL);
ini_set("display_errors", "stderr");

spl_autoload_register(function($classname) {
    include $classname . '.php';
});

$languages = array(
    array("English", "English"),
    array("Spanish", "español"),
    array("Japanese", "nihongo")
);

if ($_POST) {
    $numberizer = NumberizerFactory::build($_POST['language']);
    $number = $_POST['number'];
    $spanish_number = $numberizer->numberToText($number);
    
    echo "<h3>";
    echo $numberizer->getLanguage() . " - " . $numberizer->getLocalLanguage();
    echo "</h3>";
    
    echo "<h3>";
    echo $number . " = " . $spanish_number;
    echo "</h3>";
}?>

<form action="" method="post">
    <p>
        <label for="language">Language</label>
        <select name="language" id="language">
            <?php
            foreach ($languages as $language) {
                echo '<option value="' . $language[0] .'"';
                if (isset($_POST['language']) && $_POST['language'] == $language[0]) {
                    echo ' selected';
                }
                echo '>' .$language[1] .'</option>';
            }
            ?>
        </select>
    </p>
    <p>
        <label for="number">Number:</label>
        <input type="text" name="number" id="number" 
        <?php
            if (isset($_POST['number'])) {
                echo 'value="' . $_POST['number'] . '"';
            }
        ?>
        />
    </p>
    <p>
        <input type="submit" value="Submit" />
    </p>
</form>


</body>
</html>
