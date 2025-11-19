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

        <section class="wrapper">
            <article class="bookstore">
                <h1 id="bookstore">The Bookstore</h1>
                <p>Physical Book - Increases Physical by 1: 10gp</p>
                <p>Mental Book - Increases Mental by 1: 10gp</p>
                <p>Social Book - Increases Social by 1: 10gp</p>
                <form action="bookstore.php" method="post" id="bookstoreForm">
                    <input type="submit" value="Buy Physical Book" id="button" name="PhysicalBook" />
                    <input type="submit" value="Buy Mental Book" id="button" name="MentalBook" />
                    <input type="submit" value="Buy Social Book" id="button" name="SocialBook" />
                </form>
            </article>

            <article class="apothecary">
                <h1 id="apothecary">Apothecary</h1>

                <h3 id="potions">Health Potions</h3>
                <p>Normal - Increases Health by 5-10 points: 10gp</p>
                <p>Strong - Increases Health by 10 - Max points: 30gp</p>
                <p>Massive - Inccreases Health to Full: 50gp</p>
            </article>
        </section>
    </body>
</html>
