<?php
	ob_start();
	session_start();
	$pageTitle = 'Homepage';
	include 'init.php';
?>
<div class="container">
	<div class="row">
		<?php
			$allItems = getAllFrom('*', 'nu_electronics_shop.product', 'where ProductID = ProductID', NULL, 'ProductID');
			foreach ($allItems as $item) {
				if($item['InStock']>0){
					echo '<div class="col-sm-6 col-md-4">';
						echo '<div class="thumbnail item-box">';
							echo '<span class="price-tag">$' . $item['Price'] . '</span>';
							echo '<img class="img-responsive" src="image02.jfif" alt="" />';
							echo '<div class="caption">';
								echo '<h3><a href="items.php?itemid='. $item['ProductID'] .'">' . $item['ProductName'] .'</a></h3>';
								echo '<p>' . $item['TechnicalSpecs'] . '</p>';
								// echo '<div class="date">' . $item['Add_Date'] . '</div>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
				}
			}
		?>
	</div>
</div>
<?php
	include $tpl . 'footer.php'; 
	ob_end_flush();
?>