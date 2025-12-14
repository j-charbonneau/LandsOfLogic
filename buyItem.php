<<?php
    session_start();

    if (!isset($_SESSION['id'])) {
        header("location: login.php");
        exit;
    }

    $id = $_SESSION['id'];

    $conn = mysqli_connect("sql110.infinityfree.com", "if0_40582300", "uj0krRpEXI", "if0_40582300_LandsOfLogic");

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    $itemCode = null;

    if (isset($_POST['PhysicalBook'])) {
        $itemCode = 'PhysicalBook';
    } elseif (isset($_POST['MentalBook'])) {
        $itemCode = 'MentalBook';
    } elseif (isset($_POST['SocialBook'])) {
        $itemCode = 'SocialBook';
    } elseif (isset($_POST['NormalHealth'])) {
        $itemCode = 'NormalHealth';
    } elseif (isset($_POST['StrongHealth'])) {
        $itemCode = 'StrongHealth';
    } elseif (isset($_POST['MassiveHealth'])) {
        $itemCode = 'MassiveHealth';
    } elseif (isset($_POST['NormalShield'])) {
        $itemCode = 'NormalShield';
    } elseif (isset($_POST['StrongShield'])) {
        $itemCode = 'StrongShield';
    } elseif (isset($_POST['MassiveShield'])) {
        $itemCode = 'MassiveShield';
    } else {
        header("location: shops.php");
        exit;
    }

    $gold = 0;
    $res = mysqli_query($conn, "SELECT gold FROM users WHERE playerId = '$id'");

    if ($row = mysqli_fetch_assoc($res)) {
        $gold = (int)$row['gold'];
    }

    $itemName = "";
    $price = 0;

    switch ($itemCode) {
        case 'PhysicalBook':
            $itemName = "Physical Book";
            $price = 10;
            break;
        case 'MentalBook':
            $itemName = "Mental Book";
            $price = 10;
            break;
        case 'SocialBook':
            $itemName = "Social Book";
            $price = 10;
            break;
        case 'NormalHealth':
            $itemName = "Normal Health Potion";
            $price = 10;
            break;
        case 'StrongHealth':
            $itemName = "Strong Health Potion";
            $price = 30;
            break;
        case 'MassiveHealth':
            $itemName = "Massive Health Potion";
            $price = 50;
            break;
        case 'NormalShield':
            $itemName = "Normal Shield Potion";
            $price = 10;
            break;
        case 'StrongShield':
            $itemName = "Strong Shield Potion";
            $price = 20;
            break;
        case 'MassiveShield':
            $itemName = "Massive Shield Potion";
            $price = 30;
            break;
    }

    if ($gold < $price) {
        echo "<script>alert('Not enough gold to buy this item!'); window.location.href = 'shops.php';</script>";
        exit;
    }

    $itemNameEsc = mysqli_real_escape_string($conn, $itemName);

    $updateInv = mysqli_query($conn,
        "UPDATE inventory SET itemCount = itemCount + 1  WHERE playerId = '$id'  AND itemName = '$itemNameEsc'");

    if (mysqli_affected_rows($conn) == 0) {
        mysqli_query(
            $conn, "INSERT INTO inventory (playerId, itemName, itemDescription, itemCount) VALUES ( '$id', '$itemNameEsc', 'Purchased from shop', 1)");
    }

    $newGold = $gold - $price;
    mysqli_query(
        $conn, "UPDATE users  SET gold = '$newGold' WHERE playerId = '$id'");

    header("location: shops.php");
    exit;
?>
