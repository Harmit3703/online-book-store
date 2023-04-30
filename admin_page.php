<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id))
{
   header('location:admin_login.php');
}

  
   
 ?>  



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin panel</title>

   
   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="dashboard">

   <h1 class="title">dashboard</h1>

   <div class="box-container">

      
      <div class="box">
         <?php
            $total_delivered = 0;
            $select_delivered = mysqli_query($conn, "SELECT amt FROM `order` WHERE order_status = 'delivered'") or die('query failed');
            if(mysqli_num_rows($select_delivered) > 0){
               while($fetch_delivered = mysqli_fetch_assoc($select_delivered)){
                  $total_price = $fetch_delivered['amt'];
                  $total_delivered += $total_price;
               };
            };
         ?>
         <h3>₹<?php echo $total_delivered; ?>/-</h3>
         <p>Completed Payments</p>
      </div>

      

      <div class="box">
         <?php 
            $select_products = mysqli_query($conn, "SELECT * FROM `book`") or die('query failed');
            $number_of_products = mysqli_num_rows($select_products);
         ?>
         <h3><?php echo $number_of_products; ?></h3>
         <p>Books Added</p>
      </div>
      <div class="box">
         <?php 
           $select_orders = mysqli_query($conn, "SELECT * FROM `order`") or die('query failed');
            $number_of_orders = mysqli_num_rows($select_orders);
         ?>
         <h3><?php echo $number_of_orders; ?></h3>
         <p>Order Placed</p>
      </div>
      <div class="box">
         <?php 
            $select_account = mysqli_query($conn, "SELECT * FROM `order_return`") or die('query failed');
            $number_of_account = mysqli_num_rows($select_account);
         ?>
         <h3><?php echo $number_of_account; ?></h3>
         <p>Order Return</p>
      </div>
       
      <div class="box">
         <?php 
            $select_messages = mysqli_query($conn, "SELECT * FROM `order` WHERE order_status ='pending'") or die('query failed');
            $number_of_messages = mysqli_num_rows($select_messages);
         ?>
         <h3><?php echo $number_of_messages; ?></h3>
         <p>Pending Orders</p>
      </div>


      <div class="box">
         <?php 
            $select_users = mysqli_query($conn, "SELECT * FROM `customer`") or die('query failed');
            $number_of_users = mysqli_num_rows($select_users);
         ?>
         <h3><?php echo $number_of_users; ?></h3>
         <p>Customers</p>
      </div>

      <div class="box">
         <?php 
            $select_admins = mysqli_query($conn, "SELECT * FROM `admin`") or die('query failed');
            $number_of_admins = mysqli_num_rows($select_admins);
         ?>
         <h3><?php echo $number_of_admins; ?></h3>
         <p>Admins</p>
      </div>

     

      <div class="box">
         <?php 
            $select_messages = mysqli_query($conn, "SELECT * FROM `feedback`") or die('query failed');
            $number_of_messages = mysqli_num_rows($select_messages);
         ?>
         <h3><?php echo $number_of_messages; ?></h3>
         <p> Inquiries</p>
      </div>
     

   </div>
   <script src="admin_script.js"></script>

</section>


</form>
</body>
</html>