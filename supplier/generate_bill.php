<?php
include '../includes/supplierheader.php';

$supplier_id = $_SESSION['supplier'];

// ================= VALIDATION =================

if(!isset($_GET['order_id']))
{
    header("Location: new_orders.php");
    exit();
}

$order_id = $_GET['order_id'];

// ================= FETCH ORDER =================

$order = $conn->query("
SELECT o.*, c.customer_name, c.mobile_no, c.address 
FROM order_info o
JOIN customer_info c ON o.customer_id = c.customer_id
WHERE o.order_id='$order_id'
");

if(!$order || $order->num_rows == 0)
{
    echo "<div class='container mt-5'><h4>Order not found</h4></div>";
    include '../includes/supplierfooter.php';
    exit();
}

$o = $order->fetch_assoc();

$cart_id     = $o['cart_id'];
$customer_id = $o['customer_id'];

// ================= CHECK BILL EXISTS =================

$check = $conn->query("
SELECT * FROM bill_info 
WHERE order_id='$order_id' AND supplier_id='$supplier_id'
");

if($check && $check->num_rows > 0)
{
    echo "<script>alert('Bill already generated'); window.location='new_orders.php';</script>";
    exit();
}

// ================= FETCH ITEMS =================

$items = $conn->query("
SELECT cd.*, p.product_name, p.price 
FROM cart_detail cd
JOIN product_info p ON cd.product_id=p.product_id
WHERE cd.cart_id='$cart_id' AND p.supplier_id='$supplier_id'
");

if(!$items)
{
    echo "<div class='container mt-5'><h4>Error fetching items</h4></div>";
    include '../includes/supplierfooter.php';
    exit();
}

// ================= GENERATE BILL =================

if(isset($_POST['confirm_bill']))
{
    $total = $_POST['total_amount'];

    $conn->query("
    INSERT INTO bill_info 
    (bill_date, order_id, cart_id, customer_id, supplier_id, total_amount)
    VALUES (NOW(), '$order_id', '$cart_id', '$customer_id', '$supplier_id', '$total')
    ");

    header("Location: new_orders.php");
    exit();
}
?>

<section class="hero">
<h1>Generate Bill</h1>
</section>

<div class="container mt-5">

<div class="card shadow-lg p-4">

<h4>Order #<?php echo $order_id; ?></h4>

<!-- CUSTOMER DETAILS -->
<div class="border p-3 rounded bg-light mt-3">
<h5>Delivery Details</h5>
<p><b><?php echo $o['customer_name']; ?></b></p>
<p><?php echo $o['address']; ?></p>
<p>Contact No. <?php echo $o['mobile_no']; ?></p>
</div>

<hr>

<!-- ITEMS TABLE -->
<table class="table">
<thead>
<tr>
<th>Product</th>
<th>Price</th>
<th>Qty</th>
<th>Total</th>
</tr>
</thead>

<tbody>

<?php
$total = 0;

while($item = $items->fetch_assoc()) {

$subtotal = $item['price'] * $item['qty'];
$total += $subtotal;
?>

<tr>
<td><?php echo $item['product_name']; ?></td>
<td>₹<?php echo $item['price']; ?></td>
<td><?php echo $item['qty']; ?></td>
<td>₹<?php echo $subtotal; ?></td>
</tr>

<?php } ?>

</tbody>
</table>

<hr>

<h4 class="text-end">Grand Total: ₹<?php echo $total; ?></h4>

<!-- FORM -->
<form method="POST" class="text-end mt-3">

<input type="hidden" name="total_amount" value="<?php echo $total; ?>">

<button type="submit" name="confirm_bill" class="btn btn-success">
Confirm & Generate Bill
</button>

<a href="new_orders.php" class="btn btn-secondary">Cancel</a>

</form>

</div>

</div>

<?php include '../includes/supplierfooter.php'; ?>