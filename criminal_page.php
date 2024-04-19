<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criminal Details</title>
    <link rel="stylesheet" href="css/second_page.css">
</head>
<body>
    <h1>Criminal Details</h1>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";  // If you don't have a password, this should be empty ''
    $dbname = "Criminals";  // Make sure this is the correct database name

    // Create connection3
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT c.Criminal_ID, c.First, c.Last, c.Street, c.City, c.State, c.P_status, c.Phone, a.Alias 
    FROM Criminals AS c
    JOIN Alias AS a ON a.Criminal_ID = c.Criminal_ID 
    WHERE c.Criminal_ID = 100003";

    // $sql = "SELECT Criminal_ID, first, last from Criminals where Criminal_ID = 100001";

    // $sql = "SELECT Alias from Alias where Criminal_ID = 100001";
    
    $result = $conn->query($sql);

    // echo "Great success!";
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<section>";
            echo "<h2>Name</h2>";
            echo "<p id='name'>" . $row["First"] . " " . $row["Last"] . "</p>";
            echo "<h2>ID</h2>";  // Add additional details as needed
            echo "<p>". $row["Criminal_ID"] . "</p>";
            echo "<h2>Address</h2>";
            echo "<p>".$row["City"]."</p>";
            echo "<h2>Phone Number</h2>";
            echo "<p>".$row["Phone"]."</p>";
            echo "<h2>Alias</h2>";
            echo "<p>".$row["Alias"]."</p>";
            echo "<h2>Probabtion Status</h2>";
            echo "<p>".$row["P_status"]."</p>";
            // You can add more details here similarly
    
        }
    } else {
        echo "<p>No results found.</p>";
    }
    $conn->close();
    ?>

</body>
</html>
