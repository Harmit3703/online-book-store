<?php
    include 'config.php';
    session_start();
    
    if(isset($_POST['submit'])){

        $otp=$_POST['otp'];
        $email=$_SESSION['email'];
        $res=mysqli_query($conn,"select * from customer where email='$email' and otp='$otp'");
        $count=mysqli_num_rows($res);
        $row = mysqli_fetch_assoc($res);
        if($count>0){
            mysqli_query($conn,"update customer set otp='' where email='$email'");
            $_SESSION['IS_LOGIN']=$email;
            $_SESSION['user_id'] = $row['idCustomer'];
            echo "yes";
            if (isset($_SESSION['user_id'])) {
                // Get the user ID
                $user_id = $_SESSION['user_id'];
            
                // Get the session ID
                $session_id = session_id();
            
                // Select the visitor cart items
                $query = "SELECT book_name, qty, price, book_img FROM visitor_cart WHERE session_id = '$session_id'";
                $result_visitor = mysqli_query($conn, $query);
            
                // Loop through the visitor cart items
                while ($row_visitor = mysqli_fetch_assoc($result_visitor)) {
                    // Get the book name, quantity, amount, and image
                    $book_name = $row_visitor['book_name'];
                    $qty = $row_visitor['qty'];
                    $price = $row_visitor['price'];
                    $book_img = $row_visitor['book_img'];
            
                    // Check if the book already exists in the user's cart
                    $query = "SELECT qty FROM cart WHERE idCustomer = '$user_id' AND book_name = '$book_name'";
                    $result_cart = mysqli_query($conn, $query);
            
                    if (mysqli_num_rows($result_cart) > 0) {
                        // Update the quantity of the existing book in the user's cart
                        $row_cart = mysqli_fetch_assoc($result_cart);
                        $new_qty = $row_cart['qty'] + $qty;
                        $query = "UPDATE cart SET qty = '$new_qty' WHERE idCustomer = '$user_id' AND book_name = '$book_name'";
                        mysqli_query($conn, $query);
                    } else {
                        // Insert the new book into the user's cart
                        $query = "INSERT INTO cart (idCustomer, book_name, qty, price, book_img) VALUES ('$user_id', '$book_name', '$qty', '$price', '$book_img')";
                        mysqli_query($conn, $query);
                    }
                }
            
                // Delete the visitor cart items
                $query = "DELETE FROM visitor_cart WHERE session_id = '$session_id'";
                mysqli_query($conn, $query);
            }
            
            header('location:home.php');
        }else{
            echo '<p style="font-size: 30px;position: absolute;top: 50%;left: 45%;">invalid OTP</p>';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   

   <!-- custom css file link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
   <link rel="stylesheet" href="style.css">

</head>
<body>
    <div class="form-container" style="flex-direction:column">

        <div style="font-size:20px; margin-bottom:20px;">
            <p>we have sent you mail please check you mailbox</p>
        </div>

        <form action="otp_verify.php" method="post">
           <h3>otp verification</h3>
           <input type="number" maxlength="6" name="otp" placeholder="Enter 6 digit otp" required class="box" autofocus>
           <input type="submit" name="submit" value="verify" class="btn">
           
        
        </form>
     
     </div>
     
     </body>
     </html>