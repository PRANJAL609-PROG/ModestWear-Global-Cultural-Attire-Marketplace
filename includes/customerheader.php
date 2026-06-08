<?php
session_start();

// DB CONNECTION
include $_SERVER['DOCUMENT_ROOT'] . "/modestwear/db.php";

if(!isset($_SESSION['customer']))
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
<title>ModestWear Customer</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* NAVBAR */
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

/* HERO */
.hero {
    background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                url('<?php echo $base_url; ?>images/banner1.jpg') center/cover no-repeat;
    color: white;
    height: 30vh;
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

.gold-text {
    color: #d4af37;
    font-weight: bold;
}

</style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
<div class="container">

<a class="navbar-brand fw-bold gold-text" href="<?php echo $base_url; ?>customer/dashboard.php">
ModestWear
</a>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse justify-content-end" id="nav">

<ul class="navbar-nav">

<!-- HOME -->
<li class="nav-item">
<a class="nav-link" href="<?php echo $base_url; ?>customer/dashboard.php">
Home
</a>
</li>

<!-- CART -->
<li class="nav-item">
<a class="nav-link" href="<?php echo $base_url; ?>customer/cart.php">
Cart
</a>
</li>

<!-- ORDERS -->
<li class="nav-item">
<a class="nav-link" href="<?php echo $base_url; ?>customer/orders.php">
My Orders
</a>
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