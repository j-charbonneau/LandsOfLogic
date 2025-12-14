<html>
    <head>
        <title>Character Creation</title>
    	<link rel="stylesheet" href="landsOfLogic.css" />
    </head>

    <?php
        session_start();

        if($_SESSION['id']) {
        } else {
            header("location:index.php");
        }


    ?>

    <body>
        <article class="createCharacter">
            <h3 id="createCharacterHeader">CREATE A CHARACTER</h3>
            <form action="characterCreation.php" method="post">
                <label for="characterName">Character Name:</label>
                <input type="text" id="characterName" name="characterName" required /><br />

                <p id="statChoice">Please choose a stat to start with 4 points. The others will begin at 2.</p>
                <input type="radio" id="physicalStat" name="stats"
                    <?php if(isset($_POST['stats']) && $_POST['stats'] == "physical") { echo "checked";} ?>
                       value="physical" required />
                <label for="physicalStat">Physical Stat</label><br>

                <input type="radio" id="mentalStat" name="stats"
                    <?php if(isset($_POST['stats']) && $_POST['stats'] == "mental") { echo "checked";} ?>
                       value="mental" />
                <label for="mentalStat">Mental Stat</label><br>

                <input type="radio" id="socialStat" name="stats"
                    <?php if(isset($_POST['stats']) && $_POST['stats'] == "social") { echo "checked";} ?>
                       value="social" />
                <label for="socialStat">Social Stat</label><br>

                <input type="submit" value="Create Character" />
            </form>
        </article>
    </body>
</html>

<?php
	$conn = mysqli_connect("sql110.infinityfree.com", "if0_40582300", "uj0krRpEXI");
        mysqli_select_db($conn, "if0_40582300_LandsOfLogic");
    $physicalStat = $mentalStat = $socialStat = 2;
    $health = $maxHealth = $shield = $maxShield = 0;

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $characterName = mysqli_real_escape_string($conn, $_POST['characterName']);
        $stats = mysqli_real_escape_string($conn, $_POST['stats']);

        switch($stats){
            case "physical":
                $physicalStat = 4;
                break;
            case "mental":
                $mentalStat = 4;
                break;
            case "social":
                $socialStat = 4;
                break;
        }

        $maxHealth = $physicalStat * 10;
        $health = $maxHealth;
        $maxShield = $mentalStat * 4;
        $shield = $maxShield;

        $id = $_SESSION['id'];

        mysqli_query($conn, "UPDATE users SET physicalStat = '$physicalStat', mentalStat = '$mentalStat', 
                 socialStat = '$socialStat', health = '$health', maxHealth = '$maxHealth', shield = '$shield', 
                 maxShield = '$maxShield', characterName = '$characterName' WHERE playerId='$id';");

        $_SESSION['character'] = $characterName;

        $inventory = array (
                array("Physical Book", "book", 10, "Increases Physical Stat by 1"),
                array("Mental Book", "book", 10, "Increases Mental Stat by 1"),
                array("Social Book", "book", 10, "Increases Social Stat by 1"),
                array("Normal Health Potion", "healthPotion", 10, "Increases Health by 5 to 10 Points"),
                array("Strong Health Potion", "healthPotion", 30, "Increases Health by 10 points to Full Health"),
                array("Massive Health Potion", "healthPotion", 50, "Increases Health to Full Health"),
                array("Normal Shield Potion", "shieldPotion", 10, "Increases Shield by 1 to 4 Points"),
                array("Strong Shield Potion", "shieldPotion", 20, "Increases Shield by 4 to Full Shield"),
                array("Massive Shield Potion", "shieldPotion", 30, "Increases Shield to Full Shield")
        );

        for ($row = 0; $row < count($inventory); $row++) {

            $itemName = $inventory[$row][0];
            $itemType = $inventory[$row][1];
            $itemCost = $inventory[$row][2];
            $itemDescription = $inventory[$row][3];

            mysqli_query($conn, "INSERT INTO inventory (playerId, itemName, itemType, itemCost, itemDescription,
                itemCount) VALUES ('$id', '$itemName', '$itemType', '$itemCost', 
                '$itemDescription', 0);");
        }

        Print '<script>window.location.assign("home.php");</script>';
    }
?>