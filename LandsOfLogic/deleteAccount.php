<?php
    session_start();

    if(isset($_SESSION['id'])) {
        global $_SESSION;
        $id = $_SESSION['id'];
        $character = $_SESSION['character'];
    } else {
        header("location:login.php");
    }


    $conn = mysqli_connect("localhost", "root", "", "landsoflogic");

?>


<html>
    <head>
        <title>ACCOUNT: DELETE ACCOUNT</title>
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

        <section class = "deleteAccount">
            <h1>Update Email</h1>
            <form action="deleteAccount.php" method="post">
                <p id="deleteAccountAreYouSure">Are you <strong>SURE</strong> you want to delete your account?</p>
                <p id="deleteAccountAreYouSure">There is no going back from this action.</p>
                <br>
                <br>
                <input type="button" value="Yes" name="deleteAccount" id="deleteAccount">
                <input type="button" value="No" name="deleteAccount" id="deleteAccountCancel">
            </form>
        </section>
    </body>
</html>

<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['deleteAccount'])) {
            if($_POST['deleteAccount'] == 'yes') {
                mysqli_query($conn, "DELETE FROM users WHERE id = '$id'");
            }

            Print '<script>window.location.assign("index.php");</script>';
        }
    }
?>