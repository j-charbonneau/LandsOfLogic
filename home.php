<?php
    session_start();

    if(isset($_SESSION['id'])){
        $character = $_SESSION['character'];
        $id = $_SESSION['id'];
    } else {
        header("location: login.php");
        exit;
    }

    $physicalStat = $mentalStat = $socialStat = $maxHealth = $health = $maxShield = $shield = $gold = 0;

    $conn = mysqli_connect("sql110.infinityfree.com", "if0_40582300", "uj0krRpEXI");
    mysqli_select_db($conn, "if0_40582300_LandsOfLogic");


    $query = mysqli_query($conn, "SELECT * FROM users WHERE playerId = '$id'");

    while($row = mysqli_fetch_assoc($query)){
        $physicalStat = $row['physicalStat'];
        $mentalStat = $row['mentalStat'];
        $socialStat = $row['socialStat'];
        $maxHealth = $row['maxHealth'];
        $health = $row['health'];
        $maxShield = $row['maxShield'];
        $shield = $row['shield'];
        $gold = $row['gold'];
    }
?>

<html>
    <head>
        <title><?php Print $character ?>: Character Page</title>
        <link rel="stylesheet" type="text/css" href="landsOfLogic.css">
    </head>

    <body>
        <header>
            <h1 id="logo"><sup id="logoTHE">THE</sup>Lands of Logic</h1>
        </header>

        <nav>
            <div class="navItem">
                <a href="home.php" class="nav">
                    <h1><?php Print $character ?></h1>
                </a>
            </div>

            <div class="navItem">
                <a href="quests.php" class="nav">
                    <h1>Quest Board</h1>
                </a>
            </div>

            <div class="navItem">
                <a href="shops.php" class="nav">
                    <h1>The Market</h1>
                </a>
            </div>
            
 			<div class="navItem">
                <a href="account.php" class="nav">
                    <h1>Account</h1>
                </a>
            </div>
            
        </nav>

        <section>
            <article class="stats">
                <h1 id="stats">Character Stats</h1>
                <p>Physical Stat: <?php Print $physicalStat?></p>
                <p>Mental Stat: <?php Print $mentalStat?></p>
                <p>Social Stat: <?php Print $socialStat?></p>
            </article>

            <article class="health">
                <h1>Health: <?php Print $health?> / <?php Print $maxHealth?></h1>
                <h3>Shield: <?php Print $shield?> / <?php Print $maxShield?></h3>
            </article>

           <article class="inventory">
                <h1 id="inventory">Inventory</h1>
                <h3>Gold: <?php Print $gold; ?></h3>

                <table id="inventoryTable">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Item Description</th>
                            <th>Item Count</th>
                            <th>Use</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $query = mysqli_query(
                            $conn, "SELECT * FROM inventory WHERE playerId = '$id' AND itemCount > 0");

                        while ($row = mysqli_fetch_assoc($query)) {
                            Print "<tr>";
                            Print "<td>" . htmlspecialchars($row['itemName']) . "</td>";
                            Print "<td>" . htmlspecialchars($row['itemDescription']) . "</td>";
                            Print "<td>" . (int)$row['itemCount'] . "</td>";
                            Print "<td>
                                    <form method='post' action='useItem.php' style='margin:0; display:inline;'>
                                        <input type='hidden' name='itemName' value='" . htmlspecialchars($row['itemName'], ENT_QUOTES) . "'>
                                        <input type='submit' value='Use " . htmlspecialchars($row['itemName']) . "'>
                                    </form>
                                  </td>";
                            Print "</tr>";
                        }
                    ?>
                    </tbody>
                </table>
            </article>
        </section>
    </body>
</html>
