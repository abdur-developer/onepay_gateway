<?php
require 'config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validation
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required';
    }

    // If no errors, attempt login
    if (empty($errors)) {
        $user = authenticate_user($email, $password);
        if ($user) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'] ?? "Admin";
            $_SESSION['user_email'] = $user['email'] ?? $user['username'];
            $_SESSION['password'] = $password;
            // echo "<pre>";
            // var_dump($_SESSION);
            
            // var_dump($user);
            // echo "</pre>";
            // die(''); // Debugging line to check login success
            // Redirect to dashboard
            redirect('home.php');
        } else {
            $errors['general'] = 'Invalid email or password';
        }
    }
}

function authenticate_user($email, $password) {
    global $pdo;
    
    // Try users table first
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_gulo'] = true; // Set user session variable
        return $user;
    }
    
    // Try admin table if user not found
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();
    
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['user_gulo'] = false; // Set admin session variable
        return $admin;
    }
    
    return null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="auth-container">
    <div class="auth-card">
      <div class="auth-header">
        <h2>Login to your account</h2>
        <p>Welcome back! Please enter your details</p>
      </div>

      <?php if (isset($errors['general'])): ?>
        <div class="error-message"><?= $errors['general']; ?></div>
      <?php endif; ?>

      <form class="auth-form" method="POST">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="text" id="email" name="email" value="<?= htmlspecialchars($email ?? ''); ?>" required>
          <?php if (isset($errors['email'])): ?>
            <div class="error-message"><?= $errors['email']; ?></div>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
          <?php if (isset($errors['password'])): ?>
            <div class="error-message"><?= $errors['password']; ?></div>
          <?php endif; ?>
          
          <div class="show-password">
            <input type="checkbox" id="show-password">
            <label for="show-password">Show password</label>
          </div>
        </div>

        <button type="submit" class="btn">Sign In</button>
      </form>

      <div class="auth-footer">
        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
        <p><a href="#">Forgot password?</a></p>
      </div>
    </div>
  </div>

  <script>
    // Show/hide password functionality
    document.getElementById('show-password').addEventListener('change', function(e) {
      const password = document.getElementById('password');
      password.type = e.target.checked ? 'text' : 'password';
    });
  </script>
</body>
</html>