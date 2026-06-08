<?php
session_start();
include 'includes/header.php';

$message = "";

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $email = $_POST['txtemail'];
    $pwd   = $_POST['txtpwd'];

    // 1️⃣ Check Customer
    $stmt = $conn->prepare("SELECT * FROM customer_info WHERE email_id=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();

        if(password_verify($pwd,$row['pwd']))
        {
            session_start();
            $_SESSION['customer'] = $row['customer_id'];

            header("Location: customer/dashboard.php");
            exit();
        }
    }

    // 2️⃣ Check Supplier
    $stmt = $conn->prepare("SELECT * FROM supplier_info WHERE email_id=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();

        if(password_verify($pwd,$row['pwd']))
        {
            session_start();
            $_SESSION['supplier'] = $row['supplier_id'];

            header("Location: supplier/dashboard.php");
            exit();
        }
    }

    // 3️⃣ Check Admin
    $stmt = $conn->prepare("SELECT * FROM admin_info WHERE email_id=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();

        if(password_verify($pwd,$row['pwd']))
        {
            session_start();
            $_SESSION['admin'] = $row['admin_id'];

            header("Location: admin/dashboard.php");
            exit();
        }
    }

    // If all fail
    $message = "<div class='alert alert-danger'>Invalid Email or Password!</div>";
}
?>

<main>
<div class="container mt-5">

    <!-- Heading -->
    <div class="row">
        <div class="col-md-12 text-center">
            <h1 style="color:#d4af37; font-weight:bold;">Login</h1>
            <p style="color:gray;">Customer | Supplier | Admin Access</p>
        </div>
    </div>

    <div class="row mt-5">

        <!-- Image -->
        <div class="col-md-6">
            <img src="images/suplogin.jpg" style="width:100%; height:450px; object-fit:cover; border-radius:10px;">
        </div>

        <!-- Form -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">

                    <?php echo $message; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Enter Email Id</label>
                            <input type="email" class="form-control" name="txtemail" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Enter Password</label>
                            <input type="password" class="form-control" name="txtpwd" required>
                        </div>

                        <button type="submit" class="btn text-white w-100" style="background-color:#d4af37;">
                            LOGIN
                        </button>

                    </form>

                    <div class="mt-3 text-center">
                        <a href="customer/register.php">New Customer? Register</a><br>
                        <a href="supplier/register.php">New Supplier? Register</a>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>
</main>

<?php include 'includes/footer.php'; ?>