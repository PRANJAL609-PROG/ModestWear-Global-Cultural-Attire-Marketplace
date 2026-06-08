<?php
session_start();

// DB CONNECTION
include $_SERVER['DOCUMENT_ROOT'] . "/modestwear/db.php";

if(!isset($_SESSION['supplier']))
{
    header("Location: ../login.php");
    exit();
}

$base_url = "/modestwear/";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Supplier Panel</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.navbar-custom {
    background-color: black;
}

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

.gold-text {
    color:#d4af37;
}

.card {
    border-radius: 12px;
}
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
<div class="container">

<a class="navbar-brand fw-bold gold-text" href="<?php echo $base_url; ?>supplier/dashboard.php">
Supplier Panel
</a>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse justify-content-end" id="nav">

<ul class="navbar-nav">

<li class="nav-item">
<a class="nav-link" href="<?php echo $base_url; ?>supplier/dashboard.php">
Manage Products
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="<?php echo $base_url; ?>supplier/new_orders.php">
New Orders
</a>
</li>

<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
Reports
</a>

<ul class="dropdown-menu">

<li>
<a class="dropdown-item" href="<?php echo $base_url; ?>supplier/all_orders_report.php">
All Order Details
</a>
</li>

</ul>
</li>

<li class="nav-item">
<a class="nav-link" href="<?php echo $base_url; ?>logout.php">
Logout
</a>
</li>

</ul>

</div>
</div>
</nav>