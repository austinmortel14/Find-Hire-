<?php
session_start();
require_once 'functions.php'; 

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $user = loginUser($username, $password, $conn);

    if ($user) {
   
        if ($user['role'] === $role) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'hr') {
                header("Location: hr_dashboard.php");
            } elseif ($user['role'] === 'applicant') {
                header("Location: applicant_dashboard.php");
            }
            exit();
        } else {
            $error_message = "Invalid role selection.";
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

            <div>
                <input type="radio" id="hr" name="role" value="hr">
                <label for="hr">HR</label>
            </div>

            <div>
                <input type="radio" id="applicant" name="role" value="applicant" checked>
                <label for="applicant">Applicant</label>
            </div>

            <button type="submit">Login</button>
        </form>
        <p>Register: <a href="register.php?role=applicant">Applicant</a> | <a href="register.php?role=hr">HR</a></p> 
    </div>
</body>
</html>