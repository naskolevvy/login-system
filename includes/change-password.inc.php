<?php 
session_start();
if(isset($_POST['change-password-submit'])){ //check if the page is opened from change pass link
    require 'dbHandler.inc.php';
    $pwd = $_POST['pwd'];
    $pwdRepeat = $_POST['pwd-repeat'];

    if($pwd === $pwdRepeat){ //if the passwords match update db

        $sql = "update users set pass=? where username=?";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt,$sql)){
            echo 'ERROR time !';
            exit();
        }else{
            $pwdHashed = password_hash($pwd,PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt,'ss',$pwdHashed,$_SESSION['username']);
            mysqli_stmt_execute($stmt);

            header("Location: ../index.php?pwdchange=true");
        }
    }
}else{
    header("Location: ../index.php");
}