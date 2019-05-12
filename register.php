<?php
session_start();
require "PHP/top_nav.php";
require "PHP/mysqli_conn.php";

if ($_SESSION["is_auth"] == true) {
    header ("Location: index.php");
}

$handle = new MySQLConn;
$handle = $handle->mysql;

if(isset($_POST['submit']))

{

    $err = array();


    # проверям логин

    if(!preg_match("/^[a-zA-Z0-9._]+$/",$_POST['login']))

    {

        $err[] = "Логин может состоять только из букв английского алфавита и цифр";

    }
    
    if ($_POST['login'] == "Login") $err[] = "Выберите другой логин";
    
    if ($_POST['password'] != $_POST['password-retype']) $err[] = "Введенные пароли не совпадают";

    

    if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)

    {

        $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";

    }

    

    # проверяем, не сущестует ли пользователя с таким именем

    $query = $handle->query("SELECT COUNT(id) FROM usersdata WHERE login='".trim($_POST['login'])."'");
    $result = $query->fetch_assoc();
    if($result['COUNT(id)'] > 0)

    {

        $err[] = "Пользователь с таким логином уже существует в базе данных";

    }
    
    $query = $handle->query("SELECT COUNT(id) FROM usersdata WHERE ip='".$_SERVER['REMOTE_ADDR']."'");
    $result = $query->fetch_assoc();
    if($result['COUNT(id)'] > 0)

    {

        $err[] = "Возможно зарегестрировать только одного пользователя";

    }
    

    # Если нет ошибок, то добавляем в БД нового пользователя

    if(count($err) == 0)

    {

        
        $login = $_POST['login'];

        

        # Убераем лишние пробелы и делаем двойное шифрование

        $password = md5(md5(trim($_POST['password'])));

        

        $handle->query("INSERT INTO usersdata (login,password,ip) VALUES ('".$login."','".$password."','".$_SERVER['REMOTE_ADDR']."')");

        header("Location: login.php"); exit();

    }

    else

    {

        echo "<b style='color: red;'>При регистрации произошли следующие ошибки:</b><br>";

        foreach($err AS $error)

        {

            echo $error."<br>";

        }

    }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BlueBottle</title>
    <link rel="stylesheet" href="css/style.css">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
    <?php 
    GetTopNav();
    ?>
    <div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 p-t-50 p-b-90">
				<form class="login100-form validate-form flex-sb flex-w" method="post">
					<span class="login100-form-title p-b-51">
						Регистрация
					</span>

					
					<div class="wrap-input100 validate-input m-b-16" data-validate = "Username is required">
						<input class="input100" type="text" name="login" placeholder="Username">
						<span class="focus-input100"></span>
					</div>
					
					
					<div class="wrap-input100 validate-input m-b-16" data-validate = "Password is required">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100"></span>
					</div>
					
					<div class="wrap-input100 validate-input m-b-16" data-validate = "Password is required">
						<input class="input100" type="password" name="password-retype" placeholder="Retype password">
						<span class="focus-input100"></span>
					</div>

					<div class="container-login100-form-btn m-t-17">
						<button class="login100-form-btn" type="submit" name="submit">
							ОК
						</button>
					</div>

				</form>
			</div>
		</div>
	</div>
   <div id="dropDownSelect1"></div>

	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>
    
</body>
</html>