<?php
    $conn = mysqli_connect("localhost", "root", "");

    session_start();

    $password = "";

    if(!empty($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        header("location: quests.php");
    }
?>

<html>
    <head>
        <title>NEW PASSWORD</title>
        <link rel="stylesheet" href="landsOfLogic.css">
    </head>

    <body>
        <header>
            <h1 id="logo"><sup id="logoTHE">THE</sup>Lands of Logic</h1>
        </header>

        <section class="newPassword">
            <h1>Reset Password</h1>
            <form action = "newPword.php" method = "post">
                <label for="password">New Password</label>
                <input type="password" name="password" id="password"  required>
                <br>
                <label for="verifyPassword">Verify Password:</label>
                <input type="password" name="verifyPassword" id="verifyPassword"  required>
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

        if($password == $verifyPassword) {
            $query = "UPDATE users SET password = '$password' WHERE id = '$id'";
            mysqli_query($conn, $query);
        } else {
            Print '<script>alert("Passwords do not match");</script>")';
            Print '<script>window.location.assign("newPword.php");</script>")';
        }
    }
?>