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
\$solved = \$handle->query(\"SELECT solved FROM usersdata WHERE login='{\$login}'\");
\$solved = \$solved->fetch_assoc();
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
";

        $out = '';


        for ($i = 0; $i < $counter; $i++) {
            $out .= "
\$success = 'task_block';
if (in_array('{$contestname} '.'task_id'.'{$i}',\$solved))
    \$success = 'task_block_success'; 
  
 echo \\\"<form method='post'><div class='task-collection'><div class='{\$success}'><h2>{$tasks[$i]}</h2> <b>{$score[$i]}</b> <p>{$types[$i]}</p> <p>{$desc[$i]}</p> <input class='answer-input' type='text' name='" . 'task_id' . $i . "' value='BB{rofl}' onfocus=\"if (this.value == 'BB{rofl}') this.value = '';\"
                        onblur=\"if (this.value == '') this.value = 'BB{rofl}';\"/>
                    <button class='done-button'>
                        ну я решыл
                    </button></div></div></form>\\\" ?>|";
        }

        $templ .= $out . '</body></html>';
        fclose($fd);

        $fd = fopen($this->base_dir . trim($contestname) . '.php', 'w+') or die("went wrong");
        fwrite($fd, $templ);
        fclose($fd);


    }


}

?>
