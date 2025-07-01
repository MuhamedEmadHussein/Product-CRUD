<?php
include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <script src="https://code.jquery.com/jquery-3.7.1.js"
            integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
        <script>
        $(document).ready(function() {
            var commentCount = 2;
            $("#btn").click(function() {
                commentCount += 2;
                $("#comments").load("load-comments.php", {
                    commentNewCount: commentCount
                });
            });
        });
        </script>
        <style>
        #comments {
            max-width: 800px;
            margin: 20px auto;
            padding: 15px;
            background-color: #f5f5f5;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        #btn:hover {
            background-color: #45a049;
        }
        </style>
    </head>

    <body>
        <div id="comments">
            <?php
            $sql = "SELECT * FROM comments LIMIT 2";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_assoc($result)){
                echo "<p>";
                echo $row['author'];
                echo "<br>";
                echo $row['message'];
                echo "</p>";
            }
            ?>
        </div>
        <button id="btn">Show Comments</button>
    </body>

</html>