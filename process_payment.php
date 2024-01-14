<?php
require_once('vendor/autoload.php');

\Stripe\Stripe::setApiKey('sk_test_51OU2A4LrajUfmM6VVWm5QoPbxGQa3HUWgh1pCC91W1XHq4W6v9ucq5F4diNg5j8dRzaMgmScbUhFHAM5BfojpmDF008YYJjObR');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['stripeToken'];

    $customerEmail = $_POST['email']; // Capture customer's email from the form

    try {
        // Convert amount to cents
        $amountInCents = $_POST['package_price'] * 100;

        $customer = \Stripe\Customer::create([
            'email' => $customerEmail,
            'source' => $token,
        ]);

        $charge = \Stripe\Charge::create([
            'amount' => $amountInCents,
            'currency' => 'usd',
            'description' => 'Booking payment',
            'customer' => $customer->id,
        ]);

        // Process the payment and update your database accordingly
        // Add your logic here

        // Assuming you have a database update query here
        // For example:
        $updateStatusQuery = "UPDATE tblbooking SET status = 1 WHERE BookingId = :bookingId";
        $stmt = $dbh->prepare($updateStatusQuery);
        $stmt->bindParam(':bookingId', $_GET['bkid'], PDO::PARAM_INT);
        
        $stmt->execute();

        // Send email confirmation to the customer
        $to = $customerEmail;
        $subject = 'Booking Confirmation';
        $message = 'Thank you for your booking! Your payment has been processed successfully.';
        $headers = 'From: ' . 'ExploreEra@ExploreEra.com'; // Replace with your email address

        // Set the "sendmail_from" directive
        ini_set("sendmail_from", 'dharklike@gmail.com');

        // Use mail() function to send the email
        if (mail($to, $subject, $message, $headers)) {
            $response = [
                'status' => 'success',
                'message' => 'Payment successfully processed. Email confirmation sent.',
            ];

            // Redirect to tour-history.php after a successful payment
            header('Location: tour-history.php?payment_success=true&bkid=' . $_GET['bkid']);
            exit();
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Email sending failed. Please contact support.',
            ];
        }
    } catch (\Stripe\Exception\CardException $e) {
        $response = [
            'status' => 'error',
            'message' => $e->getError()->message,
        ];
    } catch (\Exception $e) {
        $response = [
            'status' => 'error',
            'message' => 'Payment processing failed. Please try again later.',
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} else {
    header('Location: booking.php');
    exit();
}
?>
