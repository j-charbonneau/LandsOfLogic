<?php
    session_start();
?>

<html>

    <head>
        <title>Registration</title>
    </head>

    <body>

        <a id="switchLoginType" href="login.php">Already have an account? Login here!</a>

        <article class="loginDesc">
            <br />
            <h3>Enter the Lands of Logic</h3>
            <p>Here, you'll be find adventure mixed with education. Once you enter the Lands of Logic,
                you'll be able to embark on quests with a variety of townsfolk, helping them through their
                daily problems.</p>
            <br />
            <p>Are <strong id="you">you</strong> up to the challenge?</p>
            <br />
            <br />
        </article>

        <article class="createAccount">
            <h3 id="createAccountHeader">CREATE AN ACCOUNT</h3>
            <form action="register.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required /><br />

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required /><br />

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required /><br />

                <label for="verifyPassword">Verify Password:</label>
                <input type="password" id="verifyPassword" name="verifyPassword" required /><br />

                <input type="submit" value="Create Account" />
            </form>
        </article>
    </body>
</html>

<?php
    $conn = mysqli_connect("sql110.infinityfree.com", "if0_40582300", "uj0krRpEXI");
        mysqli_select_db($conn, "if0_40582300_LandsOfLogic");

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $verifyPassword = mysqli_real_escape_string($conn, $_POST['verifyPassword']);
        $bool = true;
        $tableUsers = $tableEmails = $id = "";

       

        $query = mysqli_query($conn, "SELECT * FROM users");

        while($row = mysqli_fetch_assoc($query)){
            $tableUsers = $row['username'];
            $tableEmails = $row['email'];

            if($username == $tableUsers){
                $bool = false;
                Print '<script>alert("Username already exists!");</script>';
                Print '<script>window.location.assign("register.php");</script>';
                exit;
            }

            if($email == $tableEmails){
                $bool = false;
                Print '<script>alert("Email already exists!");</script>';
                Print '<script>window.location.assign("register.php");</script>';
                exit;
            }
        }

        if($verifyPassword != $password){
            $bool = false;
            Print '<script>alert("Passwords Do Not Match!");</script>';
            Print '<script>window.location.assign("register.php");</script>';
            exit;
        }

        if ($bool) {
                mysqli_query($conn, "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')");

            $query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
                while($row = mysqli_fetch_assoc($query)){
                $id = $row['playerId'];
           		 }

            $_SESSION['id'] = $id;
                    Print '<script>window.location.assign("characterCreation.php");</script>';

        }
        

  
    }
?>

