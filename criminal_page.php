<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criminal Details</title>
    <link rel="stylesheet" href="../PROJECTDIREC/styles/criminal.css">
</head>
<body>
    <h1>Criminal Details</h1>

    <?php
    session_start(); // Start the session to access session variables

    // Determine if the user is an admin
    $is_admin = isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin';

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "final_project";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the criminal ID is set in the GET parameters
    if(isset($_GET['criminalID']) && is_numeric($_GET['criminalID'])) {
        $criminalID = $conn->real_escape_string($_GET['criminalID']);

        // Fetching basic criminal details
        $sql = "SELECT c.Criminal_ID, c.First, c.Last, c.Street, c.City, c.State, c.P_status, c.Phone, a.Alias 
        FROM Criminals AS c
        LEFT JOIN Alias AS a ON a.Criminal_ID = c.Criminal_ID
        WHERE c.Criminal_ID = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $criminalID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<section>";
                echo "<h2>Name</h2><p id='name'>" . htmlspecialchars($row["First"]) . " " . htmlspecialchars($row["Last"]) . "</p>";
                echo "<h2>ID</h2><p>" . htmlspecialchars($row["Criminal_ID"]) . "</p>";

                if ($is_admin) {
                    echo "<h2>Address</h2><p>" . htmlspecialchars($row["Street"]) . ", " . htmlspecialchars($row["City"]) . ", " . htmlspecialchars($row["State"]) . "</p>";
                    echo "<h2>Phone Number</h2><p>" . htmlspecialchars($row["Phone"]) . "</p>";
                    echo "<h2>Probation Status</h2><p>" . htmlspecialchars($row["P_status"]) . "</p>";
                }

                echo "<h2>Alias</h2><p>" . htmlspecialchars($row["Alias"]) . "</p>";

                // Display Crimes linked to the criminal
                $sqlCrimes = "SELECT Crime_ID FROM Crimes WHERE Criminal_ID = ?";
                if ($stmtCrimes = $conn->prepare($sqlCrimes)) {
                    $stmtCrimes->bind_param("i", $criminalID);
                    $stmtCrimes->execute();
                    $resultCrimes = $stmtCrimes->get_result();
                    echo "<h2>Crimes</h2>";
                    while ($crimeRow = $resultCrimes->fetch_assoc()) {
                        echo "<p><a href='crime_page.php?crimeID=" . htmlspecialchars($crimeRow['Crime_ID']) . "'>" . htmlspecialchars($crimeRow['Crime_ID']) . "</a></p>";
                    }
                    $stmtCrimes->close();
                }

                // Display Sentences linked to the criminal
                $sqlSentences = "SELECT Sentence_ID FROM Sentences WHERE Criminal_ID = ?";
                if ($stmtSen = $conn->prepare($sqlSentences)) {
                    $stmtSen->bind_param("i", $criminalID);
                    $stmtSen->execute();
                    $resultSen = $stmtSen->get_result();
                    echo "<h2>Sentences</h2>";
                    while ($senRow = $resultSen->fetch_assoc()) {
                        echo "<p><a href='sentences.php?sentenceID=" . htmlspecialchars($senRow['Sentence_ID']) . "'>" . htmlspecialchars($senRow['Sentence_ID']) . "</a></p>";
                    }
                    $stmtSen->close();
                }

                echo "</section>";
            } else {
                echo "<p>No results found for ID " . htmlspecialchars($criminalID) . ".</p>";
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
        $conn->close();
    } else {
        echo "<p>Please enter a valid Criminal ID to search.</p>";
    }
    ?>

</body>
</html>
