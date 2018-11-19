
<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'tanhro966@gmail.com';                 // SMTP username
    $mail->Password = 'bbkdnltha';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('tanhro966@gmail.com', 'Hoang Trong Trung');
    $mail->addAddress('lenguyentrungkien999@gmail.com', 'kien le');

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Chào mừng đến với mạng xã hỗi FaceBookFake';
    $mail->Body    = 'Chào <strong>Lê Nguyễn Trung Kiên</strong>';
    $mail->AltBody = 'Chào Lê Nguyễn Trung Kiên';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}