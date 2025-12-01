<?php
    session_start();

    if(isset($_SESSION['id'])){
        global $_SESSION;
        $character = $_SESSION['character'];
        $id = $_SESSION['id'];
    } else {
        header("location:login.php");
    }

    $gold = $physicalBookCount = $mentalBookCount = $socialBookCount = $normalHealthPotionCount =
    $strongHealthPotionCount = $massiveHealthPotionCount = $normalShieldPotionCount = $strongShieldPotionCount =
    $massiveShieldPotionCount = 0;

    $conn = mysqli_connect("localhost", "root", "");
    mysqli_select_db($conn, "LandsOfLogic");

    $query = mysqli_query($conn, "SELECT * FROM inventory WHERE playerId= '$id'");

    while($row = mysqli_fetch_assoc($query)){
        switch($row['itemName']) {
            case 'Physical Book':
                $physicalBookCount = $row['itemCount'];
                break;
            case 'Mental Book':
                $mentalBookCount = $row['itemCount'];
                break;
            case 'Social Book':
                $socialBookCount = $row['itemCount'];
                break;
            case 'Normal Health Potion':
                $normalHealthPotionCount = $row['itemCount'];
                break;
            case 'Strong Health Potion':
                $strongHealthPotionCount = $row['itemCount'];
                break;
            case 'Massive Health Potion':
                $massiveHealthPotionCount = $row['itemCount'];
                break;
            case 'Normal Shield Potion':
                $normalShieldPotionCount = $row['itemCount'];
                break;
            case 'Strong Shield Potion':
                $strongShieldPotionCount = $row['itemCount'];
                break;
            case 'Massive Shield Potion':
                $massiveShieldPotionCount = $row['itemCount'];
                break;
        }
    }

    $query = mysqli_query($conn, "SELECT gold FROM users WHERE id = '$id'");
    $gold = mysqli_fetch_assoc($query);

    $itemBoughtSql = "";
    $countSql = 0;

    $itemBought = mysqli_real_escape_string($conn, $_POST['itemBought']);
    switch($itemBought){
        case 'PhysicalBook':
            $physicalBookCount++;
            $gold = $gold - 10;
            $itemBoughtSql = "Physical Book";
            $countSql = $physicalBookCount;
            break;
        case 'MentalBook':
            $mentalBookCount++;
            $gold = $gold - 10;
            $itemBoughtSql = "Mental Book";
            $countSql = $mentalBookCount;
            break;
        case 'SocialBook':
            $socialBookCount++;
            $gold = $gold - 10;
            $itemBoughtSql = "Social Book";
            $countSql = $socialBookCount;
            break;
        case 'NormalHealth':
            $normalHealthPotionCount++;
            $gold = $gold - 10;
            $itemBoughtSql = "Normal Health Potion";
            $countSql = $normalHealthPotionCount;
            break;
        case 'StrongHealth':
            $strongHealthPotionCount++;
            $gold = $gold - 30;
            $itemBoughtSql = "Strong Health Potion";
            $countSql = $strongHealthPotionCount;
            break;
        case 'Massive Health':
            $massiveHealthPotionCount++;
            $gold = $gold - 50;
            $itemBoughtSql = "Massive Health Potion";
            $countSql = $massiveHealthPotionCount;
            break;
        case 'NormalShield':
            $normalShieldPotionCount++;
            $gold = $gold - 10;
            $itemBoughtSql = "Normal Shield Potion";
            $countSql = $normalShieldPotionCount;
            break;
        case 'StrongShield':
            $strongShieldPotionCount++;
            $gold = $gold - 20;
            $itemBoughtSql = "Strong Shield Potion";
            $countSql = $strongShieldPotionCount;
            break;
        case 'MassiveShield':
            $massiveShieldPotionCount++;
            $gold = $gold - 50;
            $itemBoughtSql = "Massive Shield Potion";
            $countSql = $massiveShieldPotionCount;
            break;
    }

    $query = "UPDATE inventory SET '$itemBoughtSql' = '$countSql' WHERE playerId = '$id'";
    mysqli_query($conn, $query);

    $query = "UPDATE users SET gold = '$gold' WHERE id = '$id'";
    mysqli_query($conn, $query);
?>