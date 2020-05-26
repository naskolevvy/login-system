<?php 
    session_start();
?>

    <main>
        
        <h1>Reset Password</h1>
        <p>Send link to the email</p>
        <form action="includes/reset-request.inc.php" method="post">
            <input type="text" name="email" placeholder="email">
            <button type="submit" name = 'reset-request-submit'>Send Link</button>
        </form>    
            
        <?php 
            if(isset($_POST['reset'])){
                if($_POST['reset'] == 'success'){
                    echo '<p>Reset is successfull!</p>
                        <a href="index.php">Go back to login!</a>
                    ';
                }
            }
            //current test solution, should be removed when mailer is sorted
            if(isset($_GET['link'])){
                $link = str_replace('localhost/', '', $_GET['link']);
                echo '<a href="../'.$link.'&validator='.$_GET['validator'].'">'.$_GET['link'].'&validator='.$_GET['validator'].'</a>';
            }
        ?>
        
    </main>

<?php 
    require 'footer.php';
?>