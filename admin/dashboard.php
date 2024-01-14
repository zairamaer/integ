<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
	{	
header('location:index.php');
}
else{ 
	// code for cancel
if(isset($_REQUEST['bkid']))
	{
$bid=intval($_GET['bkid']);
$status=2;
$cancelby='a';
$sql = "UPDATE tblbooking SET status=:status,CancelledBy=:cancelby WHERE  BookingId=:bid";
$query = $dbh->prepare($sql);
$query -> bindParam(':status',$status, PDO::PARAM_STR);
$query -> bindParam(':cancelby',$cancelby , PDO::PARAM_STR);
$query-> bindParam(':bid',$bid, PDO::PARAM_STR);
$query -> execute();

$msg="Booking Cancelled successfully";
}


if(isset($_REQUEST['bckid']))
	{
$bcid=intval($_GET['bckid']);
$status=1;
$cancelby='a';
$sql = "UPDATE tblbooking SET status=:status WHERE BookingId=:bcid";
$query = $dbh->prepare($sql);
$query -> bindParam(':status',$status, PDO::PARAM_STR);
$query-> bindParam(':bcid',$bcid, PDO::PARAM_STR);
$query -> execute();
$msg="Booking Confirm successfully";
}




	?>
<!DOCTYPE HTML>
<html>
<head>
<title>ExploreEra | Admin manage Bookings</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="css/morris.css" type="text/css"/>
<link href="css/font-awesome.css" rel="stylesheet"> 
<script src="js/jquery-2.1.4.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/table-style.css" />
<link rel="stylesheet" type="text/css" href="css/basictable.css" />
<script type="text/javascript" src="js/jquery.basictable.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
      $('#table').basictable();

      $('#table-breakpoint').basictable({
        breakpoint: 768
      });

      $('#table-swap-axis').basictable({
        swapAxis: true
      });

      $('#table-force-off').basictable({
        forceResponsive: false
      });

      $('#table-no-resize').basictable({
        noResize: true
      });

      $('#table-two-axis').basictable();

      $('#table-max-height').basictable({
        tableWrapper: true
      });
    });
</script>
<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'/>
<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
  <style>
		.errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #dd3d36;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
		</style>
</head> 
<body>
   <div class="page-container">
   <!--/content-inner-->
<div class="left-content">
	   <div class="mother-grid-inner">
            <!--header start here-->
				<?php include('includes/header.php');?>
				     <div class="clearfix"> </div>	
				</div>
<!--heder end here-->
<ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php"></a><i class="fa fa-angle-right"></i>Bookings</li>
            </ol>
<div class="agile-grids">	
				<!-- tables -->
				<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>
				<div class="agile-tables">
					<div class="w3l-table-info">
						<h2>Bookings</h2>
						<table id="table">
							<thead>
								<tr>
									<th>Booking ID</th>
									<th>Name</th>
									<th>Mobile No.</th>
									<th>Email Id</th>
									<th>Package Name</th>
									<th>From</th>
									<th>Comment </th>
									<th>Status </th>
									<th>Action </th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$sql = "SELECT tblbooking.BookingId as bookid,tblusers.FullName as fname,tblusers.MobileNumber as mnumber,tblusers.EmailId as email,tbltourpackages.PackageName as pckname,tblbooking.PackageId as pid,tblbooking.FromDate as fdate,tblbooking.ToDate as tdate,tblbooking.Comment as comment,tblbooking.status as status,tblbooking.CancelledBy as cancelby,tblbooking.UpdationDate as upddate from tblusers join  tblbooking on  tblbooking.UserEmail=tblusers.EmailId join tbltourpackages on tbltourpackages.PackageId=tblbooking.PackageId";
								$query = $dbh->prepare($sql);
								$query->execute();
								$results = $query->fetchAll(PDO::FETCH_OBJ);
								$cnt = 1;
								if($query->rowCount() > 0) {
									foreach($results as $result) { ?>		
										<tr>
											<td>#BK-<?php echo htmlentities($result->bookid);?></td>
											<td><?php echo htmlentities($result->fname);?></td>
											<td><?php echo htmlentities($result->mnumber);?></td>
											<td><?php echo htmlentities($result->email);?></td>
											<td><a href="update-package.php?pid=<?php echo htmlentities($result->pid);?>"><?php echo htmlentities($result->pckname);?></a></td>
											<td><?php echo htmlentities($result->fdate);?></td>
											<td><?php echo htmlentities($result->comment);?></td>
											<td>
												<?php 
												if($result->status == 0) {
													echo "Pending";
												} elseif($result->status == 1) {
													echo "Confirmed";
												} elseif($result->status == 2 && $result->cancelby == 'a') {
													echo "Canceled by you at " . $result->upddate;
												} elseif($result->status == 2 && $result->cancelby == 'u') {
													echo "Canceled by User at " . $result->upddate;
												}
												?>
											</td>
											<?php if($result->status == 0) { ?>
												<td>
													<a href="manage-bookings.php?bckid=<?php echo htmlentities($result->bookid);?>" onclick="return confirm('Booking has been confirmed')" style="color: green; text-decoration: none; padding: 5px; border: 1px solid green; border-radius: 3px;">Confirm</a>
												</td>
											<?php } elseif($result->status == 1) { ?>
												<td>Confirmed</td>
											<?php } elseif($result->status == 2) { ?>
												<td>Cancelled</td>
											<?php } ?>
										</tr>
									<?php $cnt = $cnt + 1; } 
								} ?>
							</tbody>
						</table>
					</div>
				</div>

				<div class="agile-tables">
					<div class="w3l-table-info">
						<h2>Paid Bookings</h2>
						<table id="table">
							<thead>
								<tr>
									<th>Booking ID</th>
									<th>Name</th>
									<th>Mobile No.</th>
									<th>Email Id</th>
									<th>Package Name</th>
									<th>From</th>
									<th>Payment Status </th>
						
								</tr>
							</thead>
							<tbody>
								<?php 
								$sql = "SELECT tblbooking.BookingId as bookid,tblusers.FullName as fname,tblusers.MobileNumber as mnumber,tblusers.EmailId as email,tbltourpackages.PackageName as pckname,tblbooking.PackageId as pid,tblbooking.FromDate as fdate,tblbooking.ToDate as tdate,tblbooking.PaymentStatus as payment_status,tblbooking.CancelledBy as cancelby,tblbooking.UpdationDate as upddate from tblusers join  tblbooking on  tblbooking.UserEmail=tblusers.EmailId join tbltourpackages on tbltourpackages.PackageId=tblbooking.PackageId WHERE tblbooking.PaymentStatus = 'Paid'";;
								$query = $dbh->prepare($sql);
								$query->execute();
								$results = $query->fetchAll(PDO::FETCH_OBJ);
								$cnt = 1;
								if($query->rowCount() > 0) {
									foreach($results as $result) { ?>		
										<tr>
											<td>#BK-<?php echo htmlentities($result->bookid);?></td>
											<td><?php echo htmlentities($result->fname);?></td>
											<td><?php echo htmlentities($result->mnumber);?></td>
											<td><?php echo htmlentities($result->email);?></td>
											<td><a href="update-package.php?pid=<?php echo htmlentities($result->pid);?>"><?php echo htmlentities($result->pckname);?></a></td>
											<td><?php echo htmlentities($result->fdate);?></td>
											<td> <?php echo htmlentities($result->payment_status);?></td>
											
										</tr>
									<?php $cnt = $cnt + 1; } 
								} ?>
							</tbody>
						</table>
					</div>
				</div>

				<div class="agile-tables">
					<div class="w3l-table-info">
						<h2>Unpaid Bookings</h2>
						<table id="table">
							<thead>
								<tr>
									<th>Booking ID</th>
									<th>Name</th>
									<th>Mobile No.</th>
									<th>Email Id</th>
									<th>Package Name</th>
									<th>From</th>
								</tr>
							</thead>
							<tbody>

							<?php
								try {
									$sql = "SELECT tblbooking.BookingId as bookid,tblusers.FullName as fname,tblusers.MobileNumber as mnumber,tblusers.EmailId as email,tbltourpackages.PackageName as pckname,tblbooking.PackageId as pid,tblbooking.FromDate as fdate,tblbooking.ToDate as tdate,tblbooking.Comment as comment,tblbooking.status as status,tblbooking.CancelledBy as cancelby,tblbooking.UpdationDate as upddate from tblusers join  tblbooking on  tblbooking.UserEmail=tblusers.EmailId join tbltourpackages on tbltourpackages.PackageId=tblbooking.PackageId WHERE tblbooking.PaymentStatus != 'Paid' OR tblbooking.PaymentStatus IS NULL";
									$query = $dbh->prepare($sql);
			
									$query->execute();
									$results = $query->fetchAll(PDO::FETCH_OBJ);
								} catch (PDOException $e) {
									echo "Error: " . $e->getMessage();
								}
								$cnt = 1;
								if($query->rowCount() > 0) {
									foreach($results as $result) { ?>		
										<tr>
											<td>#BK-<?php echo htmlentities($result->bookid);?></td>
											<td><?php echo htmlentities($result->fname);?></td>
											<td><?php echo htmlentities($result->mnumber);?></td>
											<td><?php echo htmlentities($result->email);?></td>
											<td><a href="update-package.php?pid=<?php echo htmlentities($result->pid);?>"><?php echo htmlentities($result->pckname);?></a></td>
											<td><?php echo htmlentities($result->fdate);?></td>
										    <td> <?php echo htmlentities($result->payment_status);?></td>
										</tr>
									<?php $cnt = $cnt + 1; } 
								} ?>
							</tbody>
						</table>
					</div>
				</div>

				<div class="agile-tables">
					<div class="w3l-table-info">
						<h2>Confirmed Bookings</h2>
						<table id="table">
							<thead>
								<tr>
									<th>Booking ID</th>
									<th>Name</th>
									<th>Mobile No.</th>
									<th>Email Id</th>
									<th>Package Name</th>
									<th>From</th>
									<th>Comment </th>
									<th>Status </th>
									<th>Action </th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$sql = "SELECT tblbooking.BookingId as bookid,tblusers.FullName as fname,tblusers.MobileNumber as mnumber,tblusers.EmailId as email,tbltourpackages.PackageName as pckname,tblbooking.PackageId as pid,tblbooking.FromDate as fdate,tblbooking.ToDate as tdate,tblbooking.Comment as comment,tblbooking.status as status,tblbooking.CancelledBy as cancelby,tblbooking.UpdationDate as upddate from tblusers join  tblbooking on  tblbooking.UserEmail=tblusers.EmailId join tbltourpackages on tbltourpackages.PackageId=tblbooking.PackageId WHERE tblbooking.status = 1";
								$query = $dbh->prepare($sql);
								$query->execute();
								$results = $query->fetchAll(PDO::FETCH_OBJ);
								$cnt = 1;
								if($query->rowCount() > 0) {
									foreach($results as $result) { ?>		
										<tr>
											<td>#BK-<?php echo htmlentities($result->bookid);?></td>
											<td><?php echo htmlentities($result->fname);?></td>
											<td><?php echo htmlentities($result->mnumber);?></td>
											<td><?php echo htmlentities($result->email);?></td>
											<td><a href="update-package.php?pid=<?php echo htmlentities($result->pid);?>"><?php echo htmlentities($result->pckname);?></a></td>
											<td><?php echo htmlentities($result->fdate);?></td>
											<td><?php echo htmlentities($result->comment);?></td>
											<td>
												<?php 
												if($result->status == 0) {
													echo "Pending";
												} elseif($result->status == 1) {
													echo "Confirmed";
												} elseif($result->status == 2 && $result->cancelby == 'a') {
													echo "Canceled by you at " . $result->upddate;
												} elseif($result->status == 2 && $result->cancelby == 'u') {
													echo "Canceled by User at " . $result->upddate;
												}
												?>
											</td>
											<?php if($result->status == 0) { ?>
												<td>
													<a href="manage-bookings.php?bckid=<?php echo htmlentities($result->bookid);?>" onclick="return confirm('Booking has been confirmed')" style="color: green; text-decoration: none; padding: 5px; border: 1px solid green; border-radius: 3px;">Confirm</a>
												</td>
											<?php } elseif($result->status == 1) { ?>
												<td>Confirmed</td>
											<?php } elseif($result->status == 2) { ?>
												<td>Cancelled</td>
											<?php } ?>
										</tr>
									<?php $cnt = $cnt + 1; } 
								} ?>
							</tbody>
						</table>
					</div>
				</div>

				<div class="agile-tables">
					<div class="w3l-table-info">
						<h2>Unconfirmed Bookings</h2>
						<table id="table">
							<thead>
								<tr>
									<th>Booking ID</th>
									<th>Name</th>
									<th>Mobile No.</th>
									<th>Email Id</th>
									<th>Package Name</th>
									<th>From</th>
									<th>Comment </th>
									<th>Status </th>
									<th>Action </th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$sql = "SELECT tblbooking.BookingId as bookid,tblusers.FullName as fname,tblusers.MobileNumber as mnumber,tblusers.EmailId as email,tbltourpackages.PackageName as pckname,tblbooking.PackageId as pid,tblbooking.FromDate as fdate,tblbooking.ToDate as tdate,tblbooking.Comment as comment,tblbooking.status as status,tblbooking.CancelledBy as cancelby,tblbooking.UpdationDate as upddate from tblusers join  tblbooking on  tblbooking.UserEmail=tblusers.EmailId join tbltourpackages on tbltourpackages.PackageId=tblbooking.PackageId WHERE tblbooking.status != 1";
								$query = $dbh->prepare($sql);
								$query->execute();
								$results = $query->fetchAll(PDO::FETCH_OBJ);
								$cnt = 1;
								if($query->rowCount() > 0) {
									foreach($results as $result) { ?>		
										<tr>
											<td>#BK-<?php echo htmlentities($result->bookid);?></td>
											<td><?php echo htmlentities($result->fname);?></td>
											<td><?php echo htmlentities($result->mnumber);?></td>
											<td><?php echo htmlentities($result->email);?></td>
											<td><a href="update-package.php?pid=<?php echo htmlentities($result->pid);?>"><?php echo htmlentities($result->pckname);?></a></td>
											<td><?php echo htmlentities($result->fdate);?></td>
											<td><?php echo htmlentities($result->comment);?></td>
											<td>
												<?php 
												if($result->status == 0) {
													echo "Pending";
												} elseif($result->status == 1) {
													echo "Confirmed";
												} elseif($result->status == 2 && $result->cancelby == 'a') {
													echo "Canceled by you at " . $result->upddate;
												} elseif($result->status == 2 && $result->cancelby == 'u') {
													echo "Canceled by User at " . $result->upddate;
												}
												?>
											</td>
											<?php if($result->status == 0) { ?>
												<td>
													<a href="manage-bookings.php?bckid=<?php echo htmlentities($result->bookid);?>" onclick="return confirm('Booking has been confirmed')" style="color: green; text-decoration: none; padding: 5px; border: 1px solid green; border-radius: 3px;">Confirm</a>
												</td>
											<?php } elseif($result->status == 1) { ?>
												<td>Confirmed</td>
											<?php } elseif($result->status == 2) { ?>
												<td>Cancelled</td>
											<?php } ?>
										</tr>
									<?php $cnt = $cnt + 1; } 
								} ?>
							</tbody>
						</table>
					</div>
				</div>
								


			<ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php"></a><i class="fa fa-angle-right"></i>Users</li>
            </ol>
<div class="agile-grids">	
				<!-- tables -->
				
				<div class="agile-tables">
					<div class="w3l-table-info">
					  <h2>Users</h2>
					    <table id="table">
						<thead>
						  <tr>
						  <th>#</th>
							<th>Name</th>
							<th>Mobile No.</th>
							<th>Email Address</th>
							<th>Registration Date </th>
							<th>Verified </th>
							<th>Updation Date</th>
						  </tr>
						</thead>
						<tbody>
<?php $sql = "SELECT * from tblusers";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{				?>		
						  <tr>
							<td><?php echo htmlentities($cnt);?></td>
							<td><?php echo htmlentities($result->FullName);?></td>
							<td><?php echo htmlentities($result->MobileNumber);?></td>
							<td><?php echo htmlentities($result->EmailId);?></td>
							<td><?php echo htmlentities($result->RegDate);?></td>
							<td>
								<?php
								// Check if the 'verified' column value is 1
								if ($result->verified == 1) {
									echo "Verified";
								} else {
									echo "Not Verified";
								}
								?>
							</td>>
							<td><?php echo htmlentities($result->UpdationDate);?></td>
						  </tr>
						 <?php $cnt=$cnt+1;} }?>
						</tbody>
					  </table>
					</div>
				  </table>

				
			</div>

			<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php"></a><i class="fa fa-angle-right"></i>Cancellation</li>
</ol>
<div class="agile-grids">	
    <!-- tables -->
    <?php if($error) { ?>
        <div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div>
    <?php } else if($msg) { ?>
        <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div>
    <?php }?>
    <div class="agile-tables">
        <div class="w3l-table-info">
            <h2>Cancellation Reason</h2>
            <table id="table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Name</th>
                        <th>Email Id</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
					<?php 
						$sql = "SELECT tblbooking.BookingId as bookid, tblusers.FullName as fname, tblusers.MobileNumber as mnumber, tblusers.EmailId as email, tbltourpackages.PackageName as pckname, tblbooking.cancelReason as cancel_reason, tblbooking.FromDate as fdate, tblbooking.ToDate as tdate, tblbooking.Comment as comment, tblbooking.status as status, tblbooking.CancelledBy as cancelby, tblbooking.UpdationDate as upddate FROM tblusers JOIN tblbooking ON tblbooking.UserEmail=tblusers.EmailId JOIN tbltourpackages ON tbltourpackages.PackageId=tblbooking.PackageId WHERE tblbooking.status = 2 AND tblbooking.cancelReason IS NOT NULL";
						$query = $dbh->prepare($sql);
						$query->execute();
						$results = $query->fetchAll(PDO::FETCH_OBJ);
						$cnt = 1;
						if($query->rowCount() > 0) {
							foreach($results as $result) { ?>
								<tr>
									<td>#BK-<?php echo htmlentities($result->bookid);?></td>
									<td><?php echo htmlentities($result->fname);?></td>
									<td><?php echo htmlentities($result->email);?></td>
									<td><?php echo htmlentities($result->cancel_reason);?></td>
								</tr>
							<?php 
							$cnt = $cnt + 1;
							}
						} 
					?>
                </tbody>
            </table>
        </div>
    </div>
</div>

			
<!-- script-for sticky-nav -->
		<script>
		$(document).ready(function() {
			 var navoffeset=$(".header-main").offset().top;
			 $(window).scroll(function(){
				var scrollpos=$(window).scrollTop(); 
				if(scrollpos >=navoffeset){
					$(".header-main").addClass("fixed");
				}else{
					$(".header-main").removeClass("fixed");
				}
			 });
			 
		});
		</script>
		<!-- /script-for sticky-nav -->
<!--inner block start here-->
<div class="inner-block">

</div>
<!--inner block end here-->
<!--copy rights start here-->
<?php include('includes/footer.php');?>
<!--COPY rights end here-->
</div>
</div>
  <!--//content-inner-->
		<!--/sidebar-menu-->
						<?php include('includes/sidebarmenu.php');?>
							  <div class="clearfix"></div>		
							</div>
							<script>
							var toggle = true;
										
							$(".sidebar-icon").click(function() {                
							  if (toggle)
							  {
								$(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
								$("#menu span").css({"position":"absolute"});
							  }
							  else
							  {
								$(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
								setTimeout(function() {
								  $("#menu span").css({"position":"relative"});
								}, 400);
							  }
											
											toggle = !toggle;
										});
							</script>
<!--js -->
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
   <script src="js/bootstrap.min.js"></script>
   <!-- /Bootstrap Core JavaScript -->	   

</body>
</html>
<?php } ?>