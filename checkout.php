<?php include 'config.php';

    session_start();

    $user_id = $_SESSION['user_id'];
   
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <?php  include 'header.php';?>
    
</head>
<body>
   <section class="display-order">
   
      
      <?php
      $grand_total = 0;
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE idCustomer = '$user_id'") or die('query failed');
      if(mysqli_num_rows($select_cart) > 0){
         while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['price'] * $fetch_cart['qty']);
            $grand_total += $total_price;
   ?>
   <p> <?php echo $fetch_cart['book_name']; ?> <span>(<?php echo '₹'.$fetch_cart['price'].'/-'.' x '. $fetch_cart['qty']; ?>)</span> </p>
   <?php
      }
   }else{
      echo '<p class="empty"">your cart is empty</p>';
   }
   ?>
   <div class="grand-total"> grand total : <span>₹<?php echo $grand_total; ?>/-</span> </div>


   </section>
<section class="checkout">
<link rel="stylesheet" href="style.css">
<form  id="checkoutForm" method="post" action="payment.php">
   <h3>place your order</h3>
   <div class="flex">
      <div class="inputBox">
         <input type="hidden"  name="amt" id="amt" value="<?php echo $grand_total;?>">
         
         <span>your name :</span>
         <input type="text" name="name" value="<?php echo $_SESSION['name']?>" required placeholder="enter your name" id="name">
      </div>
      <div class="inputBox">
         <span>your number :</span>
         <input type="number" name="number" value="<?php echo $_SESSION['number']?>" required placeholder="enter your number" pattern="[1-9]{1}[0-9]{9}" title="Enter 10 digit mobile number" id="number">
      </div>
      <div class="inputBox">
         <span>your email :</span>
         <input type="email" name="email" value="<?php echo $_SESSION['email']?>" required placeholder="enter your email"   title="enter valid email address" id="email">
      </div>
      <div class="inputBox">
         <span>payment method :</span>
         <select name="paymentmethod" id="paymentmethod">
         
            <option value="debit card">debit card</option>
            <option value="credit card">credit card</option>
            <option value="net banking">net banking</option>
            <option value="UPI">UPI</option>
         </select>
      </div>
      <div class="inputbox">
         <span>address:</span>  
         <textarea name="address"  value="<?php echo $_SESSION['address']?>"required placeholder="address"></textarea>
      </div>
      <div class="inputBox">
         <span>pin code :</span>
         <input type="number" min="0" maxlength="6" name="pincode" value="<?php echo $_SESSION['pincode']?>" required placeholder="e.g. 123456">
      </div>
   </div>
   <input type="submit" value="submit" class="btn" name="order_btn" >
</form>

</section>

<?php include 'footer.php';?>


</body>
</html>