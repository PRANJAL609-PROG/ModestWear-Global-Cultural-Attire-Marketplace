<?php
include '../includes/customerheader.php';

// SEARCH VALUES
$search = "";
$category = "";

if(isset($_GET['search'])) {
    $search = $_GET['search'];
}

if(isset($_GET['category'])) {
    $category = $_GET['category'];
}

// FETCH CATEGORY
$cat_query = "SELECT * FROM category_info";
$cat = $conn->query($cat_query);

// PRODUCT QUERY
$query = "SELECT p.*, c.category_name 
FROM product_info p
JOIN category_info c ON p.category_id = c.category_id
WHERE 1";

if($search != "") {
    $query .= " AND p.product_name LIKE '%$search%'";
}

if($category != "") {
    $query .= " AND p.category_id = '$category'";
}

$products = $conn->query($query);
?>

<style>
.gold-text { color:#d4af37; font-weight:bold; }
.btn-gold { background:#d4af37; color:black; }
.btn-gold:hover { background:#b8962e; color:white; }

.card-custom {
    border-radius:12px;
    transition:0.3s;
}
.card-custom:hover {
    transform:scale(1.02);
}
</style>

<!-- HERO -->
<section class="hero">
<h1 class="gold-text">Explore ModestWear Collection</h1>
<p>Discover cultural & modest fashion from around the world</p>
</section>

<div class="container mt-4">

<!-- SEARCH BAR -->
<form method="GET" class="row g-2 mb-4">

<div class="col-md-6">
<input type="text" name="search" class="form-control"
placeholder="Search products..."
value="<?php echo $search; ?>">
</div>

<div class="col-md-3">
<select name="category" class="form-control">
<option value="">All Categories</option>

<?php while($c = $cat->fetch_assoc()) { ?>
<option value="<?php echo $c['category_id']; ?>"
<?php if($category == $c['category_id']) echo "selected"; ?>>
<?php echo $c['category_name']; ?>
</option>
<?php } ?>

</select>
</div>

<div class="col-md-3">
<button type="submit" class="btn btn-gold w-100">Search</button>
</div>

</form>

<!-- PRODUCTS -->
<div class="row">

<?php if($products->num_rows > 0) { ?>

<?php while($p = $products->fetch_assoc()) { ?>

<div class="col-md-12">
<div class="card card-custom shadow-sm p-3 mb-4 border-0">

<a href="product_detail.php?id=<?php echo $p['product_id']; ?>"
style="text-decoration:none; color:inherit;">

<div class="row align-items-center">

<!-- IMAGE -->
<div class="col-md-4 text-center">
<img src="../uploads/<?php echo $p['product_img']; ?>"
class="img-fluid rounded"
style="max-height:180px; object-fit:contain;">
</div>

<!-- DETAILS -->
<div class="col-md-8">
<h5 class="gold-text"><?php echo $p['product_name']; ?></h5>

<p class="text-muted">
<?php echo $p['description']; ?>
</p>

<p><b>Category:</b> <?php echo $p['category_name']; ?></p>

<p class="fw-bold" style="color:#d4af37;">
₹<?php echo $p['price']; ?>
</p>
</div>

</div>

</a>

</div>
</div>

<?php } ?>

<?php } else { ?>

<div class="col-md-12 text-center">
<h5>No products found</h5>
</div>

<?php } ?>

</div>

</div>

<?php include '../includes/customerfooter.php'; ?>