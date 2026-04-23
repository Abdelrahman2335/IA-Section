<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$auth = new \App\Auth();

$auth->signUp();
$old = $auth->old;
?>

<!DOCTYPE html>
<html>

<head>
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <link href="../../assets/css/auth.css" rel="stylesheet">
</head>

<body>
  <div class="auth-box">

    <h2>Register</h2>

    <form method="POST">


      <input type="hidden" name="action" value="register">

      <input type="id" name="id" class="form-control" placeholder="ID"
        value="<?= htmlspecialchars($old['id'] ?? '') ?>" required>

      <input type="text" name="name" class="form-control" placeholder="Name"
        value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>

      <input type="email" name="email" class="form-control" placeholder="Email"
        value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>

      <input type="password" name="password" class="form-control" placeholder="Password" required>

      <input type="password" name="confirmPassword" class="form-control" placeholder="Confirm Password" required>

      <button type="submit" name="action" value="register" class="btn btn-main">Register</button>


      <div class="links">
        Already have an account? <a href="login.php">Login</a>
      </div>

    </form>

  </div>

</body>

</html>