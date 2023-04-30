<?php
session_start();
$con=mysqli_connect('localhost','root','','bookstore');
$email=$_POST['email'];
$res=mysqli_query($con,"select * from customer where email='$email'");
$count=mysqli_num_rows($res);
if($count>0){
	$otp=rand(11111,99999);
	mysqli_query($con,"update customer set otp='$otp' where email='$email'");
	$html="Your otp verification code is ".$otp;
	$_SESSION['EMAIL']=$email;
	
	echo "yes";
}else{
	echo "not_exist";
}


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require 'smtpmail/PHPMailer/src/Exception.php';
require 'smtpmail/PHPMailer/src/PHPMailer.php';
require 'smtpmail/PHPMailer/src/SMTP.php';


$mail = new PHPMailer(true);

try {
 
                  
    $mail->isSMTP();                                           
    $mail->Host       = 'smtp.gmail.com';                     
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = 'priyanshparmar199@gmail.com';                    
    $mail->Password   = 'gywmgzledgybngfg';                             
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
    $mail->Port       = 465;                                    

  
    $mail->setFrom('priyanshparmar199@gmail.com', 'books4U');

    $mail->addAddress($email);             


    $mail->isHTML(true);                                 
    $mail->Subject = 'otp verification';
    $mail->Body    = "{$otp}";
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
	} 	catch (Exception $e) 
	{
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}

?>