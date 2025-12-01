<?php
    session_start();

    if(isset($_SESSION['id'])) {
        global $_SESSION;
        $id = $_SESSION['id'];
        $character = $_SESSION['character'];
    } else {
        header("location:login.php");
    }

    $email = "";

    $conn = mysqli_connect("localhost", "root", "", "landsoflogic");

    $query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");

    while ($row = mysqli_fetch_assoc($query)) {
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

        <section class = "updateEmail">
            <h1>Update Email</h1>
            <form action="updateEmail.php" method="post">
                <label for="email">New Email:</label>
                <input type="email" name="email" id="email">
                <br>
                <br>
                <input type="submit" value="Submit">
            </form>
        </section>
    </body>
</html>

<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];

        $query = "UPDATE users SET email = '$email' WHERE id = '$id'";
    }
?>