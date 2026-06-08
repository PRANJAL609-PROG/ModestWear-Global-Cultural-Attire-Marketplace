<?php
include '../includes/customerheader.php';

$customer_id = $_SESSION['customer'];

if(isset($_POST['return_btn']))
{
    $oid = $_POST['order_id'];
    $reason = $_POST['reason'];

    // get amount
    $res = $conn->query("SELECT total_amt FROM order_info WHERE order_id='$oid'");
    $row = $res->fetch_assoc();
    $amount = $row['total_amt'];

    $conn->query("
    UPDATE order_info 
    SET order_status = 6,
        return_reason = '$reason',
        return_approval = 0,
        refund_status = 0,
        refund_amount = '$amount'
    WHERE order_id='$oid' AND customer_id='$customer_id'
    ");

    header("Location: orders.php");
    exit();
}

// ================= CONFIRM RECEIVED =================
if(isset($_GET['confirm']))
{
    $oid = $_GET['confirm'];

    $conn->query("UPDATE order_info 
    SET order_status = 5 
    WHERE order_id='$oid' AND customer_id='$customer_id'");

    // prevent repeat on refresh
    header("Location: orders.php");
    exit();
}

// ================= STATUS BADGE =================
function getStatusBadge($status)
{
    switch($status)
    {
        case 0: return "<span class='badge bg-warning text-dark'>Pending</span>";
        case 1: return "<span class='badge bg-info text-dark'>Accepted</span>";
        case 2: return "<span class='badge bg-primary'>Shipped</span>";
        case 3: return "<span class='badge bg-success'>Delivered</span>";
        case 5: return "<span class='badge bg-dark'>Received</span>";
        case 4: return "<span class='badge bg-danger'>Rejected</span>";
        case 6: return "<span class='badge bg-danger'>Returned</span>";
        default: return "<span class='badge bg-secondary'>Unknown</span>";
    }
}

// ================= FETCH ORDERS =================
$orders = $conn->query("
SELECT * FROM order_info 
WHERE customer_id='$customer_id' 
ORDER BY order_id DESC
");
?>

<section class="hero">
<h1>My Orders</h1>
</section>

<div class="container mt-5">

<?php if($orders->num_rows == 0) { ?>

<div class="text-center p-5">
<h3>No orders found</h3>
<a href="dashboard.php" class="btn btn-gold mt-3">Start Shopping</a>
</div>

<?php } else { ?>

<?php while($o = $orders->fetch_assoc()) { ?>

<div class="card shadow-lg mb-4 border-0 p-4">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center">
<div>
<h5 class="mb-1">Order #<?php echo $o['order_id']; ?></h5>
<small class="text-muted">Date: <?php echo $o['order_date']; ?></small>
</div>

<div>
<?php echo getStatusBadge($o['order_status']); ?>
</div>
</div>

<hr>

<!-- PRODUCTS -->
<?php
$cart_id = $o['cart_id'];

$items = $conn->query("
SELECT cd.*, p.product_name, p.product_img 
FROM cart_detail cd
JOIN product_info p ON cd.product_id=p.product_id
WHERE cd.cart_id='$cart_id'
");
?>

<?php while($item = $items->fetch_assoc()) { ?>

<a href="product_detail.php?id=<?php echo $item['product_id']; ?>" 
style="text-decoration:none; color:inherit;">

<div class="row align-items-center mb-3 p-3 rounded hover-shadow">

<div class="col-md-2 text-center">
<img src="../uploads/<?php echo $item['product_img']; ?>" 
style="height:110px; width:100%; object-fit:contain;">
</div>

<div class="col-md-7">
<h5 class="mb-1"><?php echo $item['product_name']; ?></h5>
<small class="text-muted">Quantity: <?php echo $item['qty']; ?></small>
</div>

<div class="col-md-3 text-end">
<h6 class="mb-0">₹<?php echo $item['price']; ?></h6>
</div>

</div>

</a>

<?php } ?>

<hr>

<!-- FOOTER -->
<div class="d-flex justify-content-between align-items-center">

<div>
<p class="mb-1"><b>Total:</b> ₹<?php echo $o['total_amt']; ?></p>
<p class="mb-0"><b>Address:</b> <?php echo $o['delivery_add']; ?></p>
</div>
<?php if($o['order_status'] == 6) { ?>

<?php if($o['return_approval'] == 1) { ?>

<div class="card mt-3 p-3 border-success">

<h6 class="text-success">💸 Refund Receipt</h6>

<p class="mb-1"><b>Order ID:</b> <?php echo $o['order_id']; ?></p>

<p class="mb-1"><b>Refund Amount:</b> ₹<?php echo $o['refund_amount']; ?></p>

<p class="mb-1"><b>Status:</b> 
<span class="badge bg-success">Refund Completed</span>
</p>

<p class="mb-0"><b>Refund Date:</b> <?php echo date("d-m-Y"); ?></p>

</div>

<?php } ?>

<div class="mt-2 text-start">

<span class="text-danger fw-bold d-block">
📦 Product Returned
</span>

<?php if(!empty($o['return_reason'])) { ?>
<small><b>Reason:</b> <?php echo $o['return_reason']; ?></small><br>
<?php } ?>

<?php if(isset($o['return_approval'])) { ?>

    <?php if($o['return_approval'] == 0) { ?>
        <span class="text-warning">⏳ Waiting for Supplier Approval</span>
    <?php } ?>

    <?php if($o['return_approval'] == 1) { ?>
        <span class="text-success">
        💸 Refund Approved: ₹<?php echo $o['refund_amount']; ?>
        </span>
    <?php } ?>

    <?php if($o['return_approval'] == 2) { ?>
        <span class="text-danger">❌ Return Rejected</span>
    <?php } ?>

<?php } ?>

</div>

<?php } ?>

<div class="text-end">

<!-- CONFIRM RECEIVED -->
<?php if($o['order_status'] == 3) { ?>
<a href="orders.php?confirm=<?php echo $o['order_id']; ?>" 
class="btn btn-outline-primary btn-sm mb-2">
Confirm Received
</a>
<br>
<?php } ?>


<!-- RETURN PRODUCT -->
<?php if($o['order_status'] == 5) { 

    $order_date = strtotime($o['order_date']);
    $today = time();
    $days = ($today - $order_date) / (60*60*24);

    if($days <= 7) {
?>

<form method="POST" class="mb-2">
<input type="hidden" name="order_id" value="<?php echo $o['order_id']; ?>">

<textarea name="reason" class="form-control mb-2" 
placeholder="Enter reason for return..." required></textarea>

<button type="submit" name="return_btn" 
class="btn btn-outline-danger btn-sm">
Return Product
</button>
</form>
<br>

<?php } else { ?>

<span class="text-muted d-block mb-2">Return period expired</span>

<?php } } ?>


<!-- BILL CHECK -->
<?php
$bill_check = $conn->query("
SELECT * FROM bill_info 
WHERE order_id='".$o['order_id']."'
");
?>

<?php if($bill_check->num_rows > 0) { ?>

<a href="bill.php?order_id=<?php echo $o['order_id']; ?>" 
class="btn btn-outline-secondary btn-sm">
View Bill
</a>

<?php } else { ?>

<button class="btn btn-outline-secondary btn-sm" disabled>
Bill Not Available
</button>

<?php } ?>

</div>

</div>

</div>

<?php } ?>

<?php } ?>

</div>

<style>
.hover-shadow:hover {
    background-color: #f8f9fa;
    transition: 0.2s;
}
</style>

<?php include '../includes/customerfooter.php'; ?>