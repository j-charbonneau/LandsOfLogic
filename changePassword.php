<?php
    session_start();

    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
        $character = $_SESSION['character'];
    } else {
        header("location: login.php");
        exit;
    }

    $conn = mysqli_connect("sql110.infinityfree.com", "if0_40582300", "uj0krRpEXI", "if0_40582300_LandsOfLogic");

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    $message = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST['password'] ?? '';
        $verifyPassword = $_POST['verifyPassword'] ?? '';

        if ($password !== $verifyPassword) {
            $message = "Passwords do not match.";
        } elseif ($password === '') {
            $message = "Password cannot be empty.";
        } else {
            $passwordEsc = mysqli_real_escape_string($conn, $password);

            $updateSql = " UPDATE users  SET password = '$passwordEsc' WHERE playerId = '$id'";

            if (mysqli_query($conn, $updateSql)) {
                echo "<script>
                        alert('Password updated successfully.');
                        window.location.href = 'account.php';
                      </script>";
                exit;
            } else {
                $message = 'Error updating password: ' . mysqli_error($conn);
            }
        }
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
                <h1><?php echo htmlspecialchars($character); ?></h1>
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

        <section class="changePassword">
            <h1>Change Password</h1>

            <?php
                if (!empty($message)) {
                    Print '<p class="error">' . htmlspecialchars($message) . '</p>';
                }
            ?>

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
