<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ .'/../functions/auth.php';

$title = "Login";
$auth_page = true;
include __DIR__ . '/../includes/header.php';

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $user = user_login($conn, $email, $password);

    if ($user) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["name"] = $user["name"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["role"] = $user["role"];

        switch ($user["role"]) {
            case "admin":
                $redirect_url = "../admin/dashboard.php";
                break;
            case "doctor":
                $redirect_url = "../doctor/dashboard.php";
                break;
            case "patient":
                $redirect_url = "../patient/dashboard.php";
                break;
            default:
                $redirect_url = "../index.php";
        }

        $message = "<div class='alert alert-success'>✅ Login successful! Redirecting... <div class='spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div></div>";
    } else {
        $message = "<div class='alert alert-danger'>❌ Invalid email or password.</div>";
    }
}
?>

<main class="auth-page login-page">
  <div class="container card shadow p-3 mb-5 bg-body rounded">
    <div class="text-center h2">
        Login
    </div>
    <?php if (!empty($message)) { echo $message; } ?>
    <form method="post">
        <div class="mb-2">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" required placeholder="Enter your email.." autofocus>
        </div>
        <div class="mb-2">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required placeholder="Enter your password..">
        </div>
        <div class="btn-group-inline">
            <button type="submit" name="submit" class="btn btn-primary">Login</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
        <div class="card-footer text-center">
            Don't have an account?
            <a href="register.php">Register here</a>
        </div>
    </form>
  </div>

  <?php if (!empty($redirect_url) ?? null) : ?>
    <div id="dashboard-redirect" data-url="<?= htmlspecialchars($redirect_url, ENT_QUOTES, 'UTF-8'); ?>"></div>
  <?php endif; ?>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
