<?php
    include 'connection.php';
    
    $commentNewCount = $_POST['commentNewCount'];
    $sql = "SELECT * FROM comments LIMIT $commentNewCount";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)){
        echo "<p>";
        echo $row['author'];
        echo "<br>";
        echo $row['message'];
        echo "</p>";
    }
?>