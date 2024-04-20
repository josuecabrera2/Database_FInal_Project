<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/second_page.css">
</head>
<body>
    <h1>Login</h1>
    <form method="POST">
        <label for="perm_id">Permission ID:</label>
        <input type="text" id="perm_id" name="perm_id" required>
        <label for="psw">Password:</label>
        <input type="password" id="psw" name="psw" required>
        <button type="submit">Login</button>
    </form>

    <?php
    session_start();
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $servername = "localhost";
        $username = "root";
        $password = "";  // Database connection password
        $dbname = "Criminals";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $perm_id = $conn->real_escape_string($_POST["perm_id"]);
        $psw = $conn->real_escape_string($_POST["psw"]);

        $sql = "SELECT * FROM Permissions WHERE perm_id = ? AND psw = ?";

        // Prepared statement to prevent SQL Injection
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $perm_id, $psw);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Credentials are correct
                $_SESSION['perm_id'] = $perm_id;  // Store permission ID in session
                header("Location: criminal_search.php"); // Redirect to the search page
                exit();
            } else {
                // Credentials are incorrect
                echo "<p>Invalid Permission ID or Password.</p>";
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
