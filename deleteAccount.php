<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit;
}

$id = (int)$_SESSION['id'];

$conn = mysqli_connect("sql110.infinityfree.com", "if0_40582300", "uj0krRpEXI", "if0_40582300_LandsOfLogic");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("location: account.php");
    exit;
}

mysqli_query($conn, "DELETE FROM inventory WHERE playerId = '$id'");
mysqli_query($conn, "DELETE FROM quests    WHERE playerId = '$id'");

mysqli_query($conn, "DELETE FROM users WHERE playerId = '$id'");

session_unset();
session_destroy();

Print "<script>alert('Your account has been deleted.'); window.location.href = 'login.php';</script>";
exit;