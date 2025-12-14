<?php
    session_start();

    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
        $character = $_SESSION['character'];
    } else {
        header("location:login.php");
        exit;
    }

    $conn = mysqli_connect("sql110.infinityfree.com", "if0_40582300", "uj0krRpEXI", "if0_40582300_LandsOfLogic");

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    $email = "";
    $message = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['email'])) {
            $newEmail = mysqli_real_escape_string($conn, $_POST['email']);

            $updateSql = " UPDATE users SET email = '$newEmail' WHERE playerId = '$id'";

            if (mysqli_query($conn, $updateSql)) {
                $message = "Email updated successfully.";
            } else {
                $message = "Error updating email: " . mysqli_error($conn);
            }
        } else {
            $message = "Please enter an email.";
        }
    }

    $query = mysqli_query($conn, "SELECT email FROM users WHERE playerId = '$id'");

    if ($row = mysqli_fetch_assoc($query)) {
        $email = $row['email'];
    }
?>

<html>
    <head>
        <title>ACCOUNT: Update Email</title>
        <link rel="stylesheet" type="text/css" href="landsOfLogic.css">
    </head>

    <body>
        <header>
            <h1 id="logo"><sup id="logoTHE">THE</sup>Lands of Logic</h1>
        </header>

        <nav>
            <a href="home.php" class="nav">
                <h1><?php Print $character; ?></h1>
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

        <section class="updateEmail">
            <h1>Update Email</h1>

            <?php
                if (!empty($message)) {
                    Print '<p class="error">' . htmlspecialchars($message) . '</p>';
                }
            ?>

            <form action="updateEmail.php" method="post">
                <label for="email">New Email:</label>
                <input type="email" name="email" id="email" value="<?php Print htmlspecialchars($email); ?>" required>
                <br><br>
                <input type="submit" value="Submit">
            </form>
        </section>
    </body>
</html>