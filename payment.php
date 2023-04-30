<?php

if(isset($_POST['order_btn']))


{?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body onload="pay_now()">
    <!-- <script src="https://api.razorpay.com"></script> -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script>
    
        function pay_now(){
        
            var name="<?php echo $_POST['name']; ?>";
            var email="<?php echo $_POST['email']; ?>";
            var amt="<?php echo $_POST['amt']; ?>";
            var number="<?php echo $_POST['number']?>";
            var paymentmethod="<?php echo $_POST['paymentmethod']?>";
            var pincode="<?php echo $_POST['pincode']?>";
            var address="<?php echo $_POST['address']?>";
    
            var options = {
                "key": "rzp_test_wxvoYRrQf92gvp", // Enter the Key ID generated from the Dashboard
                "amount": amt*100, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
                "currency": "INR",
                "name": "Nirmal Book Store",
                "description": "payment",   
                "image": "partials/logo.png",
    
                "handler": function (response){
                    console.log(response);
                   $.ajax({
                        'type':'POST',
                        url:'payment_process.php',
                        'data':{'payment_id': response.razorpay_payment_id , 'name' : name , 'email' : email ,'amt' : amt,'number': number,'paymentmethod': paymentmethod,'pincode': pincode,'address':address},
                        success:function(result){
                            console.log(result);
                         window.location.href="orders.php";
                }
            })
        }
    };
            
         
    var rzp1 = new Razorpay(options);
        rzp1.open();

        
        }
    </script>
    </body>
</html>

    
<?php    
}
?>