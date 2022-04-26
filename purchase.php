<?php
	ob_start();
	session_start();
	$pageTitle = 'Contact';
	include 'init.php';
	if (isset($_SESSION['user'])) {
        if($_GET['do'] == 'continue'){?>
            <h1 class="text-center">Add Contact</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=pay" method="POST" enctype="multipart/form-data">
                        <!-- Start Username Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Address</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="address" class="form-control" autocomplete="off" required="required" placeholder="Address" />
                            </div>
                        </div>
                        <!-- End Username Field -->
                        <!-- Start Password Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Phone</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="phone" class="form-control" required="required" autocomplete="new-password" placeholder="Phone" />
                            </div>
                        </div>
                        <!-- End Password Field -->
                        <!-- Start Email Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">City</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="city" class="form-control" required="required" placeholder="City" />
                            </div>
                        </div>
                        <!-- End Email Field -->
                        <!-- Start First Name Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Postal Code</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="post" class="form-control" required="required" placeholder="Postal Code" />
                            </div>
                        </div>
                        <!-- End First Name Field -->
                        <!-- Start Last Name Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Appartment Number</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="appnum" class="form-control" required="required" placeholder="Apartment Number" />
                            </div>
                        </div>
                        <!-- End Last Name Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label form-check-label" for="defaultCheck1">
                                Payment Method
                            </label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="paymentmethod" id="exampleRadios1" value="cash" >
                                <label class="form-check-label" for="exampleRadios1">
                                    Cash
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="paymentmethod" id="exampleRadios2" value="install">
                                <label class="form-check-label" for="exampleRadios2">
                                    Installments
                                </label>
                            </div>		
                        </div>
                        <!-- Start Submit Field -->
                        <div class="form-group form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" name = "cash" value="pay" class="btn btn-primary btn-lg" />
                            </div>
                        </div>
                        <!-- End Submit Field -->
                    </form>
                </div>

            <?php
        }
        elseif($_GET['do'] == 'pay'){
            $address    =   $_POST['address'];
            $phone      =   $_POST['phone'];
            $city       =   $_POST['city'];
            $postcode   =   $_POST['post'];
            $appNum     =   $_POST['appnum'];
            $method     =   $_POST['paymentmethod'];
            if($method == "cash"){
                $q = "SELECT UserID FROM nu_electronics_shop.user WHERE UserName = ?";
                $stmt = $con->prepare($q);
                $stmt->execute(array($sessionUser));
                $row = $stmt->fetch();
                $userID = $row['UserID'];

                $q = "INSERT INTO nu_electronics_shop.contact(Address, Phone, City, PostalCode, AppartmentNumber, UserID)
                VALUES(?, ?, ?, ?, ?,?)";
                $stmt = $con->prepare($q);
                $stmt->execute(array($address, $phone, $city, $postcode, $appNum, $userID));

                $q2 = "SELECT * FROM nu_electronics_shop.user WHERE UserName = ?";
                $stmt = $con->prepare($q2);
                $stmt->execute(array($sessionUser));
                $user = $stmt->fetch();
                $userID = $user['UserID'];
                $balance = $user['Balance'];

                $q5 = "SELECT CartID FROM nu_electronics_shop.cart WHERE UserID = ?";
                $stmt = $con->prepare($q5);
                $stmt->execute(array($userID));
                $cartData = $stmt->fetch();
                $cartID = $cartData['CartID'];

                $q3 = "SELECT * FROM nu_electronics_shop.cart_item WHERE CartID = ?";
                $stmt = $con->prepare($q3);
                $stmt->execute(array($cartID));
                $items = $stmt->fetchAll();
                $sum = 0.0;
                foreach($items as $item){
                    $price = floatval($item['UnitPrice']);
                    $quantity = intval($item['Quantity']);
                    $sum += $price * $quantity;
                }
                
                $balance -= $sum;
                echo $balance;

                $q4 = "UPDATE nu_electronics_shop.user
                SET Balance = ?
                WHERE UserID = ?";
                $stmt = $con->prepare($q4);
                $stmt->execute(array($balance, $userID));            

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