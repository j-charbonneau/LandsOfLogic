<?php
    session_start();

    if (isset($_SESSION['id'])) {
        $character = $_SESSION['character'];
        $id = $_SESSION['id'];
    } else {
        header("location:login.php");
        exit;
    }

    $conn = mysqli_connect("sql110.infinityfree.com", "if0_40582300", "uj0krRpEXI");

    if (!$conn) {
        die("DB connection failed: " . mysqli_connect_error());
    }
    mysqli_select_db($conn, "if0_40582300_LandsOfLogic");

    $questId = 0;
    if (!empty($_GET['questId'])) {
        $questId = (int)$_GET['questId'];
        $_SESSION['questId'] = $questId;
    } elseif (!empty($_SESSION['questId'])) {
        $questId = (int)$_SESSION['questId'];
    } else {
        header("location:quests.php");
        exit;
    }

    $activeQuest = array();
    $questProgressChart = array();
    $scriptIsMade = false;
    $questReward = 0;
    $questName = "";
    $questType = "";
    $gold = 0;

    makeQuestScript();

    function makeQuestScript() {
        global $scriptIsMade, $questReward, $questName, $questType;
        if ($scriptIsMade) {
            return;
        }

        global $conn, $id, $questId, $activeQuest, $gold;

        $questIdEsc = (int)$questId;
        mysqli_query($conn, "UPDATE quests SET questStatus = 1 WHERE questId = $questIdEsc");

        $query = mysqli_query($conn, "SELECT * FROM quests WHERE questId = $questIdEsc");
        $row   = mysqli_fetch_array($query);
        if (!$row) {
            header("location:quests.php");
            exit;
        }

        $questType = $row['questType'];
        $questName = $row['questName'];
        $questReward = (int)$row['questReward'];
        $questGiver = $row['questGiver'];
        $naturalForce = $row['naturalForce'];
        $petName = $row['petName'];

        $uquery = mysqli_query($conn, "SELECT * FROM users WHERE playerId = '$id'");
        $urow   = mysqli_fetch_array($uquery);
        $physicalStat  = (int)$urow['physicalStat'];
        $mentalStat    = (int)$urow['mentalStat'];
        $socialStat    = (int)$urow['socialStat'];
        $characterName = $urow['characterName'];
        $gold          = (int)$urow['gold'];

        $f_names = file("names.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        switch ($questType) {

            case "Social":
                $tempRand = rand(2, 6);
                $activeQuest[0][0] = "You will need to verify orders with $tempRand customers through SOCIAL checks.";
                $runningTotal   = 0;
                $customerTotals = array();

                for ($i = 1; $i <= $tempRand; $i++) {
                    $npcName = $f_names[rand(0, count($f_names) - 1)];

                    $activeQuest[$i][0] = "You meet $npcName.";
                    $activeQuest[$i][1] = "$npcName: Hello, $characterName.";
                    $activeQuest[$i][2] = "If you would like to get their receipt, you will need a SOCIAL roll of at least 7.";

                    $dieRoll    = rand(1, 6);
                    $totalCheck = $dieRoll + $socialStat;
                    $activeQuest[$i][3] = "You rolled a $dieRoll, for a total of $totalCheck.";

                    $goldCost   = rand(1, 10);
                    $silverCost = rand(1, 10);
                    $copperCost = rand(1, 10);

                    $totalCostCopper = $copperCost + ($silverCost * 10) + ($goldCost * 100);
                    $totalCostGold   = $goldCost + ($silverCost / 10) + ($copperCost / 100);

                    $numberOfItems = 0;
                    $questItem     = 0;
                    for ($x = 2; $x <= 10; $x++) {
                        if ($totalCostCopper % $x == 0) {
                            $numberOfItems = $x;
                            $questItem     = $totalCostCopper / $x; // price per item in copper
                            break;
                        }
                    }
                    if ($numberOfItems === 0) {
                        $numberOfItems = 1;
                        $questItem     = $totalCostCopper;
                    }

                    $formattedGold   = number_format($totalCostGold, 2, '.', '');
                    $customerTotals[] = "$npcName: $formattedGold gold pieces";

                    if ($totalCheck >= 7) {
                        $activeQuest[$i][4] = "You Succeeded!";
                        $activeQuest[$i][5] = "$npcName: I spent a total of $goldCost gold pieces, $silverCost silver pieces, and $copperCost copper pieces.";
                        $activeQuest[$i][6] = "On to the next customer!";
                    } else {
                        $activeQuest[$i][4] = "You Failed! You'll have to put together their total.";
                        $activeQuest[$i][5] = "$npcName: I bought $numberOfItems things for $questItem copper pieces. How many gold pieces would that be?";
                        $activeQuest[$i][6] = "GET ANSWER";
                        $activeQuest[$i][7] = $formattedGold;
                        $activeQuest[$i][8] = "$npcName: That's right!";
                        $activeQuest[$i][9] = "On to the next customer!";
                    }

                    $runningTotal += $totalCostGold;
                }

                $index = $tempRand + 1;
                $activeQuest[$index][0] = "Now, you must combine all of the customers' totals!";

                $pos = 1;
                foreach ($customerTotals as $j => $line) {
                    $activeQuest[$index][$pos] = "Customer " . ($j + 1) . ": " . $line;
                    $pos++;
                }

                $finalGold = number_format($runningTotal, 2, '.', '');
                $activeQuest[$index][$pos]     = "GET ANSWER";
                $activeQuest[$index][$pos + 1] = $finalGold;
                $activeQuest[$index][$pos + 2] = "$questGiver: That sounds right! Thank you so much $characterName!";
                $activeQuest[$index][$pos + 3] = "ADD GOLD";
                break;

            case "Physical":
                $tempRand = rand(2, 6);
                $activeQuest[0][0] = "You will need to make your way to $tempRand Watchtowers to take measurements with PHYSICAL checks!";

                $towerSummaries = array();
                $totalHeight    = 0;
                $totalDistance  = 0;

                for ($i = 1; $i <= $tempRand; $i++) {
                    $activeQuest[$i][0] = "You come across a Watchtower. In order to get the correct measurement, you'll have to get up there!";
                    $activeQuest[$i][1] = "You will need a PHYSICAL roll of at least 7 in order to get to the top!";

                    $dieRoll    = rand(1, 6);
                    $totalCheck = $dieRoll + $physicalStat;

                    $distance = rand(5, 20);
                    $height   = rand(10, 50);
                    $slope    = round($height / $distance, 1);

                    $totalHeight   += $height;
                    $totalDistance += $distance;

                    $formattedSlope   = number_format($slope, 1, '.', '');
                    $towerSummaries[] = "Distance: $distance ft, Height: $height ft, Slope ≈ $formattedSlope";

                    if ($totalCheck >= 7) {
                        $activeQuest[$i][2] = "You succeeded!";
                        $activeQuest[$i][3] = "You reached the top of the Watchtower!";
                        $activeQuest[$i][4] = "You are able to measure that, at $distance feet away, the $naturalForce is $height feet high.";
                    } else {
                        $activeQuest[$i][2] = "You Failed! You'll have to find the footholds to scale the side.";
                        $pattern = getPattern();
                        $activeQuest[$i][3] = "Finish the Pattern: {$pattern[1]}, {$pattern[2]}, {$pattern[3]}, {$pattern[4]}, ?";
                        $activeQuest[$i][4] = "GET ANSWER";
                        $activeQuest[$i][5] = $pattern[5];
                        $activeQuest[$i][6] = "Correct! On to the next watchtower!";
                    }
                }

                $index = $tempRand + 1;

                $averageHeight   = $totalHeight / $tempRand;
                $averageDistance = $totalDistance / $tempRand;
                $averageSlope    = round($averageHeight / $averageDistance, 1);
                $formattedAverageSlope = number_format($averageSlope, 1, '.', '');

                $activeQuest[$index][0] = "Now calculate the average slope (height ÷ distance).";

                $pos = 1;
                foreach ($towerSummaries as $k => $line) {
                    $activeQuest[$index][$pos] = "Watchtower " . ($k + 1) . ": " . $line;
                    $pos++;
                }

                $activeQuest[$index][$pos]     = "GET ANSWER";
                $activeQuest[$index][$pos + 1] = $formattedAverageSlope;
                $activeQuest[$index][$pos + 2] = "Your measurement reveals the truth!";
                $activeQuest[$index][$pos + 3] = "ADD GOLD";
                break;

            case "Mental":
                $tempRand = rand(2, 6);
                $activeQuest[0][0] = "In order to find $petName, you must find $tempRand clues!";

                $clues = array();

                for ($i = 1; $i <= $tempRand; $i++) {
                    $activeQuest[$i][0] = "You come across a clue!";

                    $rand1 = rand(1, 20);
                    $rand2 = rand(1, 20);
                    $rand3 = rand(1, 10);

                    $eqn = randomEquation(array($rand1, $rand2, $rand3)); // [equation, answer]

                    $activeQuest[$i][1] = "You find a cryptic note! You will need a Mental roll of at least 7 to decipher it.";

                    $dieRoll    = rand(1, 6);
                    $totalCheck = $dieRoll + $mentalStat;

                    $clues[$i] = (int)$eqn[1];

                    if ($totalCheck >= 7) {
                        $activeQuest[$i][2] = "You succeeded!";
                        $activeQuest[$i][3] = "The hidden number is {$eqn[1]}.";
                    } else {
                        $activeQuest[$i][2] = "You Failed! You'll have to solve the following equation: {$eqn[0]}";
                        $activeQuest[$i][3] = "GET ANSWER";
                        $activeQuest[$i][4] = (string)$eqn[1];
                        $activeQuest[$i][5] = "That's right! The hidden number is {$eqn[1]}!";
                    }
                }

                $index = $tempRand + 1;
                $activeQuest[$index][0] = "Now put all the clues together!";

                $pos = 1;
                for ($i = 1; $i <= $tempRand; $i++) {
                    $activeQuest[$index][$pos] = "Clue $i: Hidden number = " . $clues[$i];
                    $pos++;
                }

                $finalEqn = randomEqn($clues); // [equation, answer]

                $activeQuest[$index][$pos]     = "Solve the final equation to get the address of $petName: {$finalEqn[0]}";
                $activeQuest[$index][$pos + 1] = "GET ANSWER";
                $activeQuest[$index][$pos + 2] = (string)$finalEqn[1];
                $activeQuest[$index][$pos + 3] = "You found $petName!";
                $activeQuest[$index][$pos + 4] = "ADD GOLD";
                break;
        }

        generateQuestProgressChart();
    }

    function generateQuestProgressChart() {
        global $activeQuest, $questProgressChart, $scriptIsMade;

        $questProgressChart = array();
        $index = 0;

        $rows = count($activeQuest);
        for ($row = 0; $row < $rows; $row++) {
            $temp = $activeQuest[$row];
            $cols = count($temp);
            for ($col = 0; $col < $cols; $col++) {
                if (isset($activeQuest[$row][$col]) && $activeQuest[$row][$col] !== "") {
                    $questProgressChart[$index] = $activeQuest[$row][$col];
                    $index++;
                }
            }
        }

        $scriptIsMade = true;
    }

    function getPattern() {
        $temp    = rand(1, 3);
        $pattern = array();

        switch ($temp) {
            case 1: // addition
                $change = rand(1, 20);
                $start  = rand(1, 100);
                $runningTotal = $start;
                for ($i = 1; $i <= 5; $i++) {
                    $pattern[$i] = $runningTotal;
                    $runningTotal += $change;
                }
                break;

            case 2: // subtraction
                $change = rand(1, 20);
                $start  = rand(1, 100);
                $runningTotal = $start;
                for ($i = 1; $i <= 5; $i++) {
                    $pattern[$i] = $runningTotal;
                    $runningTotal -= $change;
                }
                break;

            case 3: // multiplication
                $change = rand(1, 12);
                $start  = rand(1, 12);
                $runningTotal = $start;
                for ($i = 1; $i <= 5; $i++) {
                    $pattern[$i] = $runningTotal;
                    $runningTotal *= $change;
                }
                break;
        }

        return $pattern;
    }

    function randomEquation($params) {
        $a = (int)$params[0];
        $b = (int)$params[1];
        $c = (int)$params[2];

        $type = rand(1, 3);
        switch ($type) {
            case 1:
                $eqn = "$a + $b - $c";
                $ans = $a + $b - $c;
                break;
            case 2:
                $eqn = "$a + $b + $c";
                $ans = $a + $b + $c;
                break;
            default:
                $eqn = "($a + $b) - $c";
                $ans = ($a + $b) - $c;
                break;
        }

        return array($eqn, $ans);
    }

    function randomEqn($clues) {
        $equation = "";
        $total    = 0;

        foreach ($clues as $i => $num) {
            $num = (int)$num;
            if ($i == 1) {
                $total    = $num;
                $equation = (string)$num;
            } else {
                if (rand(0, 1) == 0) {
                    $total    += $num;
                    $equation .= " + $num";
                } else {
                    $total    -= $num;
                    $equation .= " - $num";
                }
            }
        }

        return array($equation, $total);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php Print htmlspecialchars($character); ?>: Character Page</title>
        <link rel="stylesheet" type="text/css" href="landsOfLogic.css">
    </head>

    <body>
        <header>
            <h1 id="logo"><sup id="logoTHE">THE</sup>Lands of Logic</h1>
        </header>

        <nav>
            <a href="home.php" class="nav">
                <h1><?php Print htmlspecialchars($character); ?></h1>
            </a>

            <a href="quests.php" class="nav">
                <h1>Quest Board</h1>
            </a>

            <a href="shops.php" class="nav">
                <h1>The Market</h1>
            </a>

            <a href="account.php" class="nav">
                <h1>Account</h1>
            </a>
        </nav>

        <section>
            <article class="playActiveQuest">
                <h1 id="playActiveQuestTitle">
                    <?php Print htmlspecialchars($questName); ?>: A <?php Print htmlspecialchars($questType); ?> Quest
                </h1>
                <p id="playActiveQuestText">Are you ready?</p>

                <div id="forwardAndBack">
                    <button onclick="backUp()">Back</button>
                    <button onclick="runQuest()">Next</button>
                </div>
                <br>
                <input type="text" id="submitAnswer">
                <button onclick="checkAnswer()" value="answer">Submit Answer</button>

                <script>
                    let passedArray = <?php Print json_encode(array_values($questProgressChart)); ?>;

                    const questId = <?php Print (int)$questId; ?>;
                    const storageKey = "questIndex_" + questId;

                    let index = 0;
                    let count = passedArray.length - 1;
                    let printElem = document.getElementById("playActiveQuestText");
                    let answer = null;
                    let waitingForAnswer = false;

                    let savedIndex = parseInt(localStorage.getItem(storageKey));
                    if (!isNaN(savedIndex) && savedIndex >= 0 && savedIndex <= count) {
                        index = savedIndex;
                        if (passedArray[index]) {
                            printElem.innerHTML = passedArray[index];
                        }
                    }

                    function saveProgress() {
                        localStorage.setItem(storageKey, index);
                    }

                
                    function backUp() {
                        waitingForAnswer = false;
                        document.getElementById("submitAnswer").value = "";

                        if (index <= 0) return;

                        index--;

                        while (index > 0 && (passedArray[index] === "GET ANSWER" || passedArray[index] === "ADD GOLD")) {
                            index--;
                        }

                        if (passedArray[index]) {
                            printElem.innerHTML = passedArray[index];
                        }
                        saveProgress();
                    }
                    

                    function runQuest() {
                        if (waitingForAnswer) return;

                        if (index > count) {
                            localStorage.removeItem(storageKey);
                            window.location.replace("completeQuest.php?questId=" + questId);
                            return;
                        }


                        if (passedArray[index] === "GET ANSWER") {
                            answer = passedArray[index + 1];
                            printElem.innerHTML = "Enter your answer:";
                            waitingForAnswer = true;
                            return;
                        }

                        if (passedArray[index] === "ADD GOLD") {
                            index++;
                            saveProgress();
                            runQuest();
                            return;
                        }

                        printElem.innerHTML = passedArray[index];
                        index++;
                        saveProgress();
                    }

                    function checkAnswer() {
                        if (!waitingForAnswer) return;

                        let userAnswer = document.getElementById("submitAnswer").value.trim();
                        let userNum    = parseFloat(userAnswer);
                        let correctNum = parseFloat(answer);

                        if (!isNaN(userNum) && !isNaN(correctNum) && Math.abs(userNum - correctNum) < 0.001) {
                            printElem.innerHTML = "Correct!";
                            waitingForAnswer = false;
                            index += 2;
                            document.getElementById("submitAnswer").value = "";
                            saveProgress();
                            runQuest();
                        } else {
                            printElem.innerHTML = "Try again.";
                        }
                    }
                </script>
            </article>
        </section>
    </body>
</html>