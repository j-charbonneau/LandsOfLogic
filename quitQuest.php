<?php
    session_start();

    if(isset($_SESSION['id'])){
        global $_SESSION;
        $character = $_SESSION['character'];
        $id = $_SESSION['id'];
    } else {
        header("location:login.php");
    }

    if (isset($_SESSION['questId'])) {
        $questId = $_SESSION['questId'];
    } else {
        header("location:quests.php");
    }

    $conn = new mysqli("localhost", "root", "", "LandsOfLogic");
    mysqli_query($conn, "UPDATE quests SET QuestStatus = 0 WHERE QuestId = $questId");
?>