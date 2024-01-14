<?php

// Include Composer autoloader
require 'vendor/autoload.php';
require 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Initialize variables
$errorMessage = "";

error_reporting(0);

session_start(); // Start the session

if (isset($_POST['submit'])) {
    $fname = $_POST['fname'];
    $mnumber = $_POST['mobilenumber'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $verificationCode = uniqid(); // Generate a unique verification code

    // Insert user data into the database
    $sql = "INSERT INTO tblusers (FullName, MobileNumber, EmailId, Password, verification_code) VALUES (:fname, :mnumber, :email, :password, :verificationCode)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':fname', $fname, PDO::PARAM_STR);
    $query->bindParam(':mnumber', $mnumber, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->bindParam(':verificationCode', $verificationCode, PDO::PARAM_STR);

    if ($query->execute()) {
        // Send verification email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Specify your SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'dharklike@gmail.com'; // SMTP username
            $mail->Password   = 'yweq hmqy knat zrdr'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port       = 587; // TCP port to connect to

            // Recipients
            $mail->setFrom('zairamaer@gmail.com', 'ExploreEra Support'); // Replace with your information
            $mail->addAddress($email, $fname); // Add recipient

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Verify Your Email';
            $mail->Body    = 'Welcome to ExploreEra! Enter the following code to verify your email: ' . $verificationCode;

            // Send the verification email
            $mail->send();
            echo 'Email has been sent successfully';

            $_SESSION['msg'] = "You are successfully registered. Please check your email to verify your account.";


        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$e->getMessage()}";
        }
    } else {
        // Display an error message
        $errorMessage = "Error in user registration. Please try again.";
        echo '<script>';
        echo 'alert("Error in user registration. Please try again.");';
        echo '</script>';
    }
}
?>


<!-- Javascript for check email availability -->
<script>


function checkAvailability() {
	
    $("#loaderIcon").show();
    jQuery.ajax({
        url: "check_availability.php",
        data:'emailid='+$("#email").val(),
        type: "POST",
        success:function(data){
            $("#user-availability-status").html(data);
            $("#loaderIcon").hide();
        },
        error:function (){}
    });
}

function validatePassword() {
    var password = document.forms["signup"]["password"].value;
    var passwordValidationMessage = document.getElementById("password-validation");

    // Check if the password meets the criteria
    var hasNumber = /\d/.test(password);
    var hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

    if (password.length < 8 || !hasNumber || !hasSpecialChar) {
        passwordValidationMessage.style.color = "red";
        passwordValidationMessage.innerHTML = "Password must be at least 8 characters long and include at least one number and one special character";
        document.getElementById("submit").disabled = true;
    } else {
        // Password is valid
        passwordValidationMessage.style.color = "green";
        passwordValidationMessage.innerHTML = "Password is valid";
        document.getElementById("submit").disabled = false;
    }
}

// Function to display verification code and redirect to verify.php on OK
function displayVerificationCode() {
    <?php if (!empty($verificationCode)) : ?>
        var verificationCode = "<?php echo $verificationCode; ?>";
		window.location.href = "includes/verify.php";
    <?php endif; ?>
}

// Call the displayVerificationCode function
displayVerificationCode();

// Call the displayVerificationCodeAndRedirect function
displayVerificationCodeAndRedirect();
</script>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <section>
                <div class="modal-body modal-spa">
                    <div class="login-grids">
                        <div class="login">
                            <div class="login-right">
                                <form name="signup" method="post">
                                    <h3>Create your account </h3>
                                    <input type="text" value="" placeholder="Full Name" name="fname" autocomplete="off" required="">
                                    <input type="text" value="" placeholder="Mobile number" maxlength="11" name="mobilenumber" autocomplete="off" required="">
                                    <input type="text" value="" placeholder="Email id" name="email" id="email" onBlur="checkAvailability()" autocomplete="off"  required="">
                                    <span id="user-availability-status" style="font-size:12px;"></span> 
                                    <input type="password" value="" placeholder="Password" name="password" oninput="validatePassword()" required="">
                                    <span id="password-validation" style="font-size: 12px;"></span>
                                    <input type="submit" name="submit" id="submit" value="CREATE ACCOUNT">
                                </form>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <p>By logging in you agree to our <a href="page.php?type=terms">Terms and Conditions</a> and <a href="page.php?type=privacy">Privacy Policy</a></p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
