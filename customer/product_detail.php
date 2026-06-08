<?php
include '../includes/customerheader.php';

$customer_id = $_SESSION['customer'];

// CHECK PRODUCT ID
if(!isset($_GET['id']))
{
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];

// FETCH PRODUCT
$product = $conn->query("SELECT p.*, c.category_name 
FROM product_info p 
JOIN category_info c ON p.category_id=c.category_id 
WHERE p.product_id='$id'");

if($product->num_rows == 0)
{
    echo "<h3 class='text-center mt-5'>Product not found</h3>";
    include '../includes/customerfooter.php';
    exit();
}

$p = $product->fetch_assoc();

$message = "";

// ADD TO CART
if(isset($_POST['add_to_cart']))
{
    $pid = $_POST['product_id'];
    $qty = $_POST['qty'];

    if($qty <= 0)
    {
        $message = "<div class='alert alert-danger'>Invalid quantity</div>";
    }
    else
    {
        // GET PRICE
        $res = $conn->query("SELECT price FROM product_info WHERE product_id='$pid'");
        $prod = $res->fetch_assoc();
        $price = $prod['price'];

        // CHECK ACTIVE CART
        $cart = $conn->query("SELECT * FROM cart_master 
        WHERE customer_id='$customer_id' AND cart_status=0");

        if($cart->num_rows > 0)
        {
            $cart_data = $cart->fetch_assoc();
            $cart_id = $cart_data['cart_id'];
        }
        else
        {
            // CREATE NEW CART
            $conn->query("INSERT INTO cart_master(customer_id, cart_date, cart_status) 
            VALUES('$customer_id', NOW(), 0)");

            $cart_id = $conn->insert_id;
        }

        // CHECK IF PRODUCT EXISTS IN CART
        $check = $conn->query("SELECT * FROM cart_detail 
        WHERE cart_id='$cart_id' AND product_id='$pid'");

        if($check->num_rows > 0)
        {
            // UPDATE QTY
            $conn->query("UPDATE cart_detail 
            SET qty = qty + $qty 
            WHERE cart_id='$cart_id' AND product_id='$pid'");
        }
        else
        {
            // INSERT INTO CART
            $conn->query("INSERT INTO cart_detail(cart_id, product_id, qty, price) 
            VALUES('$cart_id','$pid','$qty','$price')");
        }

        $message = "<div class='alert alert-success'>Product added to cart</div>";
    }
}
?>

<style>
.gold-text { color:#d4af37; font-weight:bold; }
.btn-gold { background:#d4af37; color:black; }
.btn-gold:hover { background:#b8962e; color:white; }
</style>

<!-- HERO -->
<section class="hero">
<h1 class="gold-text">Product Details</h1>
</section>

<div class="container mt-5">

<?php echo $message; ?>

<div class="row">

<!-- IMAGE -->
<div class="col-md-6 text-center">
<img src="../uploads/<?php echo $p['product_img']; ?>" 
class="img-fluid rounded"
style="max-height:400px; object-fit:contain;">
</div>

<!-- DETAILS -->
<div class="col-md-6">

<h3 class="gold-text"><?php echo $p['product_name']; ?></h3>

<p class="text-muted"><?php echo $p['description']; ?></p>

<p><b>Category:</b> <?php echo $p['category_name']; ?></p>

<h4 class="mb-3" style="color:#d4af37;">₹<?php echo $p['price']; ?></h4>

<!-- FORM -->
<form method="POST">

<input type="hidden" name="product_id" value="<?php echo $p['product_id']; ?>">

<label class="mb-1">Enter Quantity</label>
<input type="number" name="qty" class="form-control mb-3" value="1" min="1" required>

<button type="submit" name="add_to_cart" class="btn btn-gold px-4">
Add to Cart
</button>

<a href="dashboard.php" class="btn btn-secondary px-4">
Back
</a>

</form>

</div>

</div>

</div>

<?php include '../includes/customerfooter.php'; ?>