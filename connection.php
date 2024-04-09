<?php

    //Opening a connection with our database server using mysqli_connect
    $connection = mysqli_connect('courses', 'Your z-ID', 'Your Password');
    
    // Check if the connection was successful
    if(!$connection) {
        // If connection fails, terminate the script and display an error message
        die("Connection error");
    }

    // Selecting the database to work with using mysqli_select_db
    $database = mysqli_select_db($connection,"Your z-ID");

     // Check if the database selection was successful
    if(!$database) {
        // If database selection fails, terminate the script and display an error message
        die("Database connection error");
    }

?>
