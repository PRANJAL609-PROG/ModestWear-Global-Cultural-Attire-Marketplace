<?php
include '../includes/adminheader.php';


$suppliers = $conn->query("SELECT * FROM supplier_info ORDER BY supplier_id DESC");
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
<h1 class="gold-text">Supplier Report 📊</h1>
<p>Manage and analyze supplier data</p>
</section>

<div class="container mt-5">

<?php if(!$suppliers || $suppliers->num_rows == 0) { ?>

<div class="text-center p-5">
<h3>No Supplier Detail Found</h3>
</div>

<?php } else { ?>

<div class="card shadow-lg p-4 border-0 card-custom">

<h5 class="mb-3 gold-text">
Total Suppliers: <?php echo $suppliers->num_rows; ?>
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
<th>Bills</th>
</tr>
</thead>

<tbody>

<?php while($s = $suppliers->fetch_assoc()) { ?>

<tr>

<td><?php echo $s['supplier_id']; ?></td>

<td class="fw-bold"><?php echo $s['supplier_name']; ?></td>

<td class="text-start"><?php echo $s['address']; ?></td>

<td><?php echo $s['city']; ?></td>

<td><?php echo $s['mobile_no']; ?></td>

<td><?php echo $s['email_id']; ?></td>

<td>
<a href="view_orders.php?supplier_id=<?php echo $s['supplier_id']; ?>" 
class="btn btn-sm btn-gold">
Orders
</a>
</td>

<td>
<a href="view_orders.php?supplier_id=<?php echo $s['supplier_id']; ?>&bill=1" 
class="btn btn-sm btn-success">
Bills
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