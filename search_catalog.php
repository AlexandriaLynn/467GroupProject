<!DOCTYPE html>
<html>
<head>
    <title>Parts Catalog</title>
    <style>
        /* Product Box Formatting -- Centers in page */
        .sec
        {
            padding: 10px 5%;
        }

        /* Display the products in a grid */
        .products
        {
            display: grid;
            grid-template-columns: auto auto auto auto auto;
        }
        
        /* Product Card formatting */
        .products .card
        {
            width: 300px;
            background: #f5f5f5;
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
            border-radius: 5px;
            padding: 5px;
            margin-bottom: 20px; 
        }

        /*Product image formatting */
        .products .card img
        {
            height: 150px;
            width: 80%;
            display: block; 
            margin-left: auto; 
            margin-right: auto;
        }

        /*Product name/desc formatting*/
        .products .card .desc
        {
            font-size: 25px;
            color: black;
            padding: 0 20px;
            text-align: center;
            font-weight: bold;
        }

        /*Product Price formatting*/
        .products .card .price
        {
            color: black;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        /*Product detail formatting -- weight and quantity in stock*/
        .products .card .det
        {
            color: black;
            text-align: center;
            font-size: 20px;
        }
        
        /*Page header*/
        .products-h 
        {
            font-size: 50px;
            padding: 15px 32px;
            color: black;
            text-align: center;
            margin-bottom: 20px;
        }
         
        /*Nav bar formatting*/
        .navbar
        {
            padding: 8px 2%;
            background-color: #f3f4f6;
            box-shadow: 0 0 14px rgba(0,0,0,0.3);
        }

        /*Navbar menu items*/
        .navbar .menu li
        {
            list-style: none;
            display: inline-block;
        }
        /*hover bar formatting*/
        .navbar li a:hover 
        {
            background-color: #D3D3D3;
            padding: 15px 30px;
        }
        /*Nav bar menu items*/
        .navbar .menu li a
        {
            color: #424144;
            text-decoration: none;
            font-size: 20px;
            font-weight: bold;
            margin-right: 50px;
            margin-top: 20px;
        }
        
        /* Search bar centered*/
        .navbar-centered 
        {
            font-size: 18px;
            font-weight: bold;
            display: inline-block;
            margin-left: 350px;
            margin-top: 20px;
        }
        
        /*Employee login shifted to the right*/
        .navbar-right 
        {
            display: inline-block;
            float: right;
            margin-top: 20px;
        }

    </style>
</head>
<body>
    <!--Page Header-->
    <h1 class="products-h">Car Parts</h1>
    <nav class="navbar">

        <ul class="menu">
            <!-- Nav to go to shopping cart of product list/view catalog-->
            <li><a href='view_catalog.php'>Return to Product List</a></li>
            <li><a href='shoppingcart.php'>Shopping Cart</a></li>
            <div class="navbar-right"><li><a href='EAlogin.php'>Employee or Admin? Log in.</a></li></div>

            <!--Textbox to search for part description. If a value is submitted, it takes you to a separate php file with the search results-->
            <div class="navbar-centered">
            <li>
                <form method="POST" action="search_catalog.php">
                    <label for="search_part" class="topnav-search">Search by Part Description</label>
                    <input type ="text" name="search_part">
                    <input type="submit" value="Search"/>
                </form>
            </li>
            </div>
    </ul>
    </nav>

  <section class="sec">
        <div class="products"> 

            <?php

                include("secrets.php");
                session_start(); // start session

                try 
                {
                    // THE FORMATTING IS MESSED UP ON THIS ONE!! // 
                    // connect to ege database and mariadb 
                    $dsn = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
                    $dsn2 = "mysql:host=courses;dbname=$dbname";
                    $pdo = new PDO($dsn, 'student', 'student');
                    $pdo2 = new PDO($dsn2, $username, $password);

                    // grab searched part from html form 
                    $search_part = $_POST['search_part'];
        
                    // grab searched products from database and display. 
                    $sql_search = "SELECT * FROM parts WHERE description LIKE :search_part";
                    $sql_search_res = $pdo->prepare($sql_search);
                    $sql_search_res->execute([':search_part' => "%$search_part%"]); 
       
                    // sql to grab part quantity/inventory amount
                    $specPartQuan = $pdo2->prepare("SELECT quan_in_inv FROM PInventory WHERE inv_id = :part_number");

                    // grab the search results and display with the css styles
                    while ($row = $sql_search_res->fetch(PDO::FETCH_ASSOC)) 
                    {
                        // card/grid & image formatting for products
                        echo "<div class=\"card\">";
                        echo "<div class=\"img\">";

                        // display image
                        echo "<img src='" . $row['pictureURL'] . "' alt='Picture'>";
                        
                        // end image css
                        echo "</div>";

                        // display the part description, weight, and quantity with its own css style 
                        echo "<div class=\"desc\">" . $row['description'] . "</div>";
                        echo "<div class=\"det\">" . $row['weight'] . "lbs</div>";

                        // execite pdo2 to grab part quantity
                        $specPartQuan->execute([':part_number' => $row['number']]);
                        $spQuan = $specPartQuan->fetch(PDO::FETCH_ASSOC);
                                     
                        // display quan in stock with css style
                        echo "<div class=\"det\">Quantity in stock: " . $spQuan['quan_in_inv'] .  "</div>";
                                 
                        // display price with css style 
                        echo "<div class=\"price\">$" . $row['price'] . "</div>";
                        echo "<p></p>";           

                        //Form for adding a quantity of a certain product to cart 
                        echo "<form method='POST' action=''>";
                        echo "<input type='hidden' name='part_number' value='" . $row['number'] . "'>";
                        echo "<label for='add_cart'>Enter Quantity: </label>";
                        echo "<input type='text' name='add_cart' size='3'>";
                        echo "<input type='submit' value='Add to Cart'>";
                        echo "</form>";
                        echo "</div>";
                        
                        // check if form is not empty and ensure that form details are only being displayed on the specified part submission
                        if (!empty($_POST["add_cart"]) && $_POST['part_number'] == $row['number']) 
                        {
                            $quan_select = $_POST['add_cart'];
                        
                            // check the quantity in inventory column 
                            $cur_quan = (int)$spQuan['quan_in_inv'];

                            // if the current quantity is less than the selected quantity, display the message
                            if ($cur_quan < $quan_select) 
                            {
                                echo "<div class=\"det\">Not enough in stock.</div>";
                            }
                            else 
                            {
                                echo "<div class=\"det\">Added to cart.</div>";
                            
                                $add_to_cart = $pdo2->prepare("INSERT INTO PProdInCart (inv_id, cart_id, quan_in_order) VALUES (:part_number, '12345', :selected_quan)");
                                $add_to_cart->execute([':part_number' => $row['number'], ':selected_quan' => $quan_select]);
                            }

                            echo "</div>"; // close card
                        }
                    }

                    // invalid search; i.e no matches
                    if ($sql_search_res->rowCount() == 0) 
                    {
                        echo "<h1>Zero results found.</h1>";
                    }
                }
                catch (PDOException $e)
                {
                    echo "Connected to database failed: " . $e->getMessage(); 
                }
            ?>
    </div> <!-- close products div -->
</section> <!--close section-->
</body>
</html> 

<!--Javascript script that prevents the enter qty form to submit again when refreshing the page-->
<script>
if (window.history.replaceState) 
{
    window.history.replaceState(null, null, window.location.href);
}
</script>