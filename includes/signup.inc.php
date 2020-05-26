<?php 
session_start();
//check if the user accessed this page by the submit button
if(isset($_POST['signup-submit'])){

    //connect to database
    require 'dbHandler.inc.php';

    //fetching the form info
    $username = $_POST['name'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    //form validation
    if(empty($username) || empty($password) || empty($email)){
        header('Location: ../signup.php?error=emptyfield&name='.$username."&email=".$email);
        exit();
    }elseif(!preg_match('/^[a-zA-Z0-9]*$/', $username)){
        header('Location: ../signup.php?error=invalidname');
        exit();
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header('Location: ../signup.php?error=invalidemail');
        exit();
    }else{ //username exists
        $sql = "select username from users where username=?";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt,$sql)){
            header('Location: ../signup.php?error=sqlerror');
            exit();
        }else{
            mysqli_stmt_bind_param($stmt,'s',$username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt); //fetch info from db
            $check = mysqli_stmt_num_rows($stmt);
            if($check > 0){
                header('Location: ../signup.php?error=repeatname');
                exit();
            } else{ //check email 
                $sql = "select email from users where email=?";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt,$sql)){
                    header('Location: ../signup.php?error=sqlerror');
                    exit();
                }else{
                    mysqli_stmt_bind_param($stmt,'s',$email);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt); //fetch info from db
                    $check = mysqli_stmt_num_rows($stmt);
                    if($check > 0){
                        header('Location: ../signup.php?error=repeatemail');
                        exit();
                    }else{
                        $sql = "insert into users (username,email,pass) values (?,?,?)";
                        $stmt = mysqli_stmt_init($conn);
                        if(!mysqli_stmt_prepare($stmt,$sql)){
                            header('Location: ../signup.php?error=sqlerror&');
                            exit();
                        }else{ //succesfull signup
                            $hashedPass = password_hash($password, PASSWORD_DEFAULT);
                            mysqli_stmt_bind_param($stmt,'sss',$username,$email,$hashedPass);
                            mysqli_stmt_execute($stmt);
                            $_SESSION['username'] = $username;
                            header('Location: ../index.php?signup=success');
                            exit();
                        }
                    }
                }
            }
        }
    }
    //close the connection with the db
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}else{
    header('Location: ../signup.php');
    exit();
}