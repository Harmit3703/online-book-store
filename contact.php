
<?php
    include 'config.php';
    session_start();
    if(isset($_POST['send_btn']))
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $number = $_POST['number'];
        $message = $_POST['message'];
        $date = date("d-M-Y");
        
        mysqli_query($conn, "INSERT INTO `feedback`(email, description, feedback_date, number, name) VALUES('$email','$message','$date','$number','$name')") or die("failed");
        echo "thank you for contactin us";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>contact</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php';?>
    <div class="heading">
        <h3>contact us</h3>
        <p><a href="home.php">Home</a> / Contact </p>
    </div>
    
    <section class="contact">
        <form action="contact.php" method="post">
            <h3>say something!</h3>
            <input type="text" name="name" required placeholder="Full name" class="box">
            <input type="email" name="email" required placeholder="Email" class="box">
            <input type="number" name="number" required placeholder="Number" class="box">
            <textarea name="message" class="box" required placeholder="Message"id="" cols="30" rows="10"></textarea>
            <input type="submit" value="send message" name="send_btn" class="btn">
        </form>
    </section>
</body>
</html>