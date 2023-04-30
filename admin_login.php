<?php //require 'partials/_nav.php'
   include 'config.php';
   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\SMTP;
   use PHPMailer\PHPMailer\Exception;


   require 'smtpmail/PHPMailer/src/Exception.php';
   require 'smtpmail/PHPMailer/src/PHPMailer.php';
   require 'smtpmail/PHPMailer/src/SMTP.php';
   session_start();

   if(isset($_POST['submit'])){
      $email = $_POST['email'];
      $pass = $_POST['password'];

      $sql ="SELECT * FROM `admin` WHERE admin_email = '$email' AND admin_password = '$pass' LIMIT 1";
      $select_admin = mysqli_query($conn,$sql);

      if(mysqli_num_rows($select_admin)==1)
      {
        $row = mysqli_fetch_assoc($select_admin);
        $_SESSION['admin_id'] = $row['idadmin'];
        $_SESSION['admin_username'] = $row['admin_username'];
        $_SESSION['admin_email'] = $row['admin_email'];
       
        $res=mysqli_query($conn,"select * from admin where admin_email='$email'");
                     
        $count=mysqli_num_rows($res);
        if($count>0){
           $otp=rand(111111,999999);
           mysqli_query($conn,"update admin set otp='$otp' where admin_email='$email'");
           $html="Your otp verification code is ".$otp;
           echo "yes";
        }else{
           echo "not_exist";
        }
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
        header('location:admin_otp_verify.php');
        
      }
      else
      {
         echo '<p style="font-size: 20px;position: absolute;top: 85%;left: 40%;">incorrect email or password...!</p>';
      }

      $conn -> close();
   }
   
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin login</title>

   

   <!-- custom css file link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
   <link rel="stylesheet" href="style.css">

</head>
<body>
    <div class="form-container">

        <form action="admin_login.php" method="post">
           <h3>admin login</h3>
           <input type="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" placeholder="Email" required class="box">
           <input type="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" placeholder="Password" required class="box">
           <input type="submit" name="submit" value="login" class="btn">
           <p>don't have an account? <a href="admin_register.php">register </a></p>
           <a href="admin_forgot_pass.php" style="color:#8e44ad; font-size: 1.7rem;">forgot password? </a>
        </form>
     
     </div>
     
     </body>
     </html>