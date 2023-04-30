<?php
   include 'config.php';
   session_start();
   $admin_id = $_SESSION['admin_id'];

   function fetch_data()  
      {  
           $output = '';  
           $connect = mysqli_connect("localhost", "root", "", "bookstore2");  
           $sql = "SELECT * FROM `order` where order_status='returned'";  
           $result = mysqli_query($connect, $sql);  
           while($row = mysqli_fetch_array($result))  
           {       
           $output .= '<tr>  
                               <td>'.$row["idorder"].'</td>  
                               <td>'.$row["name"].'</td>  
                               <td>'.$row["address"].'</td>  
                               <td>'.$row["number"].'</td>  
                               <td>'.$row["total_books"].'</td> 
                               <td>'.$row["pay_method"].'</td>
                               <td>'.$row["amt"].'</td> 
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
           $content .='</h4><h3 align="center">Order return report</h3><br /><br />  
           <table border="1" cellspacing="0" cellpadding="2">  
                <tr>  
                     <th width="5%">ID</th>  
                     <th width="18%">Customer Name</th>  
                     <th width="20%">address</th>  
                     <th width="12%">mobile_no</th>  
                     <th width="16%">order details</th>
                     <th width="18%">payment method</th> 
                     <th width="8%">amount</th>  
                </tr>  
           ';  
           $content .= fetch_data();  
           $content .= '</table>';  
           $obj_pdf->writeHTML($content);  
           $obj_pdf->Output('sample.pdf', 'I');  
      }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>returns page</title>
</head>
<body>
    <link rel="stylesheet" href="admin_style.css">
    <?php include 'admin_header.php';

if(!isset($admin_id))
{
   header('location:admin_login.php');
}
    ?>
<section class="orders">

<h1 class="title">returned orders</h1>
<div>
   <form method="post" style=" display: flex; gap:1rem; justify-content:center; margin-bottom:10px;">
            
      <input type="submit" name="create_pdf" class="btn btn-danger" value="order return report" />
</form>
</div>

<div class="box-container">

   <?php
      $order_query = mysqli_query($conn, "SELECT * FROM `order` where order_status='returned'") or die('query failed');
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
      </div>
   <?php
    }
   }else{
      echo '<p class="empty" style="font-size:2rem; text-align:center;">no orders returned yet!</p>';
   }
   ?>
</div>

</section>
<script src="admin_script.js"></script>
</body>
</html>