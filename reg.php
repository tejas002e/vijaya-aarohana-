<?php
// Database connection details
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; 
$dbname = "reg_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define variables and initialize with empty values
$fullname = $email = $phone = $uucmsNo = "";
$fullname_err = $email_err = $phone_err = $uucmsNo_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate fullname
    if (empty(trim($_POST["fullname"]))) {
        $fullname_err = "Please enter a name.";
    } else {
        $fullname = trim($_POST["fullname"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate phone
    if (empty(trim($_POST["phone"]))) {
        $phone_err = "Please enter a phone number.";
    } elseif (!preg_match('/^[0-9]{10}$/', trim($_POST["phone"]))) {
        $phone_err = "Please enter a valid 10-digit phone number.";
    } else {
        $phone = trim($_POST["phone"]);
    }

    // Validate UUCMS No
    if (empty(trim($_POST["UUCMSNo"]))) {
        $uucmsNo_err = "Please enter UUCMS No.";
    } else {
        $uucmsNo = trim($_POST["UUCMSNo"]);
    }

    // Check input errors before inserting in database
    if (empty($fullname_err) && empty($email_err) && empty($phone_err) && empty($uucmsNo_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO users (fullname, email, phone, uucmsNo) VALUES (?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssss", $param_fullname, $param_email, $param_phone, $param_uucmsNo);

            // Set parameters
            $param_fullname = $fullname;
            $param_email = $email;
            $param_phone = $phone;
            $param_uucmsNo = $uucmsNo;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to success page
                header("location: success.html");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <style>
        body {
            font-family: Arial, helvetica, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            background-color: #718cbf83;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        input[type="submit"]:hover {
            background-color: #ffc941;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="fullname">Name:</label>
            <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required>
            <span class="error"><?php echo $fullname_err; ?></span>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            <span class="error"><?php echo $email_err; ?></span>
            
            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
            <span class="error"><?php echo $phone_err; ?></span>
            
            <label for="UUCMSNo">UUCMS No:</label>
            <input type="text" id="UUCMSNo" name="UUCMSNo" value="<?php echo htmlspecialchars($uucmsNo); ?>" required>
            <span class="error"><?php echo $uucmsNo_err; ?></span>
            
            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
