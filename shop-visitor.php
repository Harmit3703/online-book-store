<?php  include 'config.php';

    session_start();

    $sessionId = session_id();
    if (isset($_POST['add_to_cart'])) {
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_image = $_POST['product_image'];
        $product_quantity = $_POST['product_quantity'];
        $is_valid_quantity = true; // flag variable to track quantity validation
    
        if ($product_quantity > 5) {
            $message[] = 'Maximum quantity exceeded (5).';
            $is_valid_quantity = false;
        }
    
        $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `visitor_cart` WHERE book_name = '$product_name' AND session_id = '$sessionId'") or die('query failed');
    
        if (mysqli_num_rows($check_cart_numbers) > 0) {
            // The product is already in the cart, so update the quantity
            $cart_item = mysqli_fetch_assoc($check_cart_numbers);
            $new_qty = $cart_item['qty'] + $product_quantity;
            if ($new_qty > 5) {
                $message[] = 'Maximum quantity exceeded (5).';
                $is_valid_quantity = false;
            }
            if ($is_valid_quantity) {
                mysqli_query($conn, "UPDATE `visitor_cart` SET qty = '$new_qty' WHERE book_name = '$product_name' AND session_id = '$sessionId'") or die('query failed');
                $message[] = 'Quantity updated in cart!';
            }
        } else {
            // The product is not in the cart, so add it
            if ($product_quantity > 5) {
                $message[] = 'Maximum quantity exceeded (5).';
                $is_valid_quantity = false;
            }
            if ($is_valid_quantity) {
                mysqli_query($conn, "INSERT INTO `visitor_cart` (session_id, book_name, price, qty, book_img) VALUES ('$sessionId', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
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
    <title>Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header copy.php';?>
<div class="heading">
        <h3>our shop</h3>
        <p><a href="home-visitor.php">home</a> / shop </p>
    </div>
<section class="product">
    <h1 class="title">latest products</h1>
    <div class="box-container">
        <?php
            $select_products = mysqli_query($conn,"SELECT * FROM `book`") or die("Connection failed");
            if(mysqli_num_rows($select_products) > 0)
            {
                while($fetch_products =mysqli_fetch_assoc($select_products)){
        ?>
        <form action="shop-visitor.php" method="post" class="box">
            <div><a href="pro_details-visitor.php?id=<?php echo $fetch_products['idBook'];?>">
            <img src="upload_img/<?php echo $fetch_products['book_img']; ?>" alt="book image"></a></div>
            <div class="name"><?php echo $fetch_products['book_name']?></div>
            <div class="price">₹<?php echo $fetch_products['new_price']?>/-</div>
            <input type="hidden" name="product_id" value="<?php echo $fetch_products['idBook']; ?>">
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
</section>
<?php include 'footer.php';?>
    
</body>
</html>
