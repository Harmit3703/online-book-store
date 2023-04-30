<?php include 'config.php';

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
    <title>category</title>


    <!-- custom css file link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
   <link rel="stylesheet" href="style.css">

</head>
<body>

<?php include 'header copy.php';?>

<div class="heading">
   <h3>categories</h3>
   <p> <a href="home-visitor.php">Home</a> / Categories</p>
</div>

<?php
    if(isset($_GET['sub_cat']))
    {
        $sub_cat_id = $_GET['sub_cat'];
        ?>
            <section class="product">
            <h1 class="title">latest products</h1>
            <div class="box-container">
                <?php
                    $select_products = mysqli_query($conn,"SELECT * FROM `book` WHERE subcategory_idsubcategory = '$sub_cat_id'") or die("Connection failed");
                    if(mysqli_num_rows($select_products) > 0)
                    {
                        while($fetch_products =mysqli_fetch_assoc($select_products))
                        {
                ?>
                <form action="" method="post" class="box">
                    <div><a href="pro_details-visitor.php?id=<?php echo $fetch_products['idBook'];?>">
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
                    else
                    {
                        echo '<p class="empty">no products added yet</p>';
                    }
                ?>
            </div>
        </section>
<?php  }?>
<section class="footer1">
    <div class="box-container1">
        <div class="box1">
            <h3><?php  $cat_name = mysqli_query($conn,"SELECT * FROM `category` WHERE idCategory=5") or die('Failed');
            $row1 = mysqli_fetch_assoc($cat_name);
            echo $row1['category_name'];
            ?></h3>
            <?php 
                $select_sub = mysqli_query($conn,"SELECT * FROM `subcategory` WHERE Category_idCategory = 5");
                while($fetch_sub = mysqli_fetch_assoc($select_sub)){?>
                <a href="category-visitor.php?sub_cat=<?php echo $fetch_sub['idsubcategory'] ?>"><?php echo $fetch_sub['subcategory_name'];?></a>
           <?php }?>
        </div>

        <div class="box1">
            <h3><?php  $cat_name = mysqli_query($conn,"SELECT * FROM `category` WHERE idCategory=2") or die('Failed');
            $row1 = mysqli_fetch_assoc($cat_name);
            echo $row1['category_name'];
            ?></h3>
            <?php 
                $select_sub = mysqli_query($conn,"SELECT * FROM `subcategory` WHERE Category_idCategory = 2");
                while($fetch_sub = mysqli_fetch_assoc($select_sub)){?>
                <a href="category-visitor.php?sub_cat=<?php echo $fetch_sub['idsubcategory'] ?>"><?php echo $fetch_sub['subcategory_name'];?></a>
           <?php }?>
        </div>

        <div class="box1">
            <h3><?php  $cat_name = mysqli_query($conn,"SELECT * FROM `category` WHERE idCategory=3") or die('Failed');
            $row1 = mysqli_fetch_assoc($cat_name);
            echo $row1['category_name'];
            ?></h3>
            <?php 
                $select_sub = mysqli_query($conn,"SELECT * FROM `subcategory` WHERE Category_idCategory = 3");
                while($fetch_sub = mysqli_fetch_assoc($select_sub)){?>
                <a href="category-visitor.php?sub_cat=<?php echo $fetch_sub['idsubcategory'] ?>"><?php echo $fetch_sub['subcategory_name'];?></a>
           <?php }?>
        </div>

        <div class="box1">
            <h3><?php  $cat_name = mysqli_query($conn,"SELECT * FROM `category` WHERE idCategory=4") or die('Failed');
            $row1 = mysqli_fetch_assoc($cat_name);
            echo $row1['category_name'];
            ?></h3>
            <?php 
                $select_sub = mysqli_query($conn,"SELECT * FROM `subcategory` WHERE Category_idCategory = 4");
                while($fetch_sub = mysqli_fetch_assoc($select_sub)){?>
                <a href="category-visitor.php?sub_cat=<?php echo $fetch_sub['idsubcategory'] ?>"><?php echo $fetch_sub['subcategory_name'];?></a>
           <?php }?>
        </div>
    </div>
</section>




<!-- custom js file link  -->
<script src="script.js"></script>
    <?php include 'footer copy.php';?>
</body>
</html>