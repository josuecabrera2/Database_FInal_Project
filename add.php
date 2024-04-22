<?php

session_start();
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Records</title>
    <style>
        .container {
            display: flex; /* Use flexbox to lay out children horizontally */
            justify-content: space-between; /* Distributes space between and around content items */
            align-items: flex-start; /* Align items at the start of the flex container */
            padding: 20px; /* Add padding around the container */
        }
        .form-section {
            flex: 1; /* Each form section takes equal space */
            margin: 0 20px; /* Add margin for spacing between forms */
            display: flex;
            flex-direction: column; /* Stack the form inputs vertically */
        }
        input, button {
            margin-bottom: 10px; /* Space between form elements */
            width: 100%; /* Make input and buttons expand to the full width of the form section */
        }
        button {
            cursor: pointer; /* Indicates the button is clickable */
        }

        .search-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            height: 200px;
            max-width: 600px; /* Adjust based on your preference */
            margin-top: 5%;
            flex-direction: column;
            align-items: stretch;

        }

        input[type="text"] {
            width: calc(100% - 100px); /* Adjust the width */
            padding: 10px;
            margin: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }  

        button {
            padding: 10px 20px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.2s;
            
        }

        body {
            overflow: auto;

            font-family: 'Arial', sans-serif;
            
            background-image: url('../images/criminal_page.png'); /* Path to your background image */
            
            background-color: #f0f0f0; /* Fallback color */

        }

        h1 {
        color: #333; /* Dark grey color for headings */
        margin: 20px 0; /* Space above and below the heading */
        }

        h2 {
            color:#333; /* Lighter grey for sub-headings */
            border-bottom: 2px solid #eee; /* Light line under sub-headings */
            padding-bottom: 5px; /* Space under the sub-heading text */
            margin-bottom: 10px; /* Space below the line */
        }



        .back-button {
            position: absolute;  /* Positioning it relative to the nearest positioned ancestor */
            bottom: 20px;  /* Adjust as needed */
            right: 10%;  /* Adjust as needed */
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #722f37;
            color: white;
            cursor: pointer;
            transition: background-color 0.2s;
            width: 15%;
        }

    </style>
</head>
<body>
    <div class="container">
        <form action="add.php" method="post" class="form-section">
            <h2>Add Criminal</h2>
            <input type="hidden" name="form_type" value="criminal">
            <input type="text" name="Criminal_ID" placeholder="Criminal ID" required>
            <input type="text" name="First" placeholder="First Name" required>
            <input type="text" name="Last" placeholder="Last Name" required>
            <input type="text" name="Street" placeholder="Street">
            <input type="text" name="City" placeholder="City">
            <input type="text" name="State" placeholder="State">
            <input type="text" name="Zip" placeholder="Zip">
            <input type="text" name="Phone" placeholder="Phone">
            <input type="text" name="V_status" placeholder="Vstatus (Y or N)">
            <input type="text" name="P_status" placeholder="Pstatus (Y or N)">
            <button type="submit">Add Criminal</button>
        </form>

        <form action="add.php" method="post" class="form-section">
            <h2>Add Crime</h2>
            <input type="hidden" name="form_type" value="crime">
            <input type="text" name="Crime_ID" placeholder="Crime ID" required>
            <input type="text" name="Criminal_ID" placeholder="Criminal ID" required>
            <input type="text" name="Classification" placeholder="Classification [F (Felony), M (Misdemeanor), O (Other), U (Undefined)]" required>
            <input type="text" name="Date_charged" placeholder="Date Charged" required>
            <input type="text" name="Status" placeholder="Status [CL (Closed), CA (Can Appeal), IA (In Appeal)]" required>
            <input type="text" name="Hearing_date" placeholder="Hearing Date" required>
            <input type="text" name="Appeal_cut_date" placeholder="Appeal Cut Date" required>
            <button type="submit">Add Crime</button>
        </form>

        <button onclick="window.location.href='edit.php';" class="back-button">Back to Edit</button>

    </div>
</body>
</html>

<?php
//session_start();
// Include your database connection file

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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["form_type"]) && $_POST["form_type"] == "criminal") {
    // Sanitize input
    $criminalID = $conn->real_escape_string($_POST["Criminal_ID"]);
    $firstName = $conn->real_escape_string($_POST["First"]);
    $lastName = $conn->real_escape_string($_POST["Last"]);
    $phone = $conn->real_escape_string($_POST["Phone"]);  // Ensure 'phone' input is part of your form
    $street = $conn->real_escape_string($_POST["Street"]);
    $city = $conn->real_escape_string($_POST["City"]);
    $state = $conn->real_escape_string($_POST["State"]);
    $zip = $conn->real_escape_string($_POST["Zip"]);
    $vstatus = $conn->real_escape_string($_POST["V_status"]);
    $pstatus = $conn->real_escape_string($_POST["P_status"]);

    

    // SQL to check existing records
    $sql = "SELECT * FROM Criminals WHERE First = ? AND Last = ? AND Phone = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $firstName, $lastName, $phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If a record exists, output failure message
                echo "<p>Unsuccessful: A criminal with these details already exists.</p>";
        }
        else{
            if (!($vstatus == 'Y' || $vstatus == 'N') || !($pstatus == 'Y' || $pstatus == 'N')) {
                echo "<p>Wrong pstatus or vstatus.</p>";
            }
            else {
                $conn->begin_transaction();
                try{
                    // Insert new record if vstatus and pstatus are correct
                    $insertSql = "INSERT INTO Criminals (Criminal_ID, Last, First, Street, City, State, Zip, Phone, V_status, P_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    if ($insertStmt = $conn->prepare($insertSql)) {
                        $insertStmt->bind_param("ssssssssss", $criminalID, $lastName, $firstName, $street, $city, $state, $zip, $phone, $vstatus, $pstatus);
                        if ($insertStmt->execute()) {
                            echo "<p>Successful: New criminal added.</p>";
                            $conn->commit();
                        } else {
                            echo "<p>Error: Could not execute the insert statement. " . $insertStmt->error . "</p>";
                        }
                    }
                    $insertStmt->close();
                }
                catch (Exception $e) {
                    $conn->rollback();
                    echo "Error adding records: " . $e->getMessage();
                }
            }

        }
        $stmt->close();
    } 
} 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["form_type"]) && $_POST["form_type"] == "crime") {
    // Sanitize input
    $crimeID = $conn->real_escape_string($_POST["Crime_ID"]);
    $criminalID = $conn->real_escape_string($_POST["Criminal_ID"]);
    $classi = $conn->real_escape_string($_POST["Classification"]);
    $dateC = $conn->real_escape_string($_POST["Date_charged"]);
    $status = $conn->real_escape_string($_POST["Status"]);  // Ensure 'phone' input is part of your form
    $hear = $conn->real_escape_string($_POST["Hearing_date"]);
    $appeal = $conn->real_escape_string($_POST["Appeal_cut_date"]);
    

    // SQL to check existing records
    $sql = "SELECT * FROM Crimes WHERE Criminal_ID = ? AND Crime_ID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $criminalID, $crimeID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If a record exists, output failure message
                echo "<p>Unsuccessful: A criminal with these details already exists.</p>";
        }
        else{
            //checker if we need alter
            if (!($classi == 'F' || $classi == 'M' || $classi == 'O' || $classi == 'U') || !($status == 'CL' || $status == 'CA' || $status == 'IA' )) {
                echo "<p>Wrong classification or status.</p>";
            }
            else{
                // Insert new record if vstatus and pstatus are correct

                $conn->begin_transaction();
                try{
                    // Insert new record if vstatus and pstatus are correct
                    $insertSql = "INSERT INTO Crimes (Crime_ID, Criminal_ID, Classification, Date_charged, Status, Hearing_date, Appeal_cut_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    if ($insertStmt = $conn->prepare($insertSql)) {
                        $insertStmt->bind_param("sssssss", $crimeID, $criminalID, $classi, $dateC, $status, $hear, $appeal);
                        if ($insertStmt->execute()) {
                            echo "<p>Successful: New crime added.</p>";
                            $conn->commit();
                        } else {
                            echo "<p>Error: Could not execute the insert statement. " . $insertStmt->error . "</p>";
                        }
                    }
                    $insertStmt->close();
                }
                catch (Exception $e) {
                    $conn->rollback();
                    echo "Error adding records: " . $e->getMessage();
                }
            }
        }
        $stmt->close();
    } 
} 

$conn->close();

?>
