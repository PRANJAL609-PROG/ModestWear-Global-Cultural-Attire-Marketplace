<?php
include '../includes/supplierheader.php';

$supplier_id = $_SESSION['supplier'];

$orders = $conn->query("
SELECT DISTINCT o.*, c.customer_name, c.mobile_no
FROM order_info o
JOIN customer_info c ON o.customer_id = c.customer_id
JOIN cart_detail cd ON o.cart_id = cd.cart_id
JOIN product_info p ON cd.product_id = p.product_id
WHERE p.supplier_id = '$supplier_id'
ORDER BY o.order_id DESC
");

function getStatusBadge($status)
{
    switch($status)
    {
        case 0: return "<span class='badge bg-warning text-dark'>Pending</span>";
        case 1: return "<span class='badge bg-info'>Accepted</span>";
        case 2: return "<span class='badge bg-primary'>Shipped</span>";
        case 3: return "<span class='badge bg-success'>Delivered</span>";
        case 5: return "<span class='badge bg-dark'>Received</span>";
        case 6: return "<span class='badge bg-danger'>Returned</span>"; 
        case 4: return "<span class='badge bg-danger'>Rejected</span>";
        default: return "<span class='badge bg-secondary'>Unknown</span>";
    }
}
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
    border:none;
}

.btn-gold:hover {
    background:#b8962e;
    color:white;
}
</style>

<section class="hero">
<h1 class="gold-text">All Orders Report 📊</h1>
<p>Track all your customer orders</p>
</section>

<div class="container mt-5">

<?php if(!$orders || $orders->num_rows == 0) { ?>

<div class="text-center p-5">
<h3>No Order Detail Found</h3>
</div>

<?php } else { ?>

<div class="card shadow-lg p-4 border-0">

<h5 class="mb-3 gold-text">
Total Orders: <?php echo $orders->num_rows; ?>
</h5>

<div class="table-responsive">
<table class="table table-bordered align-middle text-center">

<thead>
<tr>
<th>Order ID</th>
<th>Date</th>
<th>Cart ID</th>
<th>Customer</th>
<th>Address</th>
<th>Mobile</th>
<th>Total Amount</th>
<th>Status</th>   
<th>Action</th>
</tr>
</thead>

<tbody>

<?php while($o = $orders->fetch_assoc()) { ?>

<tr>

<td><?php echo $o['order_id']; ?></td>

<td><?php echo date("d-m-Y", strtotime($o['order_date'])); ?></td>

<td><?php echo $o['cart_id']; ?></td>

<td><?php echo $o['customer_name']; ?></td>

<td class="text-start"><?php echo $o['delivery_add']; ?></td>

<td><?php echo $o['mobile_no']; ?></td>

<td class="fw-bold">₹<?php echo $o['total_amt']; ?></td>

<td><?php echo getStatusBadge($o['order_status']); ?></td>

<td>
<a href="order_detail.php?order_id=<?php echo $o['order_id']; ?>" 
class="btn btn-gold btn-sm">
View Details
</a>
</td>

</tr>

<?php } ?>

</tbody>

</table>
</div>

</div>

<?php } ?>

</div>

<?php include '../includes/supplierfooter.php'; ?>