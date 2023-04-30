<?php

include 'config.php';




session_start();
$admin_id = $_SESSION['admin_id'];

if(isset($_POST["create_book_pdf"]))  
{  
     require_once('TCPDF-main/tcpdf.php');  
     $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
     $obj_pdf->SetCreator(PDF_CREATOR);  
     $obj_pdf->SetTitle("report on book");  
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
       
     $content .= '  
     <h3 align="center">Report on Book</h3><br /><br />  
     <table border="1" cellspacing="0" cellpadding="2">  
          <tr>  
               <th width="5%">ID</th>  
               <th width="40%">Book Name</th> 
               <th width="15%">ISBN</th> 
               <th width="6%">price</th>  
               <th width="16%">Publisher</th>  
               <th width="16%">author</th>
               <th width="6%">stock</th>  
          </tr>  
     ';  
     $content .= fetch_product();  
     $content .= '</table>';  
     $obj_pdf->writeHTML($content);  
     $obj_pdf->Output('book.pdf', 'I');  
} 
function fetch_product()  
{  
     $output = '';  
     $connect = mysqli_connect("localhost", "root", "", "bookstore2");  
     $sql = "SELECT * FROM book ORDER BY idBook ASC";  
     $result = mysqli_query($connect, $sql);  
     while($row = mysqli_fetch_array($result))  
     {       
     $output .= '<tr>  
                         <td>'.$row["idBook"].'</td>  
                         <td>'.$row["book_name"].'</td>
                         <td>'.$row["ISBN"].'</td>  
                         <td>'.$row["new_price"].'</td>  
                         <td>'.$row["publisher_name"].'</td>  
                         <td>'.$row["author_name"].'</td> 
                         <td>'.$row["QOH"].'</td> 
                    </tr>  
                         ';  
     }  
     return $output;  
} 
if(!isset($admin_id))
{
   header('location:admin_login.php');
}

if(isset($_POST['add_product'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = $_POST['price'];
   $subid = $_POST['sub_id'];
   $qoh = $_POST['qoh'];
   $publisher = $_POST['publisher_name'];
   $author = $_POST['author_name'];
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'upload_img/'.$image;

   $select_product_name = mysqli_query($conn, "SELECT book_name FROM `book` WHERE book_name = '$name'") or die('query failed');

   if(mysqli_num_rows($select_product_name) > 0){
      $message[] = 'product name already added';
   }else{
      $add_product_query = mysqli_query($conn, "INSERT INTO `book`(book_name, new_price, book_img, QOH, publisher_name, author_name, subcategory_idsubcategory) VALUES('$name', '$price', '$image','$qoh','$publisher','$author', '$subid')") or die('query failed');

      if($add_product_query){
         if($image_size > 2000000){
            $message[] = 'image size is too large';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'product added successfully!';
         }
      }else{
         $message[] = 'product could not be added!';
      }
   }
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT book_img FROM `book` WHERE idBook = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `book` WHERE idBook = '$delete_id'") or die('query failed');
   header('location:admin_products.php');
}

if(isset($_POST['update_product'])){

   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_price = $_POST['update_price'];
   $update_qoh = $_POST['update_qoh'];
   $update_publisher = $_POST['update_publisher'];
   $update_author = $_POST['update_author'];

   mysqli_query($conn, "UPDATE `book` SET book_name = '$update_name', new_price = '$update_price',QOH = $update_qoh, publisher_name ='$update_publisher', author_name ='$update_author' WHERE idBook = '$update_p_id'") or die('query failed');

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'upload_img/'.$update_image;
   $update_old_image = $_POST['update_old_image'];

   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = 'image file size is too large';
      }else{
         mysqli_query($conn, "UPDATE `book` SET book_img = '$update_image' WHERE idBook = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/'.$update_old_image);
         $message[] = 'updated';
      }
   }

   header('location:admin_products.php');
   $message[] = 'updated';

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<!-- product CRUD section starts  -->

<section class="add-products">

   <h1 class="title">shop products</h1>

   <form action="admin_products.php" method="post" enctype="multipart/form-data">
      <h3>add product</h3>
      <input type="text" name="name" class="box" placeholder="enter product name" required>
      <input type="text" name="publisher_name" class="box" placeholder="enter publisher name" required>
      <input type="text" name="author_name" class="box" placeholder="enter author name" required>
      <input type="number" min="1" name="price" class="box" placeholder="enter product price" required>
      <select name="sub_id" id="" class="box">
         <?php 
               $select_subcat = mysqli_query($conn, "SELECT * FROM `subcategory`");
                  while($fetch_subcat = mysqli_fetch_assoc($select_subcat))
                  {?>
                  <option value="<?php echo $fetch_subcat['idsubcategory']?>" name="" ><?php echo $fetch_subcat['subcategory_name'];?></option>
         <?php    }?>
      </select>
      <input type="number" min="1" name="qoh" class="box" placeholder="enter quantity" required>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <input type="submit" value="add product" name="add_product" class="btn">
   </form>

</section>

<!-- product CRUD section ends -->

<!-- show products  -->
<div>
   <form method="post" style=" display: flex; gap:1rem; justify-content:center; margin-bottom:10px;">
            
      <input type="submit" name="create_book_pdf" class="btn btn-danger" value="get stock report" />
</form>
</div>

<section class="show-products">

   <div class="box-container">

      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `book` ") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <div class="box">
         <img src="upload_img/<?php echo $fetch_products['book_img']; ?>" alt="book image">
         <div class="name"><?php echo $fetch_products['book_name']; ?></div>
         <div class="price">₹<?php echo $fetch_products['new_price']; ?>/-</div>
         <div class="price">Quantity : <?php echo $fetch_products['QOH'] ?></div>
         <a href="admin_products.php?update=<?php echo $fetch_products['idBook']; ?>" class="option-btn">update</a>
         <a href="admin_products.php?delete=<?php echo $fetch_products['idBook']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>

</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){
          $update_id = $_GET['update'];
          $update_query = mysqli_query($conn, "SELECT * FROM `book` WHERE idBook = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="admin_products.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['idBook']; ?>">
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['book_img']; ?>">
      <img src="upload_img/<?php echo $fetch_update['book_img']; ?>" alt="book image">
      <input type="text" name="update_name" value="<?php echo $fetch_update['book_name']; ?>" class="box" required placeholder="enter product name">
      <input type="text" name="update_publisher" value="<?php echo $fetch_update['publisher_name']?>" class="box" placeholder="enter publisher name" required>
      <input type="text" name="update_author" value="<?php echo $fetch_update['author_name']?>" class="box" placeholder="enter author name" required>
      <input type="number" name="update_price" value="<?php echo $fetch_update['new_price']; ?>" min="1" class="box" required placeholder="enter product price">
      <input type="number" name="update_qoh" value="<?php echo $fetch_update['QOH']; ?>" min="0" class="box" required placeholder="enter product quantity">
      <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="update" name="update_product" class="btn">
      <input type="reset" value="cancel" id="close-update" class="option-btn">
   </form>
   <?php
         }
      }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>

</section>







<!-- custom admin js file link  -->
<script src="admin_script.js"></script>

</body>
</html>