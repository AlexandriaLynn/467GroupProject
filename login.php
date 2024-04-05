<html>
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>

      /*Formatting*/
      body 
      {
        font-family: "Times New Roman", Times, serif;
        margin: 0;
        padding: 0;
      }

      /*Hover on nav bar*/
      .topnav a:hover 
      {
        background-color: #D3D3D3;
        color: black;
      }

        /*nav bar*/
      .topnav 
      {
        padding: 8px 2%;
        background-color: #f3f4f6;
        box-shadow: 0 0 14px rgba(0,0,0,0.3);
      }

      .topnav a 
      {
        color: #424144;
        text-decoration: none;
        font-size: 20px;
        font-weight: bold;
        margin-left: 845px;
        margin-top: 20px;
      }
    </style>
    </head>

    <body>
    <div class="topnav">
        <!-- Nav go back to main page or go to checkout-->
        <a href='view_catalog.php'>Return to Product List</a>
    </div>

    <?php
          echo '<h1><center>Login</center></h1>';
          echo '<h2><center>Enter Admin or Employee ID<center></h2>';

          echo '<form align="center" method="POST" action="">';

          //Single line textbox to enter email
          echo '<label for="login">ID: </label>';
          echo '<input type ="text" name="login">';
          echo '<input type="submit"/>';
    
          echo '</form>';
    

    ?>