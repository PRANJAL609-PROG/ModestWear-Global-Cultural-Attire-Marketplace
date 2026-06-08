<?php
include '../includes/adminheader.php';

$status_filter   = $_GET['status'] ?? "";
$supplier_filter = $_GET['supplier_id'] ?? "";
$customer_filter = $_GET['customer_id'] ?? "";
$filter = $_GET['filter'] ?? "";


$query = "
SELECT DISTINCT o.*, c.customer_name 
FROM order_info o
JOIN customer_info c ON o.customer_id = c.customer_id
";

if($supplier_filter != "")
{
    $query .= "
    JOIN cart_detail cd ON o.cart_id = cd.cart_id
    JOIN product_info p ON cd.product_id = p.product_id
    ";
}

$query .= " WHERE 1 ";

// STATUS
if($status_filter != "")
{
    $query .= " AND o.order_status = '$status_filter'";
}

// CUSTOMER
if($customer_filter != "")
{
    $query .= " AND o.customer_id = '$customer_filter'";
}

// SUPPLIER
if($supplier_filter != "")
{
    $query .= " AND p.supplier_id = '$supplier_filter'";
}

if($filter == "bill")
{
    $query .= " AND o.order_id IN (SELECT order_id FROM bill_info)";
}
else if($filter == "refund")
{
    $query .= " AND (o.order_status = 6 OR o.refund_status = 1)";
}


// STATUS BADGE
function getStatusBadge($status)
{
    switch($status)
    {
        case 0: return "<span class='badge bg-warning text-dark'>Pending</span>";
        case 1: return "<span class='badge bg-info'>Accepted</span>";
        case 2: return "<span class='badge bg-primary'>Shipped</span>";
        case 3: return "<span class='badge bg-success'>Delivered</span>";
        case 5: return "<span class='badge bg-dark'>Received</span>";
        case 4: return "<span class='badge bg-danger'>Rejected</span>";
        case 6: return "<span class='badge bg-danger'>Returned</span>";
        default: return "<span class='badge bg-secondary'>Unknown</span>";
    }
}
?>

<style>
.gold-text { color:#d4af37; font-weight:bold; }
.btn-gold { background:#d4af37; color:black; }
.btn-gold:hover { background:#b8962e; color:white; }
</style>

<section class="hero">
<h1 class="gold-text">All Orders</h1>
<p>Manage and track all marketplace orders</p>
</section>

<div class="container mt-5">

<!-- FILTER FORM -->
<form method="GET" class="row g-2 mb-4">

<div class="col-md-3">
<select name="status" class="form-control">
<option value="">All Status</option>
<option value="0" <?php if($status_filter=="0") echo "selected"; ?>>Pending</option>
<option value="1" <?php if($status_filter=="1") echo "selected"; ?>>Accepted</option>
<option value="2" <?php if($status_filter=="2") echo "selected"; ?>>Shipped</option>
<option value="3" <?php if($status_filter=="3") echo "selected"; ?>>Delivered</option>
<option value="5" <?php if($status_filter=="5") echo "selected"; ?>>Received</option>
<option value="4" <?php if($status_filter=="4") echo "selected"; ?>>Rejected</option>
<option value="6" <?php if($status_filter=="6") echo "selected"; ?>>Returned</option>
</select>
</div>

<div class="col-md-3">
<select name="bill" class="form-control">
<option value="">All Orders</option>
<option value="1" <?php if($bill_filter=="1") echo "selected"; ?>>
Only Orders With Bills
</option>
</select>
</div>

<!-- ✅ NEW REFUND FILTER -->
<div class="col-md-3">
<select name="filter" class="form-control">

<option value="">All Orders</option>

<option value="bill"
<?php if($filter=="bill") echo "selected"; ?>>
Orders With Bills
</option>

<option value="refund"
<?php if($filter=="refund") echo "selected"; ?>>
Refund / Return Orders
</option>

</select>
</div>

</form>


<?php if(!$orders || $orders->num_rows == 0) { ?>

<div class="text-center p-5">
<h4>No orders found</h4>
</div>

<?php } else { ?>

<?php while($o = $orders->fetch_assoc()) { ?>

<div class="card shadow-lg mb-4 border-0">

<div class="p-4">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center">
<div>
<h5 class="gold-text">Order #<?php echo $o['order_id']; ?></h5>
<small class="text-muted"><?php echo $o['order_date']; ?></small><br>
<b>Customer:</b> <?php echo $o['customer_name']; ?>
</div>

<div>
<?php echo getStatusBadge($o['order_status']); ?>
</div>
</div>

<hr>

<?php
$cart_id = $o['cart_id'];

$item_query = "
SELECT cd.*, p.product_name, p.price, s.supplier_name 
FROM cart_detail cd
JOIN product_info p ON cd.product_id = p.product_id
JOIN supplier_info s ON p.supplier_id = s.supplier_id
WHERE cd.cart_id='$cart_id'
";

if($supplier_filter != "")
{
    $item_query .= " AND p.supplier_id = '$supplier_filter'";
}

$items = $conn->query($item_query);

while($item = $items->fetch_assoc()) {

$subtotal = $item['price'] * $item['qty'];
?>

<div class="row align-items-center mb-3">

<div class="col-md-6">
<h6><?php echo $item['product_name']; ?></h6>
<small>Supplier: <?php echo $item['supplier_name']; ?></small><br>
<small>Qty: <?php echo $item['qty']; ?></small>
</div>

<div class="col-md-3">
₹<?php echo $item['price']; ?>
</div>

<div class="col-md-3 text-end fw-bold">
₹<?php echo $subtotal; ?>
</div>

</div>

<?php } ?>

<hr>

<!-- FOOTER -->
<div class="d-flex justify-content-between align-items-center">

<div>
<b>Total Amount:</b> ₹<?php echo $o['total_amt']; ?><br>
<b>Delivery Address:</b> <?php echo $o['delivery_add']; ?>
</div>

<!-- ✅ REFUND INFO -->
<div class="text-end">

<?php if(isset($o['refund_status']) && $o['refund_status'] == 1) { ?>
    <span class="text-success fw-bold">
        💸 Refunded ₹<?php echo $o['refund_amount']; ?>
    </span><br>
    <small><?php echo $o['refund_date']; ?></small>
<?php } elseif($o['order_status'] == 6) { ?>
    <span class="text-warning">
        🔄 Return Requested
    </span>
<?php } ?>

</div>

</div>

</div>

</div>

<?php } ?>

<?php } ?>

</div>

<?php include '../includes/adminfooter.php'; ?>