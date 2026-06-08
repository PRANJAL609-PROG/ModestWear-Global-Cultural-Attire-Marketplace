<?php
// Base URL for entire project
$base_url = "/modestwear/";

// Include database (absolute path safe)
include $_SERVER['DOCUMENT_ROOT'] . $base_url . "db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ModestWear Global - Cultural Attire Marketplace</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .navbar-custom {
            background-color: #000000;
        }

        .carousel-item img {
            width: 100%;
            height: 700px;
            object-fit: cover;
        }

        .section-title {
            color: #2e7d32;
        }

        .footer-custom {
            background-color: #121b12;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container-fluid">

        <!-- LOGO -->
        <a href="<?php echo $base_url; ?>index.php">
            <img src="<?php echo $base_url; ?>images/logo.png" 
                 style="width:180px; height:85px;">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link active" 
                       href="<?php echo $base_url; ?>index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" 
                       href="<?php echo $base_url; ?>about.php">About Us</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link active dropdown-toggle" href="#" 
                       role="button" data-bs-toggle="dropdown">
                        Registration
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" 
                               href="<?php echo $base_url; ?>supplier/register.php">
                               Supplier
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" 
                               href="<?php echo $base_url; ?>customer/register.php">
                               Customer
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" 
                       href="<?php echo $base_url; ?>login.php">Login</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" 
                       href="<?php echo $base_url; ?>contact.php">Contact Us</a>
                </li>

            </ul>
        </div>
    </div>
</nav>