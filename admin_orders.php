<?php 
      include 'config.php';
    session_start();
    $admin_id = $_SESSION['admin_id'];

      if(!isset($admin_id))
      {
         header('location:admin_login.php');
      }
      function fetch_data()  
      {  
           $output = '';  
           $connect = mysqli_connect("localhost", "root", "", "bookstore2");  
           $sql = "SELECT * FROM `order` ORDER BY idorder ASC";  
           $result = mysqli_query($connect, $sql);  
           while($row = mysqli_fetch_array($result))  
           {       
           $output .= '<tr>  
                               <td>'.$row["idorder"].'</td>  
                               <td>'.$row["name"].'</td>  
                               <td>'.$row["address"].'</td>  
                               <td>'.$row["number"].'</td>  
                               <td>'.$row["total_books"].'</td> 
                               <td>'.$row["order_date"].'</td>
                               <td>'.$row["pay_method"].'</td>
                               <td>'.$row["amt"].'</td>
                               <td>'.$row["order_status"].'</td> 
                          </tr>  
                               ';  
           }  
           return $output;  
      }  
      if(isset($_POST["create_pdf"]))  
      {  
           require_once('TCPDF-main/tcpdf.php');  
           $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
           $obj_pdf->SetCreator(PDF_CREATOR);  
           $obj_pdf->SetTitle("Report on all customer");  
           $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
           $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
           $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
           $obj_pdf->SetDefaultMonospacedFont('helvetica');  
           $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
           $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);  
           $obj_pdf->setPrintHeader(false);  
           $obj_pdf->setPrintFooter(false);  
           $obj_pdf->SetAutoPageBreak(TRUE, 10);  
           $obj_pdf->SetFont('helvetica', '', 10);  
           $obj_pdf->AddPage(); 
           $date = date('d-M-Y'); 
           date_default_timezone_set('Asia/Kolkata');
           $time = date('h:i a');
           $content = '';  
           $content .= '  <style>
           table, th, td{
              padding:3px;
           }
           </style>
           <h1 align="center"><img src="upload_img/logo.jpeg" height="35px" width="45px">&nbsp;Nirmal Book Store</h1>
           <h4 style="text-align:right"> Address: Nr. Bhumika Electronics,<br> On Fernandes Bridge,<br> Gandhi Road,<br>Ahmedabad-380001</h4><br>
           <h4 style="text-align:right">Contact :- 7984131647, 7874902219</h4><br>
           <h4 align="left"> Date :';
           $content .= ' '.$date;
           $content .='</h4>
           <h4 align ="left"> Time :';
           $content .= ' '.$time.'<br><hr>';
           $content .='</h4><h3 align="center">Order report</h3><br /><br />  
           <table border="1" cellspacing="0" cellpadding="2">  
                <tr>  
                     <th width="3%">ID</th>  
                     <th width="10%">Customer Name</th>  
                     <th width="18%">address</th>  
                     <th width="12%">mobile_no</th>  
                     <th width="16%">order details</th>
                     <th width="13%">date</th>
                     <th width="10%">payment method</th>
                     <th width="8%">amount</th>
                     <th width="9%">status</th>  
                </tr>  
           ';  
           $content .= fetch_data();  
           $content .= '</table>';  
           $obj_pdf->writeHTML($content);  
           $obj_pdf->Output('sample.pdf', 'I');  
      }
      if (isset($_POST['update_order'])) {

         $order_update_id = $_POST['order_id'];
         $update_status = $_POST['update_status'];
         mysqli_query($conn, "UPDATE `order` SET order_status = '$update_status' WHERE idorder = '$order_update_id'") or die('query failed');
         $message[] = 'order status has been updated!';
   
            $select_status = mysqli_query($conn, "SELECT * FROM `order` WHERE idorder ='$order_update_id'") or die('failed');
             while ($fetch_status = mysqli_fetch_assoc($select_status)) {
            if ( $fetch_status['order_status'] == 'dispatched') {
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
      
                  mysqli_query($conn, "UPDATE `book` SET QOH = QOH - '$book_qty' WHERE book_name = '$book_name'");
      
               }
            }
            
         }
      
      }
      
      if(isset($_GET['delete'])){
         $delete_id = $_GET['delete'];
         mysqli_query($conn, "DELETE FROM `order` WHERE idorder = '$delete_id'") or die('query failed');
         header('location:admin_orders.php');
      }


?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   
   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="admin_style.css">

</head>
<body>
   <?php include 'admin_header.php';?>
<section class="orders">

   <h1 class="title">placed orders</h1>
   <div>
   <form method="post" style=" display: flex; gap:1rem; justify-content:center; margin-bottom:10px;">
            
      <input type="submit" name="create_pdf" class="btn btn-danger" value="get order report" />
</form>
</div>

   <div class="box-container">
      <?php
      $select_orders = mysqli_query($conn, "SELECT * FROM `order` ORDER BY idorder DESC") or die('query failed');
      if(mysqli_num_rows($select_orders) > 0){
         while($fetch_orders = mysqli_fetch_assoc($select_orders)){
      ?>
      <div class="box">
         <p> customer id : <span><?php echo $fetch_orders['Customer_idCustomer']; ?></span> </p>
         <p> order_date : <span><?php echo $fetch_orders['order_date']; ?></span> </p>
         <p> customer name : <span><?php echo $fetch_orders['name']; ?></span> </p>
         <p> number : <span><?php echo $fetch_orders['number']; ?></span> </p>
         <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p>
         <p> address : <span><?php echo $fetch_orders['address']; ?></span> </p>
         <p> total products : <span><?php echo $fetch_orders['total_books']; ?></span> </p>
         <p> total price : <span>₹<?php echo $fetch_orders['amt']; ?>/-</span> </p>
         <p> payment method : <span><?php echo $fetch_orders['pay_method']; ?></span> </p>
         <p> order status : <span><?php echo $fetch_orders['order_status']; ?></span> </p>
         <form action="admin_orders.php" method="post">
            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['idorder']; ?>">
            <div style="display:flex; gap:1rem">
            <select id="select1" style="max-width:300px" name="update_status" <?php 
            if ($fetch_orders['order_status'] == 'delivered' || $fetch_orders['order_status'] == 'returned') {
               echo 'disabled';
            } ?>>
               <option value="" selected disabled>choose status</option>
               <option value="dispatched">dispatched</option>
               <option value="delivered">delivered</option>
            </select>
            <input type="submit" id="submit1" value="update" name="update_order" class="option-btn" <?php if ($fetch_orders['order_status']=='delivered' || $fetch_orders['order_status']=='delivered'){echo "disabled";}?>>
            <a href="admin_orders.php?delete=<?php echo $fetch_orders['idorder']; ?>" <?php if ($fetch_orders['order_status']=='dispatched' || $fetch_orders['order_status']=='delivered'){echo 'onclick="return false;"';}?> onclick="return confirm('delete this order?');" class="delete-btn">delete</a>
            </div>
           
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
      ?>
   </div>
<script src="admin_script.js"></script>
</section>