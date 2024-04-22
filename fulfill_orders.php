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

  </style>
 </head>
  <body>
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
    <h1> Confirm Order Fulfillment</h1>
        <form method = "GET" action = "<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for = "ordernum"> Order Number: </label><br>
        <input type="text" id="ordernum" name="ordernum"><br>
	<input type = "submit" value = "GET PACKAGE LIST" name = "packingsubmit">
	<input type = "submit" value = "MARK COMPLETE" name = "statussubmit">
        </form>
<?php	 //PHP code to run order status form
         if(isset($_GET["statussubmit"]))
         {
           $Onumber = $_GET["ordernum"];
	//Query to Update Order Status
           $queryS = "UPDATE POrders SET order_status = 'Complete' WHERE order_num = '$Onumber'";
	   $row = $pdo2->query($queryS); 
	  // $row = $result->fetch(PDO::FETCH_ASSOC)
             if($row > 0)  //if more than 1 row affected, change was sucessful
             {
                echo  "Order fufillment completed sucessfully, confirmation sent to customer email.";
             }
             else
             {
                echo "Invalid order number: Unsucessful completion";
             }

         }

	if(isset($_GET["packingsubmit"]))
         {
		$number = $_GET["ordernum"];
        //Query get Order Packaging List
           $queryP = "SELECT inv_id, quan_in_order FROM PProdInOrder WHERE order_num = '$number'";
	   $rsult = $pdo2->query($queryP);
?>
	   <table border = 2 style = "background-color: white;">
	   <tr>
	   <th>Item ID</th>
	   <th>Quantity</th>
	   </tr>
	   <tr>
    <?php
      		while($row = $rsult->fetch(PDO::FETCH_ASSOC))
         	{
     ?>
     		   <td> <?php echo $row['inv_id']; ?> </td>
		   <td> <?php echo $row['quan_in_order']; ?> </td>
		</tr>
		<?php
         	}?>
	   </table>
<?php	 }

?>




<!--    			view orders w/ status			-->
    <h2>View Order List </h2>
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
        $queryO = "SELECT order_num, email, order_status, total_weight, cart_id FROM POrders WHERE order_status = '$Ostatus'";
    	$result = $pdo2->query($queryO);

   //print table of status selected
   ?>
    <table border = 2 style = "background-color: white;">
     <tr>
        <th> Order Number </th>
        <th> Email </th>
        <th> Order Status </th>
        <th> Total Weight </th>
	<th> Cart Items ID </th>
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
          <td> <?php echo $row['cart_id']; ?> </td>  
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
  </body>
</html>
