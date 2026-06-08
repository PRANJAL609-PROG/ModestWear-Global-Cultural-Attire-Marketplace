<?php
include '../includes/adminheader.php';

$message = "";

// ADD CATEGORY
if(isset($_POST['add_category']))
{
    $name = trim($_POST['category_name']);
    $desc = $_POST['description'];

    $check = $conn->prepare("SELECT category_id FROM category_info WHERE category_name=?");
    $check->bind_param("s",$name);
    $check->execute();
    $result = $check->get_result();

    if($result->num_rows > 0)
    {
        $message = "<div class='alert alert-danger'>Category already exists!</div>";
    }
    else
    {
        $stmt = $conn->prepare("INSERT INTO category_info (category_name, description) VALUES (?, ?)");
        $stmt->bind_param("ss",$name,$desc);

        if($stmt->execute())
        {
            $message = "<div class='alert alert-success'>Category Added Successfully</div>";
        }
    }
}


if(isset($_GET['delete']))
{
    $cid = $_GET['delete'];
    $conn->query("DELETE FROM category_info WHERE category_id='$cid'");
    header("Location: dashboard.php");
    exit();
}


$edit_data = null;
if(isset($_GET['edit']))
{
    $cid = $_GET['edit'];
    $result = $conn->query("SELECT * FROM category_info WHERE category_id='$cid'");
    $edit_data = $result->fetch_assoc();
}


if(isset($_POST['update_category']))
{
    $cid  = $_POST['category_id'];
    $name = trim($_POST['category_name']);
    $desc = $_POST['description'];

    $check = $conn->prepare("SELECT category_id FROM category_info WHERE category_name=? AND category_id!=?");
    $check->bind_param("si",$name,$cid);
    $check->execute();
    $result = $check->get_result();

    if($result->num_rows > 0)
    {
        $message = "<div class='alert alert-danger'>Category name already exists!</div>";
    }
    else
    {
        $stmt = $conn->prepare("UPDATE category_info SET category_name=?, description=? WHERE category_id=?");
        $stmt->bind_param("ssi",$name,$desc,$cid);

        if($stmt->execute())
        {
            $message = "<div class='alert alert-success'>Category Updated Successfully</div>";
        }
    }
}


$categories = $conn->query("SELECT * FROM category_info");
?>

<style>
.gold-text {
    color:#d4af37;
    font-weight:bold;
}

.card-custom {
    border-radius:12px;
    transition:0.3s;
}
.card-custom:hover {
    transform:scale(1.02);
}

.btn-gold {
    background-color:#d4af37;
    color:black;
}
.btn-gold:hover {
    background-color:#b8962e;
    color:white;
}
</style>

<!-- HERO -->
<section class="hero">
    <h1 class="gold-text">Welcome Admin</h1>
    <p>Manage your ModestWear Marketplace</p>
</section>

<div class="container mt-4">

<h3 class="gold-text mb-3">Manage Categories</h3>

<?php echo $message; ?>

<!-- FORM -->
<div class="card shadow p-4 mb-4">
<form method="POST">

<input type="hidden" name="category_id" 
value="<?php echo $edit_data['category_id'] ?? ''; ?>">

<input type="text" name="category_name" 
placeholder="Enter Category Name"
value="<?php echo $edit_data['category_name'] ?? ''; ?>"
class="form-control mb-3" required>

<textarea name="description"
placeholder="Enter Description"
class="form-control mb-3"><?php echo $edit_data['description'] ?? ''; ?></textarea>

<?php if($edit_data) { ?>

<button type="submit" name="update_category" class="btn btn-warning">
Update Category
</button>

<a href="dashboard.php" class="btn btn-secondary">Cancel</a>

<?php } else { ?>

<button type="submit" name="add_category" class="btn btn-gold">
Add Category
</button>

<?php } ?>

</form>
</div>

<hr>

<h4 class="gold-text mt-4">Category List</h4>

<div class="row">

<?php while($c = $categories->fetch_assoc()) { ?>

<div class="col-md-6">
<div class="card card-custom shadow-sm p-3 mb-3 border-0">

<h5 class="gold-text"><?php echo $c['category_name']; ?></h5>

<p class="text-muted"><?php echo $c['description']; ?></p>

<div class="d-flex gap-2 mt-2">

<a href="dashboard.php?edit=<?php echo $c['category_id']; ?>" 
class="btn btn-outline-dark btn-sm">
Edit
</a>

<a href="dashboard.php?delete=<?php echo $c['category_id']; ?>" 
class="btn btn-outline-danger btn-sm"
onclick="return confirm('Delete this category?')">
Delete
</a>

</div>

</div>
</div>

<?php } ?>

</div>

</div>

<?php include '../includes/adminfooter.php'; ?>