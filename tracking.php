<!DOCTYPE html>
<html>
<head>
    <title>Track Order</title>
    <style>
    
        /*formats entire page*/
        *
        {
            margin: 0px; 
            padding: 0px; 
            box-sizing: border-box; 
        }
       /*background of products*/
       .product-list
        {  
            background-color: #dfe6e9;
            padding:3em; 
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

        /* header items overall*/
        .headers
        {
            width: 65%; 
            display: flex; 
            justify-content: right;
            list-style: none;
        }

        /*header list and link items*/ 
        .headers li a
        {
            margin-left: 20px; 
            font-size: 19px; 
            color: #fff;
            text-decoration: none; 
            transition: 0.3s ease; 
        }

        /*hover over header items*/
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
        .found
        {
            background-color: white;
            border-radius: 10px;
            padding: 2em;
            text-align: center;
            margin-top: 50px;
            border: 5px solid #5f6c7d; 
            display: inline-block;
            line-height: 1.5;
//            margin-left: 790px;
            font-weight: bold; 
            font-size: 20px; 
        }

        /*order not found css*/
        .not_found
        {
            background-color: white;
            border-radius: 10px;
            padding: 2em;
            text-align: center;
            margin-top: 50px;
            border: 5px solid #FF0000;
            display: inline-block;
            line-height: 1.5;
//            margin-left: 835px;
            font-weight: bold; 
            font-size: 20px; 
        }

        /*formats the entire checkout form*/
        .checkout_box
        {
            max-width: 500px; 
            height: auto; 
            margin: 175px auto 10px;
            background: #fff;
        }

        /*checkout title*/
        .ch_header
        {
            color: #5f6c7d;
            font-size: 2em; 
            text-align: center;
            font-weight: 900; 
            margin-bottom: 10px; 
            margin-top:175px;
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

        /*track button*/
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
    </style>
</head>
<body>
    <nav_bar>
        <div class="title">Track Order</div>
        <ul class="headers">
            <!-- Nav to go to shopping cart of product list/view catalog-->
            <li><a href='order_search.php'>Forgot Order Number?</a></li>
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

<!--Html form for tracking order with CSS-->
<form action="" method="POST">
    <div class="space">
        <div class ="checkout_box">
            <div class="ch_header">Enter Order Number</div>
                <div class="input-box">
                    <div class="text">
                        <input type="text" name="order_number" size="30" placeholder="123412341234">
                    </div>
                </div>   <!--close checkout_box div-->
                <center>
                <input type='submit' name='submit' value='Track' class="purchase">
                </center>
    </div>
    </div>
</form>

<?php
    include("secrets.php");

    try 
    {
        // connect to mariadb 
        $dsn2 = "mysql:host=courses;dbname=$dbname";
        $pdo2 = new PDO($dsn2, $username, $password);

        // if order number form is not empty
        if (!empty($_POST["order_number"]))
        {
            // grab order number from form 
            $order_number = $_POST['order_number'];

            // sql to grab order number from table and the status 
            $check_order = $pdo2->prepare("SELECT order_num, order_status FROM POrders WHERE order_num = :order_number");
            $check_order->execute([':order_number' => $order_number]);

            // if order is found in table 
            if ($check_order->rowCount() > 0) 
            {
                // output status and order number 
                while ($row = $check_order->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<center><div class='found'>";
                    echo "<p>Order Number: " . $row['order_num'] . "</p>";
                    // change these from sql table values just for better user readability
                    if ($row['order_status'] == 'Complete')
                    {
                        echo "Status: Shipped";        
                    }
                    else if ($row['order_status'] == 'Pending')
                    {
                        echo "Status: Processing";                         
                    }
                    echo "</div></center>";
                }
            }
            else 
            {
                echo "<center><div class='not_found'>Order not found</div></center>";
            }
        }
    }
    catch (PDOException $e) 
    {
        echo "Connection failed: " . $e->getMessage();
    }
?>
</main>
</body>
</html>
