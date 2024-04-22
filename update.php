<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Records</title>
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

    </style>
</head>
<body>
    <div class="container">
        <form action="update.php" method="post" class="form-section">
            <h2>Update Criminal Details</h2>
            <input type="hidden" name="form_type" value="criminal">
            <input type="text" name="criminalID" placeholder="Criminal ID" required>
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="text" name="street" placeholder="Street">
            <input type="text" name="city" placeholder="City">
            <input type="text" name="state" placeholder="State">
            <input type="text" name="zip" placeholder="Zip">
            <input type="text" name="phone" placeholder="Phone">
            <input type="text" name="vstatus" placeholder="Vstatus (Y or N)">
            <input type="text" name="pstatus" placeholder="Pstatus (Y or N)">
            <button type="submit">Update Criminal</button>
        </form>

        <form action="update.php" method="post" class="form-section">
            <h2>Update Crime Details</h2>
            <input type="hidden" name="form_type" value="crime">
            <input type="text" name="criminalID" placeholder="Criminal ID" required>
            <input type="text" name="crimeID" placeholder="Crime ID" required>
            <input type="text" name="classification" placeholder="Classification" required>
            <input type="text" name="dateCharged" placeholder="Date Charged" required>
            <input type="text" name="status" placeholder="Status" required>
            <input type="text" name="hearingDate" placeholder="Hearing Date" required>
            <input type="text" name="appealCutDate" placeholder="Appeal Cut Date" required>
            <button type="submit">Update Crime</button>
        </form>
    </div>
</body>
</html>
<?php

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

// Check if the form has been submitted and the form type is 'criminal'
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $_POST['form_type'] === 'criminal') {
    // Prepare an update statement
    $stmt = $conn->prepare("UPDATE Criminals SET First=?, Last=?, Street=?, City=?, State=?, Phone=?, V_status=?, P_status=? WHERE Criminal_ID=?");

    // Check if the statement was prepared correctly
    if (false === $stmt) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    // Bind variables to the prepared statement as parameters
    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $phone = $_POST['phone'];
    $v_status = $_POST['vstatus'];
    $p_status = $_POST['pstatus'];
    $criminal_id = $_POST['criminalID']; // This should be coming from your form

    $bind = $stmt->bind_param("ssssssssi", $first, $last, $street, $city, $state, $phone, $v_status, $p_status, $criminal_id);

    // Check if the parameters were bound successfully
    if (false === $bind) {
        die("Bind failed: " . htmlspecialchars($stmt->error));
    }

    // Execute the prepared statement
    $execute = $stmt->execute();

    // Check if the execute was successful
    if (false === $execute) {
        die("Execute failed: " . htmlspecialchars($stmt->error));
    } else {
        echo "Record updated successfully";
    }

    // Close the prepared statement
    $stmt->close();
} 

// Close the database connection
$conn->close();
?>
