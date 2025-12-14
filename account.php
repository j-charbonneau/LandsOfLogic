<?php

    session_start();

    if (isset($_SESSION['id'])) {
        global $_SESSION;
        $character = $_SESSION['character'];
        $id = $_SESSION['id'];
    } else {
        header("location:login.php");
    }
?>

<html>
    <head>
        <title><?php Print $character ?>: Account Page</title>
        <link rel="stylesheet" type="text/css" href="landsOfLogic.css">
    </head>

    <body>
        <header>
            <h1 id="logo"><sup id="logoTHE">THE</sup>Lands of Logic</h1>
        </header>

        <nav>
            <a href="home.php" class="nav">
                <h1><?php Print $character ?></h1>
            </a>

            <a href="quests.php" class="nav">
                <h1>Quest Board</h1>
            </a>

            <a href="shops.php" class="nav">
                <h1>The Market</h1>
            </a>

            <a href="account.php" class="nav">
                <h1>Account</h1>
            </a>
        </nav>

        <section>
            <article class="accountOptions">
                <h1>Account Options</h1>
                <a href="updateEmail.php">Update Email</a>
                <a href="changePassword.php">Change Password</a>
                <a href="logout.php">Logout</a>
                <form action="deleteAccount.php" method="post"
                    onsubmit="return confirm('Are you sure you want to permanently delete your account?');">
                     <input type="submit" value="Delete Account">
                </form>
            </article>
        </section>
    </body>
</html>