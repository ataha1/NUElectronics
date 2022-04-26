<?php 
	session_start();
	include 'init.php';
?>

<h1 class="text-center">Manage cart</h1>
<?php
    $page = $_GET['do'];
    if($page == ''){?>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>Product Name</td>
                        <td>Quantity</td>
                        <td>Unit Price</td>
                        <td>Technical Specs</td>
                        <td>Control</td>
                    </tr>
                    <?php
                        $totalPrice = 0;
                        $cartID = intval($_GET['cartid']);
                        $query = "SELECT product.ProductName, cart_item.UnitPrice, product.TechnicalSpecs, cart_item.Quantity, cart_item.CartID, cart_item.ProductID
                        From nu_electronics_shop.cart_item 
                        LEFT JOIN nu_electronics_shop.product ON cart_item.ProductID = product.ProductID
                        WHERE cart_item.CartID = ?;";
                        $stmt = $con->prepare($query);
                        $stmt->execute(array($cartID));
                        $cartItems = $stmt->fetchAll();
                        // print_r($cartItems);
                        foreach($cartItems as $item) {
                            $totalPrice += intval($item['UnitPrice']) * intval($item['Quantity']);
                            echo "<tr>";
                                echo "<td>" . $item['ProductName'] . "</td>";
                                echo "<td>" . $item['Quantity'] . "</td>";
                                echo "<td>" . $item['UnitPrice'] . "</td>";
                                echo "<td>" . $item['TechnicalSpecs'] . "</td>";
                                echo "<td>".
                                "<a href='cart.php?do=Delete&cartid=" . $item['CartID'] ."&productid=".$item['ProductID']. "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>"
                                . "</td>";
                            echo "</tr>";
                        }
                    ?>
                    <tr>
                    <tr>
                        <td>Total Price</td>
                        <td><?php echo $totalPrice; ?></td>
                    </tr>
                </table>
            </div>
            <a href="purchase.php?do=continue" class="btn btn-md btn-primary">
                <i class="fa fa-money"></i> Continue to pay
            </a>
        </div>
    <?php
    }
    elseif($page == 'Delete'){
        $cartID     =   intval($_GET['cartid']);
        $productID  =   intval($_GET['productid']);
        $stmt = $con->prepare("DELETE FROM nu_electronics_shop.cart_item WHERE CartID = ? AND ProductID = ?");

        $stmt->execute(array($cartID, $productID));

        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';

        redirectHome($theMsg, 'back');
    }
    elseif($page == 'add'){
        $item = intval($_GET['itemid']);
        $status = intval($_GET['status']);
        $price = intval($_GET['price']);
        $getUser = $con->prepare("SELECT CartID
					FROM nu_electronics_shop.user 
					LEFT JOIN nu_electronics_shop.cart ON cart.UserID = user.UserID
					WHERE user.UserName = ?;");
        $getUser->execute(array($sessionUser));
        $info = $getUser->fetch();
        $cartID = $info['CartID'];
        $mul = 1.0;
        if($status == 0)    $mul = 0.9;
        $q = "SELECT CartID, ProductID, Quantity FROM nu_electronics_shop.cart_item WHERE CartID = ? AND ProductID = ?";
        $stmt = $con->prepare($q);
        $stmt->execute(array($cartID, $item));
        $count = $stmt->rowCount();
        if($count == 0){
            $q = "INSERT INTO nu_electronics_shop.cart_item(CartID, ProductID, UnitPrice, Quantity)
            VALUES(?, ?, ?, ?);
            ";
            $stmt = $con->prepare($q);
            $stmt->execute(array($cartID, $item, $mul*$price, 1));
        }
        else{
            $info   =   $stmt->fetch();
            $quantity   =   $info['Quantity'];
            $quantity++;
            $q = "UPDATE nu_electronics_shop.cart_item 
            SET Quantity = ?
            WHERE CartID = ? AND ProductID = ?";
            $stmt = $con->prepare($q);
            $stmt->execute(array($quantity, $cartID, $item));
        }
        $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Your item was added successfully</div>';
        // redirectHome($msg, 'cart.php');
    }
?>
<?php include $tpl . 'footer.php'; ?>