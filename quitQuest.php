<?php
    session_start();

    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit;
    }

    $character = $_SESSION['character'];
    $id = $_SESSION['id'];

    if (isset($_GET['questId'])) {
        $questId = (int)$_GET['questId'];
        $_SESSION['questId'] = $questId;
    } elseif (isset($_SESSION['questId'])) {
        $questId = (int)$_SESSION['questId'];
    } else {
        header("Location: quests.php");
        exit;
    }

    $conn = mysqli_connect("sql110.infinityfree.com", "if0_40582300", "uj0krRpEXI");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    if (!mysqli_select_db($conn, "if0_40582300_LandsOfLogic")) {
        die("Database select failed: " . mysqli_error($conn));
    }

    $sql = "UPDATE quests SET questStatus = 0 WHERE questId = $questId";
    mysqli_query($conn, $sql);

    unset($_SESSION['questId']);

    header("Location: quests.php");
    exit;
?>