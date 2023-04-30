<?php 

    include 'config.php';

    session_start();
    $user_id = $_SESSION['user_id'];
    
    if (isset($_POST['add_to_cart'])) {
      $product_name = $_POST['product_name'];
      $product_price = $_POST['product_price'];
      $product_image = $_POST['product_image'];
      $product_quantity = $_POST['product_quantity'];
  
      $max_qty_exceeded = false;
  
      // Validate maximum quantity
      if ($product_quantity > 5) {
          $message[] = 'Maximum quantity exceeded (5).';
          $max_qty_exceeded = true;
      }
  
      $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE book_name = '$product_name' AND idCustomer = '$user_id'") or die('query failed');
  
      if (mysqli_num_rows($check_cart_numbers) > 0) {
          // The product is already in the cart, so update the quantity
          $cart_item = mysqli_fetch_assoc($check_cart_numbers);
          $new_qty = $cart_item['qty'] + $product_quantity;
  
          // Validate maximum quantity for updated quantity
          if ($new_qty > 5) {
              $message[] = 'Maximum quantity exceeded (5).';
              $max_qty_exceeded = true;
          }
  
          if (!$max_qty_exceeded) {
              mysqli_query($conn, "UPDATE `cart` SET qty = '$new_qty' WHERE book_name = '$product_name' AND idCustomer = '$user_id'") or die('query failed');
              $message[] = 'Quantity updated in cart!';
          }
      } else {
          if (!$max_qty_exceeded) {
              // The product is not in the cart, so add it
              mysqli_query($conn, "INSERT INTO `cart` (idCustomer, book_name, price, qty, book_img) VALUES ('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
              $message[] = 'Product added to cart!';
          }
      }
  }
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>search</title>


    <!-- custom css file link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link rel="stylesheet" href="style.css">

</head>
<body>

<?php include 'header.php';?>
<div class="heading">
   <h3>search page</h3>
   <p> <a href="home.php">Home</a> / Search </p>
</div>

<section class="search-form">
   <form action="" method="post">
      <input type="text" name="search" placeholder="Search Books..." class="box" autofocus>
      <input type="submit" name="submit" value="search" class="btn">
   </form>
</section>

<section class="product" style="padding-top: 0;">

   <div class="box-container">
           
        <?php
                if(isset($_POST['submit'])){
                    $search_item = $_POST['search'];
                    $select_products = mysqli_query($conn, "SELECT * FROM `book` WHERE book_name LIKE '%{$search_item}%' OR publisher_name LIKE '%{$search_item}%' OR author_name LIKE '%{$search_item}%' OR ISBN LIKE '%{$search_item}%' ") or die('query failed');
                    if(mysqli_num_rows($select_products) > 0){
                    while($fetch_products = mysqli_fetch_assoc($select_products)){
        ?>
                    <form action="search.php" method="post" class="box">
                    <div><a href="pro_details.php?id=<?php echo $fetch_products['idBook'];?>">
                     <img src="upload_img/<?php echo $fetch_products['book_img']; ?>" alt="book image"></a></div>
                     <div class="name"><?php echo $fetch_products['book_name']?></div>
                     <div class="price">₹<?php echo $fetch_products['new_price']?>/-</div>
                     <input type="hidden" name="product_name" value="<?php echo $fetch_products['book_name']; ?>">
                     <input type="hidden" name="product_price" value="<?php echo $fetch_products['new_price']; ?>">
                     <input type="hidden" name="product_image" value="<?php echo $fetch_products['book_img']; ?>">
                     <input type="number" min="1" max="5" name="product_quantity" value="1" class="qty">
                     
                     <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                    </form>
        <?php
                    }
                    }else{
                        echo '<p class="empty" style="font-size: 20px; text-align:center; margin-bottom: 30px; color: #333;"><b>no result found!</b></p>';
                    }
                }
                else
                {
                  
                     $select_products = mysqli_query($conn,"SELECT * FROM `book`") or die("Connection failed");
                     if(mysqli_num_rows($select_products) > 0)
                     {
                        while($fetch_products =mysqli_fetch_assoc($select_products)){
               ?>
               <form action="shop.php" method="post" class="box">
                     <div><a href="pro_details.php?id=<?php echo $fetch_products['idBook'];?>">
                     <img src="upload_img/<?php echo $fetch_products['book_img']; ?>" alt="book image"></a></div>
                     <div class="name"><?php echo $fetch_products['book_name']?></div>
                     <div class="price">₹<?php echo $fetch_products['new_price']?>/-</div>
                     <input type="hidden" name="product_name" value="<?php echo $fetch_products['book_name']; ?>">
                     <input type="hidden" name="product_price" value="<?php echo $fetch_products['new_price']; ?>">
                     <input type="hidden" name="product_image" value="<?php echo $fetch_products['book_img']; ?>">
                     <input type="number" min="1" max="5" name="product_quantity" value="1" class="qty">
                     <input type="submit" value="add to cart" name="add_to_cart" class="btn">
               </form>
               <?php
                     }
                     }
                     else{
                        echo '<p class="empty">no products added yet</p>';
                     }
                    
               }
      ?>
   </div>
  

</section>









<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="script.js"></script>
    
</body>
</html>