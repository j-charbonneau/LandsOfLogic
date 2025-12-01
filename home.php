<?php
    session_start();

    if(isset($_SESSION['id'])){
        global $_SESSION;
        $character = $_SESSION['character'];
        $id = $_SESSION['id'];
    } else {
        header("location:login.php");
    }

    $physicalStat = $mentalStat = $socialStat = $maxHealth = $health = $maxShield = $shield = $gold = 0;

    $conn = mysqli_connect("localhost", "root", "");
    mysqli_select_db($conn, "LandsOfLogic");

    $query = mysqli_query($conn, "SELECT * FROM users WHERE playerId = '$id'");

    while($row = mysqli_fetch_assoc($query)){
        $physicalStat = $row['physicalStat'];
        $mentalStat = $row['mentalStat'];
        $socialStat = $row['socialStat'];
        $maxHealth = $row['maxHealth'];
        $health = $row['health'];
        $maxShield = $row['maxShield'];
        $shield = $row['shield'];
    }

    function changeHealth($type) {
        $healing = 0;
        global $maxHealth, $health;

        switch ($type) {
            case "Normal Health Potion":
                $healing = rand(5, 10);
                break;
            case "Strong Health Potion":
                $healing = rand(10, $maxHealth);
                break;
            case "Massive Health Potion":
                $healing = $maxHealth;
                break;
        }

        $health += $healing;

        if ($health > $maxHealth) {
            $health = $maxHealth;
        }
    }

    function changeShield($type) {
        $shielding = 0;
        global $maxShield, $shield;

        switch ($type) {
            case "Normal Shield Potion":
                $shielding = rand(1, 4);
                break;
            case "Strong Shield Potion":
                $shielding = rand(4, $maxShield);
                break;
            case "massive Shield Potion":
                $shielding = $maxShield;
                break;
        }

        $shield += $shielding;
        if ($shield > $maxShield) {
            $shield = $maxShield;
        }
    }

    function remove($type) {
        global $conn;
        global $id;

        mysqli_query($conn, "UPDATE inventory SET count = count - 1 WHERE playerId = '$id'");
    }

?>

<html>
    <head>
        <title><?php echo $character ?>: Character Page</title>
        <link rel="stylesheet" type="text/css" href="landsOfLogic.css">
    </head>

    <body>
        <header>
            <h1 id="logo"><sup id="logoTHE">THE</sup>Lands of Logic</h1>
        </header>

        <nav>
            <a href="home.php" class="nav">
                <h1><?php echo $character ?></h1>
            </a>

            <a href="quests.php" class="nav">
                <h1>Quest Board</h1>
            </a>

            <a href="shops.php" class="nav">
                <h1>The Market</h1>
            </a>

            <a href="arena.php" class="nav">
                <h1>The Arena</h1>
            </a>

            <a href="account.php" class="nav">
                <h1>Account</h1>
            </a>
        </nav>

        <section>
            <article class="stats">
                <h1 id="stats">Character Stats</h1>
                <p>Physical Stat: <?php echo $physicalStat?></p>
                <p>Mental Stat: <?php echo $mentalStat?></p>
                <p>Social Stat: <?php echo $socialStat?></p>
            </article>

            <article class="health">
                <h1>Health: <?php echo $health?> / <?php echo $maxHealth?></h1>
                <h3>Shield: <?php echo $shield?> / <?php echo $maxShield?></h3>
            </article>

            <article class="inventory">
                <h1 id="inventory">Inventory</h1>
                <h3>Gold: <?php echo $gold?></h3>

                <span id="inventoryTable">
                    <tr>
                        <th>Item Name</th>
                        <th>Item Description</th>
                        <th>Item Count</th>
                        <th></th>
                    </tr>

                    <?php
                        $query = mysqli_query($conn, "SELECT * FROM inventory WHERE playerId = '$id' AND itemCount > 0");

                        while($row = mysqli_fetch_assoc($query)){
                            Print "<tr>";
                                Print "<td>".$row['itemName']."</td>";
                                Print "<td>".$row['itemDescription']."</td>";
                                Print "<td>".$row['itemCount']."</td>";
                                $itemName = $row['itemName'];
                                Print "<td><button onclick='useItem(";
                                Print $itemName;
                                Print ")'>Use ";
                                Print $itemName;
                                Print "</button></td>";
                        }
                    ?>

                    <script>
                        function useItem(itemName){
                            switch(itemName){
                                case "Physical Book":
                                    <?php
                                        $physicalStat++;
                                        remove("Physical Book");
                                    ?>
                                    break;
                                case "Mental Book":
                                    <?php
                                        $mentalStat++;
                                        remove("Mental Book");
                                    ?>
                                    break;
                                case "Social Book":
                                    <?php
                                        $socialStat++;
                                        remove("Social Book");
                                    ?>
                                    break;
                                case "Normal Health Potion":
                                    <?php
                                        changeHealth("Normal Health Potion");
                                        remove("Normal Health Potion");
                                    ?>
                                    break;
                                case "Strong Health Potion":
                                    <?php
                                        changeHealth("Strong Health Potion");
                                        remove("Strong Health Potion");
                                    ?>
                                    break;
                                case "Massive Health Potion":
                                    <?php
                                        changeHealth("Massive Health Potion");
                                        remove("Massive Health Potion");
                                    ?>
                                    break;
                                case "Normal Shield Potion":
                                    <?php
                                        changeShield("Normal Shield Potion");
                                        remove("Normal Shield Potion");
                                    ?>
                                    break;
                                case "Strong Shield Potion":
                                    <?php
                                        changeShield("Strong Shield Potion");
                                        remove("Strong Shield Potion");
                                    ?>
                                    break;
                                case "Massive Shield Potion":
                                    <?php
                                        changeShield("Massive Shield Potion");
                                        remove("Massive Shield Potion");
                                    ?>
                            }
                            location.reload();
                        }
                    </script>

                </span>

            </article>
        </section>
    </body>
</html>
