<?php
    session_start();

    if(isset($_SESSION['id'])) {
        global $_SESSION;
        $id = $_SESSION['id'];
        $character = $_SESSION['character'];
    } else {
        header("location:login.php");
    }

    $password = "";

    $conn = mysqli_connect("localhost", "root", "", "landsoflogic");

    $query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");

    while ($row = mysqli_fetch_assoc($query)) {
        $password = $row['password'];
    }
?>


<html>
    <head>
        <title>ACCOUNT: Change Password</title>
        <link rel="stylesheet" type="text/css" href="landsOfLogic.css">
    </head>

    <body>
        <header>
            <h1 id="logo"><sup id="logoTHE">THE</sup>Lands of Logic</h1>
        </header>

        <nav>
            <a href="home.php" class="nav">
                <h1><?php echo $character ?></h1>
            </a>

            <a href="quests.php" class="nav">
                <h1>Quest Board</h1>
            </a>

            <a href="shops.php" class="nav">
                <h1>The Market</h1>
            </a>

            <a href="arena.php" class="nav">
                <h1>The Arena</h1>
            </a>

            <a href="account.php" class="nav">
                <h1>Account</h1>
            </a>
        </nav>

        <section class = "changePassword">
            <h1>Change Password</h1>
            <form action="changePassword.php" method="post">
                <label for="password">New Password:</label>
                <input type="password" name="password" id="password" required>
                <br>
                <label for="verifyPassword">Verify Password:</label>
                <input type="password" name="verifyPassword" id="verifyPassword" required>
                <br>
                <input type="submit" value="Submit">
            </form>
        </section>
    </body>
</html>

<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $password = $_POST['password'];
        $verifyPassword = $_POST['verifyPassword'];

        if($password == $verifyPassword){
            $query = mysqli_query($conn, "UPDATE users SET password = '$password' WHERE id = '$id'");
        } else {
            Print '<script>alert("Passwords do not match.");</script>';
            Print '<script>window.location.assign("changePassword.php");</script>';
        }
    }
?>