<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Details</title>
    <link rel="stylesheet" href="css/crime_page.css">
</head>
<body>
    <h1>Crime Details</h1>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";  // Use the actual password, if any
    $dbname = "Criminals";  // Ensure this is the correct database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Assume the Criminal_ID is known, e.g., from a query parameter or a session
    //$criminalID = 100003;  // Static value for demonstration; typically you'd get this from input

    $sql = "SELECT Crime_ID, Classification, Date_charged, Status, Hearing_date
            FROM Crimes
            WHERE Criminal_ID = 100003";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<section>";
            echo "<h2>Crime ID</h2>";
            echo "<p>" . $row["Crime_ID"] . "</p>";
            echo "<h2>Classification</h2>";
            echo "<p>" . $row["Classification"] . "</p>";
            echo "<h2>Date Charged</h2>";
            echo "<p>" . $row["Date_charged"] . "</p>";
            echo "<h2>Status</h2>";
            echo "<p>" . $row["Status"] . "</p>";
            echo "<h2>Hearing Date</h2>";
            echo "<p>" . $row["Hearing_date"] . "</p>";
            echo "</section>";
        }
    } else {
        echo "<p>No crime details found.</p>";
    }
    $conn->close();
    ?>

</body>
</html>
