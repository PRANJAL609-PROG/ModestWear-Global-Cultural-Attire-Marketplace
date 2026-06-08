<?php
include '../includes/customerheader.php';

$order_id = $_GET['order_id'];

$conn->query("
UPDATE order_info 
SET payment_status = 1 
WHERE order_id = '$order_id'
");
?>

<div class="container mt-5 text-center">

<h3 class="text-success">Payment Successful ✅</h3>

<a href="orders.php" class="btn btn-primary mt-3">
Go to Orders
</a>

</div>

<?php include '../includes/customerfooter.php'; ?>