<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Check if the user is verified
if (!isset($_SESSION['verified']) || $_SESSION['verified'] != 1) {
    header('location: ./include/verify.php'); // Redirect to the verification page if not verified
    exit();
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <title>ExploreEra | Confirmation </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
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
    <!--//end-animate-->
</head>
<body>
<?php include('includes/header.php');?>
<div class="banner-1">
    <div class="container">
        <h1 class="wow zoomIn animated animated" data-wow-delay=".5s" style="visibility: visible; animation-delay: 0.5s; animation-name: zoomIn;"></h1>
    </div>
</div>
<!--- /banner-1 ---->
<!--- contact ---->
<div class="contact">
    <div class="container">
        <h3> Confirmation</h3>
        <div class="col-md-10 contact-left">
            <div class="con-top animated wow fadeInUp animated" data-wow-duration="1200ms" data-wow-delay="500ms" style="visibility: visible; animation-duration: 1200ms; animation-delay: 500ms; animation-name: fadeInUp;">

                <h4>  <?php echo htmlentities($_SESSION['msg']);?></h4>

            </div>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!--- /contact ---->
<?php include('includes/footer.php');?>
<!-- sign -->
<?php include('includes/signup.php');?>    
<!-- signin -->
<?php include('includes/signin.php');?>    
<!-- //signin -->
<!-- write us -->
<?php include('includes/write-us.php');?>    
<!-- //write us -->

<?php
// Destroy the session variable containing the message
unset($_SESSION['msg']);
?>
</body>
</html>