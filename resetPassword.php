<?php
   $conn = mysqli_connect("sql110.infinityfree.com", "if0_40582300", "uj0krRpEXI");
    mysqli_select_db($conn, "if0_40582300_LandsOfLogic");

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $query = mysqli_query($conn,"SELECT username, id FROM users WHERE email = '$email'");

    $exists = mysqli_num_rows($query);

    $error = "";

    while($row = mysqli_fetch_array($query)){
        $username = $row['username'];
        $id = $row['id'];
    }

    if($exists === 1) {
        sendEmail();
    } else if($exists === 0) {
        $error = "No account found with that email";
    } else {
        $error = "Multiple accounts found with that email";
    }

    function sendEmail() {
        global $email;
        global $username;
        global $id;
        $output = '<p>'.$username.',</p>';
        $output .= '<p>Please click on the link below to reset your password.</p>';
        $output .= '<hr>';
        //to change to whatever when hosted
        $output .= '<p><a href="landsoflogic.great-site.net/newPassword.php?id='.$id.'">Reset Password</a></p>';
        $output .= '<hr>';
        $output .= '<p>If you did not make this request, please ignore this email.</p>';
        $output .= 'Thank you, ';
        $output .= 'Jackie';

        $body = $output;
        $subject = "Lands Of Logic Reset Password";

        $emailTo = $email;
        $fromServer = "jackieecharbonneau@gmail.com"; //For now
        require("PHPMailer/PHPMailerAutoload.php");
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = $fromServer;
        $mail->Password =  d; //edit this too
        $mail->Port = 25;
        $mail->IsHTML(true);
        $mail->From = $fromServer;
        $mail->FromName = "Lands Of Logic";
        $mail->Sender = $fromServer;
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AddAddress($emailTo);

        if(!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "<p>An email has been sent to you with instructions on how to reset your password.</p>";
        }
    }
?>