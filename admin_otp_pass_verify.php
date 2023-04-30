<?php
    include 'config.php';
    session_start();
    
    if(isset($_POST['submit'])){

        $otp=$_POST['otp'];
        $email=$_SESSION['admin_email'];
        $res=mysqli_query($conn,"select * from admin where admin_email='$email' and otp='$otp'");
        $count=mysqli_num_rows($res);
        $row = mysqli_fetch_assoc($res);
        if($count>0){
            mysqli_query($conn,"update admin set otp='' where admin_email='$email'");
            $_SESSION['IS_LOGIN']=$email;
            $_SESSION['admin_id'] = $row['idadmin'];
            echo "yes";
            header('location:admin_set_pass.php');
        }else{
            echo '<p style="font-size: 30px;position: absolute;top: 45%;left: 43%;">incorrect OTP!</p>';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title></title>

   

   <!-- custom css file link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
   <link rel="stylesheet" href="style.css">

</head>
<body>
    <div class="form-container" style="flex-direction:column">

    <div style="font-size:20px; margin-bottom:20px;">
            <p>we have sent you mail please check you mailbox</p>
        </div>

        <form action="admin_otp_pass_verify.php" method="post">
           <h3>otp verification</h3>
           <input type="number" maxlength="6" name="otp" placeholder="Enter 6 digit otp" required class="box" autofocus>
           <input type="submit" name="submit" value="verify" class="btn">
           
        
        </form>
     
     </div>
     
     </body>
     </html>