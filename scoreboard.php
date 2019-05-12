<?php
session_start();
require "PHP/top_nav.php";
require "PHP/mysqli_conn.php";

$handle = new MySQLConn;
$handle = $handle->mysql;

$query = $handle->query("SELECT login,score,admin FROM usersdata ORDER BY score DESC");
$counter = 1;
$handle->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>BlueBottle</title>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
</head>

<body>
<?php
GetTopNav();
?>
<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Имя</th>
        <th scope="col">Очки</th>
    </tr>
    </thead>
    <tbody>
    <?php
    while ($row = $query->fetch_assoc()) {

        if ($row['admin'])
            continue;
        if ($counter == 1){
        echo sprintf("<tr>
           <th scope=\"row\">%d</th>
           <td><span class='top1'>%s</span></td>
           <td>%s</td>
       </tr>", $counter, $row["login"], $row["score"]);
    }
        if ($counter == 2){
            echo sprintf("<tr>
           <th scope=\"row\">%d</th>
           <td><span class='top2'>%s</span></td>
           <td>%s</td>
       </tr>", $counter, $row["login"], $row["score"]);
        }

        if ($counter == 3){
            echo sprintf("<tr>
           <th scope=\"row\">%d</th>
           <td><span class='top3'>%s</span></td>
           <td>%s</td>
       </tr>", $counter, $row["login"], $row["score"]);

        }
        $counter++;
    }
    ?>
    </tbody>
</table>
</body>

</html>
