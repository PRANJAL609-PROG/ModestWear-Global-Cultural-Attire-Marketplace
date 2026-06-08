<?php
include '../includes/header.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name    = trim($_POST['txtname']);
    $address = trim($_POST['txtadd']);
    $city    = trim($_POST['txtcity']);
    $mobile  = trim($_POST['txtmno']);
    $email   = trim($_POST['txtemail']);
    $pwd     = trim($_POST['txtpwd']);

    if (empty($name) || empty($address) || empty($city) || empty($mobile) || empty($email) || empty($pwd)) {

        $message = "<div class='alert alert-danger'>All fields are required!</div>";

    } else {

        // Check if email already exists
        $check = $conn->prepare("SELECT supplier_id FROM supplier_info WHERE email_id=?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $message = "<div class='alert alert-danger'>Email already registered!</div>";

        } else {

            $hashed_pwd = password_hash($pwd, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO supplier_info
            (supplier_name, address, city, mobile_no, email_id, pwd)
            VALUES (?, ?, ?, ?, ?, ?)");

            $stmt->bind_param("ssssss", $name, $address, $city, $mobile, $email, $hashed_pwd);

            if ($stmt->execute()) {

                $message = "<div class='alert alert-success'>Supplier Registered Successfully!</div>";

            } else {

                $message = "<div class='alert alert-danger'>Error registering supplier.</div>";

            }
        }
    }
}
?>

<main>
<div class="container mt-5">

    <!-- Golden Heading -->
    <div class="row">
        <div class="col-md-12 text-center">
            <h1 style="color:#d4af37; font-weight:bold;">Supplier Registration</h1>
        </div>
    </div>

    <div class="row mt-5">

        <!-- Left Image -->
        <div class="col-md-6">
            <img src="../images/supregister.jpg" style="width:100%; height:500px; object-fit:cover;">
        </div>

        <!-- Registration Form -->
        <div class="col-md-6">

            <?php echo $message; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Enter Name</label>
                    <input type="text" class="form-control" name="txtname" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Enter Address</label>
                    <textarea class="form-control" name="txtadd" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Enter City</label>
                    <input type="text" class="form-control" name="txtcity" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Enter Mobile No</label>
                    <input type="text" class="form-control" name="txtmno" maxlength="10" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Enter Email Id</label>
                    <input type="email" class="form-control" name="txtemail" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Enter Password</label>
                    <input type="password" class="form-control" name="txtpwd" required>
                </div>

                <button type="submit" class="btn text-white" style="background-color:#d4af37;">
                    REGISTER
                </button>

            </form>

        </div>

    </div>
</div>
</main>

<?php include '../includes/footer.php'; ?>