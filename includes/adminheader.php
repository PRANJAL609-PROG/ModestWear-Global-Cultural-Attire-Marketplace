<?php
session_start();

// ✅ CORRECT DB PATH (as per your project)
include $_SERVER['DOCUMENT_ROOT'] . "/modestwear/db.php";

// ✅ SESSION CHECK
if(!isset($_SESSION['admin']))
{
    header("Location: /modestwear/login.php");
    exit();
}

// ✅ BASE URL
$base_url = "/modestwear/";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>ModestWear Admin</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* NAVBAR - BLACK + GOLD */
.navbar-custom {
    background-color: black;
}

.navbar-custom .nav-link {
    color: #d4af37 !important;
    font-weight: 500;
}

.navbar-custom .nav-link:hover {
    color: white !important;
}

/* BRAND */
.navbar-brand {
    color: #d4af37 !important;
}

/* HERO */
.hero {
    background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                url('<?php echo $base_url; ?>images/banner1.jpg') center/cover no-repeat;
    color: white;
    height: 35vh;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    text-align: center;
}

/* CARD */
.card {
    border-radius: 12px;
}

/* BUTTON SMALL */
.btn-sm {
    font-size: 13px;
}

/* GOLD BUTTON */
.btn-gold {
    background: #d4af37;
    color: black;
    border: none;
}

.btn-gold:hover {
    background: #b8962e;
    color: white;
}

</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
<div class="container">

<a class="navbar-brand fw-bold" href="<?php echo $base_url; ?>admin/dashboard.php">
ModestWear Admin
</a>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse justify-content-end" id="nav">

<ul class="navbar-nav">

<!-- DASHBOARD -->
<li class="nav-item">
<a class="nav-link" href="<?php echo $base_url; ?>admin/dashboard.php">
Manage Category
</a>
</li>

<!-- PRODUCTS -->
<li class="nav-item">
<a class="nav-link" href="<?php echo $base_url; ?>admin/view_products.php">
View Products
</a>
</li>

<!-- ORDERS -->
<li class="nav-item">
<a class="nav-link" href="<?php echo $base_url; ?>admin/view_orders.php">
Orders
</a>
</li>

<!-- REPORTS -->
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
Reports
</a>

<ul class="dropdown-menu">

<li>
<a class="dropdown-item" href="<?php echo $base_url; ?>admin/supplier_report.php">
Supplier Details
</a>
</li>

<li>
<a class="dropdown-item" href="<?php echo $base_url; ?>admin/customer_report.php">
Customer Details
</a>
</li>


</ul>
</li>

<!-- LOGOUT -->
<li class="nav-item">
<a class="nav-link text-warning" href="<?php echo $base_url; ?>logout.php">
Logout
</a>
</li>

</ul>

</div>
</div>
</nav>