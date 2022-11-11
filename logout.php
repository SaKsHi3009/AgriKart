<?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "agrikartdb";
        
        $conn = new mysqli($servername, $username, $password, $dbname);
        $sql = "TRUNCATE TABLE `agrikartdb`.`user_curr`";
        if ($conn->query($sql) === TRUE) {

            header("location:Home.php");
        }
?>