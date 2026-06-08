<?php
include '../includes/supplierheader.php';

$supplier_id = $_SESSION['supplier'];

// ================= ACTIONS =================

if(isset($_GET['accept']))
{
    $oid = $_GET['accept'];
    $conn->query("UPDATE order_info SET order_status=1 WHERE order_id='$oid'");
    header("Location: new_orders.php");
    exit();
}

if(isset($_GET['reject']))
{
    $oid = $_GET['reject'];
    $conn->query("UPDATE order_info SET order_status=4 WHERE order_id='$oid'");
    header("Location: new_orders.php");
    exit();
}

if(isset($_GET['ship']))
{
    $oid = $_GET['ship'];
    $conn->query("UPDATE order_info SET order_status=2 WHERE order_id='$oid'");
    header("Location: new_orders.php");
    exit();
}

if(isset($_GET['deliver']))
{
    $oid = $_GET['deliver'];
    $conn->query("UPDATE order_info SET order_status=3 WHERE order_id='$oid'");
    header("Location: new_orders.php");
    exit();
}



// ================= STATUS BADGE =================

function getStatusBadge($status)
{
    switch($status)
    {
        case 0: return "<span class='badge bg-warning text-dark'>Pending</span>";
        case 1: return "<span class='badge bg-info'>Accepted</span>";
        case 2: return "<span class='badge bg-primary'>Shipped</span>";
        case 3: return "<span class='badge bg-success'>Delivered</span>";
        case 5: return "<span class='badge bg-dark'>Received by customer</span>";
        case 4: return "<span class='badge bg-danger'>Rejected</span>";
        case 6: return "<span class='badge bg-danger'>Returned</span>";
        default: return "<span class='badge bg-secondary'>Unknown</span>";
    }
}

// ================= FETCH ORDERS =================

$orders = $conn->query("
SELECT DISTINCT o.* 
FROM order_info o
JOIN cart_detail cd ON o.cart_id = cd.cart_id
JOIN product_info p ON cd.product_id = p.product_id
WHERE p.supplier_id='$supplier_id'
ORDER BY o.order_id DESC
");
?>

<section class="hero">
<h1>New Orders</h1>
</section>

<div class="container mt-5">

<?php if($orders->num_rows == 0) { ?>

<div class="text-center p-5">
<h4>No orders available</h4>
</div>

<?php } else { ?>

<?php while($o = $orders->fetch_assoc()) { ?>

<div class="card shadow-lg mb-4 p-4 border-0">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center">
<div>
<h5>Order #<?php echo $o['order_id']; ?></h5>
<small class="text-muted"><?php echo $o['order_date']; ?></small>
</div>

<div>
<?php echo getStatusBadge($o['order_status']); ?>
</div>

<?php if($o['order_status'] == 6) { ?>
    <div class="mt-2">
        <span class="text-danger fw-bold">
            📦 Product Returned by Customer
        </span>
    </div>
<?php } ?>

</div>

<hr>

<?php
$cart_id = $o['cart_id'];

$items = $conn->query("
SELECT cd.*, p.product_name, p.product_img, p.price 
FROM cart_detail cd
JOIN product_info p ON cd.product_id=p.product_id
WHERE cd.cart_id='$cart_id' AND p.supplier_id='$supplier_id'
");

$total = 0;

while($item = $items->fetch_assoc()) { 
    $subtotal = $item['price'] * $item['qty'];
    $total += $subtotal;
?>

<div class="row align-items-center mb-3">

<div class="col-md-2 text-center">
<img src="../uploads/<?php echo $item['product_img']; ?>" 
style="height:100px; object-fit:contain;">
</div>

<div class="col-md-7">
<h6 class="mb-1"><?php echo $item['product_name']; ?></h6>
<small>Qty: <?php echo $item['qty']; ?></small>
</div>

<div class="col-md-3 text-end">
₹<?php echo $subtotal; ?>
</div>

</div>

<?php } ?>

<hr>

<p><b>Total (Your Products):</b> ₹<?php echo $total; ?></p>

<div class="mt-3">

<!-- PENDING -->
<?php if($o['order_status'] == 0) { ?>
<a href="?accept=<?php echo $o['order_id']; ?>" class="btn btn-success btn-sm">Accept</a>
<a href="?reject=<?php echo $o['order_id']; ?>" class="btn btn-danger btn-sm">Reject</a>
<?php } ?>

<!-- ACCEPTED -->
<?php if($o['order_status'] == 1) { 

$bill_check = $conn->query("
SELECT * FROM bill_info 
WHERE order_id='".$o['order_id']."' AND supplier_id='$supplier_id'
");

if($bill_check->num_rows == 0) {
?>

<a href="generate_bill.php?order_id=<?php echo $o['order_id']; ?>" 
class="btn btn-primary btn-sm">
Generate Bill
</a>

<?php } else { ?>

<a href="?ship=<?php echo $o['order_id']; ?>" class="btn btn-warning btn-sm">
Ship
</a>

<?php } } ?>

<!-- SHIPPED -->
<?php if($o['order_status'] == 2) { ?>
<a href="?deliver=<?php echo $o['order_id']; ?>" class="btn btn-success btn-sm">
Mark Delivered
</a>
<?php } ?>

<!-- DELIVERED -->
<?php if($o['order_status'] == 3) { ?>
<span class="text-muted">Waiting for customer confirmation</span>
<?php } ?>

<!-- COMPLETED -->
<?php if($o['order_status'] == 5) { ?>
<span class="text-success fw-bold">Order Completed</span>
<?php } ?>

</div>

</div>

<?php } ?>

<?php } ?>

</div>

<?php include '../includes/supplierfooter.php'; ?>