<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->CharSet = "UTF-8";
    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.hostinger.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $email_username;                 // SMTP username
    $mail->Password = $email_password;                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom($from['address'], $from['name_company']);

    $email = explode(',', $email);

    for ($i=0; $i < count($email); $i++) {
        if ($i==0) $mail->addAddress($email[$i]); // Name is optional
        else $mail->addBCC($email[$i]);
    }

    //$mail->addReplyTo('contact@example.com', 'Contact');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Content

    $message = "<html>
                        <body style='background-color:#fafafa;padding:30px 0;'>
                            <div style='display:block;width:70%;margin:0 auto;background-color:#fff;border:1px solid #ccc;border-radius:4px;padding:0 30px;'>
                                <p style='text-align:center;'><img src='".$server_protocole."://".$_SERVER['SERVER_NAME']."/assets/images/core/logo.png' width='100'></p>
                                ".$message."
                            </div>
                        </body>
                    </html>";


    $mail->isHTML(true);
    $mail->Subject = adopt($subject);
    $mail->Body    = $message;

    $mail->send();
    //echo 'Message has been sent';
    $status_mail = 'success';
} catch (Exception $e) {
    //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    $status_mail = 'error';
}