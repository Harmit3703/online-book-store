<link rel="stylesheet" href="admin_style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message" style="display:flex; justify-content: center">
         <span style="font-size: 1.5rem;">'.$message.'</span>
         <i class="fas fa-times" style:"font-size:1.5rem;" onclick="this.parentElement.remove();"></i>
      </div>';
   }
}?>
<header class="header">

      <div class="flex">
 
       <a href="admin_page.php" class="logo">Admin<span>Panel</span></a>
 
       <nav class="navbar">
          <a href="admin_page.php">Home</a>
          <a href="admin_products.php">Books</a>
          <a href="admin_orders.php">Orders</a>
          <a href="admin_returns.php">Order Return</a>
          <a href="admin_users.php">Customer</a>
          <a href="admin_messages.php">Inquiries</a>
       </nav>
 
       <div class="icons">
          <div id="menu-btn" class="fas fa-bars"></div>
          <div id="user-btn" class="fas fa-user"></div>
       </div>
 
       <div class="account-box">
          <p>username : <span><?php echo $_SESSION['admin_username']; ?></span></p>
          <p>email : <span><?php echo $_SESSION['admin_email']; ?></span></p>
          <a href="admin_logout.php" class="delete-btn">logout</a>
          <div>new <a href="admin_login.php">login</a> | <a href="admin_register.php">register</a></div>
       </div>
 
    </div>
 
 </header>