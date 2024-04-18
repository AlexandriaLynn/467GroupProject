<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
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

        /*display authorization box message*/
        h3
        {
            background-color: white;
            border-radius: 10px;
            padding: 2em;
            text-align: center;
            margin-top: 300px;
            border: 5px solid #5f6c7d;
            display: inline-block;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    <nav_bar>
        <div class="title">Thank You!</div>
        <ul class="headers">
            <!-- Nav to go to shopping cart of product list/view catalog-->
            <li><a href='view_catalog.php'>Return to Product List</a></li>
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
    <?php
        // grab variables from shoppingcart.php 
        if(isset($_GET['auth_num']) && isset($_GET['order_number']) && isset($_GET['email']) && isset($_GET['cart_total']) && isset($_GET['name'])) 
        {
            // grab variables 
            $auth_num = $_GET['auth_num'];
            $order_number = $_GET['order_number'];
            $email = $_GET['email'];
            $cart_total = $_GET['cart_total'];
            $name = $_GET['name'];

            echo "<center>";
            echo "<h3>";
            // Display the retrieved information
            echo "Thank's for your order $name!";
            echo "<p>An email confirmation has been sent to $email</p>";
            echo "<p>You can track your order with the order number: $order_number</p>";
            echo "<p>Total Amount: $$cart_total</p>";
            echo "<p>Authorization Number: $auth_num</p>";
            echo "</h3>";
            echo "</center>";
        } 
    ?>

</main>
</body>
</html>