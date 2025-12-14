<?php
session_start();

if (isset($_SESSION['id'])) {
    header("location: home.php");
    exit;
}

$conn = mysqli_connect("sql110.infinityfree.com", "if0_40582300", "uj0krRpEXI", "if0_40582300_LandsOfLogic");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $newPassword = mysqli_real_escape_string($conn, $_POST['newPassword']);
    $verifyPassword = mysqli_real_escape_string($conn, $_POST['verifyPassword']);

    if ($newPassword !== $verifyPassword) {
        $message = "New passwords do not match.";
    } else {
        $q = mysqli_query($conn, "SELECT playerId FROM users WHERE username = '$username'AND email    = '$email'");

        if ($row = mysqli_fetch_assoc($q)) {
            $playerId = (int)$row['playerId'];

            mysqli_query($conn, "UPDATE users SET password = '$newPassword' WHERE playerId = '$playerId'");

            Print "<script>alert('Password reset successfully! Please log in.'); 
                    window.location.href = 'login.php';
                  </script>";
            exit;
        } else {
            $message = "No account found with that username + email.";
        }
    }
}
?>

<html>
    <head>
        <title>Forgot Password</title>
        <link rel="stylesheet" type="text/css" href="landsOfLogic.css">
    </head>
    <body id="indexBody">
        <header>
            <h1 id="logo"><sup id="logoTHE">THE</sup>Lands of Logic</h1>
        </header>

        <a id="switchLoginType" href="login.php">Back to Login</a>

        <article class="loginDesc">
            <br />
            <h3>Forgot Your Password?</h3>
            <p>Enter your username and email to reset your password.</p>
            <br />
            <?php
                if (!empty($message)) {
                    Print '<p class="error">' . htmlspecialchars($message) . '</p>';
                }
            ?>
        </article>

        <article class="createAccount">
            <h3 id="createAccountHeader">RESET PASSWORD</h3>
            <form action="forgotPassword.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required /><br />

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required /><br />

                <label for="newPassword">New Password:</label>
                <input type="password" id="newPassword" name="newPassword" required /><br />

                <label for="verifyPassword">Verify New Password:</label>
                <input type="password" id="verifyPassword" name="verifyPassword" required /><br />

                <input type="submit" value="Reset Password" />
            </form>
        </article>
    </body>
</html>
