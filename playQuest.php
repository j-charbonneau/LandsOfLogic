<?php
    session_start();

    if(isset($_SESSION['id'])){
        global $_SESSION;
        $character = $_SESSION['character'];
        $id = $_SESSION['id'];
    } else {
        header("location:login.php");
    }

    if(isset($_SESSION['questProgress'])){
        $questProgress = $_SESSION['questProgress'];
    } else {
        $questProgress = 0;
    }

    if(isset($_SESSION['nextButton'])){
        $nextButton = $_SESSION['nextButton'];
    }

    if(isset($_SESSION['submitAnswer'])){
        $submitAnswer = $_SESSION['submitAnswer'];
    }

    $conn = mysqli_connect("localhost", "root", "");
    mysqli_select_db($conn, "LandsOfLogic");

    $id_exists = false;
    $questsId = 0;
    //$questProgress = 0;
    $activeQuest[][] = "";
    $scriptIsMade = false;
    $questProgressChart[] = 0;
    $questReward = 0;
    $questName = $questType = "";
    $nextButton = false;
    $lengthOfScript = 0;
    $submitAnswer = true;

    if (!empty($_GET['questId'])) {
        $questsId = $_GET['questId'];
        $_SESSION['questId'] = $questsId;
        $id_exists = true;
    } else {
        header("location:quests.php");
    }

    makeQuestScript();

    function makeQuestScript() { 
        global $scriptIsMade, $questReward, $questName, $questType;
        if (!$scriptIsMade) {
            global $conn, $id, $questsId, $activeQuest;

            $f_names = file("names.txt");
            $randNames[] = "";

            mysqli_query($conn, "UPDATE quests SET questStatus = 1 WHERE questId = '$questsId'");

            $characterName = $questGiver = $naturalForce = $petName = $petType = $questName = "";
            $socialStat = $physicalStat = $mentalStat = $gold = $questType = 0;

            $query = mysqli_query($conn, "SELECT * FROM quests WHERE questId = '$questsId'");
            $row = mysqli_fetch_array($query);
            $questType = $row['questType'];
            $questName = $row['questName'];
            $questReward = $row['questReward'];
            $questGiver = $row['questGiver'];
            $naturalForce = $row['naturalForce'];
            $petName = $row['petName'];

            $query = mysqli_query($conn, "SELECT * FROM users WHERE playerId = '$id'");
            $row = mysqli_fetch_array($query);
            $physicalStat = $row['physicalStat'];
            $mentalStat = $row['mentalStat'];
            $socialStat = $row['socialStat'];
            $characterName = $row['characterName'];
            $gold = $row['gold'];

            switch ($questType) {
                case "Social":
                    $tempRand = rand(2,6);
                    $activeQuest[0][0] = "You will need to verify orders with $tempRand customers through SOCIAL checks.";
                    $runningTotal = 0;
                    $randNames[] = "";



                    for ($i = 1; $i <= $tempRand; $i++) {
                        $randNames[$i] = $f_names[rand(0, count($f_names) - 1)];
                        $npcName = $randNames[$i];
                        $activeQuest[$i][0] = "You meet $npcName.";
                        $activeQuest[$i][1] = "$npcName: Hello, $characterName.";
                        $activeQuest[$i][2] = "If you would like to get their receipt, you will need a SOCIAL roll of at least 7.";

                        $dieRoll = rand(1, 6);
                        $totalCheck = $dieRoll + $socialStat;

                        $activeQuest[$i][3] = "You rolled a $dieRoll, for a total of $totalCheck.";

                        $goldCost = rand(1, 10);
                        $silverCost = rand(1, 10);
                        $copperCost = rand(1, 10);
                        $totalCostCopper = $copperCost + ($silverCost * 10) + ($goldCost * 100);
                        $totalCostGold = $goldCost + ($silverCost / 10) + ($copperCost / 100);
                        $questItem = "";
                        $numberOfItems = "";

                        for ($x = 3; $x < $totalCostCopper; $x++) {
                            if ($totalCostCopper % $x == 0) {
                                $questItem = $totalCostGold / $x;
                                $numberOfItems = $x;
                                break;
                            }
                        }

                        if ($totalCheck >= 7) {
                            $activeQuest[$i][4] = "You Succeeded!";
                            $activeQuest[$i][5] = "$npcName: I spent a total of $goldCost, gold pieces, $silverCost silver pieces, and $copperCost copper pieces.";
                            $activeQuest[$i][6] = "On to the next customer!";
                        } else {
                            $activeQuest[$i][4] = "You Failed! You'll have to put together their total.";
                            $activeQuest[$i][5] = "$npcName: I bought $numberOfItems things for $questItem copper pieces. How many gold pieces would that be?";
                            $activeQuest[$i][6] = "GET ANSWER";
                            $activeQuest[$i][7] = $totalCostGold;
                            $activeQuest[$i][8] = "$npcName: That's right!";
                            $activeQuest[$i][9] = "On to the next customer!";
                        }

                        $runningTotal += $totalCostGold;
                    }

                    $index = $tempRand + 1;

                    $activeQuest[$index][0] = "Now, you must combine all of the customer's totals!";
                    $activeQuest[$index][1] = "GET ANSWER";
                    $activeQuest[$index][2] = $runningTotal;
                    $activeQuest[$index][3] = "$questGiver: That sounds right! Thank you so much $characterName!";
                    $activeQuest[$index][4] = "ADD GOLD";
                    break;

                case "Physical":
                    $tempRand = rand(2,6);
                    $activeQuest[0][0] = "You will need to make your way to $tempRand Watchtowers to take measurements with PHYSICAL checks!";
                    $measurements[][] = "";

                    for ($i = 1; $i <= $tempRand; $i++) {
                        $activeQuest[$i][0] = "You come across a Watchtower. In order to get the correct measurement, you'll have to get up there!";
                        $activeQuest[$i][1] = "You will need a PHYSICAL roll of at least 7 in order to get to the top!";

                        $dieRoll = rand(1, 6);
                        $totalCheck = $dieRoll + $physicalStat;
                        $distance = rand(10, 30);
                        $height = rand(10, 30);
                        $measurements[$i][0] = $distance;
                        $measurements[$i][1] = $height;
                        $slope = $height / $distance;
                        $measurements[$i][2] = $slope;

                        if ($totalCheck >= 7) {
                            $activeQuest[$i][2] = "You succeeded!";
                            $activeQuest[$i][3] = "You reached the top of the Watchtower!";
                            $activeQuest[$i][4] = "You are able to measure that, at $distance feet away, the $naturalForce is $height feet high.";
                        } else {
                            $activeQuest[$i][2] = "You Failed! You'll have to find the footholds to scale the side.";
                            $pattern = getPattern();
                            $activeQuest[$i][3] = "Finish the Pattern: $pattern[1], $pattern[2], $pattern[3], $pattern[4], ?";
                            $activeQuest[$i][4] = "GET ANSWER";
                            $activeQuest[$i][5] = $pattern[5];
                            $activeQuest[$i][6] = "Correct! On to the next watchtower!";
                        }
                    }

                    $index = $tempRand + 1;

                    $activeQuest[$index][1] = "Now you must calculate the average slope (heights divided by distances)";

                    $totalHeight = $totalDistance = $totalSlope = 0;
                    for ($i = 1; $i <= $tempRand; $i++) {
                        $totalHeight += $measurements[$i][1];
                        $totalDistance += $measurements[$i][0];
                    }



                    $averageHeight = $totalHeight / $tempRand;
                    $averageDistance = $totalDistance / $tempRand;
                    $averageSlope = $averageHeight / $averageDistance;

                    $activeQuest[$index][2] = "GET ANSWER";
                    $activeQuest[$index][3] = $averageSlope;
                    $activeQuest[$index][4] = "ADD GOLD";
                    break;

                case "Mental":
                    $tempRand = rand(2,6);
                    $activeQuest[0][0] = "In order to find $petName, you must find $tempRand clues!";
                    $clues[] = "";
                    $eqnParams[] = "";

                    for ($i = 1; $i <= $tempRand; $i++) {
                        $activeQuest[$i][0] = "You come across a clue!";

                        $rand1  = rand(1, 50);
                        $rand2  = rand(1, 50);
                        $rand3  = rand(1, 50);
                        $eqnParams[0] = $rand1;
                        $eqnParams[1] = $rand2;
                        $eqnParams[2] = $rand3;

                        $eqn[] = randomEquation($eqnParams);

                        $activeQuest[$i][1] = "You find a cryptic note! You will need a Mental roll of at least 7 to decipher it.";

                        $dieRoll = rand(1, 6);
                        $totalCheck = $dieRoll + $mentalStat;
                        $clues[$i] = $eqn[1];

                        if ($totalCheck >= 7) {
                            $activeQuest[$i][2] = "You succeeded!";
                            $activeQuest[$i][3] = "The hidden number is $eqn[1]";
                        } else {
                            $activeQuest[$i][2] = "You Failed! You'll have to solve the following equation: $eqn[0]";
                            $activeQuest[$i][3] = "GET ANSWER";
                            $activeQuest[$i][4] = "$eqn[1]";
                            $activeQuest[$i][5] = "That's right! The hidden number is $eqn[1]!";
                        }
                    }

                    $index = $tempRand + 1;

                    $activeQuest[$index][0] = "Now to put them all together!";

                    $finalEqn = randomEqn($clues);



                    $activeQuest[$index][2] = "Solve this to get the address of $petName: $finalEqn[0]";
                    $activeQuest[$index][3] = "GET ANSWER";
                    $activeQuest[$index][4] = "$finalEqn[1]";
                    $activeQuest[$index][5] = "You found $petName! Good job!";
                    $activeQuest[$index][6] = "ADD GOLD";
                    break;
            }
        }

        generateQuestProgressChart();
    }

    function generateQuestProgressChart() {
        global $activeQuest, $questProgressChart, $scriptIsMade, $lengthOfScript;

        $index = 0;

        $rows = count($activeQuest);

        for ($row = 0; $row < $rows; $row++) {

            $temp = $activeQuest[$row];

            $cols = count($temp) - 1;
            for ($col = 0; $col < $cols; $col++) {
                $questProgressChart[$index] = $activeQuest[$row][$col];
                $index++;
           }
        }

        $lengthOfScript = count((array)$questProgressChart);

        $scriptIsMade = true;
    }

    function runQuestScript() {
        global $questProgressChart, $gold, $questReward, $questProgress;

        $count = count($questProgressChart);

        if ($questProgress <= $count) {

            if ($questProgressChart[$questProgress] == "GET ANSWER") {
                getAnswer($questProgressChart[$questProgress+1]);
                $questProgress = $questProgress + 2;
            } else if ($questProgressChart[$questProgress] == "ADD GOLD") {
                $gold += $questReward;
                updateGoldToTable();
            } else if ($questProgressChart[$questProgress] == "") {
                header("location: quests.php");
            } else {
                echo $questProgressChart[$questProgress];
            }
            $questProgress++;
        }

    }

    function hideNextButton() {
        global $nextButton;
        return $nextButton;
    }



    function getPattern() {
        $temp = rand(1, 3);
        $pattern[] = 0;

        switch ($temp) {
            case 1: //addition pattern
                $change = rand(1, 20);
                $start = rand(1, 100);
                $runningTotal = $start;
                $pattern[1] = $runningTotal;
                $runningTotal += $change;
                $pattern[2] = $runningTotal;
                $runningTotal += $change;
                $pattern[3] = $runningTotal;
                $runningTotal += $change;
                $pattern[4] = $runningTotal;
                $runningTotal += $change;
                $pattern[5] = $runningTotal;
                break;
            case 2: //subtraction pattern
                $change = rand(1, 20);
                $start = rand(1, 100);
                $runningTotal = $start;
                $pattern[1] = $runningTotal;
                $runningTotal -= $change;
                $pattern[2] = $runningTotal;
                $runningTotal -= $change;
                $pattern[3] = $runningTotal;
                $runningTotal -= $change;
                $pattern[4] = $runningTotal;
                $runningTotal -= $change;
                $pattern[5] = $runningTotal;
                break;
            case 3: //multiplication pattern
                $change = rand(1, 12);
                $start = rand(1, 12);
                $runningTotal = $start;
                $pattern[1] = $runningTotal;
                $runningTotal *= $change;
                $pattern[2] = $runningTotal;
                $runningTotal *= $change;
                $pattern[3] = $runningTotal;
                $runningTotal *= $change;
                $pattern[4] = $runningTotal;
                $runningTotal *= $change;
                $pattern[5] = $runningTotal;
                break;
            //case 4: //division pattern
                //$change = rand(1, 12);
                //$start = $change * $change * $change * $change * $change;
                //$runningTotal = $start;
                //$pattern[1] = $runningTotal;
                //$runningTotal = $runningTotal / $change;
                //$pattern[2] = $runningTotal;
                //$runningTotal = $runningTotal / $change;
                //$pattern[3] = $runningTotal;
               // $runningTotal = $runningTotal / $change;
               // $pattern[4] = $runningTotal;
               // $runningTotal = $runningTotal / $change;
               // $pattern[5] = $runningTotal;
                break;
        }

        return $pattern;
    }

    function setStatusFinished() {
        global $conn, $questId;

        mysqli_query($conn, "UPDATE quests SET questStatus = '2' WHERE questId = '$questId'");
    }
    
    function hideSubmitAnswer() {
        global $submitAnswer;
        return $submitAnswer;
    }

    function updateGoldToTable() {
        global $gold, $conn, $playerId;

        mysqli_query($conn, "UPDATE users SET gold = '$gold' WHERE playerId = '$playerId'");
    }

    function prepareToReload() {
        global $submitAnswer, $nextButton, $questProgress, $questProgressChart;

        $_SESSION['submitAnswer'] = $submitAnswer;
        $_SESSION['nextButton'] = $nextButton;
        $_SESSION['questProgress'] = $questProgress;
        $_SESSION['questProgressChart'] = $questProgressChart;

        //echo "console.log('Prepared to Reload')";

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
            <article class = "playActiveQuest">
                <h1 id="playActiveQuestTitle"><?php echo $questName ?>: A <?php echo $questType?> Quest</h1>
                <p id="playActiveQuestText">Are you ready?</p>
                <button onclick="runQuest()" <?php if(hideNextButton()){Print "hidden";}?>>Next</button>
                <input type="text"  id="submitAnswer" <?php if(hideSubmitAnswer()){Print "hidden";}?>>
                <button onclick="checkAnswer()" value="answer" <?php if(hideSubmitAnswer()){Print "hidden";}?>>Submit Answer</button>
                <script>

                    let passedArray = <?php echo json_encode((array)$questProgressChart);?>;
                    var index = <?php echo $questProgress;?>;
                    let count = <?php echo count((array)$questProgressChart) - 1;?>;
                    var print = document.getElementById("playActiveQuestText");
                    var answer = 0;
                    var answerChecked = false;

                    function runQuest() {
                        if (index <= count) {
                            <?php prepareToReload(); ?>
                            console.log("prepared to reload, Quest Progress = <?php echo $questProgress;?>");
                            if (passedArray[index+1] === "GET ANSWER") {
                                getAnswer(passedArray[index + 1]);
                            } else if (passedArray[index] === "ADD GOLD") {
                                <?php global $gold, $questProgress; $gold += $questReward; updateGoldToTable(); $questProgress++;?>
                                index++;
                            } else {
                                print.innerHTML = passedArray[index];
                                <?php global $questProgress; $questProgress++;?>
                                console.log("Quest Progress = <?php echo $questProgress++;?>");
                                index++;
                                console.log(index);
                            }
                        } else {
                            <?php setStatusFinished();?>
                            window.location.replace("quests.php");
                        }

                    }

                    function getAnswer(answer) {

                        <?php global $nextButton, $submitAnswer; $nextButton = true; $submitAnswer = false; prepareToReload();?>

                    }

                    function checkAnswer() {
                        if (document.getElementById("submitAnswer").value === answer) {
                            document.getElementById("playActiveQuestText").innerHTML = "Correct!";
                            answerChecked = true;
                        }
                    }
                </script>

            </article>
        </section>
    </body>
</html>
