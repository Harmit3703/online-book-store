<?php
include 'config.php';

session_start();

if(isset($_POST['submit'])){
    $pass = $_POST['password'];
    $cpass = $_POST['cpassword'];
    $email = $_SESSION['admin_email'];
    
    $old_pass = $_SESSION['pass'];
    
    if($cpass != $pass){
        echo '<p style="font-size: 20px;position: absolute;top: 50%;left: 35%;">password and confirm password does not match</p>';
    }elseif($pass == $old_pass){
        echo '<p style="font-size: 20px;position: absolute;top: 50%;left: 38%;">old and new password cannot be same</p>';
    }else{
        mysqli_query($conn,"UPDATE `admin` SET admin_password ='$pass' WHERE admin_email = '$email'");
        header('location:admin_login.php');
    }
}




?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>new password</title>

   

   <!-- custom css file link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 

   <link rel="stylesheet" href="style.css">

</head>
<body>
    <div class="form-container">

    <form action="admin_set_pass.php" method="post">
           <h3>set new password</h3>
           <input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" name="password" placeholder="new password" required class="box">
           <input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" name="cpassword" placeholder="confirm password" required class="box">
           <input type="submit" name="submit" value="OK" class="btn" confirm="password changed">
        </form>
     
     </div>
</body>
</html>