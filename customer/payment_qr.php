<?php
include '../includes/customerheader.php';

$order_id = $_GET['order_id'];
?>

<div class="container mt-5 text-center">

<h3>Scan QR to Pay</h3>

<img src="../images/qr.png" style="width:250px;">

<p class="mt-3">After payment click below</p>

<a href="payment_success.php?order_id=<?php echo $order_id; ?>" 
class="btn btn-success">
I Have Paid
</a>

</div>

<?php include '../includes/customerfooter.php'; ?>