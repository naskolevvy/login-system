<?php

//if the user entered this page by clicking the specific button
if(isset($_POST['reset-request-submit'])){

    //generate the two tokens
    $selector = bin2hex(random_bytes(8));
    $token = random_bytes(32);

    //create the url for the websitte
    $url = "localhost/loginsystem/create-new-password.php?selector=".$selector."&validator=".bin2hex($token);
    //the token expires after some time
    $expires = date("U")+1800;
    //set the connection to db 
    require 'dbHandler.inc.php';

    //delete any previous tokens for the specific user
    $userMail = $_POST['email'];
    $sql = 'delete from pwdReset where pwdResetEmail=?';
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){
        echo 'ERROR !';
        exit();
    }else{
        mysqli_stmt_bind_param($stmt,'s',$userMail);
        mysqli_stmt_execute($stmt);
    }

    //insert the new data into the db
    $sql = "insert into pwdReset (pwdResetEmail,pwdResetSelector,pwdResetToken,pwdResetExpires) values (?,?,?,?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){
        echo 'ERROR !';
        exit();
    }else{
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt,'ssss',$userMail,$selector,$hashedToken,$expires);
        mysqli_stmt_execute($stmt);
    }
    //close the db connections and statements
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    //email stuff setup
    /*
    $to = $userMail;
    $subject = "Reset password";
    $message = '<p>For password reset press this link: </p><br><a href = "'.$url.'">'.$url.'</a>';
    $headers = "From: website <atanaskolev@gmail.com>\r\n Reply-To: atanaskolev@gmail.com\r\n Content-type: text/html\r\n";

    mail($to, $subject, $message, $headers);

    header("Location: ../reset-pasword.php?reset=success");
    exit();
    */

    //current solution - copy this link and paste it in the browser
    header("Location: ../reset-password.php?link=".$url);
    exit();


}else{
    header("Location: ../index.php");
    exit();
}