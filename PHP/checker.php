<?
session_start();

function protect_page (){
    if ($_SESSION["is_auth"] != true) {
        header("Location: login.php"); 
        return false;
    }
    else return true;
}
?>