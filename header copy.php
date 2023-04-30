
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
                <a href="home-visitor.php">Home</a>
                <a href="category-visitor.php">Category</a>
                <a href="shop-visitor.php">Shop</a>
                <a href="login.php">Orders</a>
                <a href="contact-visitor.php">Contact us</a>
            </nav>
            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
                <a href="search-visitor.php" class="fas fa-search"></a>
                <div id="user-btn" class="fas fa-user"></div>
                <?php
                    $sessionId = session_id();
                    $select_cart_number = mysqli_query($conn, "SELECT * FROM `visitor_cart` WHERE session_id = '$sessionId'") or die('query failed');
                    $cart_rows_number = mysqli_num_rows($select_cart_number); 
                ?>
                <a href="cart-visitor.php"><i class="fas fa-shopping-cart"><span>(<?php echo $cart_rows_number;?>)</span></i></a>
            </div>
            <div class="user-box">
                <a href="login.php" class="delete-btn">login</a>
                <a href="register.php" class="delete-btn">register</a>
            </div>
        </div>
    </div>

<script src="script.js"></script>

</header>