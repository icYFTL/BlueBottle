<?php


function GetTopNav(){
    $output = '<div class="topnav">
        <a class="active" href="/bluebottle/index.php">Главная</a>
        <a href="/bluebottle/scoreboard.php">Скорборд</a>
        <a href="/bluebottle/contests.php">Контесты</a>';
    
    if ($_SESSION['is_auth'] != true)
        $output .= '<a href="/bluebottle/login.php" style="float:right;">Вход</a><a href="/bluebottle/register.php" style="float:right;">Регистрация</a>';
    else 
    {
        
    $output .= '<a href="/bluebottle/PHP/closesession.php" style="float:right;">Выход</a>';
    if ($_SESSION['admin'])
        $output .= "<a style=\"float:right; color: red;\" href=\"/bluebottle/panel.php\">You're poweruser!</a>";
    $output .= "<a style=\"float:right;\">{$_SESSION['login']}</a>";
    }
    $output .= '</div>';
      echo $output;  
}

?>
