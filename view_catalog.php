<!DOCTYPE html>
<html>
<head>
    <title>Parts Catalog</title>
    <style>
    
        /*formats entire page*/
        *
        {
            margin: 0px; 
            padding: 0px; 
            box-sizing: border-box; 
        }

        /*Small border around products*/
        main 
        {
            margin: 10px; 
        }

        /*background of products*/
        .product-list
        {  
            background-color: #dfe6e9;
            padding:3em; 
        }

        /*display product cards in a grid like format*/
        .product-container
        {
            display: grid; 
            grid-template-columns: 1fr 1fr 1fr; 
            grid-column-gap: 20px; 
            grid-row-gap: 20px;
        }

        /*product cards*/
        .product-list .display 
        {
            background-color: white; 
            border-radius: 10px; 
            padding: 1em; 
            box-shadow: 0px 10px 5px #b2bec3; 
            text-align: center; 
        }

        /*product name/description*/ 
        .display .title
        {
            font-size: 25px; 
            font-weight: bold; 
        }

        /*product price*/
        .display .price
        {
            font-size: 20px; 
            font-weight: bold; 
        }

        /*product picture*/
        .display img
        {
            width: 30%;
            display: block; 
            margin-left: auto; 
            margin-right: auto;
        }

        /*product description/weight/qty in stock*/ 
        .display .text
        {
            text-align: left; 
            margin-left:2em; 
            margin-bottom: 0.5em; 
            font-size: 17px;
        }

        /*add to cart button*/ 
        .cart-button
        {
            background-color: #778ca3;
            border:none;
            padding: 1em; 
            border-radius: 5px; 
            font-weight: bold; 
            width: 100%;
            margin-top: 10px;
        }

        /*hover over add to cart button*/
        .cart-button:hover
        {
            box-shadow: 0px 5px 5px #b2bec3; 
            color: white;
            cursor: pointer; 
        }

        /*nav bar formatting*/
        nav_bar
        {
            width: 100%; 
            height: 75px; 
            background-color: #4b6584; 
            color: #fff; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 0px 50px; 
            position: fixed; 
            top: 0; 
            z-index: 1; 
            box-shadow: 0px 10px 5px #b2bec3; 
        }

        /*Cart parts store header*/
        .title
        {
            font-size: 32px; 
            font-weight: bold; 
        }

        /* navbar header items overall*/
        .headers
        {
            width: 65%; 
            display: flex; 
            justify-content: right;
            list-style: none;
        }

        /*menu list and link items*/ 
        .headers li a
        {
            margin-left: 20px; 
            font-size: 19px; 
            color: #fff;
            text-decoration: none; 
            transition: 0.3s ease; 
        }

        /*hover over menu items*/
        .headers li a:hover 
        {
            color: #b2bec3; 
        }

        /*search bar*/
        .search_bar
        {
            display: flex;
            align-items: center; 
            margin-right: 20; 
        }

        /*search bar form html*/
        .search_bar form
        {
            display: flex; 
            margin: 0; 
        }

        /*search bar input formatting*/ 
        .search_bar input
        {
            padding: 10px; 
            border: 1px solid #ddd;
            outline: none;
            border-radius: 5px; 
            margin-right: 10px; 
            font-size: 16px; 
        }

        /*search button formatting*/ 
        .search_bar button
        {
            padding: 10px; 
            background-color: #fff;
            border:none; 
            border-radius: 5px; 
            color: #333; 
            font-size: 19px; 
            cursor: pointer; 
        }

        /*hover over search bar*/ 
        .search_bar button:hover
        {
            background-color:  #b2bec3; 
            box-shadow: 0px 5px 5px;
        }
    </style>
</head>
<body>

    <!--Page Header-->
    <h1>Car Parts</h1>

    <nav_bar>
        <div class="title">Car Parts</div>
        <ul class="headers">
            <!-- Nav to go to shopping cart of product list/view catalog-->
            <li><a href='tracking.php'>Track Order</a></li>
            <li><a href='shoppingcart.php'>Shopping Cart</a></li>
            <li><a href='EAlogin.php'>Employee or Admin? Log in.</a></li>
        </ul>
        
        <!--Textbox to search for part description. If a value is submitted, it takes you to a separate php file with the search results-->
        <div class ="search_bar">
                <form method="POST" action="search_catalog.php">
                    <input type ="text"  placeholder="Search for Parts..." name="search_part">
                    <button type="submit">Search</button>
                </form>
        </div>
    </nav_bar>
<main> 
<section class ="product-list">
 <div class="product-container"> 
        <?php

            include("secrets.php");

            try 
            {
                // connect to ege database and mariadb 
                $dsn = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
                $dsn2 = "mysql:host=courses;dbname=$dbname";
                $pdo = new PDO($dsn, 'student', 'student');
                $pdo2 = new PDO($dsn2, $username, $password);
            
                // sql to grab part details
                $sql = "SELECT * FROM parts";
                $result = $pdo->query($sql);
            
                // sql to grab part quantity/inventory amount
                $specPartQuan = $pdo2->prepare("SELECT quan_in_inv FROM PInventory WHERE inv_id = :part_number");

                // grab part info and display
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<div class='display'>";

                    echo "<div class='title'>" . $row['description'] . "</div>";
                    echo "<p></p>";

                    echo "<div class='img'>";
                    // display image
                    echo "<img src='" . $row['pictureURL'] . "' alt='Picture'>";

                    echo "</div>"; 

                    // display price with css style
                    echo "<p></p>"; 
                    echo "<div class='price'>$" . $row['price'] . "</div>";
                    echo "<p></p>";

                    // display the part description, weight, and quantity with its own css style 
                    echo "<div class='text'> Weight: " . $row['weight'] . "lbs</div>";

                    // execite pdo2 to grab part quantity
                    $specPartQuan->execute([':part_number' => $row['number']]);
                    $spQuan = $specPartQuan->fetch(PDO::FETCH_ASSOC);
                  
                    // display quan in stock with css style
                    echo "<div class='text'>" . $spQuan['quan_in_inv'] . " in stock</div>";
                    
                    echo "<p></p>";

                    //Form for adding a quantity of a certain product to cart 
                    echo "<form method='POST' action=''>";
                    echo "<input type='hidden' name='part_number' value='" . $row['number'] . "'>";
                    echo "<label for='add_cart'>Enter Quantity: </label>";
                    echo "<input type='text' name='add_cart' size='3'>";
                    echo "<button type='submit' name='submit' class='cart-button'>";
                    echo "Add to cart";
                    echo "</button>";
                    echo "</form>";

                    // check if form is not empty and ensure that form details are only being displayed on the specified part submission
                    if (!empty($_POST["add_cart"]) && $_POST['part_number'] == $row['number']) 
                    {
                        $quan_select = $_POST['add_cart'];
                        
                        // check the quantity in inventory column 
                        $cur_quan = (int)$spQuan['quan_in_inv'];

                        // if the current quantity is less than the selected quantity, display the message
                        if ($cur_quan < $quan_select) 
                        {
                            echo "<p><h4>Not enough in stock.</h4></p>";
                        }
                        else 
                        {
                            echo "<p><h4>Added to cart.</h4></p>";
                            
                            // add the products to the PProdInCart table
                            $add_to_cart = $pdo2->prepare("INSERT INTO PProdInCart (inv_id, cart_id, quan_in_order) VALUES (:part_number, '12345', :selected_quan)");
                            $add_to_cart->execute([':part_number' => $row['number'], ':selected_quan' => $quan_select]);
                        }
                    }
                    echo "</div>"; // close display css  
                }         
            } 
            catch (PDOException $e) 
            {
                echo "Connection failed: " . $e->getMessage();
            }
        ?>
    </div>
</section> 
</main>
</body>
</html>

<!--Javascript script that prevents the enter qty form to submit again when refreshing the page-->
<script>
if (window.history.replaceState) 
{
    window.history.replaceState(null, null, window.location.href);
}
</script>