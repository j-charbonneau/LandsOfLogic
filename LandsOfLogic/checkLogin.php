<?php
    session_start();

    $conn = mysqli_connect("localhost", "root", "");

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $bool = true;

    mysqli_select_db($conn, "landsoflogic");

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

    $exists = mysqli_num_rows($query);

    $tableUsers = $tablePassword = $tableCharacterName = $tablePlayerId = "";

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
        Print '<script>alert("Username is Wrong!");</script>")';
        Print '<script>window.location.assign("login.php");</script>';
    }
?>