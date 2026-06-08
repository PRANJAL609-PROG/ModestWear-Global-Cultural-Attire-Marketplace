<?php
include '../includes/customerheader.php';

$customer_id = $_SESSION['customer'];

// GET ACTIVE CART
$cart = $conn->query("SELECT * FROM cart_master 
WHERE customer_id='$customer_id' AND cart_status=0");

$cart_id = null;

if($cart->num_rows > 0)
{
    $cart_data = $cart->fetch_assoc();
    $cart_id = $cart_data['cart_id'];
}

// UPDATE QTY
if(isset($_POST['update']))
{
    $pid = $_POST['product_id'];
    $qty = $_POST['qty'];

    $conn->query("UPDATE cart_detail 
    SET qty='$qty' 
    WHERE cart_id='$cart_id' AND product_id='$pid'");
}

// DELETE ITEM
if(isset($_GET['delete']))
{
    $pid = $_GET['delete'];

    $conn->query("DELETE FROM cart_detail 
    WHERE cart_id='$cart_id' AND product_id='$pid'");

    header("Location: cart.php");
    exit();
}

// FETCH ITEMS
if($cart_id != null)
{
    $items = $conn->query("SELECT cd.*, p.product_name, p.product_img, p.description 
    FROM cart_detail cd
    JOIN product_info p ON cd.product_id=p.product_id
    WHERE cd.cart_id='$cart_id'");
}
else
{
    $items = (object)["num_rows" => 0];
}
?>

<style>
.gold-text { color:#d4af37; font-weight:bold; }
.btn-gold { background:#d4af37; color:black; }
.btn-gold:hover { background:#b8962e; color:white; }
</style>

<section class="hero">
<h1 class="gold-text">Your Cart</h1>
</section>

<div class="container mt-5">

<div class="card shadow-lg p-4 border-0">

<h3 class="text-center gold-text mb-4">Shopping Cart</h3>

<?php if($items->num_rows == 0) { ?>

<!-- EMPTY CART -->
<div class="text-center p-5">

<h1 style="font-size:60px;">🛒</h1>
<h4 class="text-muted mb-3">Your cart is empty</h4>
<p class="mb-4">Looks like you haven't added anything yet.</p>

<a href="dashboard.php" class="btn btn-gold px-4">
Continue Shopping
</a>

</div>

<?php } else { ?>

<!-- CART TABLE -->
<table class="table table-bordered align-middle text-center">

<tr style="background:black; color:#d4af37;">
<th>Image</th>
<th>Product</th>
<th>Description</th>
<th>Price (₹)</th>
<th>Quantity</th>
<th>Subtotal (₹)</th>
<th>Action</th>
</tr>

<?php
$grand_total = 0;

while($row = $items->fetch_assoc())
{
    $total = $row['price'] * $row['qty'];
    $grand_total += $total;
?>

<tr>

<td>
<img src="../uploads/<?php echo $row['product_img']; ?>" 
style="height:70px;">
</td>

<td><?php echo $row['product_name']; ?></td>

<td class="text-start"><?php echo $row['description']; ?></td>

<td>₹<?php echo $row['price']; ?></td>

<td>
<form method="POST" class="d-flex justify-content-center">
<input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">

<input type="number" name="qty" value="<?php echo $row['qty']; ?>" 
class="form-control me-2" style="width:70px;" min="1">

<button type="submit" name="update" class="btn btn-sm btn-gold">
Update
</button>
</form>
</td>

<td>₹<?php echo $total; ?></td>

<td>
<a href="cart.php?delete=<?php echo $row['product_id']; ?>" 
class="btn btn-sm btn-danger"
onclick="return confirm('Remove item?')">
Delete
</a>
</td>

</tr>

<?php } ?>

</table>

<!-- TOTAL -->
<div class="d-flex justify-content-between align-items-center mt-4">

<h4 class="gold-text mb-0">
Total: ₹<?php echo $grand_total; ?>
</h4>

<a href="checkout.php" class="btn btn-gold px-4">
Place Your Order
</a>

</div>

<?php } ?>

</div>

</div>

<?php include '../includes/customerfooter.php'; ?>