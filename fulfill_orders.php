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
    <h1> Order List </h1>
    <!-- FORM TO CHECK WHAT ORDER LIST TO VIEW -->
    <form method = "GET" action = "<?php echo $_SERVER['PHP_SELF']; ?>">

    <label for = "check_orders"> Select to see Orders with status: </label>
    <select name = "check_orders">

    <option value = "NULL"> --Select Option--- </option>
    <option value = "complete"> Sucessfully Completed </option>
    <option value = "pending"> Pending Completion </option>
    </select>

    <input type = "submit" value = "SUBMIT" name = "ordersubmit">
    </form>

    <?php
    //PHP code to run if above form is submitted
/*    if(isset($_GET["ordersubmit"]) && $_GET["check_orders"] != "NULL")
        {
        $Ostatus = $_GET["check_orders"];
        $queryO =  $pdo1->prepare("SELECT order_num, email, order_status, total_weight FROM POrders WHERE order_status = '$Ostatus'");
    ?>
    <table border = 2 style = "background-color: white;">
     <tr>
        <th> Order Number </th>
        <th> Email </th>
        <th> Order Status </th>
        <th> Total Weight </th>
     </tr>
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
        }*/?>
    </table>


<?php }
    catch(PDOexception $e) {
      echo "Connection to database failed: " . $e->getMessage();
    }
   ?>
  </body>
</html>

