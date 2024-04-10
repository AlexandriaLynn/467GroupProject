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
        <div class="navbar-right"><li><a href='login.php'>Employee or Admin? Log in.</a></li></div>
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
                $cart_display = "Select * FROM PProdInOrder";
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
                        echo"<input type ='text' name='quantity' size='3'>";
                        echo"<input type='submit' value='Update'/>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "</center>";
            }
            catch (PDOException $e) 
            {
                echo "Connection failed: " . $e->getMessage();
            }
        ?>
        <!--
            Code here to update quantity/remove from cart
        -->

        <!--HTML Forms for handling/taking in customer information-->
        <center>
            <form action="" method="POST">

                <p>
                <label for='name'>Name:</label>
                <input type="text" name="name" size="30">
                </p>

                <p>
                <label for='name'>Email:</label>
                <input type="text" name="email" size="30">
                </p>
            
                <p>
                <label for='name'>Shipping Address:</label>
                <input type="text" name="ship_addr" size="30">
                </p>

                <p>
                <label for='name'>Credit Card Number:</label>
                <input type="text" name="cc_num" size="30">
                </p>

                <p>
                <label for='name'>Credit Card Expiration:</label>
                <input type="text" name="cc_exp" size="3">
                </p>

            <input type='submit' name='submit' value='Confirm Order'>
            </form>
        </center>
    </body>
    </html>            