<?php

include "config.php";
session_start();

$user_id = $_SESSION['user_id'];
$username = $_SESSION["username"];
    $query = "SELECT * FROM customer WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    $fetch_user = mysqli_fetch_assoc($result);
    $username = $fetch_user["username"];
    $mobile = $fetch_user["mobile_no"];
    $email = $fetch_user["email"];
    $name = $fetch_user["customer_name"];
    $password = $fetch_user["password"];
    
    if (isset($_POST["update_user"]))
     {
        $new_name = $_POST["name"];
        $query = "UPDATE customer SET customer_name = '$new_name' WHERE username = '$username'";
        mysqli_query($conn,$query);
        $name = $new_name;

        $new_username = $_POST["username"];
        $query = "UPDATE customer SET username = '$new_username' WHERE username = '$username'";
        mysqli_query($conn, $query);
        $username = $new_username;

        $new_mobile = $_POST["mobile"];
        $query = "UPDATE customer SET mobile_no = '$new_mobile' WHERE username = '$username'";
        mysqli_query($conn, $query);
        $mobile = $new_mobile;

        $new_email = $_POST["email"];
        $query = "UPDATE customer SET email = '$new_email' WHERE username = '$username'";
        mysqli_query($conn, $query);
        $email = $new_email;

        $message[] = 'Profile updated';
      }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>
<body>
    <link rel="stylesheet" href="style.css">
    <?php 
          
      include 'header.php';
    ?>
    <div class="heading">
   <h3>Edit Profile</h3>
   <p> <a href="home.php">Home</a> / Edit Profile </p>
</div>

<div class="profile">

        <form action="edit_profile.php" method="post">
           <h3>Edit Profile</h3>
    
            <label for="username">Name:</label>
            <input type="text" id="name" name="name" class="box" value="<?php echo $name; ?>">
            <br>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username"class="box" value="<?php echo $username; ?>">
            <br>
            <label for="mobile">Mobile Number:</label>
            <input type="number" id="mobile" name="mobile" class="box" maxlength="10"  pattern="[1-9]{1}[0-9]{9}" value="<?php echo $mobile;?>">
            <br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="box"value="<?php echo $email; ?>">
            <br>
            <button type="submit" name="update_user" class="btn">Update</button>
            <a href="changepass.php" class="option-btn">change password</a>
        </form>     
     </div>
    </div>
     <?php include 'footer.php';?>
 
</body>
</html>