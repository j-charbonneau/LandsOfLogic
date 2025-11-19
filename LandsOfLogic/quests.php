<?php
    session_start();

    $id = 0;

if (isset($_SESSION['id'])) {
        global $_SESSION, $id;
        $character = $_SESSION['character'];
        $id = $_SESSION['id'];
    } else {
        header("location:login.php");
    }

    $conn = mysqli_connect("localhost", "root", "");
    mysqli_select_db($conn, "LandsOfLogic");

    $questName = $questType = $questDescription = "";
    $questReward = $questStatus = $questId = 0;
    $amountOfQuests = 0;

    function showActiveQuest() {
        global $conn, $questName, $questType, $questDescription, $questReward, $id, $questStatus, $activeQuestArray, $questId;

        $query = mysqli_query($conn, "SELECT * FROM quests WHERE questStatus = 1 AND playerId = '$id'");

        $row = mysqli_fetch_assoc($query);
        $questName = $row['questName'];
        $questType = $row['questType'];
        $questDescription = $row['questDescription'];
        $questReward = $row['questReward'];
        $questStatus = $row['questStatus'];
        $questGiver = $row['questGiver'];
        $naturalForce = $row['naturalForce'];
        $petName = $row['petName'];
        $questId = $row['questId'];

        $activeQuestArray = array($questType, $questDescription, $questName, $questReward, $questGiver, $naturalForce,
            $petName, $questId);
    }

    function isActiveQuest() {
        global $conn, $id;

        $query = mysqli_query($conn, "SELECT * FROM Quests WHERE questStatus = 1 AND playerId = '$id'");

        $count = mysqli_num_rows($query);

        if ($count > 0) {
           return true;
        } else {
            return false;
        }
    }

    function createHelpWantedQuests($amountOfQuests) {
        if ($amountOfQuests > 0) {
            global $conn, $id, $helpWantedArray, $amountOfQuests;
            $questType = $questGiver = $giverProfession = $questDescription = $naturalForce = $petName = $petType = "";
            $questReward = $questStatus = 0;

            $f_names = file("names.txt");
            $f_professions = file("professions.txt");
            $f_petNames = file("petNames.txt");
            $f_petTypes = file("petTypes.txt");
            $f_naturalForces = file("naturalForces.txt");
            $type = rand(1, 2);

            $questReward = rand(1, 5);
            $questGiver = $f_names[rand(0, (count($f_names) - 1))];
            $giverProfession = $f_professions[rand(0, (count($f_professions) - 1))];

            switch ($type) {
                case 1:
                    $questType = "Social";
                    $questDescription = "$questGiver, the $giverProfession, needs your help! Someone spilled ink all over 
                        the log-books and they need to figure out how many Gold, Silver, and Copper pieces they have left.";
                    $questName = "Helping the Merchants";
                    break;
                case 2:
                    $questType = "Physical";
                    $naturalForce = $f_naturalForces[rand(0, (count($f_naturalForces) - 1))];
                    $questDescription = "$questGiver, a citizen of Arithmetia, needs your help! There is a giant $naturalForce
                        coming for the city! They need to know how high to build their defenses.";
                    $questName = "The Incoming Doom";
                    break;
                case 3:
                    $questType = "Mental";
                    $petName = $f_petNames[rand(0, (count($f_petNames) - 1))];
                    $petType = $f_petTypes[rand(0, (count($f_petTypes) - 1))];
                    $questDescription = "$questGiver, a citizen of Arithmetia, needs your help! Their $petType named 
                        $petName escaped and they need help finding them!";
                    break;
            }

            $index = 4 - $amountOfQuests;

            $helpWantedArray[$index][0] = $questType;
            $helpWantedArray[$index][1] = $questDescription;
            $helpWantedArray[$index][2] = $questName;
            $helpWantedArray[$index][3] = $questReward;
            $helpWantedArray[$index][4] = $questGiver;
            $helpWantedArray[$index][5] = $naturalForce;
            $helpWantedArray[$index][6] = $petName;

            $helpWantedArray = array(
                    array($questType, $questDescription, $questName, $questReward, $questGiver, $naturalForce, $petName),
            );

            mysqli_query($conn, "INSERT INTO quests (playerId, questType, questDescription, questName, questReward, 
                        questGiver, naturalForce, petName, questStatus) VALUES ('$id', '$questType', '$questDescription', 
                        '$questName', '$questReward', '$questGiver', '$naturalForce', '$petName', '$questStatus')");



            $amountOfQuests--;

            if ($amountOfQuests > 0) {
                createHelpWantedQuests($amountOfQuests);
            }

        }

    }

    function showHelpWantedQuests($index) {
        global $questName, $questType, $questDescription, $questReward, $helpWantedArray, $naturalForce, $petName, $questId, $questGiver;

        $questType = $helpWantedArray[$index][0];
        $questDescription = $helpWantedArray[$index][1];
        $questReward = $helpWantedArray[$index][2];
        $questGiver = $helpWantedArray[$index][3];
        $naturalForce = $helpWantedArray[$index][4];
        $petName = $helpWantedArray[$index][5];
        $questId = $helpWantedArray[$index][6];
        $questName = $helpWantedArray[$index][7];
    }

    function countHelpWantedNeeded() {
        global $conn, $id;

        $query = mysqli_query($conn, "SELECT * FROM Quests WHERE questStatus = 0 AND playerId = '$id'");

        $count = mysqli_num_rows($query);

        if ($count >= 4) {
            $count = 3;
        }

        $temp = 3 - $count;

        helpWantedPopulate($count);

        return $temp;
    }

    function helpWantedPopulate($count) {
        global $conn, $id, $helpWantedArray;
        $questType = $questName = $questGiver = $questDescription = $naturalForce = $petName = "";
        $questReward = $questStatus = 0;

        $query = mysqli_query($conn, "SELECT * FROM Quests WHERE questStatus = 0 AND playerId = '$id'");

        $temp = 1;
        $count = $count +1;

        while ($row = mysqli_fetch_assoc($query)) {
            if ($count > 1) {
                $questType = $row['questType'];
                $questDescription = $row['questDescription'];
                $questReward = $row['questReward'];
                $questGiver = $row['questGiver'];
                $naturalForce = $row['naturalForce'];
                $petName = $row['petName'];
                $questId = $row['questId'];
                $questName = $row['questName'];

                $helpWantedArray[$temp][0] = $questType;
                $helpWantedArray[$temp][1] = $questDescription;
                $helpWantedArray[$temp][2] = $questReward;
                $helpWantedArray[$temp][3] = $questGiver;
                $helpWantedArray[$temp][4] = $naturalForce;
                $helpWantedArray[$temp][5] = $petName;
                $helpWantedArray[$temp][6] = $questId;
                $helpWantedArray[$temp][7] = $questName;

                $count--;
                $temp++;
            }
        }
    }
?>

<html>
    <head>
        <title><?php echo $character ?>: The Quest Board</title>
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
            <article class="active quest" <?php if(isActiveQuest()){showActiveQuest();}else{Print "hidden";}?>>
                <a href="playQuest.php?questId=<?php echo $questId?>">
                <h1 id="currentQuestName"><?php echo $questName?>: A <?php echo $questType?> Quest!</h1>
                <p>This is your active Quest</p>
                <h3 id="currentQuestReward">Reward: <?php echo $questReward?> Gold</h3>
                <p id="currentQuestDescription"><?php echo $questDescription?></p>
                </a>
            </article>
        </section>

        <section <?php if(!isActiveQuest()){createHelpWantedQuests(countHelpWantedNeeded());}else{Print "hidden";}?>>
            <article class="helpWanted" id="helpWanted1" <?php showHelpWantedQuests(1);?>>
                <a href="playQuest.php?questId=<?php echo $questId?>" >
                <h1 id="helpWantedName"><?php echo $questName?>: A <?php echo $questType?> Quest!</h1>
                <h3 id="helpWantedReward">Reward: <?php echo $questReward?> Gold</h3>
                <p id="helpWantedDescription"><?php echo $questDescription?></p> </a>
            </article>

            <article class="helpWanted" id="helpWanted2" <?php showHelpWantedQuests(2);?>>
                <a href="playQuest.php?questId=<?php echo $questId?>" >
                <h1 id="helpWantedName"><?php echo $questName?>: A <?php echo $questType?> Quest!</h1>
                <h3 id="helpWantedReward">Reward: <?php echo $questReward?> Gold</h3>
                <p id="helpWantedDescription"><?php echo $questDescription?></p>
                </a>
            </article>

            <article class="helpWanted" id="helpWanted3" <?php showHelpWantedQuests(3);?>>
                <a href="playQuest.php?questId=<?php echo $questId?>">
                <h1 id="helpWantedName"><?php echo $questName?></h1>
                <h3 id="helpWantedReward">Reward: <?php echo $questReward?> Gold</h3>
                <p id="helpWantedDescription"><?php echo $questDescription?></p>
                </a>
            </article>
        </section>


    </body>
</html>
