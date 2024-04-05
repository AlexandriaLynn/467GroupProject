<!DOCTYPE html>
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
            margin-top: 10px;
        }
        
        /*Employee login shifted to the right*/
        .navbar-right 
        {
            display: inline-block;
            float: right;
            margin-top: 10px;
        }

    </style>
</head>
<body>

    <!--Page Header-->
    <h1 class="products-h">Car Parts</h1>
    <nav class="navbar">

    <ul class="menu">
    <!-- Nav to go to shopping cart of product list/view catalog-->
    <li><a href='view_catalog.php'>Product List</a></li>
    <li><a>Shopping Cart</a></li>
    <div class="navbar-right"><li><a href='login.php'>Employee or Admin? Log in.</a></li></div>

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

  </div>

<section class="sec">
        <div class="products"> 
        
            <?php
            try {
                $dsn = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
                $pdo = new PDO($dsn, 'student', 'student');
            
                $sql = "SELECT * FROM parts";
                $result = $pdo->query($sql);
            
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class=\"card\">";
                    echo "<div class=\"img\">";
                    if (isset($row['pictureURL'])) {
                        echo "<img src='" . $row['pictureURL'] . "' alt='Picture'>";
                    }
                    echo "</div>";
                    echo "<div class=\"desc\">" . $row['description'] . "</div>";
                    echo "<div class=\"det\">" . $row['weight'] . "lbs</div>";
                    echo "<div class=\"det\">Quantity in stock: N/A</div>";
                    echo "<div class=\"price\">$" . $row['price'] . "</div>";
                    echo "<p></p>";
            

                    // Form for adding to cart
                    echo "<div class=\"det\">";
                    echo "<!-- Form for adding a quantity to cart -->";
                    echo "<form method='POST' action='" . $_SERVER['PHP_SELF'] . "'>";
                    echo "<label for='add_cart'></label>";
                    echo "<input type='text' name='add_cart' size='3'>";
                    echo "<input type='submit' value='Add to Cart'>";
                    echo "</form>";
                    echo "</div>";

                    echo "</div>"; // Close card
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            ?>
        
        </div> <!-- Close the products div -->
    </section>
</body>
</html>