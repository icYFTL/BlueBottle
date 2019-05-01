<?php 
require "PHP/checker.php";
include "PHP/file_handler.php";
require "PHP/top_nav.php";
protect_page();
if (!$_SESSION['admin']) header("Location: index.php");

if (isset($_FILES) && $_FILES['inputfile']['error'] == 0)
$filehandler = new FileHandler();

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
    <div style='margin: 0 auto; margin-top: 40px;'>
        <h1>Upload contest</h1><br>
        <form method="post" action="panel.php" enctype="multipart/form-data">
            <label for="inputfile">Upload File</label><br>
            <input type="file" id="inputfile" name="inputfile"><br>
            <input type="submit" value="Click To Upload">
        </form>
    </div>


</body>

</html>
