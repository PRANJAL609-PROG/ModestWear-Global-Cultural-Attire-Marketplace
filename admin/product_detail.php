<?php
include '../includes/adminheader.php';

// CHECK ID
if(!isset($_GET['id']))
{
    header("Location: view_products.php");
    exit();
}

$id = $_GET['id'];

// FETCH PRODUCT
$product = $conn->query("
SELECT p.*, c.category_name, s.supplier_name, s.email_id, s.mobile_no 
FROM product_info p 
JOIN category_info c ON p.category_id=c.category_id 
JOIN supplier_info s ON p.supplier_id=s.supplier_id
WHERE p.product_id='$id'
");

if($product->num_rows == 0)
{
    echo "<h3 class='text-center mt-5'>Product not found</h3>";
    include '../includes/adminfooter.php';
    exit();
}

$p = $product->fetch_assoc();
?>

<style>
.gold-text { color:#d4af37; font-weight:bold; }
.btn-gold { background:#d4af37; color:black; }
.btn-gold:hover { background:#b8962e; color:white; }
</style>


<section class="hero">
<h1 class="gold-text">Product Details</h1>
<p>Detailed view of selected product</p>
</section>

<div class="container mt-5">

<div class="card shadow-lg border-0 p-4">

<div class="row align-items-center">


<div class="col-md-6 text-center">
<img src="../uploads/<?php echo $p['product_img']; ?>" 
class="img-fluid rounded"
style="max-height:400px; object-fit:contain;">
</div>


<div class="col-md-6">

<h3 class="gold-text"><?php echo $p['product_name']; ?></h3>

<p class="text-muted"><?php echo $p['description']; ?></p>

<hr>

<p><b>Category:</b> <?php echo $p['category_name']; ?></p>

<p><b>Supplier:</b> <?php echo $p['supplier_name']; ?></p>

<p><b>Email:</b> <?php echo $p['email_id']; ?></p>

<p><b>Contact:</b> <?php echo $p['mobile_no']; ?></p>

<h4 class="text-success mb-3">₹<?php echo $p['price']; ?></h4>

<hr>

<div class="d-flex gap-2">

<a href="view_products.php" class="btn btn-secondary">
← Back
</a>

<a href="view_products.php?delete=<?php echo $p['product_id']; ?>" 
class="btn btn-danger"
onclick="return confirm('Delete this product?')">
Delete
</a>

</div>

</div>

</div>

</div>

</div>

<?php include '../includes/adminfooter.php'; ?>