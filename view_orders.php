<html>
 <head>
  <title>View Orders</title>
  <style>

   table
   {
     border-collapse: collapse;
     width: 100%;
   }

   th, td
   {
     padding: 8px;
     margin: auto;
     text-align: center;
     border-bottom: 1px solid #ddd;
   }

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

   p
   {
     padding-left: 10px;
   }

  </style>
 </head>
  <body>
   <header>
     <div class="navbar" id="navbar">
       <a href='view_orders.php'>View Orders</a>
       <a href='ship_and_hand.php'>Edit S&H</a>
       <c><a href='EAlogin.php'>Logout</a></c>
       <a href="javascript:void(0);" class="icon" onclick="myFunction()"> &#9776; </a>
     </div>
   </header>
   <?php

    include("secrets.php"); //this is another php, that has a $username, $password, and $dbname to connect to the db
                            //$username = zid, $password = yearMonDay, $dbname = zid
    try{
      $dsn1 = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
      $dsn2 = "mysql:host=courses;dbname=$dbname";
      $pdo1 = new PDO($dsn1, "student", "student");
      $pdo2 = new PDO($dsn2, $username, $password);

      $defaultDate1 = "2024-01-01"; //random date before date2
      $defaultDate2 = date('Y-m-d'); //today's date
      $defaultPriceMin = 0;
      $defaultPriceMax = 99999;

      //filter orders
      echo "<form method='POST' action=''>";
      echo "<p>Search for an order based on:</p>";

      //date
      echo "<p>Date placed: ";
      echo "<input type='date' id='date1' name='date1' value=$defaultDate1>";
      echo " to ";
      echo "<input type='date' id='date2' name='date2' value=$defaultDate2>";
      echo "</p>";

      //price
      echo "<p>Price range: ";
      echo "<input type='number' id='price1' name='price1' min='0' max='99999' value=$defaultPriceMin>";
      echo " to ";
      echo "<input type='number' id='price2' name='price2' min='0' max='99999' value=$defaultPriceMax>";
      echo "</p>";

      //submit button
      echo "<p><input type='submit' name='Search' value='Search'></p>";
      echo "</form>";

      $priceMin = $_POST['price1'];
      $priceMax = $_POST['price2'];
      $dateFirst = $_POST['date1'];
      $dateSecond = $_POST['date2'];

      //if a number/date has NOT been entered then use the default values (0-99999, 2024-01-01-CURDATE)
      if(empty($_POST['price1']))
      {
        $priceMin = $defaultPriceMin;
      }
      if(empty($_POST['price2']))
      {
        $priceMax = $defaultPriceMax;
      }
      if(empty($_POST['date1']))
      {
        $dateFirst = $defaultDate1;
      }
      if(empty($_POST['date2']))
      {
        $dateSecond = $defaultDate2;
      }

      //if a "details" button was clicked, then display the order details
      if (isset($_POST['viewOrderDetails']))
      {
        //return button
        echo "<p><a href='view_orders.php'><button name='Return'>Return</button></a></p>";

        $orderNumber = $_POST['orderNumber'];

        //get order info/details
        $orderDetails = $pdo2->prepare("SELECT * FROM POrders WHERE order_num = :orderNumber;");
        $orderDetails->execute([':orderNumber' => $orderNumber]);
        $oDetails = $orderDetails->fetch(PDO::FETCH_ASSOC);

        //get order parts
        $orderParts = $pdo2->prepare("SELECT * FROM PProdInOrder WHERE order_num = :orderNumber;");
        $orderParts->execute([':orderNumber' => $orderNumber]);

        //customer information
        echo "<h3>Customer Info</h3>";
        echo "<p>Name: " . $oDetails['cust_name'] . "</p>";
        echo "<p>Email: " . $oDetails['email'] . "</p>";
        echo "<p>Address: " . $oDetails['shipping_addr'] . "</p>";

        //order info
        echo "<h3>Order Details</h3>";
        echo "<p>Order Number: " . $oDetails['order_num'] . "</p>";
        echo "<p>Status: " . $oDetails['order_status'] . "</p>";
        echo "<p>Date Placed: " . $oDetails['date_placed'] . "</p>";
        echo "<p>Total Price: $" . $oDetails['total_price'] . "</p>";
        echo "<p>Total Weight: " . $oDetails['total_weight'] . " lbs</p>";

        //parts info
        echo "<h3>Parts included</h3>";
        while($rows = $orderParts->fetch(PDO::FETCH_ASSOC))
        {
          $partName = $pdo1->prepare("SELECT * FROM parts WHERE number = :inv_num;");
          $partName->execute([':inv_num' => $rows['inv_id']]);
          $pName = $partName->fetch(PDO::FETCH_ASSOC);

          echo "<p>" . $rows['quan_in_order'] . "x " . $pName['description'] . "</p>" ;
        }
      }
      else
      {
        //get the orders table based on the inputted parameters
        $orderTable = $pdo2->prepare("SELECT order_num, order_status, date_placed, total_price FROM POrders WHERE total_price >= :priceMin && total_price <= :priceMax && date_placed BETWEEN :dateFirst AND :dateSecond;");
        $orderTable->execute([':priceMin' => $priceMin,':priceMax' => $priceMax, ':dateFirst' => $dateFirst,':dateSecond' => $dateSecond]);

        //check to see if any orders fit the parameters
        if ($orderTable->rowCount() == 0)
        {
          //return button
          echo "<p><a href='view_orders.php'><button name='Return'>Return</button></a></p>";

          echo "<h3><p>No orders within those parameters.</p></h3>";
        }
        else
        {
          //if something was searched AND each field IS NOT the default values, then include a return button
          if(!empty($_POST['price1']) || !empty($_POST['price2']) || !empty($_POST['date1']) || !empty($_POST['date2']))
          {
            if($_POST['price1'] != $defaultPriceMin || $_POST['price2'] != $defaultPriceMax || $_POST['date1'] != $defaultDate1 || $_POST['date2'] != $defaultDate2)
            {
              //return button
              echo "<p><a href='view_orders.php'><button name='Return'>Return</button></a></p>";
            }
          }

          //print the orders table (order #, status, date placed, price, details button)
          echo "<table>";
          echo "<tr><th>OrderID</th><th>Status</th><th>Date Placed</th><th>Price</th><th>View Order</th></tr>";

          while($rows = $orderTable->fetch(PDO::FETCH_ASSOC))
          {
            echo "<tr>";

            echo "<td>" . $rows['order_num'] . "</td>";
            echo "<td>" . $rows['order_status'] . "</td>";
            echo "<td>" . $rows['date_placed'] . "</td>";
            echo "<td>$" . $rows['total_price'] . "</td>";

            //view order details button
            echo "<td>";
            echo "<form method='POST' action=''>";
            echo "<input type='hidden' name='orderNumber' value='" . $rows['order_num'] . "'>";
            echo "<input type='submit' name='viewOrderDetails' value='Details'>";
            echo "</form>";
            echo "</td>";

            echo "</tr>";
          }
          echo "</table>";
        }
      }
    }
    catch(PDOexception $e) {
      echo "Connection to database failed: " . $e->getMessage();
    }
   ?>
  </body>
</html>
