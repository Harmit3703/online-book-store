<?php include 'config.php';

   session_start();

$user_id = $_SESSION['user_id'];
$return= "";
if(isset($_GET['return'])){
   $return= $_GET['return'];
   $date = date("d-M-Y");
   $seven_days_ago = date("d-M-Y", strtotime("-7 days"));

   $select_status = mysqli_query($conn,"SELECT * FROM `order` WHERE idorder ='$return'");
   $fetch_status = mysqli_fetch_assoc($select_status);
   if($fetch_status['order_status']=='delivered')
   {
      if($fetch_status['order_date'] < $seven_days_ago)
      {
         $message[] = "Sorry, the order date is more than 7 days ago. Return request rejected.";
      }
      else {
         mysqli_query($conn,"UPDATE `order` SET order_status ='returned' WHERE idorder='$return'") or die('failed');
         mysqli_query($conn,"INSERT INTO `order_return`(return_date, order_idorder) VALUES('$date', '$return')") or die("failed");
         $message[] = "Return request accepted.";
      }
   }

   elseif ( $fetch_status['order_status'] == 'returned') {
      $total_books = $fetch_status["total_books"];
      $total_books_array = explode(",", $total_books);

      foreach ($total_books_array as $book) {
         $book_array = explode("(", $book);
         if (count($book_array) >= 2) {
            $book_name = trim($book_array[0]);
            $book_qty = trim(str_replace(")", "", $book_array[1]));
         } else {
            
            continue;
         }

         mysqli_query($conn, "UPDATE `book` SET QOH = QOH + '$book_qty' WHERE book_name = '$book_name'");

      }
   }
   else{
      $message[] = "Cannot return order before delivered..!";
   }
   
}

   

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $select_status = mysqli_query($conn,"SELECT * FROM `order`WHERE idorder = '$delete_id'");
   $fetch_status = mysqli_fetch_assoc($select_status);
   if($fetch_status['order_status']=='pending')
   {
      mysqli_query($conn, "DELETE FROM `order` WHERE idorder = '$delete_id'") or die('query failed');
      header('location:orders.php');
   }
   else
   {
      $message[] = "Cannot delete order once dispatched or delivered...!";
   }
   
}


if(isset($_GET['invoice']))  
{  
  $order_id = $_GET['invoice'];
  require_once('TCPDF-main/tcpdf.php');  
  $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
  $obj_pdf->SetCreator(PDF_CREATOR);  
  $obj_pdf->SetTitle("Invoice");  
  $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
  $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
  $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
  $obj_pdf->SetDefaultMonospacedFont('helvetica');  
  $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
  $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);  
  $obj_pdf->setPrintHeader(false);  
  $obj_pdf->setPrintFooter(false);  
  $obj_pdf->SetAutoPageBreak(TRUE, 20);  
  $obj_pdf->SetFont('helvetica', '', 10); 
  $obj_pdf->AddPage();  
  $date = date('d-M-Y'); 
  date_default_timezone_set('Asia/Kolkata');
  $time = date('h:i a');
  $content = '';
  $content .='<head><style>

  table, th, td{
     border: 1px solid black;
     padding: 10px;
  }
  </style></head>
  <body>
  <div>
  <h1 align="center"><img src="upload_img/logo.jpeg" height="35px" width="45px">&nbsp;Nirmal Book Store</h1>
    <h4 style="text-align:right"> Date: '.$date.'</h4>
    <h4 style="text-align:right"> Address: Nr. Bhumika Electronics,<br> On Fernandes Bridge,<br> Gandhi Road,<br>Ahmedabad-380001</h4>
    <h4 style="text-align:right">Contact :- 7984131647, 7874902219</h4><br><hr>
    <div style="font-size:12px">';
      $res = mysqli_query($conn,"select * from customer where idCustomer = '$user_id'");
      if(mysqli_num_rows($res) >0){
        $content .='<address>';
        while($row = mysqli_fetch_assoc($res)){
         $content .='<br>'.'<h4>Billed To,</h4>'.'<br>';
          $content .= '<h4>Name : </h4>' . $row['customer_name'] .',' .'<br>'.'<h4>Address : </h4>' .$row['address'].'.<br>';
        }
        $content .='</address>';
      }
      $content .='<h4>';
        $res = mysqli_query($conn,"SELECT * FROM `order` WHERE idorder = '$order_id'");
        while ($fetch_status = mysqli_fetch_assoc($res)) {
         $pay_method = $fetch_status['pay_method'];
         $pay_id = $fetch_status['payment_id'];
      }
      $content .='payment method :</h4>'.$pay_method.'<br>';
      $content .='<h4> payment id :</h4>'.$pay_id.'<br><hr>';
    $content .='</div>
  </div>';
  $content .='<table>
    <tr>
      <th>Item name</th>
      <th>Quantity</th>
      <th>Price</th>
    </tr>';
    $total = 0;
  $res = mysqli_query($conn,"SELECT * FROM `order` WHERE idorder = '$order_id'");
  while ($fetch_status = mysqli_fetch_assoc($res)) {
    $total_books = $fetch_status["total_books"];
    $total_books_array = explode(",", $total_books);
    foreach ($total_books_array as $book) {
      $book_array = explode("(", $book);
      if (count($book_array) >= 2) {
        $book_name = trim($book_array[0]);
        $book_qty = trim(str_replace(")", "", $book_array[1]));

        $content .= '<tr>';
        $content .= '<td>'.$book_name.'</td>';
        $content .= '<td>'.$book_qty.'</td>';
        
        $res = mysqli_query($conn,"SELECT * FROM `book` WHERE book_name LIKE '$book_name'");
         while($fetch = mysqli_fetch_assoc($res)){
            $price_raw = $fetch['new_price'];
            $price = $price_raw * $book_qty;
            $total = $total + $price;
         }
        $content .= '<td>'.'Rs.'.$price.'/-'.' ('.$price_raw.'*'.$book_qty.')'.'</td>'; // replace "price" with the actual price of the book
        $content .= '</tr>';
      } 
      
    }
  }
  $content .='<tr>';
      $content .='<td colspan="2">'.'Total '.'</td>';
      $content .='<td>'.'Rs.'.$total.'/-'.'</td>';
      $content .='</tr>';

  $content .='</table><br>
  <h2> Thanks for shopping with us!</h2>
  </body>';

  $obj_pdf->writeHTML($content);  
  $obj_pdf->Output('invoice.pdf', 'I');  
 }  
 



?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>your orders</h3>
   <p> <a href="home.php">Home</a> / Orders </p>
</div>

<section class="placed-orders">

   <h1 class="title">placed orders</h1>

   <div class="box-container">

      <?php
        $order_query = mysqli_query($conn, "SELECT * FROM `order` WHERE Customer_idCustomer = '$user_id' ORDER BY idorder DESC") or die('query failed');

         if(mysqli_num_rows($order_query) > 0){
            while($fetch_orders = mysqli_fetch_assoc($order_query)){
      ?>
      <div class="box">
         <p> placed on : <span><?php echo $fetch_orders['order_date']; ?></span> </p>
         <p> name : <span><?php echo $fetch_orders['name']; ?></span> </p>
         <p> number : <span><?php echo $fetch_orders['number'];?></span> </p>
         <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p>
         <p> address : <span><?php echo $fetch_orders['address'];?></span> </p>
         <p> payment method : <span><?php echo $fetch_orders['pay_method']; ?></span> </p>
         <p> your orders : <span><?php echo $fetch_orders['total_books']; ?></span> </p>
         <p> total price : <span>₹<?php echo $fetch_orders['amt'];?>/-</span> </p>
         <p>order status : <span><?php echo $fetch_orders['order_status']?></span></p>
         <a href="orders.php?delete=<?php echo $fetch_orders['idorder']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
         <a href="orders.php?return=<?php echo $fetch_orders['idorder']; ?>" class="delete-btn" onclick="return confirm('return this order?');">return</a>
        
           
            
            <a href="orders.php?invoice=<?php echo $fetch_orders['idorder']; ?>" name="create_pdf" value="get invoice" class="btn btn-danger">get invoice</a>
          <!--<input type="submit" name="create_pdf" class="btn btn-danger" value="get invoice" />-->
        
         
         

      <?php
       }
      }else{
         echo '<p class="empty" style="font-size:2rem; text-align:center;">no orders placed yet!</p>';
      }
      ?>
      </div>
   </div>

</section>








<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="script.js"></script>

</body>
</html>