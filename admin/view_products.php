<?php
include '../includes/adminheader.php';

// DELETE PRODUCT
if(isset($_GET['delete']))
{
    $pid = $_GET['delete'];
    $conn->query("DELETE FROM product_info WHERE product_id='$pid'");
    header("Location: view_products.php");
    exit();
}

// FILTER VALUES
$search   = $_GET['search']   ?? "";
$category = $_GET['category'] ?? "";
$supplier = $_GET['supplier'] ?? "";
$sort     = $_GET['sort']     ?? "";

// MAIN QUERY
$query = "
SELECT p.*, c.category_name, s.supplier_name
FROM product_info p
JOIN category_info c ON p.category_id = c.category_id
JOIN supplier_info s ON p.supplier_id = s.supplier_id
WHERE 1
";

if($search != "")
{
    $query .= " AND p.product_name LIKE '%$search%'";
}

if($category != "")
{
    $query .= " AND p.category_id = '$category'";
}

if($supplier != "")
{
    $query .= " AND p.supplier_id = '$supplier'";
}

// SORTING
if($sort == "low")
{
    $query .= " ORDER BY p.price ASC";
}
elseif($sort == "high")
{
    $query .= " ORDER BY p.price DESC";
}
else
{
    $query .= " ORDER BY p.product_id DESC";
}

$products = $conn->query($query);
$total_products = $products->num_rows;

// FETCH FILTER DATA
$categories = $conn->query("SELECT * FROM category_info");
$suppliers  = $conn->query("SELECT * FROM supplier_info");
?>

<style>
.gold-text { color:#d4af37; font-weight:bold; }
.btn-gold { background:#d4af37; color:black; }
.btn-gold:hover { background:#b8962e; color:white; }
</style>

<!-- HERO -->
<section class="hero">
<h1 class="gold-text">View Products</h1>
<p>Manage all products in marketplace</p>
</section>

<div class="container mt-5">

<!-- FILTER FORM -->
<form method="GET" class="row g-2 mb-4">

<div class="col-md-3">
<input type="text" name="search" class="form-control" 
placeholder="Search product..." value="<?php echo $search; ?>">
</div>

<div class="col-md-2">
<select name="category" class="form-control">
<option value="">All Categories</option>
<?php while($c = $categories->fetch_assoc()) { ?>
<option value="<?php echo $c['category_id']; ?>"
<?php if($category == $c['category_id']) echo "selected"; ?>>
<?php echo $c['category_name']; ?>
</option>
<?php } ?>
</select>
</div>

<div class="col-md-2">
<select name="supplier" class="form-control">
<option value="">All Suppliers</option>
<?php while($s = $suppliers->fetch_assoc()) { ?>
<option value="<?php echo $s['supplier_id']; ?>"
<?php if($supplier == $s['supplier_id']) echo "selected"; ?>>
<?php echo $s['supplier_name']; ?>
</option>
<?php } ?>
</select>
</div>

<div class="col-md-2">
<select name="sort" class="form-control">
<option value="">Sort</option>
<option value="low" <?php if($sort=="low") echo "selected"; ?>>Price Low → High</option>
<option value="high" <?php if($sort=="high") echo "selected"; ?>>Price High → Low</option>
<option value="latest" <?php if($sort=="latest") echo "selected"; ?>>Latest</option>
</select>
</div>

<div class="col-md-3">
<button class="btn btn-gold w-100">Apply Filters</button>
</div>

</form>

<h5 class="mb-3 gold-text">
Total Products: <?php echo $total_products; ?>
</h5>

<!-- TABLE -->
<div class="card shadow-lg p-3 border-0">

<div class="table-responsive">
<table class="table table-bordered align-middle text-center">

<thead style="background:black; color:#d4af37;">
<tr>
<th>Image</th>
<th>Product Name</th>
<th>Category</th>
<th>Supplier</th>
<th>Price</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php if($products->num_rows == 0) { ?>

<tr>
<td colspan="6">No products found</td>
</tr>

<?php } else { ?>

<?php while($p = $products->fetch_assoc()) { ?>

<tr>

<td>
<img src="../uploads/<?php echo $p['product_img']; ?>" 
style="height:70px; object-fit:contain;">
</td>

<td><?php echo $p['product_name']; ?></td>

<td><?php echo $p['category_name']; ?></td>

<td><?php echo $p['supplier_name']; ?></td>

<td class="fw-bold text-success">₹<?php echo $p['price']; ?></td>

<td>

<a href="product_detail.php?id=<?php echo $p['product_id']; ?>" 
class="btn btn-sm btn-outline-info">
View
</a>

<a href="?delete=<?php echo $p['product_id']; ?>" 
class="btn btn-sm btn-outline-danger"
onclick="return confirm('Delete this product?')">
Delete
</a>

</td>

</tr>

<?php } ?>

<?php } ?>

</tbody>

</table>
</div>

</div>

</div>

<?php include '../includes/adminfooter.php'; ?>