<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Criminal Details</title>
    <link rel="stylesheet" href="css/second_page.css">
</head>
<body>
    <h1>Search for Criminal</h1>

    <form action="criminal_page.php" method="GET">
        <label for="criminalID">Enter Criminal ID:</label>
        <input type="text" id="criminalID" name="criminalID" required>
        <button type="submit">Search</button>
    </form>

    <?php
    // Add an Edit button
    echo "<button onclick='location.href=\"edit_page.php?criminalID=" . $row["Criminal_ID"] . "\"'>Edit</button>";
    echo "</section>";
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["criminalID"])) {
        $servername = "localhost";
        $username = "root";
        $password = "";  // If you don't have a password, this should be empty
        $dbname = "Criminals";  // Make sure this is the correct database name

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $criminalID = $conn->real_escape_string($_GET["criminalID"]);
        $sql = "SELECT c.Criminal_ID, c.First, c.Last, c.Street, c.City, c.State, c.P_status, c.Phone, a.Alias 
        FROM Criminals AS c
        INNER JOIN Alias AS a ON a.Criminal_ID = c.Criminal_ID 
        WHERE c.Criminal_ID = ?";

        // Prepared statement to prevent SQL Injection
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $criminalID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<section>";
                    echo "<h2>Name</h2><p id='name'>" . $row["First"] . " " . $row["Last"] . "</p>";
                    echo "<h2>ID</h2><p>" . $row["Criminal_ID"] . "</p>";
                    echo "<h2>Address</h2><p>" . $row["Street"] . ", " . $row["City"] . ", " . $row["State"] . "</p>";
                    echo "<h2>Phone Number</h2><p>" . $row["Phone"] . "</p>";
                    echo "<h2>Alias</h2><p>" . $row["Alias"] . "</p>";
                    echo "<h2>Probation Status</h2><p>" . $row["P_status"] . "</p>";
                
                }
            } else {
                echo "<p>No results found for ID " . $criminalID . ".</p>";
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
        $conn->close();
    }
    ?>

</body>
</html>
