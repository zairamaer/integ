<?php
// cancel-booking.php

// Include necessary files and database configuration
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login']) == 0) {  
    header('location:index.php');
} else {
    if(isset($_GET['bkid']) && isset($_GET['reason'])) {
        $bid = intval($_GET['bkid']);
        $reason = $_GET['reason'];

        // Update the database with the cancellation reason
        $sql = "UPDATE tblbooking SET status = 2, CancelledBy = 'u', CancellationReason = :reason WHERE BookingId = :bid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':reason', $reason, PDO::PARAM_STR);
        $query->bindParam(':bid', $bid, PDO::PARAM_STR);
        $query->execute();

        // Set a session variable to indicate successful cancellation
        $_SESSION['cancel_success'] = true;

        // Redirect to the tour history page
        header('location:tour-history.php');
    }
}
?>