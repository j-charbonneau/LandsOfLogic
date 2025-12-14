<html>
    <head>
        <title>Login</title>
    	<link rel="stylesheet" href="landsOfLogic.css">
    </head>

    <body>
        <header>
            <h1 id="logo"><sup id="logoTHE">THE</sup>Lands of Logic</h1>
        </header>

        <a id="switchLoginType" href="register.php">Don't have an account? Register here!</a>

        <article class="loginDesc">
            <br />
            <h3>Enter the Lands of Logic</h3>
            <p>Here' you'll find adventure mixed with education. Once you enter the Lands of Logic,
                you'll be able to embark on quests with a variety of townsfolk, helping them through
                their daily problems.</p>
            <br />
            <p>Are <strong id="you">you</strong> up to the challenge?</p>
            <br />
            <br />
        </article>

        <article class="login">
            <h3 id="loginHeader">LOGIN</h3>
            <form action="checkLogin.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required /> <br />

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required /> <br />

                <input type="submit" value="Login" />
            </form>

            <a id="forgotPassword" href="forgotPassword.php">Forgot Password</a>
        </article>
    </body>
</html>