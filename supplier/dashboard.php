<?php
include '../includes/supplierheader.php';

$supplier_id = $_SESSION['supplier'];
$message = "";

// ADD PRODUCT
if(isset($_POST['add_product']))
{
    $name = $_POST['product_name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    $img = "";
    if($_FILES['product_img']['name']!="")
    {
        $img = time()."_".$_FILES['product_img']['name'];
        move_uploaded_file($_FILES['product_img']['tmp_name'], "../uploads/".$img);
    }

    $stmt = $conn->prepare("INSERT INTO product_info 
    (product_name, category_id, supplier_id, description, price, product_img)
    VALUES (?,?,?,?,?,?)");

    $stmt->bind_param("siisss",$name,$category_id,$supplier_id,$desc,$price,$img);

    if($stmt->execute())
    {
        $message = "<div class='alert alert-success'>Product Added Successfully</div>";
    }
}

// DELETE
if(isset($_GET['delete']))
{
    $pid = $_GET['delete'];
    $conn->query("DELETE FROM product_info WHERE product_id='$pid' AND supplier_id='$supplier_id'");
    header("Location: dashboard.php");
    exit();
}

// FETCH FOR EDIT
$edit = null;
if(isset($_GET['edit']))
{
    $pid = $_GET['edit'];
    $res = $conn->query("SELECT * FROM product_info WHERE product_id='$pid'");
    $edit = $res->fetch_assoc();
}

// UPDATE
if(isset($_POST['update_product']))
{
    $pid = $_POST['product_id'];
    $name = $_POST['product_name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    if($_FILES['product_img']['name']!="")
    {
        $img = time()."_".$_FILES['product_img']['name'];
        move_uploaded_file($_FILES['product_img']['tmp_name'], "../uploads/".$img);

        $stmt = $conn->prepare("UPDATE product_info 
        SET product_name=?, category_id=?, description=?, price=?, product_img=? 
        WHERE product_id=? AND supplier_id=?");

        $stmt->bind_param("sisssii",$name,$category_id,$desc,$price,$img,$pid,$supplier_id);
    }
    else
    {
        $stmt = $conn->prepare("UPDATE product_info 
        SET product_name=?, category_id=?, description=?, price=? 
        WHERE product_id=? AND supplier_id=?");

        $stmt->bind_param("sissii",$name,$category_id,$desc,$price,$pid,$supplier_id);
    }

    if($stmt->execute())
    {
        $message = "<div class='alert alert-success'>Product Updated Successfully</div>";
    }
}

// FETCH DATA
$categories = $conn->query("SELECT * FROM category_info");

$products = $conn->query("SELECT p.*, c.category_name 
FROM product_info p 
JOIN category_info c ON p.category_id=c.category_id
WHERE p.supplier_id='$supplier_id'");
?>

<style>
.gold-text { color:#d4af37; font-weight:bold; }
.btn-gold { background:#d4af37; color:black; }
.btn-gold:hover { background:#b8962e; color:white; }
.card-custom { border-radius:12px; transition:0.3s; }
.card-custom:hover { transform:scale(1.01); }
</style>

<!-- HERO -->
<section class="hero">
<h1 class="gold-text">Supplier Dashboard</h1>
<p>Manage your products</p>
</section>

<div class="container mt-4">

<h3 class="gold-text mb-3">Add / Update Product</h3>

<?php echo $message; ?>

<div class="card shadow p-4 mb-4">
<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="product_id" value="<?php echo $edit['product_id'] ?? ''; ?>">

<input type="text" name="product_name" 
class="form-control mb-3" placeholder="Product Name"
value="<?php echo $edit['product_name'] ?? ''; ?>" required>

<textarea name="description" class="form-control mb-3"
placeholder="Description"><?php echo $edit['description'] ?? ''; ?></textarea>

<select name="category_id" class="form-control mb-3" required>
<option value="">Select Category</option>
<?php while($c=$categories->fetch_assoc()) { ?>
<option value="<?php echo $c['category_id']; ?>"
<?php if(isset($edit) && $edit['category_id']==$c['category_id']) echo "selected"; ?>>
<?php echo $c['category_name']; ?>
</option>
<?php } ?>
</select>

<input type="number" name="price" class="form-control mb-3"
placeholder="Price"
value="<?php echo $edit['price'] ?? ''; ?>" required>

<input type="file" name="product_img" class="form-control mb-3">

<?php if($edit) { ?>
<button type="submit" name="update_product" class="btn btn-warning">Update Product</button>
<a href="dashboard.php" class="btn btn-secondary">Cancel</a>
<?php } else { ?>
<button type="submit" name="add_product" class="btn btn-gold">Add Product</button>
<?php } ?>

</form>
</div>

<hr>

<h4 class="gold-text">Your Products</h4>

<div class="row">

<?php while($p=$products->fetch_assoc()) { ?>

<div class="col-md-12">
<div class="card card-custom shadow-sm p-3 mb-4 border-0">

<div class="row align-items-center">

<div class="col-md-4 text-center">
<?php if($p['product_img']) { ?>
<img src="../uploads/<?php echo $p['product_img']; ?>" 
class="img-fluid rounded"
style="max-height:180px; object-fit:contain;">
<?php } ?>
</div>

<div class="col-md-8">

<h5 class="gold-text"><?php echo $p['product_name']; ?></h5>

<p class="text-muted"><?php echo $p['description']; ?></p>

<p><b>Category:</b> <?php echo $p['category_name']; ?></p>

<p class="fw-bold">₹<?php echo $p['price']; ?></p>

<div class="d-flex gap-2 mt-2">

<a href="dashboard.php?edit=<?php echo $p['product_id']; ?>" 
class="btn btn-outline-dark btn-sm">
Edit
</a>

<a href="dashboard.php?delete=<?php echo $p['product_id']; ?>" 
class="btn btn-outline-danger btn-sm"
onclick="return confirm('Delete this product?')">
Delete
</a>

</div>

</div>

</div>

</div>
</div>

<?php } ?>

</div>

</div>

<?php include '../includes/supplierfooter.php'; ?>