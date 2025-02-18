<html>
 <head>
  <title>Inventory</title>
  <style>

   table
   {
     border-collapse: collapse;
     width: 100%;
   }

   th, td
   {
     padding: 8px;
     text-align: left;
     border-bottom: 1px solid #ddd;
   }

   tr:nth-child(even)
   {
     background-color: #f2f2f2;
   }

  </style>
 </head>
  <body>
   <?php

    include("secrets.php"); //this is another php, that has a $username, $password, and $dbname to connect to the db
                                                            //zid,       bday,          zid

    try{
      $dsn1 = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
      $dsn2 = "mysql:host=courses;dbname=$dbname";
      $pdo1 = new PDO($dsn1, "student", "student");
      $pdo2 = new PDO($dsn2, $username, $password);

      //search bar
      echo "<form method='POST' action=''>";
      echo "Search for a specific part: ";
      echo "<input type='text' name='partSearch'/>";
      echo "<input type='submit' name='Search' value='Search'>";
      echo "</form>";

      //if there is something in the search bar, then limit the table to what was entered
      if(isset($_POST['partSearch']) && !empty($_POST['partSearch']))
      {
        //return button
        echo "<p><a href='inventory.php'><button name='Return'>Return</button></a></p>";

        $partSearch = $_POST['partSearch'];

        //find the parts with similar desc or number
        $specificPart = $pdo1->prepare("SELECT * FROM parts WHERE description LIKE :specificPart OR number LIKE :specificPart;");
        $specificPart->execute([':specificPart' => '%' . $partSearch . '%', ':specificPart' => '%' . $partSearch . '%']);

        //check to see if the filtered part exsists
        if ($specificPart->rowCount() == 0)
        {
            echo "<h3>No such part exsists.</h3>";
        }
        else
        {
          //table to display the filtered parts
          echo "<table>";
          echo "<tr><th>Number</th><th>Picture</th><th>Description</th><th>Price</th><th>Weight</th><th>Quantity</th><th>Add Quantity</th></tr>";
          while($rows = $specificPart->fetch(PDO::FETCH_ASSOC))
          {
            echo "<tr>";
            echo "<td>" . $rows['number'] . "</td>";
            echo "<td>"; //picture
            if(isset($rows['pictureURL']))
            {
              echo "<img src='" . $rows['pictureURL'] . "' alt='Picture'>";
            }
            echo "</td>";

            echo "<td>" . $rows['description'] . "</td>";
            echo "<td>$" . $rows['price'] . "</td>";
            echo "<td>" . $rows['weight'] . "lbs</td>";

            //get the part quantity from inventory table in 2nd db
            $specPartQuan = $pdo2->prepare("SELECT quan_in_inv FROM PInventory WHERE inv_id = :partNumber;");
            $specPartQuan->execute([':partNumber' => $rows['number']]);
            $spQuan = $specPartQuan->fetch(PDO::FETCH_ASSOC);
            echo "<td>" . $spQuan['quan_in_inv'] . "</td>";

            //input box and submit button
            echo "<td>";
            echo "<form method='POST' action=''>";
            echo "<input type='hidden' name='partNumber' value='" . $rows['number'] . "'>";
            echo "<input type='number' name='addQuan' value='0' min='0'>";
            echo "<input type='submit' name='updateQuantity' value='Update'>";
            echo "</form>";
            echo "</td>";

            echo "</tr>";
          }
          echo "</table>";
        }
      }
      else //the search bar is empty / there is no filter
      {
        $changed = 0;

        //grab the parts info and quantities
        $partsTable = $pdo1->query('SELECT * FROM parts;');
        $partsQuantity = $pdo2->query('SELECT * FROM PInventory;');

        //display the parts, their info, and their quantity
        echo "<table>";
        echo "<tr><th>Number</th><th>Picture</th><th>Description</th><th>Price</th><th>Weight</th><th>Quantity</th><th>Add Quantity</th><tr>";

        while($rows = $partsTable->fetch(PDO::FETCH_ASSOC))
        {
          echo "<tr>";
          echo "<td>" . $rows['number'] . "</td>";
          echo "<td>"; //picture
          if(isset($rows['pictureURL']))
          {
            echo "<img src='" . $rows['pictureURL'] . "' alt='Picture'>";
          }
          echo "</td>";

          echo "<td>" . $rows['description'] . "</td>";
          echo "<td>$" . $rows['price'] . "</td>";
          echo "<td>" . $rows['weight'] . " lbs</td>";

          //makes sure the quanitity is updated only once
          if(isset($_POST['updateQuantity']) && $changed == 0)
          {
            //update quantities if a number was inputed
            $partNum = $_POST['partNumber'];
            $quanToAdd = $_POST['addQuan'];

            if($quanToAdd != 0)
            {
              $updateQuan = $pdo2->prepare("UPDATE PInventory SET quan_in_inv=quan_in_inv+:quanToAdd WHERE inv_id=:partNum;");
              $updateQuan->execute([':quanToAdd' => $quanToAdd, ':partNum' => $partNum]);
              $changed = 1;
            }
          }

          //get quantitiy
          $currQuan = $pdo2->prepare("SELECT quan_in_inv FROM PInventory WHERE inv_id = :partNumber;");
          $currQuan->execute([':partNumber' => $rows['number']]);
          $cQuan = $currQuan->fetch(PDO::FETCH_ASSOC);
          echo "<td>" . $cQuan['quan_in_inv'] . "</td>";

          //input box and submit button
          echo "<td>";
          echo "<form method='POST' action=''>";
          echo "<input type='hidden' name='partNumber' value='" . $rows['number'] . "'>";
          echo "<input type='number' name='addQuan' value='0' min='0'>";
          echo "<input type='submit' name='updateQuantity' value='Update'>";
          echo "</form>";
          echo "</td>";

          echo "</tr>";
        }
        echo "</table>";
      }
    }
    catch(PDOexception $e) {
      echo "Connection to database failed: " . $e->getMessage();
    }
   ?>
  </body>
</html>
