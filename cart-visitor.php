<?php include 'config.php';

   session_start();

   $sessionId = session_id();

   if(isset($_POST['update_cart'])){
      $cart_id = $_POST['cart_id'];
      $cart_quantity = $_POST['cart_quantity'];
      mysqli_query($conn, "UPDATE `visitor_cart` SET qty = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
      $message[] = 'cart quantity updated!';
   }
   
   if(isset($_GET['delete'])){
      $delete_id = $_GET['delete'];
      mysqli_query($conn, "DELETE FROM `visitor_cart` WHERE id = '$delete_id'") or die('query failed');
      header('location:cart-visitor.php');
   }
   
   if(isset($_GET['delete_all'])){
      mysqli_query($conn, "DELETE FROM `visitor_cart` WHERE session_id = '$sessionId'") or die('query failed');
      header('location:cart-visitor.php');
   }



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header copy.php';
?>

<div class="heading">
   <h3>shopping cart</h3>
   <p> <a href="home-visitor.php">home</a> / cart </p>
</div>
<section class="shopping-cart">

   <h1 class="title">products added</h1>

   <div class="box-container">
      <?php
         $grand_total = 0;
         $select_cart = mysqli_query($conn, "SELECT * FROM `visitor_cart` WHERE session_id = '$sessionId'") or die('query failed');
         if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){   
      ?>
      <div class="box">
         <a href="cart-visitor.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
         <img src="upload_img/<?php echo $fetch_cart['book_img']; ?>" alt="">
         <div class="name"><?php echo $fetch_cart['book_name']; ?></div>
         <div class="price">₹<?php echo $fetch_cart['price']; ?>/-</div>
         <form action="cart-visitor.php" method="post">
            <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
            <input type="number" min="1" max="5" name="cart_quantity" value="<?php echo $fetch_cart['qty']; ?>">
            <input type="submit" name="update_cart" value="update" class="option-btn">
         </form>
         <div class="sub-total"> sub total : <span>₹<?php echo $sub_total = ($fetch_cart['qty'] * $fetch_cart['price']); ?>/-</span> </div>
      </div>
      <?php
      $grand_total += $sub_total;
         }
      }else{
         echo '<p class="empty" style="font-size:2rem; text-align:center;"><b>your cart is empty</b></p>';
      }
      ?>
   </div>

   <div style="margin-top: 2rem; text-align:center;">
      <a href="cart-visitor.php?delete_all" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('delete all from cart?');">delete all</a>
   </div>

   <div class="cart-total">
      <p>grand total : <span>₹<?php echo $grand_total; ?>/-</span></p>
      <div class="flex">
         <a href="shop-visitor.php" class="option-btn">continue shopping</a>
         <a href="login.php" class="btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">proceed to checkout</a>
      </div>
   </div>

</section>








<?php include 'footer copy.php'; ?>

<!-- custom js file link  -->
<script src="script.js"></script>
    
</body>
</html>