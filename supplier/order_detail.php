<?php
include '../includes/supplierheader.php';

$supplier_id = $_SESSION['supplier'];

// ================= APPROVE RETURN =================
if(isset($_GET['approve_return']))
{
    $oid = $_GET['approve_return'];

    $conn->query("
    UPDATE order_info 
    SET return_approval = 1,
        refund_status = 1
    WHERE order_id = '$oid'
    ");

    header("Location: order_detail.php?order_id=".$oid);
    exit();
}

// ================= GET ORDER =================
if(!isset($_GET['order_id']))
{
    header("Location: all_orders_report.php");
    exit();
}

$order_id = $_GET['order_id'];

$order = $conn->query("
SELECT o.*, c.customer_name 
FROM order_info o
JOIN customer_info c ON o.customer_id = c.customer_id
WHERE o.order_id='$order_id'
");

if(!$order || $order->num_rows == 0)
{
    echo "<h3 class='text-center mt-5'>Order not found</h3>";
    include '../includes/supplierfooter.php';
    exit();
}

$o = $order->fetch_assoc();
$cart_id = $o['cart_id'];
?>

<style>
.gold-text { color:#d4af37; font-weight:bold; }

.table thead {
    background-color: black;
    color: #d4af37;
}

.btn-gold {
    background:#d4af37;
    color:black;
}

.btn-gold:hover {
    background:#b8962e;
    color:white;
}
</style>

<section class="hero">
<h1 class="gold-text">Order Detail 📦</h1>
<p>View complete order breakdown</p>
</section>

<div class="container mt-5">

<div class="text-center mb-4">
<h4 class="gold-text">Order #<?php echo $o['order_id']; ?></h4>
<p>
<b>Customer:</b> <?php echo $o['customer_name']; ?> |
<b>Date:</b> <?php echo date("d-m-Y", strtotime($o['order_date'])); ?>
</p>
</div>

<?php
$items = $conn->query("
SELECT cd.*, p.product_name, p.product_img, p.price 
FROM cart_detail cd
JOIN product_info p ON cd.product_id = p.product_id
WHERE cd.cart_id='$cart_id' AND p.supplier_id='$supplier_id'
");

$total = 0;
?>

<?php if(!$items || $items->num_rows == 0) { ?>

<div class="text-center">
<h4>No products found for this order</h4>
</div>

<?php } else { ?>

<div class="card shadow-lg p-4 border-0">

<div class="table-responsive">
<table class="table table-bordered text-center align-middle">

<thead>
<tr>
<th>Image</th>
<th>Product Name</th>
<th>Qty</th>
<th>Price</th>
<th>Amount</th>
</tr>
</thead>

<tbody>

<?php while($item = $items->fetch_assoc()) {

$subtotal = $item['qty'] * $item['price'];
$total += $subtotal;
?>

<tr>
<td>
<img src="../uploads/<?php echo $item['product_img']; ?>" 
style="width:100px; height:100px; object-fit:contain;">
</td>

<td><?php echo $item['product_name']; ?></td>
<td><?php echo $item['qty']; ?></td>
<td>₹<?php echo $item['price']; ?></td>
<td class="fw-bold">₹<?php echo $subtotal; ?></td>
</tr>

<?php } ?>

</tbody>
</table>
</div>

<hr>

<div class="text-end">
<h4>Total Amount: ₹<?php echo $total; ?></h4>
</div>

</div>

<!-- ================= RETURN SECTION ================= -->

<?php if($o['order_status'] == 6) { ?>

<div class="text-end mt-3">

<span class="text-danger fw-bold d-block">
⚠️ Product Returned by Customer
</span>

<?php if(!empty($o['return_reason'])) { ?>
<small><b>Reason:</b> <?php echo $o['return_reason']; ?></small><br>
<?php } ?>

<?php if(($o['return_approval'] ?? 0) == 0) { ?>

<a href="?order_id=<?php echo $o['order_id']; ?>&approve_return=<?php echo $o['order_id']; ?>" 
class="btn btn-success btn-sm mt-2">
Approve & Refund
</a>

<?php } ?>

<?php if(($o['refund_status'] ?? 0) == 1) { ?>

<span class="text-success fw-bold d-block mt-2">
💸 Refunded Amount: ₹<?php echo $o['refund_amount']; ?>
</span>

<?php } ?>

</div>

<?php } ?>

<?php } ?>

<div class="mt-4 text-center">
<a href="all_orders_report.php" class="btn btn-gold">
⬅ Back to Report
</a>
</div>

</div>

<?php include '../includes/supplierfooter.php'; ?>