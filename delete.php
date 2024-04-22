<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Criminal Record</title>
    <link rel="stylesheet" href="../PROJECTDIREC/styles/delete.css">
</head>
<body>
    <h1>Delete Criminal Record</h1>
    <form action="delete.php" method="post">
        <label for="criminalID">Enter Criminal ID:</label>
        <input type="text" id="criminalID" name="criminalID" required>
        <button type="submit">Delete Criminal</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Database credentials
        $servername = "localhost";
        $username = "root"; // Replace with your database username
        $password = ""; // Replace with your database password
        $dbname = "final_project";

        // Create database connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check the database connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $criminalID = $_POST['criminalID'];

        // Validate the input to ensure it is a numeric value
        if (!is_numeric($criminalID)) {
            echo "Criminal ID must be a number.";
        } else {
            // Prepare the DELETE SQL statement
            $stmt = $conn->prepare("DELETE FROM Criminals WHERE Criminal_ID = ?");
            if (!$stmt) {
                echo "Prepare failed: " . htmlspecialchars($conn->error);
            } else {
                // Bind the integer type criminal ID to the statement
                $stmt->bind_param("i", $criminalID);

                // Execute the statement and check if it was successful
                if ($stmt->execute()) {
                    // Check if any rows were actually affected
                    if ($stmt->affected_rows > 0) {
                        echo "Criminal record deleted successfully.";
                    } else {
                        echo "No criminal found with ID $criminalID or already deleted.";
                    }
                } else {
                    echo "Execute failed: " . htmlspecialchars($stmt->error);
                }

                // Close the prepared statement
                $stmt->close();
            }

            // Close the database connection
            $conn->close();
        }
    }
    ?>
</body>
</html>
