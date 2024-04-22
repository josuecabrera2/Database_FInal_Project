<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../PROJECTDIREC/styles/login.css">
</head>
<body>
    <h1>Login</h1>

    <!-- Login Form for Admin -->
    <form method="POST" action="">
        <label for="perm_id">ID:</label>
        <input type="text" id="perm_id" name="perm_id" required>
        <label for="psw">Password:</label>
        <input type="password" id="psw" name="psw" required>
        <button type="submit" name="action" value="login">Login</button>
    </form>

    <!-- Separate Form for Guest Access -->
    <form method="POST" action="">
        <button type="submit" name="action" value="guest">Guest</button>
    </form>

    <?php
    session_start();
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['action']) && $_POST['action'] == 'guest') {
            $_SESSION['user_role'] = 'guest'; // Set session variable for guest
            header("Location: search.php"); // Assuming search.php is a non-sensitive info page
            exit();
        }

        if (isset($_POST['action']) && $_POST['action'] == 'login') {
            $servername = "localhost";
            $username = "root";
            $password = "";  // Database connection password
            $dbname = "final_project";

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
                    $_SESSION['user_role'] = 'admin';  // Set session variable for admin
                    $_SESSION['perm_id'] = $perm_id;  // Store permission ID if needed for further use
                    header("Location: search_admin.php"); // Redirect to a page with full admin privileges
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
    }
    ?>
</body>
</html>
