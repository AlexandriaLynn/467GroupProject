<html>
 <head><title>Inventory</title></head>
  <body>
   <?php

    include("secrets.php"); //this is another php, that has a $username and $password to connect to the db

    try{
      $dbname = "z1979706"; //change the zID to your own zID

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
        echo "<p><a href='inventory.php'><button>Return</button></a></p>";
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
          echo "<table border=1>";
          echo "<tr><th>Number</th><th>Picture</th><th>Description</th><th>Price</th><th>Weight</th><th>Quantity</th><tr>";
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
            echo "<td>" . $rows['price'] . "</td>";
            echo "<td>" . $rows['weight'] . "</td>";

            //get the part quantity from inventory table in 2nd db
            $specPartQuan = $pdo2->prepare("SELECT quan_in_inv FROM Inventory WHERE inv_id = :partNumber;");
            $specPartQuan->execute([':partNumber' => $rows['number']]);
            $spQuan = $specPartQuan->fetch(PDO::FETCH_ASSOC);
            echo "<td>" . $spQuan['quan_in_inv'] . "</td>";
            echo "</tr>";
          }
          echo "</table>";
        }
      }
      else //the search bar is empty / there is no filter
      {
        //grab the parts info and quantities
        $partsTable = $pdo1->query('SELECT * FROM parts;');
        $partsQuantity = $pdo2->query('SELECT * FROM Inventory;');

        //display the parts, their info, and their quantity
        echo "<table border=1>";
        echo "<tr><th>Number</th><th>Picture</th><th>Description</th><th>Price</th><th>Weight</th><th>Quantity</th><tr>";

        while($rows = $partsTable->fetch(PDO::FETCH_ASSOC))
        {
          $row = $partsQuantity->fetch(PDO::FETCH_ASSOC);
          echo "<tr>";
          echo "<td>" . $rows['number'] . "</td>";
          echo "<td>"; //picture
          if(isset($rows['pictureURL']))
          {
            echo "<img src='" . $rows['pictureURL'] . "' alt='Picture'>";
          }
          echo "</td>";

          echo "<td>" . $rows['description'] . "</td>";
          echo "<td>" . $rows['price'] . "</td>";
          echo "<td>" . $rows['weight'] . "</td>";
          echo "<td>" . $row['quan_in_inv'] . "</td>";
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
