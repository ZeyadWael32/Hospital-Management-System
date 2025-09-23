<?php
session_start();
$title = "Register";
include "header.php";
require "db.php";
require "functions.php";
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $name = trim($_POST["name"]);
    $email = strtolower(trim($_POST["email"]));
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password !== $confirm_password) {
        $message = "<div class='alert alert-danger'>❌ Passwords do not match.</div>";

    } elseif (strlen($password) < 6 || strlen($password) > 20) {
        $message = "<div class='alert alert-danger'>❌ Password must be between 6 and 20 characters.</div>";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert alert-danger'>❌ Invalid email format.</div>";

    } elseif (email_check($conn, $email)) {
        $message = "<div class='alert alert-danger'>❌ Email is already registered.</div>";
        
    } else {
        $new_user_id = insert_user($conn, $name, $email, $password);
        if ($new_user_id) {
            $_SESSION["user_id"] = $new_user_id;
            $_SESSION["user_name"] = $name;
            $_SESSION["role"] = "patient";
                $message = "<div class='alert alert-success'>✅ Registration successful! Redirecting...</div>";
                $redirect_url = "index.php";
            } else {
                $message = "<div class='alert alert-danger'>❌ Registration failed. Please try again.</div>";
            }
        }
    }
?>
<main class="register-page">
  <div class="container card shadow p-3 mb-5 bg-body rounded">
    <div class="text-center h2 text-white">
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
    <div class="mb-2">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required placeholder="Enter your password.." aria-describedby="passwordHelp">
        <div id="passwordHelp" class="form-text mt-1">Your password must be 6-20 characters long.</div>
    </div>
    <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control" required placeholder="Confirm your password..">
    </div>
    <div class="btn-group-inline">
        <button type="submit" name="submit" class="btn btn-primary">Register</button>
        <button type="reset" class="btn btn-secondary">Reset</button>
    </div>
 </form>
</div>

<?php if (!empty($redirect_url ?? null)) : ?>
    <div id="home-redirect" data-home-url="<?= htmlspecialchars($redirect_url, ENT_QUOTES, 'UTF-8'); ?>"></div>
<?php endif; ?>
</main>

<?php include "footer.php"; ?>