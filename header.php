
<link rel="stylesheet" href="style.css">
<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message" style="display:flex; justify-content: center">
         <span style="font-size: 1.5rem;">'.$message.'</span>
         <i class="fas fa-times" style:"font-size:1.5rem;" onclick="this.parentElement.remove();"></i>
      </div>';
   }
}
    
    include 'config.php';
   
    
?>
<header class="header">
    <div class="header-2">

        <div class="flex">
            <!--<img src="partials/logo.png" alt="">-->
            <a href="home.php" class="logo"><b>Books4U</b></a>
            <nav class="navbar">
                <a href="home.php">Home</a>
                <a href="category.php">Category</a>
                <a href="shop.php">Shop</a>
                <a href="orders.php">Orders</a>
                <a href="contact.php">Contact us</a>
            </nav>
            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
                <a href="search.php" class="fas fa-search"></a>
                <div id="user-btn" class="fas fa-user"></div>
                <?php
                    $user_id = $_SESSION['user_id'];
                    $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE idCustomer = '$user_id'") or die('query failed');
                    $cart_rows_number = mysqli_num_rows($select_cart_number); 
                ?>
                <a href="cart.php"><i class="fas fa-shopping-cart"><span>(<?php echo $cart_rows_number;?>)</span></i></a>
            </div>
            <div class="user-box">
                <p>username: <span><?php echo $_SESSION['username'];?></span></p>
                <p>email: <span><?php echo $_SESSION['email'];?></span></p>
                <a href="logout.php" class="delete-btn">logout</a>
                <a href="edit_profile.php" class="delete-btn">edit profile</a>
            </div>
        </div>
    </div>

<script src="script.js"></script>

</header>