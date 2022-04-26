<?php

	/*
	================================================
	== Items Page
	================================================
	*/

	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = 'Items';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') {


			$stmt = $con->prepare("SELECT * FROM nu_electronics_shop.product
									LEFT JOIN nu_electronics_shop.category 
									ON product.CategoryID = category.CategoryID;");

			// Execute The Statement

			$stmt->execute();

			// Assign To Variable 

			$items = $stmt->fetchAll();

			if (! empty($items)) {

			?>

			<h1 class="text-center">Manage Items</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Product Name</td>
							<td>In Stock?</td>
							<td>Technical Specs</td>
							<td>Price</td>
							<!-- <td>Adding Date</td> -->
							<td>Category</td>
							<!-- <td>Username</td> -->
							<td>New Product Count</td>
							<td>Returned product Count</td>
							<td>Refurbished Product Count</td>
							<td>Control</td>
						</tr>
						<?php
							foreach($items as $item) {
								echo "<tr>";
									echo "<td>" . $item['ProductID'] . "</td>";
									echo "<td>" . $item['ProductName'] . "</td>";
									echo "<td>";  if($item['InStock'] == 0) echo "NO"; else echo "YES"; echo "</td>";
									echo "<td>" . $item['TechnicalSpecs'] . "</td>";
									echo "<td>" . $item['Price'] . "</td>";
									// echo "<td>" . $item['Add_Date'] ."</td>";
									echo "<td>" . $item['CategoryName'] ."</td>";
									// echo "<td>" . $item['Username'] ."</td>";
									echo "<td>" . $item['NewProductCount'] ."</td>";
									echo "<td>" . $item['ReturnedProductCount'] ."</td>";
									echo "<td>" . $item['RefurbishedProductCount'] ."</td>";
									echo "<td>
										<a href='items.php?do=Edit&itemid=" . $item['ProductID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
										<a href='items.php?do=Delete&itemid=" . $item['ProductID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
										// if ($item['Approve'] == 0) {
										// 	echo "<a 
										// 			href='items.php?do=Approve&itemid=" . $item['Item_ID'] . "' 
										// 			class='btn btn-info activate'>
										// 			<i class='fa fa-check'></i> Approve</a>";
										// }
									echo "</td>";
								echo "</tr>";
							}
						?>
						<tr>
					</table>
				</div>
				<a href="items.php?do=Add" class="btn btn-sm btn-primary">
					<i class="fa fa-plus"></i> New Item
				</a>
			</div>

			<?php } else {

				echo '<div class="container">';
					echo '<div class="nice-message">There\'s No Items To Show</div>';
					echo '<a href="items.php?do=Add" class="btn btn-sm btn-primary">
							<i class="fa fa-plus"></i> New Item
						</a>';
				echo '</div>';

			} ?>

		<?php 

		} elseif ($do == 'Add') { ?>

			<h1 class="text-center">Add New Item</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Insert" method="POST">
					<!-- Start Name Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10 col-md-6">
							<input 
								type="text" 
								name="name" 
								class="form-control" 
								required="required"  
								placeholder="Name of The Item" />
						</div>
					</div>
					<!-- End Name Field -->
					<!-- Start Description Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">TechnicalSpecs</label>
						<div class="col-sm-10 col-md-6">
							<input 
								type="text" 
								name="description" 
								class="form-control" 
								required="required"  
								placeholder="Description of The Item" />
						</div>
					</div>
					<!-- End Description Field -->
					<!-- Start Price Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Price</label>
						<div class="col-sm-10 col-md-6">
							<input 
								type="text" 
								name="price" 
								class="form-control" 
								required="required" 
								placeholder="Price of The Item" />
						</div>
					</div>
					<!-- End Price Field -->
					<!-- Start Manufacturer Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Manufacturer</label>
						<div class="col-sm-10 col-md-6">
							<input 
								type="text" 
								name="manufacturer" 
								class="form-control" 
								required="required" 
								placeholder="Country of Made" />
						</div>
					</div>
					<!-- End Manufacturer Field -->
					<!-- Start New Product Count -->
					<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">New product Count</label>
							<div class="col-sm-10 col-md-6">
								<input 
									type="text" 
									name="new" 
									class="form-control" 
									required="required" 
									placeholder="New Product Count"/>
							</div>
						</div>
						<!-- End New Product Count -->
						<!-- Start Refurbished Product Count -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Refurbished product Count</label>
							<div class="col-sm-10 col-md-6">
								<input 
									type="text" 
									name="refurbished" 
									class="form-control" 
									required="required" 
									placeholder="Refurbished Product Count"/>
							</div>
						</div>
						<!-- Start Returned Product Count -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Returned product Count</label>
							<div class="col-sm-10 col-md-6">
								<input 
									type="text" 
									name="returned" 
									class="form-control" 
									required="required" 
									placeholder="Returned Product Count"/>
							</div>
						</div>
						<!-- End Returned Product Count -->
						<!-- Start Categories Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Category</label>
							<div class="col-sm-10 col-md-6">
								<select name="category">
									<?php
										$allCats = getAllFrom("*", "nu_electronics_shop.category", "where Parent = Parent", "", "CategoryID");
										// print_r($allCats);
										echo "<option value = '0'> NA </option>";
										foreach ($allCats as $cat) {
											echo "<option value='" . $cat['CategoryID'] . "'";
											echo ">" . $cat['CategoryName'] . "</option>";
											// $childCats = getAllFrom("*", "categories", "where parent = {$cat['ID']}", "", "ID");
											// foreach ($childCats as $child) {
											// 	echo "<option value='" . $child['ID'] . "'";
											// 	if ($item['Cat_ID'] == $child['ID']) { echo ' selected'; }
											// 	echo ">--- " . $child['Name'] . "</option>";
											// }
										}
									?>
								</select>
							</div>
						</div>
						<!-- End Categories Field -->
						<!-- Start InStock button -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label form-check-label" for="defaultCheck1">
								In Stock?
							</label>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="InStockYes" id="exampleRadios1" value="1" >
								<label class="form-check-label" for="exampleRadios1">
									Yes
								</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="InStockYes" id="exampleRadios2" value="0">
								<label class="form-check-label" for="exampleRadios2">
									No
								</label>
							</div>		
						</div>
						<!--End InStock button -->
					<!-- Start Submit Field -->
					<div class="form-group form-group-lg">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" value="Add Item" class="btn btn-primary btn-sm" />
						</div>
					</div>
					<!-- End Submit Field -->
				</form>
			</div>

			<?php

		} elseif ($do == 'Insert') {

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				echo "<h1 class='text-center'>Insert Item</h1>";
				echo "<div class='container'>";

				// Get Variables From The Form

				$name		= $_POST['name'];
				$desc 		= $_POST['description'];
				$price 		= floatval($_POST['price']);
				$manufac 	= $_POST['manufacturer'];
				$new		= intval($_POST['new']);
				$refurb		= intval($_POST['refurbished']);
				$returned	= intval($_POST['returned']);
				$cat 		= intval($_POST['category']);
				$instock	= intval($_POST['InStockYes']);

				// Validate The Form

				$formErrors = array();

				if (empty($name)) {
					$formErrors[] = 'Name Can\'t be <strong>Empty</strong>';
				}

				if (empty($desc)) {
					$formErrors[] = 'Description Can\'t be <strong>Empty</strong>';
				}

				if (empty($price)) {
					$formErrors[] = 'Price Can\'t be <strong>Empty</strong>';
				}

				if (empty($manufac)) {
					$formErrors[] = 'Country Can\'t be <strong>Empty</strong>';
				}

				if ($cat == 0) {
					$formErrors[] = 'You Must Choose the <strong>Category</strong>';
				}

				// Loop Into Errors Array And Echo It

				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {

					// Insert Userinfo In Database

					$stmt = $con->prepare("INSERT INTO 

						nu_electronics_shop.product
						(ProductName, TechnicalSpecs, Price, Manufacturer, NewProductCount, 
						RefurbishedProductCount, ReturnedProductCount, CategoryID, InStock)

						VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?);");

					$stmt->execute(array(

						$name,	$desc, $price, $manufac, $new,
						$refurb, $returned, $cat, $instock

					));

					// Echo Success Message

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';

					redirectHome($theMsg, 'back');

				}

			} else {

				echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

				redirectHome($theMsg);

				echo "</div>";

			}

			echo "</div>";

		} elseif ($do == 'Edit') {

			// Check If Get Request item Is Numeric & Get Its Integer Value

			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

			// Select All Data Depend On This ID

			$stmt = $con->prepare("SELECT * FROM nu_electronics_shop.product WHERE ProductID = ?");

			// Execute Query

			$stmt->execute(array($itemid));

			// Fetch The Data

			$item = $stmt->fetch();

			// The Row Count

			$count = $stmt->rowCount();

			// If There's Such ID Show The Form

			if ($count > 0) { ?>

				<h1 class="text-center">Edit Product</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="itemid" value="<?php echo $itemid ?>" />
						<!-- Start Name Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Name</label>
							<div class="col-sm-10 col-md-6">
								<input 
									type="text" 
									name="name" 
									class="form-control" 
									required="required"  
									placeholder="Name of The Item"
									value="<?php echo $item['ProductName'] ?>" />
							</div>
						</div>
						<!-- End Name Field -->
						<!-- Start Description Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Technical Specs</label>
							<div class="col-sm-10 col-md-6">
								<input 
									type="text" 
									name="description" 
									class="form-control" 
									required="required"  
									placeholder="Description of The Item"
									value="<?php echo $item['TechnicalSpecs'] ?>" />
							</div>
						</div>
						<!-- End Description Field -->
						<!-- Start Price Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Price</label>
							<div class="col-sm-10 col-md-6">
								<input 
									type="text" 
									name="price" 
									class="form-control" 
									required="required" 
									placeholder="Price of The Item"
									value="<?php echo $item['Price'] ?>" />
							</div>
						</div>
						<!-- End Price Field -->
						<!-- Start Manufacturer Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Manufacturer</label>
							<div class="col-sm-10 col-md-6">
								<input 
									type="text" 
									name="manufacturer" 
									class="form-control" 
									required="required" 
									placeholder="Manufacurer"
									value="<?php echo $item['Manufacturer'] ?>" />
							</div>
						</div>
						<!-- End Manufacturer Field -->
						<!-- Start New Product Count -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">New product Count</label>
							<div class="col-sm-10 col-md-6">
								<input 
									type="text" 
									name="new" 
									class="form-control" 
									required="required" 
									placeholder="Country of Made"
									value="<?php echo $item['NewProductCount'] ?>" />
							</div>
						</div>
						<!-- End New Product Count -->
						<!-- Start Refurbished Product Count -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Refurbished product Count</label>
							<div class="col-sm-10 col-md-6">
								<input 
									type="text" 
									name="refurbished" 
									class="form-control" 
									required="required" 
									placeholder="Country of Made"
									value="<?php echo $item['RefurbishedProductCount'] ?>" />
							</div>
						</div>
						<!-- Start Returned Product Count -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Returned product Count</label>
							<div class="col-sm-10 col-md-6">
								<input 
									type="text" 
									name="returned" 
									class="form-control" 
									required="required" 
									placeholder="Country of Made"
									value="<?php echo $item['ReturnedProductCount'] ?>" />
							</div>
						</div>
						<!-- End Returned Product Count -->
						<!-- Start Status Field -->
						<!-- <div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Status</label>
							<div class="col-sm-10 col-md-6">
								<select name="status">
									<option value="1" <?php if ($item['Status'] == 1) { echo 'selected'; } ?>>New</option>
									<option value="2" <?php if ($item['Status'] == 2) { echo 'selected'; } ?>>Like New</option>
									<option value="3" <?php if ($item['Status'] == 3) { echo 'selected'; } ?>>Used</option>
									<option value="4" <?php if ($item['Status'] == 4) { echo 'selected'; } ?>>Very Old</option>
								</select>
							</div>
						</div> -->
						<!-- End Status Field -->
						<!-- Start Members Field -->
						<!-- <div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Member</label>
							<div class="col-sm-10 col-md-6">
								<select name="member">
									<?php
										$allMembers = getAllFrom("*", "users", "", "", "UserID");
										foreach ($allMembers as $user) {
											echo "<option value='" . $user['UserID'] . "'"; 
											if ($item['Member_ID'] == $user['UserID']) { echo 'selected'; } 
											echo ">" . $user['Username'] . "</option>";
										}
									?>
								</select>
							</div>
						</div> -->
						<!-- End Members Field -->
						<!-- Start Categories Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Category</label>
							<div class="col-sm-10 col-md-6">
								<select name="category">
									<?php
										$allCats = getAllFrom("*", "nu_electronics_shop.category", "where Parent = Parent", "", "CategoryID");
										print_r($allCats);
										foreach ($allCats as $cat) {
											echo "<option value='" . $cat['CategoryID'] . "'";
											if ($item['CategoryID'] == $cat['CategoryID']) { echo ' selected'; }
											echo ">" . $cat['CategoryName'] . "</option>";
											// $childCats = getAllFrom("*", "categories", "where parent = {$cat['ID']}", "", "ID");
											// foreach ($childCats as $child) {
											// 	echo "<option value='" . $child['ID'] . "'";
											// 	if ($item['Cat_ID'] == $child['ID']) { echo ' selected'; }
											// 	echo ">--- " . $child['Name'] . "</option>";
											// }
										}
									?>
								</select>
							</div>
						</div>
						<!-- End Categories Field -->
						<!-- Start Tags Field -->
						<!-- <div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Tags</label>
							<div class="col-sm-10 col-md-6">
								<input 
									type="text" 
									name="tags" 
									class="form-control" 
									placeholder="Separate Tags With Comma (,)" 
									value="<?php echo $item['tags'] ?>" />
							</div>
						</div> -->
						<!-- End Tags Field -->
						<!-- Start Submit Field -->
						<!-- Start InStock button -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label form-check-label" for="defaultCheck1">
								In Stock?
							</label>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="InStockYes" id="exampleRadios1" value="yes" <?php if($item['InStock'] > 0) echo "checked"; ?>>
								<label class="form-check-label" for="exampleRadios1">
									Yes
								</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="InStockYes" id="exampleRadios2" value="no" <?php if($item['InStock'] == 0) echo "checked"; ?>>
								<label class="form-check-label" for="exampleRadios2">
									No
								</label>
							</div>		
						</div>
						<!--End InStock button -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Save Item" class="btn btn-primary btn-sm" />
							</div>
						</div>
						<!-- End Submit Field -->
					</form>

					<?php

					// Select All Users Except Admin 

					$stmt = $con->prepare("SELECT 
												comments.*, users.Username AS Member  
											FROM 
												comments
											INNER JOIN 
												users 
											ON 
												users.UserID = comments.user_id
											WHERE item_id = ?");

					// Execute The Statement

					$stmt->execute(array($itemid));

					// Assign To Variable 

					$rows = $stmt->fetchAll();

					if (! empty($rows)) {
						
					?>
					<h1 class="text-center">Manage [ <?php echo $item['Name'] ?> ] Comments</h1>
					<div class="table-responsive">
						<table class="main-table text-center table table-bordered">
							<tr>
								<td>Comment</td>
								<td>User Name</td>
								<td>Added Date</td>
								<td>Control</td>
							</tr>
							<?php
								foreach($rows as $row) {
									echo "<tr>";
										echo "<td>" . $row['comment'] . "</td>";
										echo "<td>" . $row['Member'] . "</td>";
										echo "<td>" . $row['comment_date'] ."</td>";
										echo "<td>
											<a href='comments.php?do=Edit&comid=" . $row['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
											<a href='comments.php?do=Delete&comid=" . $row['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
											if ($row['status'] == 0) {
												echo "<a href='comments.php?do=Approve&comid="
														 . $row['c_id'] . "' 
														class='btn btn-info activate'>
														<i class='fa fa-check'></i> Approve</a>";
											}
										echo "</td>";
									echo "</tr>";
								}
							?>
							<tr>
						</table>
					</div>
					<?php } ?>
				</div>

			<?php

			// If There's No Such ID Show Error Message

			} else {

				echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';

				redirectHome($theMsg);

				echo "</div>";

			}			

		} elseif ($do == 'Update') {

			echo "<h1 class='text-center'>Update Item</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				// Get Variables From The Form

				$id 		= $_POST['itemid'];
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$price 		= floatval($_POST['price']);
				$manufac	= $_POST['manufacturer'];
				$new		= intval($_POST['new']);
				$refurbished = intval($_POST['refurbished']);
				$returned	= intval($_POST['returned']);
				// $status 	= $_POST['status'];
				$cat 		= intval($_POST['category']);
				if($_POST['InStockYes']=='yes')		$instock = 1;
				elseif ($_POST['InStockYes'] == 'no')	$instock = 0;
				// echo $cat;
				// $member 	= $_POST['member'];
				// $tags 		= $_POST['tags'];

				// Validate The Form

				$formErrors = array();

				if (empty($name)) {
					$formErrors[] = 'Name Can\'t be <strong>Empty</strong>';
				}

				if (empty($desc)) {
					$formErrors[] = 'Description Can\'t be <strong>Empty</strong>';
				}

				if (empty($price)) {
					$formErrors[] = 'Price Can\'t be <strong>Empty</strong>';
				}

				if (empty($manufac)) {
					$formErrors[] = 'Country Can\'t be <strong>Empty</strong>';
				}

				// if ($status == 0) {
				// 	$formErrors[] = 'You Must Choose the <strong>Status</strong>';
				// }

				// if ($member == 0) {
				// 	$formErrors[] = 'You Must Choose the <strong>Member</strong>';
				// }

				if ($cat == 0) {
					$formErrors[] = 'You Must Choose the <strong>Category</strong>';
				}

				// Loop Into Errors Array And Echo It

				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {

					// Update The Database With This Info

					$stmt = $con->prepare("UPDATE 
												nu_electronics_shop.Product 
											SET 
												ProductName = ?, 
												TechnicalSpecs = ?, 
												Price = ?, 
												Manufacturer = ?,
												NewProductCount = ?,
												RefurbishedProductCount = ?,
												ReturnedProductCount = ?,
												CategoryID = ?,
												InStock = ?
											WHERE 
												ProductID = ?");

					$stmt->execute(array($name, $desc, $price, $manufac, $new, $refurbished, $returned, $cat, $instock, $id));

					// Echo Success Message

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

					redirectHome($theMsg, 'back');

				}

			} else {

				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

				redirectHome($theMsg);

			}

			echo "</div>";

		} elseif ($do == 'Delete') {

			echo "<h1 class='text-center'>Delete Item</h1>";
			echo "<div class='container'>";

				// Check If Get Request Item ID Is Numeric & Get The Integer Value Of It

				$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('ProductID', 'nu_electronics_shop.product', $itemid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("
					SET FOREIGN_KEY_CHECKS=0;
					DELETE FROM nu_electronics_shop.product WHERE ProductID = ?;
					SET FOREIGN_KEY_CHECKS=1;
					");

					$stmt->execute(array($itemid));

					$theMsg = "<div class='alert alert-success'>" . "1 " . ' Record Deleted</div>';

					redirectHome($theMsg, 'back');

				} else {

					$theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

					redirectHome($theMsg);

				}

			echo '</div>';

		} elseif ($do == 'Approve') {

			echo "<h1 class='text-center'>Approve Item</h1>";
			echo "<div class='container'>";

				// Check If Get Request Item ID Is Numeric & Get The Integer Value Of It

				$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('Item_ID', 'items', $itemid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");

					$stmt->execute(array($itemid));

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

					redirectHome($theMsg, 'back');

				} else {

					$theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

					redirectHome($theMsg);

				}

			echo '</div>';

		}

		include $tpl . 'footer.php';

	} else {

		header('Location: index.php');

		exit();
	}

	ob_end_flush(); // Release The Output

?>