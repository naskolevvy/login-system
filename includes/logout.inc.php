<?php 

if(isset($_POST['logout-submit'])){
    session_start();
    $_SESSION['username'] = '';
    session_unset();
    session_destroy();

    setcookie("username", "", time() - 3600,'/');
    setcookie("password", "", time() - 3600,'/');
    setcookie("selector", "", time() - 3600,'/');
    header('Location: ../index.php');
    exit();
}
