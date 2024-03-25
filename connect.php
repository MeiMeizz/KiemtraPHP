<?php
    $servername = "localhost";
    $username = "root"
    $password = "";

    // create connection
    $conn = new mysqli($servername, $username, $password);

    // check connection
    if($conn->connect_error) {
        die("Connection failed: ". $coon->connect_error);
    }
    echo "Connected successfully"
    ?>