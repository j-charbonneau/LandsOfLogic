<?php
    
?>

<html>
    <head>
        <title>Forgot Password</title>
    </head>

    <body>
        <header>
            <h1 id="logo"><sup id="logoTHE">THE</sup>Lands of Logic</h1>
        </header>

        <a id="returnToLogin" href="login.php">Return to Login</a>

        <article class="forgotPasswordDescription">
            <br>
            <h3>To Reset Your Password:</h3>
            <ul>
                <li>Enter your email below</li>
                <li>Check your email for a password reset link</li>
            </ul>
        </article>

        <article class="forgotPasswordForm">
            <form action="resetPassword.php" method="post">
                <label for="email">Email:</label><br>
                <input type="email" name="email" id="email" /> <br />
                <input type="submit" value="Submit" />
            </form>
        </article>
    </body>
</html>