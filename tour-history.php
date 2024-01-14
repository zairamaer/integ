<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {
    // Check if the cancel form is submitted
    if (isset($_POST['submitCancel'])) {
        $cancelReason = $_POST['cancelReason'];
        $bid = intval($_POST['bkid']);
        $email = $_SESSION['login'];

        // Set status to '2' (Cancelled) and CancelledBy to 'u'
        $status = 2;
        $cancelby = 'u';

        $sql = "UPDATE tblbooking SET status=:status, CancelledBy=:cancelby, cancelReason=:cancelReason WHERE UserEmail=:email and BookingId=:bid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':cancelby', $cancelby, PDO::PARAM_STR);
        $query->bindParam(':cancelReason', $cancelReason, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':bid', $bid, PDO::PARAM_STR);

        $query->execute();

        $msg = "Booking Cancelled successfully";
    }
    
    if (isset($_GET['payment_success']) && $_GET['payment_success'] === 'true') {
        // Retrieve the booking ID from the URL
        $bid = isset($_GET['bkid']) ? intval($_GET['bkid']) : 0;

        // Update the payment status to 'Paid'
        $status = 'Paid';

        $sql = "UPDATE tblbooking SET PaymentStatus=:PaymentStatus WHERE BookingId=:bid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':PaymentStatus', $status, PDO::PARAM_STR);
        $query->bindParam(':bid', $bid, PDO::PARAM_STR);

        $query->execute();

        $msg = "Payment completed successfully!";
        
    }

?>

<!DOCTYPE HTML>
<html>

<head>
    <title>ExploreEra | Tourism Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Tourism Management System In PHP" />
    <script type="applijewelleryion/x-javascript">
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
    <!-- Custom Theme files -->
    <script src="js/jquery-1.12.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!--animate-->
    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
    <script src="js/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>

    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }

        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }
            /* Style for the form container */
    .cancel-form-container {
        max-width: 400px;
        margin: 20px auto;
        padding: 20px;
        background-color: #f4f4f4;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    /* Style for the textarea */
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    /* Style for the submit button */
    input[type="submit"] {
        background-color: #4caf50;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    /* Hover effect for the submit button */
    input[type="submit"]:hover {
        background-color: #45a049;
    }

    /* Style for the close button */
    button {
        background-color: #ccc;
        color: #333;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    /* Hover effect for the close button */
    button:hover {
        background-color: #999;
    }
    </style>
</head>

<body>
    <!-- top-header -->
    <div class="top-header">
        <?php include('includes/header.php'); ?>
        <div class="banner-1 ">
            <div class="container">
                <h1 class="wow zoomIn animated animated" data-wow-delay=".5s" style="visibility: visible; animation-delay: 0.5s; animation-name: zoomIn;">ExploreEra-Tourism Management System</h1>
            </div>
        </div>
        <!--- /banner-1 ---->
        <!--- privacy ---->
        <div class="privacy">
            <div class="container">
                <h3 class="wow fadeInDown animated animated" data-wow-delay=".5s" style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInDown;">My Tour History</h3>
                <form name="chngpwd" method="post" onSubmit="return valid();">
                    <?php if ($error) { ?>
                        <div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div>
                    <?php } else if ($msg) { ?>
                        <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div>
                    <?php } ?>
                    <p>
                        <table border="1" width="100%">
                            <tr align="center">
                                <th>#</th>
                                <th>Booking Id</th>
                                <th>Package Name</th>
                                <th>Date</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th>Booking Date</th>
                                <th>Action</th>
                            </tr>
                            <?php

                            $uemail = $_SESSION['login'];;
                            $sql = "SELECT tblbooking.BookingId as bookid,tblbooking.PackageId as pkgid,tbltourpackages.PackageName as packagename,tblbooking.FromDate as fromdate,tblbooking.ToDate as todate,tblbooking.Comment as comment,tblbooking.status as status,tblbooking.PaymentStatus as payment_status,tblbooking.RegDate as regdate,tblbooking.CancelledBy as cancelby,tblbooking.UpdationDate as upddate from tblbooking join tbltourpackages on tbltourpackages.PackageId=tblbooking.PackageId where UserEmail=:uemail";
                            $query = $dbh->prepare($sql);
                            $query->bindParam(':uemail', $uemail, PDO::PARAM_STR);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            $cnt = 1;
                            if ($query->rowCount() > 0) {
                                foreach ($results as $result) {
                            ?>
                                    <tr align="center">
                                        <td><?php echo htmlentities($cnt); ?></td>
                                        <td>#BK<?php echo htmlentities($result->bookid); ?></td>
                                        <td><a href="package-details.php?pkgid=<?php echo htmlentities($result->pkgid); ?>"><?php echo htmlentities($result->packagename); ?></a></td>
                                        <td><?php echo htmlentities($result->fromdate); ?></td>
                                        <td><?php echo htmlentities($result->comment); ?></td>
                                        <td>
                                        <?php
                                        if ($result->status == 0) {
                                            echo "Pending";
                                        } elseif ($result->status == 1) {
                                            if ($result->payment_status == 'Paid' || isset($_GET['payment_success']) && $_GET['payment_success'] === 'true') {
                                                echo 'Paid';
                                            } else {
                                                echo 'Confirmed';
                                            }
                                        } elseif ($result->status == 2 &&  $result->cancelby == 'u') {
                                            echo 'Cancelled by you at ' . $result->upddate;
                                        }
                                        ?>
                                        </td>
                                        <td><?php echo htmlentities($result->regdate); ?></td>

                                        <td>
                                            <?php
                                            if ($result->status == 0) {
                                            ?>
                                            <a href="#" onclick="showCancelPopup('<?php echo htmlentities($result->bookid); ?>')">Cancel</a>
                                            <?php
                                            } elseif ($result->status == 1) {
                                                if ($result-> payment_status == 'Paid' || isset($_GET['payment_success'])) {
                                                    echo 'Paid';
                                                } else {
                                                    // Display the 'Pay' link
                                                    echo '<a href="booking.php?bkid=' . htmlentities($result->bookid) . '">Pay</a>';
                                                }
                                            } elseif ($result->status == 2) {
                                                echo 'Cancelled';
                                            } else {
                                                echo ''; // Empty for other statuses
                                            }
                                            ?>
                                        </td>

                                    <!-- Popup (modal) for entering cancel reason -->
                                        <div id="cancelPopup_<?php echo htmlentities($result->bookid); ?>" style="display: none;">
                                            <form method="post" action="tour-history.php">
                                                <input type="hidden" name="bkid" value="<?php echo htmlentities($result->bookid); ?>">
                                                <textarea name="cancelReason" placeholder="Enter cancel reason"></textarea>
                                                <input type="submit" name="submitCancel" value="Submit Cancel Reason">
                                                <button type="button" onclick="hideCancelPopup('<?php echo htmlentities($result->bookid); ?>')">Close</button>
                                            </form>
                                        </div>

                                    </tr>
                            <?php $cnt = $cnt + 1;
                                }
                            } ?>
                        </table>

                    </p>
                </form>


            </div>
        </div>
        <!--- /privacy ---->
        <!--- footer-top ---->
        <!--- /footer-top ---->
        <?php include('includes/footer.php'); ?>
        <!-- signup -->
        <?php include('includes/signup.php'); ?>
        <!-- //signu -->
        <!-- signin -->
        <?php include('includes/signin.php'); ?>
        <!-- //signin -->
        <!-- write us -->
        <?php include('includes/write-us.php'); ?>

		
<!-- JavaScript functions to show and hide the popup -->
<script>
    function showCancelPopup(bookId) {
        var cancelPopup = document.getElementById('cancelPopup_' + bookId);
        cancelPopup.style.display = 'block';
    }

    function hideCancelPopup(bookId) {
        var cancelPopup = document.getElementById('cancelPopup_' + bookId);
        cancelPopup.style.display = 'none';
    }
</script>

</body>

</html>
<?php } ?>
