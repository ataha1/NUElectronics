<?php
	ob_start();
	session_start();
	$pageTitle = 'Profile';
	include 'init.php';
	if (isset($_SESSION['user'])) {
		$getUser = $con->prepare("SELECT * FROM nu_electronics_shop.user WHERE Username = ?");
		$getUser->execute(array($sessionUser));
		$info = $getUser->fetch();
		$userid = $info['UserID'];
		$do = isset($_GET['do']) ? $_GET['do'] : 'Display';
		if($do == 'Display'){ //Display profile page ?>
			<h1 class="text-center">My Profile</h1>
			<div class="information block">
				<div class="container">
					<div class="panel panel-primary">
						<div class="panel-heading">My Information</div>
						<div class="panel-body">
							<ul class="list-unstyled">
								<li>
									<i class="fa fa-unlock-alt fa-fw"></i>
									<span>Login Name</span> : <?php echo $info['UserName'] ?>
								</li>
								<li>
									<i class="fa fa-envelope-o fa-fw"></i>
									<span>Email</span> : <?php echo $info['EmailAddress'] ?>
								</li>
								<li>
									<i class="fa fa-user fa-fw"></i>
									<span>First Name</span> : <?php echo $info['FirstName'] ?>
								</li>
								<li>
									<i class="fa fa-user fa-fw"></i>
									<span>Last Name</span> : <?php echo $info['LastName'] ?>
								</li>
								<li>
									<i class="fa fa-money fa-fw"></i>
									<span>Balance </span> : <?php echo $info['Balance'] ?>
								</li>
							</ul>
							<a href="?do=Edit&userid=<?php echo $userid?>" class="btn btn-default">Edit Information</a>
						</div>
					</div>
				</div>
			</div>
			<!-- <div id="my-ads" class="my-ads block">
				<div class="container">
					<div class="panel panel-primary">
						<div class="panel-heading">My Items</div>
						<div class="panel-body">
						<?php
							$myItems = getAllFrom("*", "items", "where Member_ID = $userid", "", "Item_ID");
							if (! empty($myItems)) {
								echo '<div class="row">';
								foreach ($myItems as $item) {
									echo '<div class="col-sm-6 col-md-3">';
										echo '<div class="thumbnail item-box">';
											if ($item['Approve'] == 0) { 
												echo '<span class="approve-status">Waiting Approval</span>'; 
											}
											echo '<span class="price-tag">$' . $item['Price'] . '</span>';
											echo '<img class="img-responsive" src="img.png" alt="" />';
											echo '<div class="caption">';
												echo '<h3><a href="items.php?itemid='. $item['Item_ID'] .'">' . $item['Name'] .'</a></h3>';
												echo '<p>' . $item['Description'] . '</p>';
												echo '<div class="date">' . $item['Add_Date'] . '</div>';
											echo '</div>';
										echo '</div>';
									echo '</div>';
								}
								echo '</div>';
							} else {
								echo 'Sorry There\' No Ads To Show, Create <a href="newad.php">New Ad</a>';
							}
						?>
						</div>
					</div>
				</div>
			</div> -->
			<?php
		}
		elseif($do == 'Edit'){
			// Check If Get Request userid Is Numeric & Get Its Integer Value

			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

			// Select All Data Depend On This ID

			$stmt = $con->prepare("SELECT * FROM nu_electronics_shop.user WHERE UserID = ? LIMIT 1");

			// Execute Query

			$stmt->execute(array($userid));

			// Fetch The Data

			$row = $stmt->fetch();

			// The Row Count

			$count = $stmt->rowCount();

			// If There's Such ID Show The Form

			if ($count > 0) { ?>
				<h1 class="text-center">Edit Profile</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="userid" value="<?php echo $userid ?>" />
						<!-- Start Username Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Username</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="username" class="form-control" value="<?php echo $row['UserName'] ?>" autocomplete="off" required="required" />
							</div>
						</div>
						<!-- End Username Field -->
						<!-- Start Password Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Password</label>
							<div class="col-sm-10 col-md-6">
								<input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />
								<input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Dont Want To Change" />
							</div>
						</div>
						<!-- End Password Field -->
						<!-- Start Email Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Email</label>
							<div class="col-sm-10 col-md-6">
								<input type="email" name="email" value="<?php echo $row['EmailAddress'] ?>" class="form-control" required="required" />
							</div>
						</div>
						<!-- End Email Field -->
						<!-- Start First Name Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">First Name</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="fname" value="<?php echo $row['FirstName'] ?>" class="form-control" required="required" />
							</div>
						</div>
						<!-- End First Name Field -->
						<!-- Start Last Name Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Last Name</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="lname" value="<?php echo $row['LastName'] ?>" class="form-control" required="required" />
							</div>
						</div>
						<!-- End Last Name Field -->
						<!-- Start Submit Field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Save" class="btn btn-primary btn-lg" />
							</div>
						</div>
						<!-- End Submit Field -->
					</form>
				</div>
			<?php
			}
		}
		
	}
	else {
		header('Location: login.php');
		exit();
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>