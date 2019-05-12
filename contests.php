<?php

require "PHP/checker.php";
require "PHP/top_nav.php";
protect_page();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BlueBottle</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php 
    GetTopNav();
    ?>
<?php 
    $path = "./contests/";
    $scan = scandir($path);
    
    foreach ($scan as $value){
        $ext = pathinfo('./'.$value, PATHINFO_EXTENSION);
        if ($ext != 'php')
            continue;
        if ($value != '.' && $value!='..')
            echo "<div class='contest_block'><h2>".trim(str_replace(".php","",$value))."</h2><p><a href=".$path.$value.">Решать</a></p></div>";
    }
    ?>
</body>
</html>