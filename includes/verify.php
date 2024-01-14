<?php
session_start();
include('config.php');

// Initialize variables
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    if (isset($_POST['verification_code'])) {
        $enteredCode = $_POST['verification_code'];

        // Perform database update to mark the user as verified
        $sql = "UPDATE tblusers SET verified = 1 WHERE verification_code = :verificationCode";
        $query = $dbh->prepare($sql);
        $query->bindParam(':verificationCode', $enteredCode, PDO::PARAM_STR);
        $query->execute();

        // Check if the update was successful
        $rowCount = $query->rowCount();
        if ($rowCount > 0) {
            // Set session variable to indicate successful verification
            $_SESSION['verified'] = 1;

            // Redirect to confirmation page
            header('location: ../thankyou.php');
            exit();
        } else {
            // Display an error message
            $errorMessage = "Invalid verification code. Please try again.";
            echo '<script>';
            echo 'alert("Incorrect verification code. Please try again.");';
            echo '</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input {
            padding: 8px;
            margin-bottom: 15px;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <?php if (!empty($errorMessage)) : ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <form method="post" action="">
            <label for="verification_code">Enter Verification Code to verify your account: </label>
            <input type="text" id="verification_code" name="verification_code" required>
            <button type="submit">Verify</button>
        </form>
    </div>

</body>
</html>


