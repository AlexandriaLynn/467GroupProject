<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        
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

        /*Table formatting*/
        table 
        {
            color: black; 
            background-color: white; 
            border-collapse: collapse;
            width: 70%;
            margin-top: 50px;
        }

        table, th, td 
        {
            border: 1px solid black;
        }

        th, td 
        {
            padding: 10px;
            text-align: center;
        }

        th 
        {
            background-color: #D3D3D3; 
        }

    </style>
</head>
<body>

    <!--Page Header-->
    <nav class="navbar">

    <!--navbar menu items-->
    <ul class="menu">
    <!-- Nav to go to shopping cart of product list/view catalog-->
        <li><a href='view_catalog.php'>Return to Product List</a></li>
        <li><a>In Cart</a></li>
        <div class="navbar-right"><li><a href='EAlogin.php'>Employee or Admin? Log in.</a></li></div>
    </ul>
    </nav>

<section class="sec">
    <div class="products"> 
        <?php

            include("secrets.php");

            try 
            {
                // connect to ege database and mariadb 
                $dsn = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
                $dsn2 = "mysql:host=courses;dbname=$dbname";
                $pdo = new PDO($dsn, 'student', 'student');
                $pdo2 = new PDO($dsn2, $username, $password);

                // grab information from PProdInOrder table; contains inv id and quantity in order
                $cart_display = "Select * FROM PProdInCart";
                $cart_res = $pdo2->query($cart_display);

                // grab the part information based on the inventory id in PProdOrder
                $part_display = $pdo->prepare("Select * FROM parts WHERE number = :inv_id");

                echo "<center>";
                echo "<table border=1>";
                echo "<tr><th>Product Image</th><th>Product Name</th><th>Price of Product</th><th>Weight of Product</th><th>Qty in Cart</th><th>Change Quantity</th></tr>";

                // display the information 
                while ($row = $cart_res->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<tr>";

                    $part_display->execute([':inv_id' => $row['inv_id']]);

                    while ($part_row = $part_display->fetch(PDO::FETCH_ASSOC)) 
                    {
                        echo "<td><img src='" . $part_row['pictureURL'] . "' alt='Picture'></td>";
                        echo "<td><center>" . $part_row['description'] . "</center></td>";
                        echo "<td><center>$" . $part_row['price'] . "</center></td>";
                        echo "<td><center>" . $part_row['weight'] . "lbs</center></td>";
                    }

                    echo "<td><center>" . $row['quan_in_order'] . "</center></td>";

                    // html form for remove button
                    echo "<td>";
                    echo "<form action='' method='POST'>";
                    echo "<label for='quantity'>0 to Remove From Cart </label>";
                    echo "<input type ='hidden' name='inv_id' value='" . $row['inv_id'] . "'>"; // Hidden input for inv_id
                    echo "<input type ='text' name='quantity' size='3'>";
                    echo "<input type='submit' value='Update'/>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";

                    // check if value was submitted and that its not empty 
                    if (isset($_POST['quantity']) && $_POST['quantity'] !== '') 
                    {
                        $quantity = $_POST['quantity'];
                        $inv_id = $_POST['inv_id']; 
                        
                        //STILL NEED TO DO ERROR CHECKING HERE TO MAKE SURE THAT THE QUANTITY ENTERED IS NOT MORE THAN WHATS IN STOCK// 
                        // if the quantity entered is 0, remove from cart 
                        if ($quantity == 0) 
                        {
                            $remove_prod = $pdo2->prepare("DELETE FROM PProdInCart WHERE inv_id = :inv_id");
                            $remove_prod->execute([':inv_id' => $inv_id]);
                            echo "<h3><center>Product removed from cart! Refresh to see updated cart.</h3></center>";
                        } 
                        else 
                        {
                            // else update the quantity 
                            $update_prod = $pdo2->prepare("UPDATE PProdInCart SET quan_in_order = :quantity WHERE inv_id = :inv_id");
                            $update_prod->execute([':quantity' => $quantity, ':inv_id' => $inv_id]);
                            echo "<h3><center>Product quantity updated from cart! Refresh to see updated cart.</h3></center>";
                        }
                    }
                }
                echo "</table>";
                echo "</center>";

                // sql query for quantity of product 
                $q_cart_total = "SELECT inv_id, quan_in_order FROM PProdInCart";
                $q_cart_res = $pdo2->query($q_cart_total);

                // sql query for grabbing product number and total price 
                $p_cart_total = $pdo->prepare("SELECT number, price, weight FROM parts WHERE number = :inv_id");

                $cart_total = 0; 

                $weight_total = 0; 

                // grab the values from PProdInOrder table
                while ($row = $q_cart_res->fetch(PDO::FETCH_ASSOC))
                {
                    // grab the part number and price based on the inventory id in PProdInOrder table
                    $p_cart_total->execute([':inv_id' => $row['inv_id']]);
                    $p_prod_price = $p_cart_total->fetch(PDO::FETCH_ASSOC);

                    // grab the price of the product and the quantity in the order
                    $price_of_prod = $p_prod_price['price'];
                    $quan_of_prod = $row['quan_in_order'];

                    // multiply the price of the individual product by the quantity in cart
                    $total_price = $price_of_prod * $quan_of_prod;

                    // add to cart total 
                    $cart_total += $total_price;

                    // grab and calculate total weight 
                    $weight_of_prod = $p_prod_price['weight'];

                    $total_weight = $quan_of_prod * $weight_of_prod; 

                    $weight_total += $total_weight;
                }

                // setprecision; ensure cart total rounds to 2 decimal places. 
                $cart_total = number_format($cart_total, 2);
                $weight_total = number_format($weight_total, 2);

                echo "<center>";
                echo "<h3><center>Your shopping cart total is: $$cart_total </center></h3>";
                echo "<h3><center>Your weight total is: $weight_total lbs</center></h3>";
            }
            catch (PDOException $e) 
            {
                echo "Connection failed: " . $e->getMessage();
            }
        ?>
        
        <!--HTML Forms for handling/taking in customer information-->
        <center>
            <form action="" method="POST">

                <p>
                <label for='name'>Name:</label>
                <input type="text" name="name" size="30">
                </p>

                <p>
                <label for='email'>Email:</label>
                <input type="text" name="email" size="30">
                </p>
            
                <p>
                <label for='ship_addr'>Shipping Address:</label>
                <input type="text" name="ship_addr" size="30">
                </p>

                <p>
                <label for='cc_num'>Credit Card Number:</label>
                <input type="text" name="cc_num" size="30">
                </p>

                <p>
                <label for='cc_exp'>Credit Card Expiration:</label>
                <input type="text" name="cc_exp" size="3">
                </p>

            <input type='submit' name='submit' value='Confirm Order'>
            </form>
        </center>

        <!--PHP Code for credit card autorization based on inputted values above--> 
        <?php 

            // if the html form is submitted
            if (isset($_POST['submit']))
            {
                // grab the values entered in the form
                $name = $_POST['name'];
                $email = $_POST['email'];
                $ship_addr = $_POST['ship_addr'];
                $cc_num = $_POST['cc_num'];
                $cc_exp = $_POST['cc_exp'];

                // 3 loops to generate transaction # in for format: 907-297880-296 (random number gen)
                $trans_num = '';

                for ($i = 0; $i < 3; $i++)
                {
                    $trans_num .= rand(0, 9);
                }

                $trans_num2 = '';
                for ($i = 0; $i < 6; $i++)
                {
                    $trans_num2 .= rand(0, 9);
                }

                $trans_num3 = '';
                for ($i = 0; $i < 3; $i++)
                {
                    $trans_num3 .= rand(0, 9);
                }

                // combine the three trans numbers generated with hyphens in the appropriate places 
                $comb_trans = $trans_num . '-' . $trans_num2 . '-' . $trans_num3;

                // credit card authorization 
                $url = 'http://blitz.cs.niu.edu/CreditCard/';

                // put values gathered above into an array 
                $data = array(
                    'vendor' => 'VE001-99',
                    'trans' => $comb_trans,
                    'cc' => $cc_num,
                    'name' => $name,
                    'exp' => $cc_exp,
                    'amount' => $cart_total);

                $options = array(
                    'http' => array(
                    'header' => array('Content-type: application/json', 'Accept: application/json'),
                    'method' => 'POST',
                    'content'=> json_encode($data)
                    ));
            
                $context  = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                    
                // decode string from server to check if card processing went through 
                $response = json_decode($result, true);

                // if authorization is set, the card was successful. echo successful message. otherwise, display why it went wrong 
                if (isset($response['authorization']))
                {
                    // will update this to add authorization and stuff too 
                    echo "Card verfied!";

                    //insert values into POrders table and clear cart. 
                    //generate a random order number. 
                    $order_number = '';
                    
                    for ($i = 0; $i < 10; $i++)
                    {
                        $order_number .= rand(0, 9);
                    }

                    // get todays current date
                    $cur_date = date('Y/m/d');

                    // insert the data into the orders table. 
                    $gen_order = $pdo2->prepare("INSERT INTO POrders (order_num, date_placed, cust_name, email, order_status, shipping_addr, total_price, total_weight, cart_id) VALUES (:order_number, :cur_date, :name, :email, 'Processing', :ship_addr, :cart_total, :weight_total, '12345')");
                    $gen_order->execute([':order_number' => $order_number, ':cur_date' => $cur_date, ':name' => $name, ':email' => $email, ':ship_addr' => $ship_addr, ':cart_total' => $cart_total, ':weight_total' => $weight_total]);

                    // grab inv_id and quantity currently in the cart 
                    $in_cart = $pdo2->query("SELECT inv_id, quan_in_order FROM PProdInCart");

                    // grab the values from cart
                    while ($row = $in_cart->fetch(PDO::FETCH_ASSOC)) 
                    {
                        $inv_id = $row['inv_id'];
                        $quan_in_order = $row['quan_in_order'];
                                        
                        // grab the current inventory amount for the inv_id 
                        $cur_inv = $pdo2->prepare("SELECT quan_in_inv FROM PInventory WHERE inv_id = :inv_id");
                        $cur_inv->execute([':inv_id' => $inv_id]);
                        $inv_row = $cur_inv->fetch(PDO::FETCH_ASSOC);
                        $quan_in_inv = $inv_row['quan_in_inv'];
                                        
                        // subtract the quantity ordered from the quantity in the inventory 
                        $updated_inv = $quan_in_inv - $quan_in_order;
                                        
                        // set new inventory
                        $inv_update = $pdo2->prepare("UPDATE PInventory SET quan_in_inv = :updated_inv WHERE inv_id = :inv_id");
                        $inv_update->execute([':updated_inv' => $updated_inv, ':inv_id' => $inv_id]);

                        //add the parts/quant into the PProdInOrder table
                        $part_order = $pdo2->prepare("INSERT INTO PProdInOrder (inv_id, order_num, quan_in_order) VALUES (:inv_id, :order_number, :quan_in_order);");
                        $part_order->execute([':inv_id' => $inv_id, ':order_number' => $order_number, ':quan_in_order' => $quan_in_order]);

                    }

                    // clear cart after order is placed 
                    $clear_cart = $pdo2->prepare("DELETE from PProdInCart where cart_id = 12345");
                    $clear_cart->execute();
                }
                else 
                {
                    // echo why it didnt work; will also update this to a more user-readable format
                    echo($result);
                }
                
        }
        ?>
    </body>
    </html>            

<!--Javascript script that prevents the enter qty form to submit again when refreshing the page-->
<script>
if (window.history.replaceState) 
{
    window.history.replaceState(null, null, window.location.href);
}
</script>
