<?php 
    session_start();
?>

    <main>
        <h1>Create new password</h1>            
        <?php 
            $selector = $_GET['selector'];
            $validator = $_GET['validator'];

            if(empty($selector) || empty($validator)){
                echo "missing info, can't validate the request";
            }else{ //check  if valid hex values
                if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false) {
                    ?>

                    <form action="includes/reset-password.inc.php" method="post">
                        <input type="hidden" name='selector' value="<?php echo $selector ?>">
                        <input type="hidden" name='validator' value="<?php echo $validator ?>">
                        <input type="password" name="pwd" placeholder="enter new pass">
                        <input type="password" name="pwd-repeat" placeholder="repeat new pass">
                        <button type="submit" name='reset-password-submit'>reset password</button>
                    </form>

                    <?php
                }
            }
        ?>  
        
    </main>
