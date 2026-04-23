<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$auth = new \App\Auth();
$auth->logIn();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/auth.css" rel="stylesheet">
</head>

<body>
    <div class="auth-box">

        <h2>Login</h2>

        <form method="POST">


            <input type="hidden" name="action" value="login">

            <input type="email" name="email" class="form-control" placeholder="Email" required>

            <input type="password" name="password" class="form-control" placeholder="Password" required>

            <button type="submit" name="action" value="login" class="btn btn-main">
                Login
            </button>

            <div class="links">
                Don't have account? <a href="register.php">Register</a>
            </div>



        </form>

    </div>

</body>

</html>