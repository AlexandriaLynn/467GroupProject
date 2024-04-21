<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        /*whole page formatting*/
        *
        {
            margin: 0px; 
            padding: 0px; 
            box-sizing: border-box; 
            outline: none; 
        }

        /*Table formatting*/
        table 
        {
            color: black; 
            background-color: white; 
            border-collapse: collapse;
            width: 80%;
            margin-top: 100px;
        }

        table, th, td 
        {
            border: 2px solid #5f6c7d; 
        }

        th, td 
        {
            padding: 10px;
            text-align: center;
        }

        th 
        {
            background-color: #5f6c7d; 
            color: white;
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

        /* menu items overall*/
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

        /*space between table and form*/
        .space
        {
            width: 100%; 
            padding: 10px; 
        }

        /*formats the entire checkout form*/
        .checkout_box
        {
            max-width: 500px; 
            height: auto; 
            margin: 50px auto; 
            background: #fff;
            padding: 20px 40px 30px; 
            border-bottom-left-radius: 5px; 
            border-bottom-right-radius: 5px; 
            border-top: 7px solid #5f6c7d; 
        }

        /*checkout title*/
        .ch_header
        {
            color: #5f6c7d;
            font-size: 2em; 
            text-align: center;
            text-transform: uppercase; 
            font-weight: 900; 
            margin-bottom: 20px; 
        }

        /*spaces out the html inputs*/
        .input-box, 
        .input-box .text
        {
            margin-bottom: 20px; 
        }

        /*html label/header formatting*/
        .input-box .text label
        {
            display: block; 
            font-size: 1em;
            color: #5f6c7d;
            text-transform: capitalize; 
            margin-bottom: 8px;
            text-align: left;
            font-weight: bold;
        }

        /*html text box formatting*/
        .input-box .text input
        {
            background: transparent;
            border: 2px solid #bdbdbd; 
            width: 100%; 
            padding: 10px; 
            color: #212121; 
            border-radius: 3px; 
        }

        /*purchase button*/
        .purchase
        {
            background: #5f6c7d;
            color: #fff;
            font-size: 1em; 
            padding: 10px 30px; 
            text-align: center;
            border-radius: 5px; 
            cursor: pointer;
            font-weight: bold;
            letter-spacing: 2px; 
        }

        /*cart total formatting with h3*/
        h3
        {
            background-color: white; 
            border-radius: 10px; 
            padding: 1em; 
            text-align: center; 
            margin-top: 50px;
            border: 5px solid #5f6c7d; 
            display: inline-block;
        }

        /*not enough in stock formatting*/ 
        h2
        {
            background-color: white; 
            border-radius: 10px; 
            padding: 1em; 
            text-align: center; 
            margin-top: 100px;
            border: 5px solid #5f6c7d; 
            display: inline-block;
        }

        /*product qty updated/removed formatting*/
        h4
        {
            background-color: white; 
            border-radius: 10px; 
            padding: 1em; 
            text-align: center; 
            margin-top: 100px;
            margin-left:725px;
            border: 5px solid #5f6c7d; 
            display: inline-block;
        }

        /*if transaction fails to go through message*/
        .failed
        {
            background-color: white; 
            border-radius: 10px; 
            padding: 1em; 
            text-align: center; 
            margin-top: 10px;
            margin-left:0px;
            border: 5px solid #FF0000; 
            display: inline-block;
            font-size: 19px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <nav_bar>
        <div class="title">Cart</div>
            <ul class="headers">
                <!-- Nav to go to shopping cart of product list/view catalog-->
                <li><a href='view_catalog.php'>Return to Product List</a></li>
            </ul>

        <div class ="search_bar">
            <form method="POST" action="search_catalog.php">
                <input type ="text"  placeholder="Search for Parts..." name="search_part">
                <button type="submit">Search</button>
            </form>
        </div>
    </nav_bar>
<main> 

    <?php

        include("secrets.php");

        try 
        {
            // connect to ege database and mariadb 
            $dsn = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
            $dsn2 = "mysql:host=courses;dbname=$dbname";
            $pdo = new PDO($dsn, 'student', 'student');
            $pdo2 = new PDO($dsn2, $username, $password);

            // boolean removed and updated variables to be used in updating quantity 
            $removed = false; 
            $updated = false; 

            // grab information from PProdInOrder table; contains inv id and quantity in order
            $cart_display = "Select * FROM PProdInCart";
            $cart_res = $pdo2->query($cart_display);

            // grab the part information based on the inventory id in PProdOrder
            $part_display = $pdo->prepare("Select * FROM parts WHERE number = :inv_id");

            // sql query used to grab part number
            $sql = "SELECT * FROM parts";
            $result = $pdo->query($sql);

            // sql to grab part quantity/inventory amount
            $specPartQuan = $pdo2->prepare("SELECT quan_in_inv FROM PInventory WHERE inv_id = :part_number");

            echo "<center>";
            echo "<table border=1>";
            echo "<tr><th>Product Image</th><th>Product Name</th><th>Price of Product</th><th>Weight of Product</th><th>Qty in Cart</th><th>Change Quantity</th></tr>";

            // display the products in cart
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

                // use part_number to grab quantity in inventory 
                $specPartQuan->execute([':part_number' => $row['inv_id']]);
                $spQuan = $specPartQuan->fetch(PDO::FETCH_ASSOC);
                    
                if (isset($_POST['quantity']) && $_POST['quantity'] !== '') 
                {
                    $quantity = $_POST['quantity'];
                    $inv_id = $_POST['inv_id']; 
                        
                    $quan_in_inv = (int)$spQuan['quan_in_inv'];

                    // if quantity in inventory is less than quantity selected 
                    if ($quan_in_inv < $quantity)
                    {
                        echo "<h2>Not enough in stock.</h2>";
                    }
                    else if ($quantity == 0) // removing product
                    {
                        // delete from cart
                        $remove_prod = $pdo2->prepare("DELETE FROM PProdInCart WHERE inv_id = :inv_id");
                        $remove_prod->execute([':inv_id' => $inv_id]);

                        // set removed to true 
                        $removed = true; 
                    } 
                    else if ($quan_in_inv > $quantity && $quantity != 0) // theres enough in inventory and we're not removing 
                    {
                        // else update the quantity 
                        $update_prod = $pdo2->prepare("UPDATE PProdInCart SET quan_in_order = :quantity WHERE inv_id = :inv_id");
                        $update_prod->execute([':quantity' => $quantity, ':inv_id' => $inv_id]);

                        // set updated to true 
                        $updated = true;
                    }
                }
            }

            echo "</table>";
            echo "</center>";

            // if an item was removed or updated, display the refresh cart message 
            if ($removed == true) 
            {
                echo "<h4><center>Product removed from cart! Refresh to see updated cart.</h4></center>";
            }

            if ($updated == true)
            {
                echo "<h4><center>Product quantity updated from cart! Refresh to see updated cart.</h4></center>";                   
            }

            // sql query for quantity of product 
            $q_cart_total = "SELECT inv_id, quan_in_order FROM PProdInCart";
            $q_cart_res = $pdo2->query($q_cart_total);

            // sql query for grabbing product number and total price 
            $p_cart_total = $pdo->prepare("SELECT number, price, weight FROM parts WHERE number = :inv_id");

            // set cart and weight total to 0 
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

            if ($cart_total == 0)
            {
                echo "<center>";
                echo "<h3>";
                echo "Cart is empty.";  
                echo "</h3>";    
                echo "</center>";         
            }
            else 
            {
                echo "<center>";
                echo "<h3>";
                echo "<center>Cart Total: $$cart_total </center>";
                echo "<center>Weight Total: $weight_total lbs</center>";
                echo "</h3>";
                echo "</center>";  
            }              
        }
        catch (PDOException $e) 
        {
            echo "Connection failed: " . $e->getMessage();
        }
    ?>
        
    <!--HTML Forms for handling/taking in customer information with CSS styles-->
    <form action="" method="POST">
        <div class="space">
            <div class ="checkout_box">
                <div class="ch_header">Checkout</div>
                    <div class="input-box">
                        <div class="text">
                            <label for='name'>Name:</label>
                            <input type="text" name="name" size="30" placeholder="John Doe">
                        </div>

                        <div class="text"> 
                            <label for='email'>Email:</label>
                            <input type="text" name="email" size="30" placeholder="johndoe@gmail.com">
                        </div>               
            
                        <div class="text">                 
                            <label for='ship_addr'>Shipping Address:</label>
                            <input type="text" name="ship_addr" size="30" placeholder="1234 Generic Address Town, State ZIP">
                        </div>
                
                        <div class="text">                 
                            <label for='cc_num'>Credit Card Number:</label>
                            <input type="text" name="cc_num" size="30" placeholder="1234 1234 1234 1234">
                        </div>                   

                        <div class="text">                 
                            <label for='cc_exp'>Credit Card Expiration:</label>
                            <input type="text" name="cc_exp" size="5" placeholder="MM/YYYY">
                        </div>
            </div>   <!--close checkout_box div-->
                        <center>
                        <input type='submit' name='submit' value='Purchase' class="purchase">
                        </center>
 
    </form>

    <!--PHP Code for credit card autorization based on inputted values above--> 
    <?php 

        echo "REMOVE THIS LINE!!! For testing purposes: Credit cart to use: 6011 1234 4321 1234 and expiration date: 12/2024";
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
            if (isset($response['authorization']) && $cart_total != 0)
            {
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
                $gen_order = $pdo2->prepare("INSERT INTO POrders (order_num, date_placed, cust_name, email, order_status, shipping_addr, total_price, total_weight, cart_id) VALUES (:order_number, :cur_date, :name, :email, 'pending', :ship_addr, :cart_total, :weight_total, '12345')");
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

                // script to redirect to order confirmation page with variables
                $auth_num = $response['authorization'];
                $auth_php = "authorized.php?auth_num=$auth_num&order_number=$order_number&email=$email&cart_total=$cart_total&name=$name";
                echo "<script>window.location.href = '$auth_php';</script>";
                exit(); 
            }
            else if (!isset($response['authorization']) || $cart_total == 0)
            {
                echo "<center><div class='failed'>Transaction Failed. Please ensure card is not empty, and that a valid card number and expiration has been entered.</div></center>"; 
            }
        }
    ?>

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
