<?php
    session_start();

    if(isset($_SESSION['id'])){
        global $_SESSION;
        $character = $_SESSION['character'];
        $id = $_SESSION['id'];
    } else {
        header("location:login.php");
    }
?>

<html>
    <head>
        <title>The Market</title>
        <link rel="stylesheet" type="text/css" href="landsOfLogic.css">
    </head>

    <body>
        <header>
            <h1 id="logo"><sup id="logoTHE">THE</sup>Lands of Logic</h1>
        </header>

        <nav>
            <a href="home.php" class="nav">
                <h1><?php Print $character ?></h1>
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

        <section class="wrapper">
            <article class="bookstore">
                <h1 id="bookstore">The Bookstore</h1>
                <p>Physical Book - Increases Physical by 1: 10gp</p>
                <p>Mental Book - Increases Mental by 1: 10gp</p>
                <p>Social Book - Increases Social by 1: 10gp</p>
                <form action="buyItem.php" method="post" id="itemBought">
                    <input type="submit" value="Buy Physical Book" id="button" name="PhysicalBook" />
                    <input type="submit" value="Buy Mental Book" id="button" name="MentalBook" />
                    <input type="submit" value="Buy Social Book" id="button" name="SocialBook" />
                </form>
            </article>

            <article class="apothecary">
                <h1 id="apothecary">Apothecary</h1>

                <div class="healthPotions">
                    <h3 id="potions">Health Potions</h3>
                    <p>Normal - Increases Health by 5-10 points: 10gp</p>
                    <p>Strong - Increases Health by 10 - Max points: 30gp</p>
                    <p>Massive - Inccreases Health to Full: 50gp</p>
                    <form action="buyItem.php" method="post" id="itemBought">
                        <input type="submit" value="Buy Normal Health Potion" id="button" name="NormalHealth" />
                        <input type="submit" value="Buy Strong Health Potion" id="button" name="StrongHealth" />
                        <input type="submit" value="Buy Massive Health Potion" id="button" name="MassiveHealth" />
                    </form>
                </div>

                <div class="shieldPotions">
                    <h3 id="potions">Shield Potions</h3>
                    <p>Normal - Increases Shield by 1-4 points: 10gp</p>
                    <p>Strong - Increases Shield by 4-full points: 20gp</p>
                    <p>Massive - Increases shield to full points: 30gp</p>
                    <form action="buyItem.php" method="post" id="itemBought">
                        <input type="submit" value="Buy Normal Shield Potion" id="button" name="NormalShield" />
                        <input type="submit" value="Buy Strong Shield Potion" id="button" name="StrongShield" />
                        <input type="submit" value="Buy Massive Health Potion" id="button" name="MassiveShield" />
                    </form>
                </div>
            </article>
        </section>
    </body>
</html>
