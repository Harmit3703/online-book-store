<?php


include 'config.php';

session_start();

    $user_id = $_SESSION['user_id'];


if (isset($_POST['submit'])) {
    
    $current_password = $_POST['current_password'];

  
    $new_password = $_POST['new_password'];

    
    $username = $_SESSION['username'];

    
    $query = "SELECT * FROM customer WHERE username = '$username' AND password = '$current_password'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        
        $query = "UPDATE customer SET password = '$new_password' WHERE username = '$username'";
        mysqli_query($conn, $query);

        
        $message[] = 'Your password has been updated successfully!';
    } else {
        
        $message[] = 'Your current password is incorrect. Please try again.';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>
    <?php include 'header.php';?>
<div class="heading">
   <h3>change password</h3>
   <p> <a href="home.php">Home</a> / <a href="edit_profile.php">Edit Profile</a> / Change Password </p>
</div>

    <link rel="stylesheet" href="style.css">
    
    <div class="form-container">
        <form action="changepass.php" method="post" >
            <input class="box"  type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" name="current_password" placeholder="Current Password">
            <input  class="box" type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" name="new_password" placeholder="New Password">
            <input  class="btn" type="submit" name="submit" value="Change Password">
        </form>

    </div>
<?php include 'footer.php';?>
</body>
</html>
