<?php include 'config.php';
  
  
  session_start();

  $user_id = $_SESSION['user_id'];
  
  if(!isset($user_id))
  {
    header('location:login.php');
  }

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
    <title>Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body class="body">

<?php include 'header.php'; ?>

<section id="home"class="home">
    <div class="content">
        <h3>get books in affordable price.</h3>
        <p>Hand picked books to your doorstep.</p>
        <!--<a href="about.php" class="white-btn">Discover more</a>-->
    </div>
</section>

<section class="product">
    <h1 class="title">Latest Books</h1>
    <div class="box-container">
        <?php
            $select_products = mysqli_query($conn,"SELECT * FROM `book`  LIMIT 6 ") or die("Connection failed");
            if(mysqli_num_rows($select_products) > 0)
            {
                while($fetch_products =mysqli_fetch_assoc($select_products)){
        ?>
        <form action="home.php" method="post" class="box">
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
        ?>
    </div>
    <div class="load-more" style="text-align: center; margin-top: 2rem;">
        <a href="shop.php" class="option-btn">load more</a>
    </div>
</section>

<section class="about">
    <div class="flex">
        <div class="image">
            <img src="partials/images/about-img.jpg" alt="">
        </div>
        <div class="content">
            <h3>about us</h3>
            <p>we deliver used books directly to you with very affordable price </p>
            <!--<a href="about.php" class="btn">read more</a>-->
        </div>
    </div>
</section>

<section class="home-contact">
    <div class="content">
        <h3>have any questions?</h3>
        <p>if you have any questions you can message us we will try our best to respond to your message</p>
        <a href="contact.php" class="white-btn">contact us</a>
    </div>
</section>

<?php include 'footer.php';?>
    <script src="script.js"></script>
</body>
</html>
