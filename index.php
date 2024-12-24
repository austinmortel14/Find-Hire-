<?php
session_start();
require_once 'functions.php'; 

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = loginUser($username, $password, $conn);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'hr') {
            header("Location: hr_dashboard.php");
            exit(); 
        } elseif ($user['role'] === 'applicant') {
            header("Location: applicant_dashboard.php");
            exit();
        }
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Find Hire</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Find Hire</h1>
        <?php if (isset($error_message)) : ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="post" action="login.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Username" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required><br><br>

            <button type="submit">Login</button>
        </form>
        <p>Register: <a href="register.php">Here</a></p>
    </div>
</body>
</html>

<div class="navbar">
    <span class="navbar-brand">Find Hire</span> 
    <ul class="navbar-links">
        <li><a href="#">About</a></li> 
        <li><a href="#">Contact</a></li> 
    </ul>
</div>