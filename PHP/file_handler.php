<?php

class FileHandler
{
    function __construct()
    {
        $this->GetFile();
    }

    private $base_dir = '/home/user7180/public_html/bluebottle/contests/';

    function GetFile()
    {
        $destiation_dir = $this->base_dir . $_FILES['inputfile']['name'];
        $allowed = array('txt');
        $filename = $_FILES['inputfile']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array($ext, $allowed)) {
            echo 'Bad exstension. Only txt allowed.';
            return false;
        }

        move_uploaded_file($_FILES['inputfile']['tmp_name'], $destiation_dir);
        $this->ConTestPageGenerator($_FILES['inputfile']['name']);
        return true;
    }


    function ConTestPageGenerator($name)
    {


        $fd = fopen($this->base_dir . $name, 'r') or die("Bad txt.");
        $types = [];
        $tasks = [];
        $score = [];
        $desc = [];
        $answers = [];
        $contestname = htmlentities(fgets($fd));


        while (!feof($fd)) {
            $data = htmlentities(fgets($fd));

            if (strpos($data, '#typeoftask=') !== false) {
                $data = str_replace('#typeoftask=', '', $data);
                array_push($types, $data);
            }

            if (strpos($data, '#taskname=') !== false) {
                $data = str_replace('#taskname=', '', $data);
                array_push($tasks, $data);
            }

            if (strpos($data, '#score=') !== false) {
                $data = str_replace('#score=', '', $data);
                array_push($score, $data);
            }

            if (strpos($data, '#description=') !== false) {
                $data = str_replace('#description=', '', $data);
                array_push($desc, $data);
            }

            if (strpos($data, '#answer=') !== false) {
                $data = str_replace('#answer=', '', $data);
                array_push($answers, $data);
            }
        }
        $counter = 0;
        foreach ($types as $value)
            $counter = $counter + 1;
        $templ =
            "
<?php
session_start();
require '../PHP/top_nav.php';
require '../PHP/checker.php';
require '../PHP/mysqli_conn.php';
\$login = \$_SESSION['login'];
\$contestname = '$contestname';
\$handle = new MySQLConn;
\$handle = \$handle->mysql;
";

        for ($i = 0; $i < $counter; $i++)
            $templ .= "
    \$answer = '" . $answers[$i] . "';
    if (isset(\$_POST['" . 'task_id' . $i . "']))
    if (\$_POST['" . 'task_id' . $i . "'] === trim(\$answer))
    {
    \$name = 'task_id" . $i . "';
    \$query = \$handle->query(\"SELECT solved FROM usersdata WHERE login='{\$login}'\");
    \$query = \$query->fetch_assoc();
    \$solved_str = '';
	foreach (\$query as \$value)
		\$solved_str .= \$value;
    if (strpos(\$solved_str,\$contestname.\$name) !== false){
    die('Уже решыл герой!');
    }
        echo 'cool!';
        
        \$score = \$handle->query(\"SELECT score FROM usersdata WHERE login='{\$login}'\");
        \$score = \$score->fetch_assoc();
        \$forscore = " . $score[$i] . ";
        \$updatedscore = (int)\$score['score'] + \$forscore;
        \$handle->query(\"UPDATE usersdata SET score={\$updatedscore} WHERE login='{\$login}'\");
        \$solved = \$handle->query(\"SELECT solved FROM usersdata WHERE login='{\$login}'\");
        \$solved = \$solved->fetch_assoc();
        \$solved_str = '';
        foreach (\$solved as \$value)
        \$solved_str .= \$value;
        \$handle->query(\"UPDATE usersdata SET solved='\".\$solved_str.' '.\$contestname.\$name.\"' WHERE login='{\$login}'\");
        unset(\$_POST['" . 'task_id' . $i . "']);
    } else {echo 'Neto';}
    ";
        $templ .= "
?>
    <!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>BlueBottle</title>
    <link rel='stylesheet' href='../css/style.css'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
</head>

<body>
    <?php 
GetTopNav();
?>";

        $out = '';


        for ($i = 0; $i < $counter; $i++)
            $out .= "<form method='post'><div class='task-collection'><div class='task_block'><h2>{$tasks[$i]}</h2> <b>{$score[$i]}</b> <p>{$types[$i]}</p> <p>{$desc[$i]}</p> <input class='answer-input' type='text' name='" . 'task_id' . $i . "' value='BB{rofl}' onfocus=\"if (this.value == 'BB{rofl}') this.value = '';\"
                        onblur=\"if (this.value == '') this.value = 'BB{rofl}';\"/>
                    <button class='done-button'>
                        ну я решыл
                    </button></div></div></form>";

        $templ .= $out . '</body></html>';
        fclose($fd);

        $fd = fopen($this->base_dir . trim($contestname) . '.php', 'w+') or die("went wrong");
        fwrite($fd, $templ);
        fclose($fd);


    }


}


/*
<div class='task-line'>
        <h2>Crypto:</h2>
    </div>
    <div class='task-line'>
        <h2>PWN:</h2>
        <div class='task-block'><a href='#'>200</a></div>
        <div class='task-block'></div>
    </div>
    </div>
</body>
</html>



if(isset($_FILES) && $_FILES['inputfile']['error'] == 0){ // Проверяем, загрузил ли пользователь файл

$destiation_dir = dirname(__FILE__) .'/contests/'.$_FILES['inputfile']['name']; // Директория для размещения файла
$allowed = array('zip');
$filename = $_FILES['inputfile']['name'];
$ext = pathinfo($filename, PATHINFO_EXTENSION);
if(!in_array($ext,$allowed) ) {
echo 'Bad exstension. Only zip allowed.';
} else {

move_uploaded_file($_FILES['inputfile']['tmp_name'], $destiation_dir ); // Перемещаем файл в желаемую директорию
$zip = new ZipArchive;
if ($zip->open(dirname(__FILE__) .'/contests/'.$_FILES['inputfile']['name']) === TRUE) {

// путь к каталогу, в который будут помещены файлы
$zip->extractTo(dirname(__FILE__) .'/contests/');
$zip->close();
unlink(dirname(__FILE__) .'/contests/'.$_FILES['inputfile']['name']);
$scan = scandir("./contests/");
$names = [];
foreach ($scan as $value)
if ($value != '.' && $value!='..')=
array_push($names,$value);
foreach ($names as $value)
{
$scan = scandir("./contests/".$value);
$line = [];
foreach ($scan as $value)
if ($value != '.' && $value!='..')

{
$value = str_replace('.txt','',$value);
$wr = fopen("./contests/".$value.".php", 'w+') or die("не удалось создать файл");
$fd = fopen("./contests/".$value."/".$value.".txt", 'r') or die("Bad files.");
while (!feof($fd))
array_push($line,fgets($fd));
$str = "
<?php 
if (isset(\$_POST['flag']))
if (\$_POST['flag'] != '{$line[3]}') echo 'Bad answer'; else echo 'good boy!';


?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <title>BlueBottle</title>
    <link rel='stylesheet' href='../css/style.css'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <!--===============================================================================================-->
    <link rel='icon' type='image/png' href='../images/icons/favicon.ico' />
    <!--===============================================================================================-->
    <link rel='stylesheet' type='text/css' href='../vendor/bootstrap/css/bootstrap.min.css'>
    <!--===============================================================================================-->
    <link rel='stylesheet' type='text/css' href='../fonts/font-awesome-4.7.0/css/font-awesome.min.css'>
    <!--===============================================================================================-->
    <link rel='stylesheet' type='text/css' href='../fonts/Linearicons-Free-v1.0.0/icon-font.min.css'>
    <!--===============================================================================================-->
    <link rel='stylesheet' type='text/css' href='../vendor/animate/animate.css'>
    <!--===============================================================================================-->
    <link rel='stylesheet' type='text/css' href='../vendor/css-hamburgers/hamburgers.min.css'>
    <!--===============================================================================================-->
    <link rel='stylesheet' type='text/css' href='../vendor/animsition/css/animsition.min.css'>
    <!--===============================================================================================-->
    <link rel='stylesheet' type='text/css' href='../vendor/select2/select2.min.css'>
    <!--===============================================================================================-->
    <link rel='stylesheet' type='text/css' href='../vendor/daterangepicker/daterangepicker.css'>
    <!--===============================================================================================-->
    <link rel='stylesheet' type='text/css' href='../css/util.css'>
    <link rel='stylesheet' type='text/css' href='../css/main.css'>
    <!--===============================================================================================-->
</head>

<body>
    <div class='topnav'>
        <a class='active' href='/bluebottle/index.php'>Главная</a>
        <a href='#'>Скорборд</a>
        <a href='contests.php'>Контесты</a>
        <?php if (\$_SESSION['is_auth'] != true) {?>
        <a href='/bluebottle/login.php' style='float:right;'>Вход</a>
        <a href='/bluebottle/register.php' style='float:right;'>Регистрация</a>
        <?php } else { ?>
        <a href='/bluebottle/PHP/closesession.php' style='float:right;'>Выход</a>
        <?php if (\$_SESSION['admin']) { ?>
        <a style='float:right; color: red;' href='/bluebottle/panel.php'>You're poweruser!</a>
        <?php }?>
        <a style='float:right;'>
            <?php echo \$_SESSION['login']; ?></a>
        <?php } ?>

    </div>

    <div class='task_block'>
        <h2>{$line[0]}</h2>
        <b>{$line[1]}</b>
        <p>{$line[2]}</p>
        <form class='login100-form validate-form flex-sb flex-w' method='post'>
            <div class='wrap-input100 validate-input m-b-16' data-validate='Э, пустой мне не нужен. Флаг давай.'>
                <input class='input100' type='text' name='flag' placeholder='BB{}'>
                <span class='focus-input100'></span>
            </div>
            <div class='container-login100-form-btn m-t-17'>
                <button class='login100-form-btn'>
                    Отправить флог
                </button>
            </div>
    </div>
    </form>
</body>

</html>";
fwrite($wr, $str);
fclose($fd);
fclose($wr);



}


echo 'Done it';
}

}


else {
echo 'Bad zip';
}
}

}

else
echo 'No File Uploaded'; // Оповещаем пользователя о том, что файл не был загружен
*/
?>
