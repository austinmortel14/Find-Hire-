<?php
require_once 'functions.php';

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
    $role = isset($_GET['role']) ? $_GET['role'] : 'applicant'; 
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    if ($role === 'hr') {
        if (strpos($email, '@findhire.com') === false) {
            $error_message = "Only emails ending with '@findhire.com' are allowed for HR registration.";
        } else {
            try {
                if (registerUser($username, $password, $email, $role, $conn)) {
                    header("Location: login.php?role=$role");
                    exit();
                } else {
                    $error_message = "Registration failed. Please try again.";
                }
            } catch(PDOException $e) {
                $error_message = "Registration failed: " . $e->getMessage();
            }
        }
    } else { 
        try {
            if (registerUser($username, $password, $email, $role, $conn)) {
                header("Location: login.php?role=$role");
                exit();
            } else {
                $error_message = "Registration failed. Please try again.";
            }
        } catch(PDOException $e) {
            $error_message = "Registration failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Find Hire - Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Find Hire - Register</h1>
        <?php if (isset($error_message)) : ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Username" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Email" required><br><br>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>