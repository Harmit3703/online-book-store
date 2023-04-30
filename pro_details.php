<?php 
include 'config.php';

session_start();

  $user_id = $_SESSION['user_id'];
  
  if(!isset($user_id))
  {
    header('location:login.php');
  }

  $product_name = "";
  $product_price = "";
  $product_image = "";
  $product_quantity ="";

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

<link rel="stylesheet" href="style.css">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book details</title>
      <!-- custom css file link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
   <link rel="stylesheet" href="style.css">

</head>
<body class="body">
<?php include 'header.php';?>
<div class="heading">
   <h3>Book details</h3>
   <p> <a href="home.php">Home</a> / Book details </p>
</div>
<?php
    if(isset($_GET['id']))
    {
        $idbook = $_GET['id'];
        ?>
        <section class="product-details">
            
            <div class="box-container">
                <?php
                    $select_product = mysqli_query($conn,"SELECT * FROM `book` WHERE idBook = '$idbook'") or die("Connection failed");
                    $fetch_products = mysqli_fetch_assoc($select_product);
                ?>
                    <form action="" method="post" class="box">
                        <div class="product-img">
                            <img src="upload_img/<?php echo $fetch_products['book_img']; ?>" alt="book image"></div>
                        </div>
                        <div class="product-info">
                            <h1 style="display : inline-block; font-size : 6rem; color : #8e44ad; text-transform : capitalize;" ><span><?php echo $fetch_products['book_name']; ?></span></h1><br>
                            <div>Author : <span ><?php echo $fetch_products['author_name']; ?></span></div>
                            <div>Publisher : <span ><?php echo $fetch_products['publisher_name']; ?></span></div>
                            <div>ISBN : <span ><?php echo $fetch_products['ISBN']; ?></span></div>
                            <div>Price : <span style="color : #c0392b">₹<?php echo $fetch_products['new_price']; ?>/-</span></div>
                            <input type="hidden" name="product_name" value="<?php echo $fetch_products['book_name']; ?>">
                            <input type="hidden" name="product_price" value="<?php echo $fetch_products['new_price']; ?>">
                            <input type="hidden" name="product_image" value="<?php echo $fetch_products['book_img']; ?>">
                            <input type="number" min="1" max="5" name="product_quantity" value="1" class="qty" style="width: 20rem; border: .1rem solid #333; padding: 1.2rem 1.4rem; border-radius: .5rem; margin: 1rem 0; font-size: 2rem;"><br>
                            <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                        </div>
                </form>
            </div>
<?php }?>
        </section>










<?php include 'footer.php';?>



<script src="script.js">

</body>
</html>