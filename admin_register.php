
<?php

if(isset($_POST['submit'])){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "bookstore2";

        // create connection

        $conn = mysqli_connect($servername, $username, $password, $dbname);
        $email = $_POST['email'];
        $pass = $_POST['password'];

        //check connection

        if(!$conn)
        {
            echo "Connection failed".mysqli_connect_error();
            die();
        }
        
        $sql = "select * from admin where admin_email = '".$email."' and admin_password = '".$pass."'" or die("failed");
        $sql1 = "INSERT INTO admin (admin_username, admin_email, admin_password) VALUES('$_POST[username]', '$_POST[email]', '$_POST[password]')";
        
        $select_users = mysqli_query($conn,$sql);

        if(mysqli_num_rows($select_users) > 0)
        {
            echo '<p style="font-size: 20px;position: absolute;top: 85%;left: 44%;">User already exists...!</p>';
        }
        else
        {
            if($_POST['cpassword'] != $_POST['password'])
            {
                echo '<p style="font-size: 20px;position: absolute;top: 85%;left: 44%;">password and confirm password does not match...!</p>';
            }
            else
            {
                mysqli_query($conn,$sql1) or die("Failed");
                echo "Registered successfully.";
                header('location:admin_login.php');
            }
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
   <title>admin register</title>

  
   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>
<body>
    <div class="form-container">

        <form action="admin_register.php" method="post">
           <h3>admin Register </h3>
           <input type="text" name="username" pattern="^[a-z0-9]+$" title="only letters and numbers are allowed" placeholder="Username" required class="box" autofocus>
           <input type="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" placeholder="Email" required class="box">
           <input type="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" placeholder="Password" required class="box">
           <input type="password" name="cpassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" placeholder="Confirm password" required class="box">

           <input type="submit" name="submit" value="register" class="btn">
           <p>already have an account? <a href="admin_login.php">login </a></p>
        </form>
     
     </div>
     
     </body>
     </html>


