<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "bookstore2") or die("Connection Failed...");


if(isset($_POST['payment_id']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['amt']) && isset($_POST['number']) && isset($_POST['paymentmethod']) && isset($_POST['pincode']) && isset($_POST['address'])) 
{
    $user_id=$_SESSION['user_id'];
    $payment_id = $_POST['payment_id'];
    $name =  $_POST['name'];
    $number = $_POST['number'];
    $email =  $_POST['email'];
    $method =  $_POST['paymentmethod'];
    $address =  $_POST['address'];
    $placed_on = date('d-M-Y');
    $pincode = $_POST['pincode'];
     
    mysqli_query($conn,"UPDATE `customer` SET customer_name ='$name', address='$address', mobile_no='$number', area_pincode='$pincode' WHERE idCustomer = '$user_id'") or die('failed');

    $cart_total = 0;
    $cart_products[] = '';

    $select_cart = mysqli_query($conn,"SELECT * FROM `cart` WHERE idCustomer = '$user_id'") or die('Failed');
    $cart_items_qty = mysqli_num_rows($select_cart);
    if( $cart_items_qty > 0)
    {
       while($cart_item = mysqli_fetch_assoc($select_cart))
       {
          $cart_products[] = $cart_item['book_name'].' ('.$cart_item['qty'].') ';
          $sub_total = ($cart_item['price'] * $cart_item['qty']);
          $cart_total += $sub_total;
       }
    }
      $total_products = implode(', ',$cart_products);

      $select_order = mysqli_query($conn , "SELECT * FROM `order` WHERE name = '$name' AND number = '$number' AND email = '$email' AND pay_method = '$method' AND address = '$address' AND total_books= '$total_products' AND qty = '$cart_items_qty' AND amt= '$cart_total'") or die('failed');

      if($cart_total == 0)
      {
         echo  "your cart is empty";
      }
      else
      {
         if(mysqli_num_rows($select_order) > 0)
         {
            echo  "order already placed!";
         }
         else
         {
            mysqli_query($conn,"INSERT INTO `order`(order_date, Customer_idCustomer, pay_method, qty, amt, name, email, address, total_books, number,payment_id) VALUES('$placed_on', '$user_id', '$method', '$cart_items_qty','$cart_total','$name','$email','$address','$total_products','$number','$payment_id')") or die('failed');
            echo "order placed successfully!";
            mysqli_query($conn, "DELETE FROM `cart` WHERE idCustomer = '$user_id'") or die('query failed');
            
         }
      }
}
?>