<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width= , initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <?php 
            if(!isset($_SESSION['username'])){ //if not logged in

                if(!empty($_COOKIE['username']) && !empty($_COOKIE['password']) && !empty($_COOKIE['selector'])){
                    //check for authentic cookies
                    require 'includes/dbHandler.inc.php';
                    $sql = 'select * from remember_me where username=?';
                    $stmt = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt,$sql)){
                        header('Location: ../index.php?error=sqlerror');
                        exit();
                    }else{
                        mysqli_stmt_bind_param($stmt,'s',$_COOKIE['username']);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        if($row = mysqli_fetch_assoc($result)){
                            $pwdCheck = password_verify($_COOKIE['password'], $row['hash_pass']);
                            $selectorCheck = ($_COOKIE['selector']==$row['hash_token'])? true: false;
                            $timeNow = time();
                            $expire = strtotime($row['expireDate']);
                            if($pwdCheck == true && $selectorCheck == true && $timeNow < $expire){
                                $_SESSION['username']=$row['username'];
                                echo '
                                    <form action="includes/logout.inc.php" method = "post">
                                        <button type="submit" name="logout-submit">Logout (cookies)</button>
                                    </form>
                                ';
                            }
                            var_dump($pwdCheck);
                        }
                        
                    }
                }else{
                    echo '<form action="includes/login.inc.php" method = "post">
                    <input type="text" name="name" placeholder="username">
                    <input type="password" name="password" placeholder="pass">
                    <input type="checkbox" name = "remember-me">Remember me</input>
                    <button type="submit" name="login-submit">Login</button>
                    </form>
                    
                    <a href="signup.php">SIGN UP</a>
                    <a href="reset-password.php">Forgotten Password</a>

                    ';
                }
                
            }else{
                echo '
                
                <form action="includes/logout.inc.php" method = "post">
                    <button type="submit" name="logout-submit">Logout </button>
                </form>
                ';
            }
        ?>    
    </div>
        
    
</body>
</html>