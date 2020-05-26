<?php 
   session_start();
?>
    <main>
        <p>SIGN UP</p>
        <form action="includes/signup.inc.php" method="post">
            <input type="text" name = 'name' placeholder="name">
            <input type="password" name = 'password' placeholder="password">
            <input type="email" name = 'email' placeholder="email">
            <button type="submit" name='signup-submit'>sign up</button>
        </form>
        <a href="index.php">Go to Login</a>
    </main>

<?php 
    require 'footer.php';
?>