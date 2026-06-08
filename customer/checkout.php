<?php
include '../includes/customerheader.php';

$customer_id = $_SESSION['customer'];

// GET CUSTOMER DETAILS
$cust = $conn->query("SELECT * FROM customer_info WHERE customer_id='$customer_id'");
$c = $cust->fetch_assoc();

// GET ACTIVE CART
$cart = $conn->query("SELECT * FROM cart_master 
WHERE customer_id='$customer_id' AND cart_status=0");

if($cart->num_rows == 0)
{
    echo "<div class='container mt-5 text-center'><h4>No active cart found</h4></div>";
    include '../includes/customerfooter.php';
    exit();
}

$cart_data = $cart->fetch_assoc();
$cart_id = $cart_data['cart_id'];

// CALCULATE TOTAL
$items = $conn->query("SELECT * FROM cart_detail WHERE cart_id='$cart_id'");

$total_amt = 0;
while($row = $items->fetch_assoc())
{
    $total_amt += ($row['price'] * $row['qty']);
}

// PLACE ORDER
if(isset($_POST['place_order']))
{
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $payment_method = $_POST['payment_method'];

    if(!preg_match('/^[0-9]{10}$/', $mobile))
    {
        echo "<script>alert('Enter valid mobile number');</script>";
    }
    else
    {
        $payment_status = ($payment_method == "ONLINE") ? 1 : 0;

        $conn->query("INSERT INTO order_info 
        (order_date, cart_id, customer_id, delivery_add, delivery_mno, total_amt, order_status, payment_method, payment_status)
        VALUES (NOW(), '$cart_id', '$customer_id', '$address', '$mobile', '$total_amt', 0, '$payment_method', '$payment_status')");

        $conn->query("UPDATE cart_master SET cart_status=1 WHERE cart_id='$cart_id'");

        echo "<script>alert('Order Placed Successfully'); window.location='orders.php';</script>";
    }
}
?>

<style>
.gold-text { color:#d4af37; font-weight:bold; }
.btn-gold { background:#d4af37; color:black; }
.btn-gold:hover { background:#b8962e; color:white; }
.qr-box { display:none; text-align:center; }
</style>

<section class="hero">
<h1 class="gold-text">Checkout</h1>
</section>

<div class="container mt-5">

<div class="row justify-content-center">
<div class="col-md-6">

<div class="card shadow-lg p-4 border-0">

<h4 class="text-center gold-text mb-4">Confirm Your Order</h4>

<form method="POST">

<!-- NAME -->
<div class="mb-3">
<label>Customer Name</label>
<input type="text" class="form-control" value="<?php echo $c['customer_name']; ?>" readonly>
</div>

<!-- ADDRESS -->
<div class="mb-3">
<label>Delivery Address</label>
<textarea name="address" class="form-control" required><?php echo $c['address']; ?></textarea>
</div>

<!-- MOBILE -->
<div class="mb-3">
<label>Mobile Number</label>
<input type="text" name="mobile" class="form-control"
value="<?php echo $c['mobile_no']; ?>"
required maxlength="10">
</div>

<!-- TOTAL -->
<div class="mb-3">
<label>Total Amount</label>
<input type="text" class="form-control" value="₹<?php echo $total_amt; ?>" readonly>
</div>

<!-- PAYMENT METHOD -->
<div class="mb-3">
<label>Payment Method</label><br>

<input type="radio" name="payment_method" value="COD" checked onclick="hideQR()"> Cash on Delivery<br>

<input type="radio" name="payment_method" value="ONLINE" onclick="showQR()"> Online Payment (QR)

</div>

<!-- QR SECTION -->
<div class="qr-box" id="qrBox">
<p class="text-center fw-bold">Scan & Pay</p>

<img src="../images/qr.png" style="width:250px;"><br><br>

<p><b>UPI ID:</b> modestwearglobal@oksbi</p>

<p class="text-success">After payment click Place Order</p>
</div>

<!-- BUTTON -->
<div class="text-center mt-4">
<button type="submit" name="place_order" class="btn btn-gold px-5">
Place Order
</button>
</div>

</form>

</div>

</div>
</div>

</div>

<script>
function showQR(){
    document.getElementById("qrBox").style.display = "block";
}
function hideQR(){
    document.getElementById("qrBox").style.display = "none";
}
</script>

<?php include '../includes/customerfooter.php'; ?>