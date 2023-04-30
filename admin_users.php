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
      $sql = "SELECT * FROM customer ORDER BY idCustomer ASC";  
      $result = mysqli_query($connect, $sql);  
      while($row = mysqli_fetch_array($result))  
      {       
      $output .= '<tr>  
                          <td>'.$row["idCustomer"].'</td>  
                          <td>'.$row["customer_name"].'</td>  
                          <td>'.$row["address"].'</td>  
                          <td>'.$row["mobile_no"].'</td>  
                          <td>'.$row["username"].'</td> 
                          <td>'.$row["email"].'</td> 
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
      $content .='</h4><h3 align="center">Report of customer</h3><br /><br />  
      <table border="1" cellspacing="0" cellpadding="2">  
           <tr>  
                <th width="5%">ID</th>  
                <th width="18%">Customer Name</th>  
                <th width="20%">address</th>  
                <th width="12%">mobile_no</th>  
                <th width="16%">username</th>
                <th width="29%">email</th>  
           </tr>  
      ';  
      $content .= fetch_data();  
      $content .= '</table>';  
      $obj_pdf->writeHTML($content);  
      $obj_pdf->Output('sample.pdf', 'I');  
 }  
   
  
  

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `customer` WHERE idCustomer = '$delete_id'") or die('query failed');
    header('location:admin_users.php');
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

<section class="users">

   <h1 class="title"> customer accounts </h1>
   <div>
   <form method="post" style=" display: flex; gap:1rem; justify-content:center; margin-bottom:10px;">
            
      <input type="submit" name="create_pdf" class="btn btn-danger" value="get customer report" />
</form>
</div>

   <div class="box-container">
      <?php
         $select_users = mysqli_query($conn, "SELECT * FROM `customer`") or die('query failed');
         while($fetch_users = mysqli_fetch_assoc($select_users)){
      ?>
      <div class="box">
         <p> customer id : <span><?php echo $fetch_users['idCustomer']; ?></span> </p>
         <p> customer name : <span><?php echo $fetch_users['customer_name']; ?></span> </p>
         <p> address : <span><?php echo $fetch_users['address']; ?></span> </p>
         <p> username : <span><?php echo $fetch_users['username']?></span></p>
         <p> email : <span><?php echo $fetch_users['email']?></span></p>
         <p> mobile_no : <span><?php echo $fetch_users['mobile_no'] ?></span> </p>
         <a href="admin_users.php?delete=<?php echo $fetch_users['idCustomer']; ?>" onclick="return confirm('delete this user?');" class="delete-btn">delete user</a>
      </div>
      <?php
         };
      ?>
   </div>

</section>   
<script src="admin_script.js"></script>
</body>
</html>