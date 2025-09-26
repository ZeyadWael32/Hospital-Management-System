<?php
session_start();
$title = "Register";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../functions/auth_functions.php';
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $name = trim($_POST["name"]);
    $email = strtolower(trim($_POST["email"]));
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $phone = $_POST["phone"];
    $gender = $_POST["gender"];
    $date_of_birth = $_POST["dob"];
    $address = trim($_POST["address"]);

    if ($password !== $confirm_password) {
        $message = "<div class='alert alert-danger'>❌ Passwords do not match.</div>";

    } elseif (strlen($password) < 6 || strlen($password) > 20) {
        $message = "<div class='alert alert-danger'>❌ Password must be between 6 and 20 characters.</div>";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert alert-danger'>❌ Invalid email format.</div>";

    } elseif (reg_email_check($conn, $email)) {
        $message = "<div class='alert alert-danger'>❌ Email is already registered.</div>";
    
    } elseif (strtotime($date_of_birth) > time()) {
        $message = "<div class='alert alert-danger'>❌ Date of birth cannot be in the future.</div>";

    } else {
        $new_user_id = user_register($conn, $name, $email, $password, $gender); 

        if ($new_user_id) {
            if (patient_register($conn, $new_user_id, $phone, $date_of_birth, $address)) {
            $_SESSION["user_id"] = $new_user_id;
            $_SESSION["role"] = "patient";
                $message = "<div class='alert alert-success'>✅ Registration successful! Redirecting...</div>";
                $redirect_url = "../index.php";
            } else {
                $message = "<div class='alert alert-danger'>❌ Failed to save patient details.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>❌ Registration failed. Please try again.</div>";
        }
    }
}
?>
<main class="auth-page register-page">
  <div class="container card shadow p-3 mb-5 bg-body rounded">
    <div class="text-center h2">
        Register
    </div>
        <?php if (!empty($message)) { echo $message; } ?>
  <form method="post">
    <div class="mb-2">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" required placeholder="Enter your name.." autofocus>
    </div>
    <div class="mb-2">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required placeholder="Enter your email..">
    </div>
    <div class="row">
    <div class="col-6 mb-2">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required placeholder="Enter your password.." aria-describedby="passwordHelp">
        <div id="passwordHelp" class="form-text mt-1">Password must be 6-20 characters long.</div>
    </div>
    <div class="col-6 mb-2">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control" required placeholder="Confirm your password..">
    </div>
    <div class="col-6 mb-2">
        <label class="form-label">Gender</label>
        <select name="gender" class="form-select" required>
            <option value="">Select your gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>
    </div>
    <div class="col-6 mb-2">
        <label class="form-label">Date of birth</label>
        <input type="date" name="dob" class="form-control" required>
    </div>
   </div>
   <div class="mb-2">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" required placeholder="Enter your phone number..">
    </div>
    <div class="mb-2">
        <label class="form-label">Address</label>
        <textarea name="address" class="form-control" rows="3" placeholder="Enter your address.."></textarea>
    </div>
        <div class="btn-group-inline">
        <button type="submit" name="submit" class="btn btn-primary">Register</button>
        <button type="reset" class="btn btn-secondary">Reset</button>
    </div>
    <div class="card-footer text-center">
        Already have an account?
        <a href="login.php">Login here</a>
    </div>
</form>
</div>

<?php if (!empty($redirect_url ?? null)) : ?>
    <div id="home-redirect" data-url="<?= htmlspecialchars($redirect_url, ENT_QUOTES, 'UTF-8'); ?>"></div>
<?php endif; ?>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>