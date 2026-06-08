<?php
include '../includes/customerheader.php';

$customer_id = $_SESSION['customer'];

// CHECK ORDER ID
if(!isset($_GET['order_id']))
{
    header("Location: orders.php");
    exit();
}

$order_id = $_GET['order_id'];

// FETCH ORDER + CUSTOMER (SECURITY CHECK)
$order = $conn->query("
SELECT o.*, c.customer_name, c.mobile_no, c.address 
FROM order_info o
JOIN customer_info c ON o.customer_id = c.customer_id
WHERE o.order_id='$order_id' AND o.customer_id='$customer_id'
");

if($order->num_rows == 0)
{
    echo "<div class='container mt-5 text-center'>
            <h4>Invalid Order</h4>
          </div>";
    include '../includes/customerfooter.php';
    exit();
}

$o = $order->fetch_assoc();
$cart_id = $o['cart_id'];
?>

<section class="hero">
<h1>Bill Details 🧾</h1>
</section>

<div class="container mt-5">

<!-- DELIVERY DETAILS -->
<div class="card shadow-lg p-4 mb-4 border-0">

<h4>Order #<?php echo $order_id; ?></h4>

<div class="border p-3 rounded bg-light mt-3">
<h5>Delivery Details</h5>
<p><b><?php echo $o['customer_name']; ?></b></p>
<p><?php echo $o['address']; ?></p>
<p>Contact No. <?php echo $o['mobile_no']; ?></p>
</div>

</div>

<?php
// FETCH ALL SUPPLIER BILLS
$bills = $conn->query("
SELECT b.*, s.supplier_name 
FROM bill_info b
JOIN supplier_info s ON b.supplier_id = s.supplier_id
WHERE b.order_id='$order_id'
");
?>

<?php if($bills && $bills->num_rows > 0) { ?>

<?php while($bill = $bills->fetch_assoc()) { ?>

<div class="card shadow-sm mb-4 p-4 border-0">

<h5>Supplier: <?php echo $bill['supplier_name']; ?></h5>
<small class="text-muted">Bill Date: <?php echo $bill['bill_date']; ?></small>

<hr>

<?php
// FETCH ITEMS FOR THIS SUPPLIER
$items = $conn->query("
SELECT cd.*, p.product_name, p.price 
FROM cart_detail cd
JOIN product_info p ON cd.product_id=p.product_id
WHERE cd.cart_id='".$bill['cart_id']."' 
AND p.supplier_id='".$bill['supplier_id']."'
");
?>

<table class="table table-bordered text-center align-middle">
<thead class="table-light">
<tr>
<th>Product</th>
<th>Price (₹)</th>
<th>Qty</th>
<th>Total (₹)</th>
</tr>
</thead>

<tbody>

<?php while($item = $items->fetch_assoc()) { ?>

<tr>
<td><?php echo $item['product_name']; ?></td>
<td>₹<?php echo $item['price']; ?></td>
<td><?php echo $item['qty']; ?></td>
<td>₹<?php echo $item['price'] * $item['qty']; ?></td>
</tr>

<?php } ?>

</tbody>
</table>

<hr>

<h5 class="text-end text-success">
Supplier Total: ₹<?php echo $bill['total_amount']; ?>
</h5>

</div>

<?php } ?>

<?php } else { ?>

<div class="alert alert-warning text-center">
Bill not generated yet
</div>

<?php } ?>

<!-- BACK BUTTON -->
<div class="text-center mt-4">
<a href="orders.php" class="btn btn-secondary px-4">
Back to Orders
</a>
</div>

</div>

<?php include '../includes/customerfooter.php'; ?>