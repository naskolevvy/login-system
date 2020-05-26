<?php 
    require 'header.php';
?>

    <main>
        <?php
            if(isset($_SESSION['username'])){
                echo 'You are logged in: '.$_SESSION['username'];
                echo '<br><a href="change-password.php">change pass</a><br>';
            }else{
                echo 'You are logged out<br>';
            }

            //var_dump($_GET);  //use the get variale to sort out the error handling
        ?>
        
    </main>

<?php 
    require 'footer.php';
?>