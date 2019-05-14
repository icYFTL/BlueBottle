<?php
session_start();
require "PHP/top_nav.php";
require "PHP/mysqli_conn.php";

class AuthClass
{

    private $handle;
    private $userdata;

    function __construct()
    {
        $this->handle = new MySQLConn;
        $this->handle = $this->handle->mysql;
    }

    public function isAuth()
    {
        return isset($_SESSION["is_auth"]) ? $_SESSION["is_auth"] : false;
    }

    public function auth($login, $password)
    {
        if ($this->isAuth())
            header("Location: contests.php");
        if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['login'])) {
            return false;
        }
        $this->userdata = $this->handle->query(sprintf("SELECT * FROM usersdata WHERE login='%s'", $login))->fetch_assoc();
        $this->handle->close();
        if (md5(md5($password)) == $this->userdata['password']) {
            $_SESSION["is_auth"] = true;
            $_SESSION["login"] = $login;
            $_SESSION['admin'] = $this->userdata['admin'];
            header("Location: contests.php");
            return true;
        } else {
            $_SESSION["is_auth"] = false;
            return false;
        }

    }

    public function getLogin()
    {
        if ($this->isAuth()) {
            return $_SESSION["login"];
        }
    }


    public function out()
    {
        $_SESSION = array();
        session_destroy();
    }
}

$auth = new AuthClass();


if (isset($_GET["is_exit"])) {
    if ($_GET["is_exit"] == 1) {
        $auth->out();
        header("Location: ?is_exit=0");
    }
}
if (isset($_POST['login']) && isset($_POST['password'])) {
    $auth->auth($_POST['login'], $_POST['password']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Benefactori</title>
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
						Войти
					</span>


                <div class="wrap-input100 validate-input m-b-16" data-validate="Username is required">
                    <input class="input100" type="text" name="login" placeholder="Username">
                    <span class="focus-input100"></span>
                </div>


                <div class="wrap-input100 validate-input m-b-16" data-validate="Password is required">
                    <input class="input100" type="password" name="password" placeholder="Password">
                    <span class="focus-input100"></span>
                </div>

                <div class="flex-sb-m w-full p-t-3 p-b-24">
                    <div class="contact100-form-checkbox">
                        <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
                        <label class="label-checkbox100" for="ckb1">
                            Запомнить пароль
                        </label>
                    </div>
                </div>
                <div class="container-login100-form-btn m-t-17">
                    <button class="login100-form-btn">
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