<?php

if(isset($_POST['reset-password-submit'])){

    $selector = $_POST['selector'];
    $validator = $_POST['validator'];
    $pwd = $_POST['pwd'];
    $pwdRepeat = $_POST['pwd-repeat'];

    if(empty($pwd) || empty($pwdRepeat)){
        header("Location: ../create-new-password.php?pwd=empty&selector=".$selector."&validator=".$validator);
        exit();
    }else if($pwd !== $pwdRepeat){
        header("Location: ../create-new-password.php?pwd=norepeat&selector=".$selector."&validator=".$validator);
        exit();
    }

    $currentDate = date('U');

    require 'dbHandler.inc.php';

    $sql = "select * from pwdReset where pwdResetSelector=? and pwdResetExpires >=".$currentDate;
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){
        echo 'ERROR time !'.$selector.$validator.$pwd.$pwdRepeat;
        exit();
    }else{
        mysqli_stmt_bind_param($stmt,'s',$selector);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        if(!$row = mysqli_fetch_assoc($result)){
            echo 'ERROR resubmit!';
            exit();
        }else{
            $tokenBin = hex2bin($validator);
            $tokenCheck = password_verify($tokenBin,$row['pwdResetToken']);
            if($tokenCheck === false){
                echo 'ERROR wrong token!';
                exit();
            }elseif($tokenCheck === true){
                $tokenEmail = $row['pwdResetEmail'];

                $sql = "select * from users where email=?";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt,$sql)){
                    echo 'ERROR !';
                    exit();
                }else{
                    mysqli_stmt_bind_param($stmt,'s',$tokenEmail);
                    mysqli_stmt_execute($stmt);

                    $result = mysqli_stmt_get_result($stmt);
                    if(!$row = mysqli_fetch_assoc($result)){
                        echo 'ERROR with email!'.$tokenEmail;
                        exit();
                    }else{
                        $sql = "update users set pass=? where email=?";
                        $stmt = mysqli_stmt_init($conn);
                        if(!mysqli_stmt_prepare($stmt,$sql)){
                            echo 'ERROR somewhere!';
                            exit();
                        }else{
                            $hashedPassword = password_hash($pwd, PASSWORD_DEFAULT);
                            mysqli_stmt_bind_param($stmt,'ss',$hashedPassword,$tokenEmail);
                            mysqli_stmt_execute($stmt);

                            $sql = "delete from pwdReset where pwdResetEmail=?";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt,$sql)){
                                echo 'ERROR somewhere!';
                                exit();
                            }else{
                                mysqli_stmt_bind_param($stmt,'s',$tokenEmail);
                                mysqli_stmt_execute($stmt);
                                header("Location: ../index.php?pwd=updated");
                                exit();
                            }
                        }        
                    }
                }
            }
        }
    }

}else{
    header("Location: ../index.php");
    exit();
}