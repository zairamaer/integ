<?php
session_start();
include('includes/config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
$packageName = '';
$packagePrice = '';
$packageStatus = '';
$bookingId = 0;
$packageCompleted = false;

if (isset($_GET['bkid'])) {
    $bookingId = $_GET['bkid'];

    $sql = "SELECT tp.PackageName, tp.PackagePrice, b.status
            FROM tbltourpackages tp
            JOIN tblbooking b ON tp.PackageId = b.PackageId
            WHERE b.BookingId = :BookingId";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':BookingId', $bookingId, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $packageName = $row['PackageName'];
        $packagePrice = $row['PackagePrice'];
        $bookingStatus = $row['status'];
    }


}

// Check if the payment is completed and update the button text
$paymentCompleted = isset($_GET['payment_success']) && $_GET['payment_success'] === 'true' && $bookingStatus == 1;

// After successful payment
if ($paymentCompleted) {
   
    $sqlUpdatePaymentStatus = "UPDATE tblbooking SET PaymentStatus = 'Paid', status = 'Paid' WHERE BookingId = :bid";
    $queryUpdatePaymentStatus = $dbh->prepare($sqlUpdatePaymentStatus);
    $queryUpdatePaymentStatus->bindParam(':bid', $bookingId, PDO::PARAM_INT);
    $queryUpdatePaymentStatus->execute();

    
 
    // Redirect to tour-history.php with payment success parameter
    header("Location: tour-history.php?bkid=$bookingId&payment_success=true");
    

  
   
    
}

?>

<!DOCTYPE HTML>
<html>

<head>
    <title>ExploreEra</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <script type="application/x-javascript">
        addEventListener("load", function() {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>

    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <link href="css/font-awesome.css" rel="stylesheet">

    <script src="js/jquery-1.12.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
    <script src="js/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>

    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
    <?php include('includes/header.php');?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 mb-5">
                <h2 class="text-center p-2 text-primary">Fill the details to complete your booking</h2>
                <h3>Package Details : </h3>
                <table class="table table-bordered" width="500px">
                    <tr>
                        <th>Package Name :</th>
                        <td><?php echo $packageName; ?></td>
                    </tr>
                    <tr>
                        <th>Package Price :</th>
                        <td><?php echo $packagePrice; ?></td>
                    </tr>
                </table>
                <h4>Enter your Details: </h4>
                <form action="process_payment.php" method="post" id="payment-form">
                    <input type="hidden" name="package_name" value="<?php echo $packageName; ?>">
                    <input type="hidden" name="package_price" value="<?php echo $packagePrice; ?>">

                    <div class="form-group">
                        <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="phone" class="form-control" placeholder="Enter your phone number" required>
                    </div>

                    <div class="form-group">
                        <div id="card-element"></div>
                        <div id="card-errors" role="alert"></div>
                    </div>

                    <div class="form-group">
                        <?php if ($paymentCompleted) : ?>
                            <!-- Display "Paid" if payment is completed -->
                            <button type="button" class="btn btn-success btn-lg" disabled>Paid</button>
                        <?php else : ?>
                            <!-- Display "Pay Now" if payment is not completed -->
                            <button type="submit" id="payButton" class="btn btn-primary btn-lg">Click to Pay: $ <?php echo $packagePrice; ?></button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <br><br><br><br>

    <?php include('includes/footer.php');?>
    <!-- signup -->
    <?php include('includes/signup.php');?>            
    <!-- //signup -->
    <!-- signin -->
    <?php include('includes/signin.php');?>            
    <!-- //signin -->
    <!-- write us -->
    <?php include('includes/write-us.php');?>            
    <!-- //write us -->

    <script>
    var stripe = Stripe('pk_test_51OU2A4LrajUfmM6VBUiyZqn4yt57CoOhVjYgF9z4BlmBOxseq0XNIGVVF6YoGYMa655ClBMNGn7z2KkXl7ESFBwz002QxZYuAk');
    var elements = stripe.elements();
    var card = elements.create('card');
    card.mount('#card-element');

    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    var form = document.getElementById('payment-form');
    var payButton = document.getElementById('payButton');

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        var proceed = window.confirm("Are you sure you want to proceed with the payment?");

        if (proceed) {
            payButton.disabled = true;
            
            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                    payButton.disabled = false;
                } else {
                    var token = result.token;
                    var hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'stripeToken');
                    hiddenInput.setAttribute('value', token.id);
                    form.appendChild(hiddenInput);

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', form.action, true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            window.alert("Confirmation: Payment complete!");
                            // Redirect to tour-history.php after a successful payment
                            fetch('tour-history.php?bkid=<?php echo $bookingId; ?>&payment_success=true')
                                .then()
                                .catch((error) => {
                                console.error('Error:', error);
                                });

                                fetch('includes/send_mail.php?bkid=<?php echo $bookingId; ?>')
                                .then()
                                .catch((error) => {
                                console.error('Error:', error);
                                });
                            window.location.href = 'tour-history.php';
                 
                        } else {
                            console.error('Payment failed. Status: ' + xhr.status);
                            window.alert("Payment failed. Please try again.");
                        }
                        payButton.disabled = false;
                    };
                    xhr.send(new URLSearchParams(new FormData(form)));
                }
            });
        }
    });

    // Check if the payment is completed and update the button text
    var paymentCompleted = <?php echo json_encode($paymentCompleted); ?>;
    if (paymentCompleted) {
        payButton.textContent = 'Paid';
        payButton.disabled = true;
       
    }
</script>
</body>

</html>
