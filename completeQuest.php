<?php
    session_start();

    if (!isset($_SESSION['id'])) {
        header("location: login.php");
        exit;
    }

    if (empty($_GET['questId'])) {
        header("location: quests.php");
        exit;
    }

    $id = (int)$_SESSION['id'];
    $questId = (int)$_GET['questId'];

    $conn = mysqli_connect("sql110.infinityfree.com", "if0_40582300", "uj0krRpEXI");
    if (!$conn) {
        die("DB connection failed: " . mysqli_connect_error());
    }

    mysqli_select_db($conn, "if0_40582300_LandsOfLogic");

    $q = mysqli_query($conn, "SELECT questReward  FROM quests  WHERE questId = $questId  AND playerId = '$id' AND questStatus = 1");

    if ($row = mysqli_fetch_assoc($q)) {
        $reward = (int)$row['questReward'];

        mysqli_query($conn, "UPDATE users  SET gold = gold + $reward  WHERE playerId = '$id'");

        mysqli_query($conn, "UPDATE quests  SET questStatus = 2  WHERE questId = $questId");
    }

    header("location: quests.php");
    exit;
?>
