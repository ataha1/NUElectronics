<?php

	/*
	================================================
	== Manage Members Page
	== You Can Add | Edit | Delete Members From Here
	================================================
	*/

	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = 'Members';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		// Start Manage Page

		if ($do == 'Manage') { // Manage Members Page

			$query = '';

			if (isset($_GET['page']) && $_GET['page'] == 'Pending') {

				$query = 'AND RegStatus = 0';

			}

			// Select All Users Except Admin 
            $q = "SELECT returned.ReturnID, product.ProductName, product.ProductID, purchased_products.Quantity, purchased_products.PurchaseID, returned.ReturnDate 
            FROM nu_electronics_shop.returned
            LEFT JOIN nu_electronics_shop.purchased_products
            ON nu_electronics_shop.returned.PurchaseID = nu_electronics_shop.purchased_products.PurchaseID
            LEFT JOIN nu_electronics_shop.product
            ON nu_electronics_shop.purchased_products.ProductID = nu_electronics_shop.product.ProductID;";

			$stmt = $con->prepare($q);

			// Execute The Statement

			$stmt->execute();

			// Assign To Variable 

			$rows = $stmt->fetchAll();

			if (! empty($rows)) {

			?>

			<h1 class="text-center">Manage Return Requests</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table manage-members text-center table table-bordered">
						<tr>
							<td>#ID</td>
							<!-- <td>Avatar</td> -->
							<td>Product Name</td>
							<td>ProductID</td>
							<td>Quantity</td>
                            <td>Purchase ID</td>
							<td>Return Date</td>
							<!-- <td>Registered Date</td> -->
							<td>Control</td>
						</tr>
						<?php
							foreach($rows as $row) {
                                if($row['PurchaseID'] != null){
                                    echo "<tr>";
                                        echo "<td>" . $row['ReturnID'] . "</td>";
                                        echo "<td>" . $row['ProductName'] . "</td>";
                                        echo "<td>" . $row['ProductID'] . "</td>";
                                        echo "<td>" . $row['Quantity'] . "</td>";
                                        echo "<td>" . $row['PurchaseID'] . "</td>";
                                        echo "<td>" . $row['ReturnDate'] . "</td>";
                                        // echo "<td>" . $row['Date'] ."</td>";
                                        echo "<td>
                                            <a href='' class='btn btn-success'><i class='fa fa-edit'></i> Approve</a>
                                            <a href='' class='btn btn-danger confirm'><i class='fa fa-close'></i> Discard </a>";										
                                        echo "</td>";
                                    echo "</tr>";
                                }
							}
						?>
						<!-- <tr> -->
					</table>
				</div>
				<!-- <a href="members.php?do=Add" class="btn btn-primary">
					<i class="fa fa-plus"></i> New Member
				</a> -->
			</div>

			<?php } else {

				echo '<div class="container">';
					echo '<div class="nice-message">There\'s No Members To Show</div>';
					echo '<a href="members.php?do=Add" class="btn btn-primary">
							<i class="fa fa-plus"></i> New Member
						</a>';
				echo '</div>';

			} ?>

		<?php 

		} 
		
				include $tpl . 'footer.php';

	} else {

		header('Location: index.php');

		exit();
	}

	ob_end_flush(); // Release The Output

?>