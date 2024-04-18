<html>
 <head>
  <title>Shipping And Handling</title>
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
       <a href='view_orders.php'>View Orders</a>
       <a href='ship_and_hand.php'>Edit S&H</a>
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
       // Get bracket table from Database
        $query = "SELECT * FROM ShipAndHand";
        $brackets = $pdo2->query($query);
        while($row = $brackets->fetch(PDO::FETCH_ASSOC)){
          ?> //Create forms for entering weight bracket and cost
          <h><b>Weight brackets to calculate shipping costs: <br></b></h>
          <form method="POST" action="">
            <label for="weight">Weight:</label>
            <input type="number" id="weight" name="weight"><br>
            <label for="cost">Cost:</label>
            <input type="text" id="cost" name="cost"><br>
            <input type="submit" name="submit" value="Add new bracket">
          </form>
           <?php
          if(isset($_POST['submit'])){
            //Insert into table if both the weight and cost forms are filled
            if(!empty($_POST['weight']) && !empty($_POST['cost'])){
                $weightBracket = $_POST['weight'];
                $bracketCost = $_POST['cost'];
                $gen_bracket = $pdo2->prepare("INSERT INTO ShipAndHand (weight_bracket, price) VALUES (:weightBracket, :bracketCost)");
                $gen_bracket->execute([':weightBracket' => $weightBracket, ':bracketCost' => $bracketCost]);
            }else{
              //Tell admin to put in weight and cost if they did not
              echo "Please input cost and weight";
            }
        }
      }


    }
    catch(PDOexception $e) {
      echo "Connection to database failed: " . $e->getMessage();
    }
   ?>
  </body>
</html>
