<?php
include '../includes/adminheader.php';


$customers = $conn->query("SELECT * FROM customer_info ORDER BY customer_id DESC");
?>

<style>
.gold-text { color:#d4af37; font-weight:bold; }
.btn-gold { background:#d4af37; color:black; border:none; }
.btn-gold:hover { background:#b8962e; color:white; }

.table-dark-header {
    background:black;
    color:#d4af37;
}

.card-custom {
    border-radius:12px;
}
</style>

<section class="hero">
<h1 class="gold-text">Customer Report 👥</h1>
<p>View and manage customer data</p>
</section>

<div class="container mt-5">

<?php if(!$customers || $customers->num_rows == 0) { ?>

<div class="text-center p-5">
<h3>No Customer Detail Found</h3>
</div>

<?php } else { ?>

<div class="card shadow-lg p-4 border-0 card-custom">

<h5 class="mb-3 gold-text">
Total Customers: <?php echo $customers->num_rows; ?>
</h5>

<div class="table-responsive">
<table class="table table-bordered align-middle text-center">

<thead class="table-dark-header">
<tr>
<th>ID</th>
<th>Name</th>
<th>Address</th>
<th>City</th>
<th>Mobile</th>
<th>Email</th>
<th>Orders</th>
</tr>
</thead>

<tbody>

<?php while($c = $customers->fetch_assoc()) { ?>

<tr>

<td><?php echo $c['customer_id']; ?></td>

<td class="fw-bold"><?php echo $c['customer_name']; ?></td>

<td class="text-start"><?php echo $c['address']; ?></td>

<td><?php echo $c['city']; ?></td>

<td><?php echo $c['mobile_no']; ?></td>

<td><?php echo $c['email_id']; ?></td>

<td>
<a href="view_orders.php?customer_id=<?php echo $c['customer_id']; ?>" 
class="btn btn-sm btn-gold">
Orders
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

<?php include '../includes/adminfooter.php'; ?>