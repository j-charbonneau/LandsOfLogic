<?php

    session_start();

	$dbHost = "sql110.infinityfree.com";
	$dbUser = "if0_40582300";
	$dbPassword = "uj0krRpEXI";
	$dbName = "if0_40582300_LandsOfLogic";

    $conn = mysqli_connect($dbHost, $dbUser, $dbPassword);

	if(!$conn) {
        die("Connection failed: ".mysqli_connect_error());
    }

    mysqli_select_db($conn, $dbName);

	if(!mysqli_select_db($conn, $dbName)) {
        die('Database select failed.');
    }

    if(!isset($_POST['username'], $_POST['password'])) {
        header("Location: login.php");
        exit;
    }
        
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

	$sql = "SELECT * FROM users WHERE username = '$username'";

    $query = mysqli_query($conn, $sql);

	if (!$query) {
        die("Query failed");
    }

    $exists = mysqli_num_rows($query);

    if ($exists > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $table_users = $row['username'];
            $table_password = $row['password'];
            $table_characterName = $row['characterName'];
            $table_playerId = $row['playerId'];
        }

        if (($table_users == $username) && ($table_password == $password)) {
            if ($password == $table_password) {
                $_SESSION['character'] = $table_characterName;
                $_SESSION['id'] = $table_playerId;

                header("location: home.php");
            }
        } else {
            Print '<script>alert("Password is Wrong!");</script>';
            Print '<script>window.location.assign("login.php");</script>';
        }
    } else {
        Print '<script>alert("Username is Wrong!");</script>';
        Print '<script>window.location.assign("login.php");</script>';
    }
?>