<?php 

//check if the user accessed this page by the submit button
if(isset($_POST['login-submit'])){

    //connect to database
    require 'dbHandler.inc.php';

    //fetching the form info
    $username = $_POST['name'];
    $password = $_POST['password'];

    if(empty($username) || empty($password)){
        header("Location: ../index.php?error=emptyfield&name=".$username);
        exit();
    }
    else{
        $sql = 'select * from users where username=?';
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt,$sql)){
            header('Location: ../index.php?error=sqlerror');
            exit();
        }else{
            mysqli_stmt_bind_param($stmt,'s',$username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if($row = mysqli_fetch_assoc($result)){
                $pwdCheck = password_verify($password, $row['pass']);
                if($pwdCheck == false){
                    header('Location: ../index.php?error=wrongpass');
                    exit();
                }else if($pwdCheck == true){ //login here, start a session
                    

                    session_start();
                    //$_SESSION['id'] = $row['iduser'];
                    $_SESSION['username'] = $row['username'];

                    if(isset($_POST['remember-me'])){ //if remember me is selected 
                        if($_POST['remember-me'] == true){
                            //create cookies and session 
                            $current_time = time();
                            $exparation_time = $current_time + 24*60*60; //1day

                            $random_pass = bin2hex(random_bytes(8));
                            $random_selector = bin2hex(random_bytes(10));
                            setcookie('username', $username, $exparation_time, "/");
                            setcookie('password',$random_pass,$exparation_time,'/');
                            setcookie('selector',$random_selector,$exparation_time,'/');

                            //put this info into db
                            $exparation_time_db = date('Y-m-d H:i:s',$exparation_time);
                            $hashedPass = password_hash($random_pass, PASSWORD_DEFAULT);

                            //delete previous records
                            $sql = 'delete from remember_me where username=?';
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt,$sql)){
                                header('Location: ../index.php?error=sqlerror');
                                exit();
                            }else{
                                mysqli_stmt_bind_param($stmt,'s',$username);
                                mysqli_stmt_execute($stmt);
                            }

                            //insert new record
                            $sql = 'insert into remember_me values (default,?,?,?,?)';
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt,$sql)){
                                header('Location: ../index.php?error=sqlerror');
                                exit();
                            }else{
                                mysqli_stmt_bind_param($stmt,'ssss',$username,$random_selector,$hashedPass,$exparation_time_db);
                                mysqli_stmt_execute($stmt);
                            }
                        }
                    }

                    header('Location: ../index.php?login=success');
                    exit();
                }
            }else{
                header('Location: ../index.php?error=nouser');
                exit();
            }
        }
    }


}else{
    header("Location: ../index.php");
    exit();
}