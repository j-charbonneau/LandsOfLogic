<?php
    session_start();

    if (!isset($_SESSION['id'])) {
        header("location: login.php");
        exit;
    }

    $id = $_SESSION['id'];

    if (empty($_POST['itemName'])) {
        header("location: home.php");
        exit;
    }

    $itemName = $_POST['itemName'];

    $conn = mysqli_connect("sql110.infinityfree.com", "if0_40582300", "uj0krRpEXI", "if0_40582300_LandsOfLogic");

    if (!$conn) {
        die("DB connection failed: " . mysqli_connect_error());
    }

    $userStats = mysqli_query($conn, "SELECT physicalStat, mentalStat, socialStat, maxHealth, health, maxShield, shield FROM users WHERE playerId = '$id'");

    if (!$userStats || !($userRow = mysqli_fetch_assoc($userStats))) {
        header("location: home.php");
        exit;
    }

    $physicalStat = (int)$userRow['physicalStat'];
    $mentalStat   = (int)$userRow['mentalStat'];
    $socialStat   = (int)$userRow['socialStat'];
    $maxHealth    = (int)$userRow['maxHealth'];
    $health       = (int)$userRow['health'];
    $maxShield    = (int)$userRow['maxShield'];
    $shield       = (int)$userRow['shield'];

    $itemEsc = mysqli_real_escape_string($conn, $itemName);
    $invRes = mysqli_query($conn, "SELECT itemCount FROM inventory WHERE playerId = '$id' AND itemName = '$itemEsc' AND itemCount > 0");

    if (!$invRes || !($invRow = mysqli_fetch_assoc($invRes))) {
        header("location: home.php");
        exit;
    }

    switch ($itemName) {
        case "Physical Book":
            $physicalStat++;
            break;

        case "Mental Book":
            $mentalStat++;
            break;

        case "Social Book":
            $socialStat++;
            break;

        case "Normal Health Potion":
            $heal = rand(5, 10);
            $health += $heal;
            if ($health > $maxHealth) $health = $maxHealth;
            break;

        case "Strong Health Potion":
            $heal = rand(10, max(10, $maxHealth));
            $health += $heal;
            if ($health > $maxHealth) $health = $maxHealth;
            break;

        case "Massive Health Potion":
            $health = $maxHealth;
            break;

        case "Normal Shield Potion":
            $shield += rand(1, 4);
            if ($shield > $maxShield) $shield = $maxShield;
            break;

        case "Strong Shield Potion":
            $shield += rand(4, max(4, $maxShield));
            if ($shield > $maxShield) $shield = $maxShield;
            break;

        case "Massive Shield Potion":
            $shield = $maxShield;
            break;
    }

    mysqli_query($conn, "UPDATE users SET physicalStat = '$physicalStat', mentalStat   = '$mentalStat', socialStat   = '$socialStat', health       = '$health', shield       = '$shield' WHERE playerId = '$id'");

    mysqli_query($conn, "UPDATE inventory SET itemCount = itemCount - 1 WHERE playerId = '$id' AND itemName = '$itemEsc' AND itemCount > 0");

    header("location: home.php");
    exit;
?>