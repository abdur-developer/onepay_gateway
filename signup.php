<?php
require 'config.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($name)) {
        $errors['name'] = 'Name is required';
    }

    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email';
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $errors['email'] = 'Email already exists';
        }
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters';
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match';
    }
    if ($name == 'Admin' || $name == 'admin') {
        $errors['name'] = 'Invalid name. Please choose a different name.';
    }

    // If no errors, register user
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $hashed_password])) {
            $id = $pdo->lastInsertId();
            $success = 'Registration successful! You can now login.';
            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            
            // Clear form
            $name = $email = $password = $confirm_password = '';
            // Redirect to dashboard
            redirect('home.php');
        } else {
            $errors['general'] = 'Registration failed. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="auth-container">
    <?php if ($success): ?>
      <div class="success-message"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="auth-card">
      <div class="auth-header">
        <h2>Create an account</h2>
        <p>Get started with your free account</p>
      </div>

      <?php if (isset($errors['general'])): ?>
        <div class="error-message"><?php echo $errors['general']; ?></div>
      <?php endif; ?>

      <form class="auth-form" method="POST">
        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
          <?php if (isset($errors['name'])): ?>
            <div class="error-message"><?php echo $errors['name']; ?></div>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
          <?php if (isset($errors['email'])): ?>
            <div class="error-message"><?php echo $errors['email']; ?></div>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
          <?php if (isset($errors['password'])): ?>
            <div class="error-message"><?php echo $errors['password']; ?></div>
          <?php endif; ?>
          
          <div class="show-password">
            <input type="checkbox" id="show-password">
            <label for="show-password">Show password</label>
          </div>
        </div>

        <div class="form-group">
          <label for="confirm-password">Confirm Password</label>
          <input type="password" id="confirm-password" name="confirm_password" required>
          <?php if (isset($errors['confirm_password'])): ?>
            <div class="error-message"><?php echo $errors['confirm_password']; ?></div>
          <?php endif; ?>
        </div>

        <button type="submit" class="btn">Sign Up</button>
      </form>

      <div class="auth-footer">
        <p>Already have an account? <a href="signin.php">Sign in</a></p>
      </div>
    </div>
  </div>

  <script>
    // Show/hide password functionality
    document.getElementById('show-password').addEventListener('change', function(e) {
      const password = document.getElementById('password');
      const confirmPassword = document.getElementById('confirm-password');
      const type = e.target.checked ? 'text' : 'password';
      password.type = type;
      confirmPassword.type = type;
    });
  </script>
</body>
</html>