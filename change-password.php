<?php 
    session_start();
?>

    <main>
        <h1>change password</h1>            
        <?php 

            if($_SESSION['username']){
                    ?>
                    <form action="includes/change-password.inc.php" method="post">
                        <input type="password" name="pwd" placeholder="enter new pass">
                        <input type="password" name="pwd-repeat" placeholder="repeat new pass">
                        <button type="submit" name='change-password-submit'>reset password</button>
                    </form>
                    <?php
                }
        ?>  
        
    </main>
