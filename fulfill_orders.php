<html>
 <head>
  <title>Fulfill Orders</title>
  <style>
   body
   {
     font-family: Arial, sans-serif;
     margin: 0;
     padding: 0;
     background-color: #fff;
   }

   header
   {
     background-color:#848B79;
     color: #000000;
     text-align: center;
     padding: 1em 0;
   }

   header a
   {
     color: #fff;
     text-decoration: none;
     margin: 0 15px;
   }

   .navbar
   {
     background-color:#848B79;
     overflow: hidden;
   }

   .navbar a
   {
     float: left;
     display: block;
     color: white;
     text-align: center;
     padding: 14px 16px;
     text-decoration: none;
   }

   .navbar c
   {
     float: right;
     display: block;
     color: white;
     text-align: center;
     text-decoration: none;
   }

   .navbar a:hover
   {
     background-color: #848B79;
     color: black;
   }

   .navbar .icon
   {
     display: none;
   }

   @media screen and (max-width: 600px)
   {
     .navbar a:not(:first-child)
     {
       display: none;
     }

     .navbar a.icon
     {
       float: right;
       display: block;
     }
   }

   @media screen and (max-width: 600px)
   {
     .navbar.responsive
     {
       position: relative;
     }

     .navbar.responsive .icon
     {
       position: absolute;
       right: 0;
       top: 0;
     }

     .navbar.responsive a
     {
       float: none;
       display: block;
       text-align: left;
     }
   }
    table {
 	   border-collapse: collapse;
	   padding: 15px;
	   text-align: center;
	  }
    .content{
	max-width: auto;
   	margin: auto;
    form{
  	text-align: center;
      }
    .center {
  	margin-left: auto;
    	margin-right: auto;
     } 
    h1 {text-align: center;}
    h2 {text-align: center;}

    .my-button {
  	display: block;
  	margin: auto;
       }
   }
  </style>
<style media="print">
  .noPrint{ display: none; }
  .yPrint{ display: block !important; }
  
  @media only print {table}

</style>
<!--         	start of body 			--> 
 </head>
  <body>
<div class="content"
   <header>
     <div class="navbar" id="navbar">
      <a href='fulfill_orders.php'>Fulfill Orders</a>
      <a href='inventory.php'>Update Inventory</a>
      <c><a href='EAlogin.php'>Logout</a></c>
      <a href="javascript:void(0);" class="icon" onclick="myFunction()"> &#9776; </a>
     </div>
   </header>

   <?php

    include("secrets.php"); //this is another php, that has a $username, $password, and $dbname to connect to the db
                                                            //zid,       bday,          zid

    try{
	      $dsn1 = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
      	      $dsn2 = "mysql:host=courses;dbname=$dbname";
	      $pdo1 = new PDO($dsn1, "student", "student");
	      $pdo2 = new PDO($dsn2, $username, $password);
    ?>


<!-- 			fulfill Order Completion at Warehouse		-->

    <!-- FORM TO MARK SUCESSFUL ORDER COMPLETION -->
    <h1 style="background-color:#DAF7A6;"> Confirm Order Fulfillment</h1>
        <form method = "GET" action = "<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for = "ordernum"> Order Number: </label><br>
        <input type="text" id="ordernum" name="ordernum"><br>
	<input type = "submit" value = "GET PACKAGE LIST" name = "packingsubmit">
	<input type = "submit" value = "MARK COMPLETE" name = "statussubmit">
        </form>

<!--                    PHP code to run when MARK COMPLETE button clicked           -->
<?php	 //PHP code to run order status form
         if(isset($_GET["statussubmit"]))
         {
           $Onumber = $_GET["ordernum"];

           // sql query to check if order_number is in table 
           $check_order = $pdo2->prepare("SELECT order_num, order_status FROM POrders WHERE order_num = :order_number");
           $check_order->execute([':order_number' => $Onumber]);
           $check_order_status = $check_order->fetch(PDO::FETCH_ASSOC); 

           // if order_num is found in table 
          if ($check_order->rowCount() > 0) 
          {
	          //Query to Update Order Status
            $queryS = $pdo2->prepare("UPDATE POrders SET order_status = 'Complete' WHERE order_num = :order_number");
            $queryS->execute([':order_number' => $Onumber]);

	          // $row = $result->fetch(PDO::FETCH_ASSOC)
            if($check_order->rowCount() > 0)  //if more than 1 row affected, change was sucessful
            {
              // checking if order has already been marked as complete 
              if ($check_order_status['order_status'] == 'Complete')
              {
                echo "*Order already completed"; 
              } 
              else 
              {
                // order was marked as pending, complete the order 
                echo  "*Order fufillment completed sucessfully, confirmation sent to customer email.";
              } 
            }
            else 
            {
              echo "*Error: Unsucessful completion";
            }
          }
          else 
          {
            echo "*Order number '$Onumber' not found";
          }

         }
?>
<!--                    PHP code to run when GET PACKAGE LIST button clicked           -->
<?php
	if(isset($_GET["packingsubmit"]))
          {
		$number = $_GET["ordernum"];
           //Query get Order Packaging List
	   $pack_list = $pdo2->prepare("SELECT inv_id, quan_in_order FROM PProdInOrder WHERE order_num = :order_num");
	   $pack_list->execute([':order_num' => $number]);
	 //  $status = $pack_list->fetch(PDO::FETCH_ASSOC);
	   // if order number is found in table 
           if($pack_list->rowCount() > 0)
           {?>
	   <div class = "yPrint">
	   <table border = 2 style = "background-color: white;" class="center">
	   <tr>
	   <th>Item ID</th>
	   <th>Quantity</th>
	   </tr>
	   <tr>
    <?php
      		while($row = $pack_list->fetch(PDO::FETCH_ASSOC))
         	{
     ?>
     		   <td> <?php echo $row['inv_id']; ?> </td>
		   <td> <?php echo $row['quan_in_order']; ?> </td>
		</tr>
		<?php
         	}?>
	   </table>
	   <input TYPE="button" value= "Print" onClick="window.print()" class="my-button">
	   </div>
<?php
	  }
	  else 
          {
            echo "*No packing list found for order number entered : '$number'";
          }
	 }

?>




<!--    			view orders w/ status			-->

   <br><br>
   <h2 style="background-color:#DAF7A6;">View Order List </h2>
   <!-- FORM TO CHECK WHAT ORDER LIST TO VIEW -->
    <form method = "GET" action = "<?php echo $_SERVER['PHP_SELF']; ?>">

    <label for = "check_orders"> Select to see Orders: </label><br>

    <select name = "check_orders">
    <option value = "NULL"> --Select Option--- </option>
    <option value = "Complete"> Sucessfully Completed </option>
    <option value = "Pending"> Pending Completion </option>
    </select>

    <input type = "submit" value = "SUBMIT" name = "ordersubmit">
    </form>

    <?php
    //PHP code to run view orders form if submitted
    if(isset($_GET["ordersubmit"]) && $_GET["check_orders"] != "NULL")
    {
        $Ostatus = $_GET["check_orders"];
        $queryO = "SELECT order_num, email, order_status, total_weight FROM POrders WHERE order_status = '$Ostatus'";
    	$result = $pdo2->query($queryO);

   //print table of status selected
   ?>
    <table border = 2 style = "background-color: white;" class="center">
     <tr>
        <th> Order Number </th>
        <th> Email </th>
        <th> Order Status </th>
        <th> Total Weight </th>
     </tr>
     <tr>
    <?php
     while($row = $result->fetch(PDO::FETCH_ASSOC))
         {
        ?>
        <td> <?php echo $row['order_num']; ?> </td>
          <td> <?php echo $row['email']; ?> </td>
          <td> <?php echo $row['order_status']; ?> </td>
          <td> <?php echo $row['total_weight']; ?> </td>
	   </tr>
           <?php
         }?>
	</table>
<?php }  ?>


<?php }
    catch(PDOexception $e) {
      echo "Connection to database failed: " . $e->getMessage();
    }
   ?>
  </div>
  </body>
</html>
