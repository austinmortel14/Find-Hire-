<?php
session_start();
require_once 'functions.php';

if ($_SESSION['role'] === 'hr') {
    if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['requirements']) && isset($_POST['company'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $requirements = $_POST['requirements'];
        $company = $_POST['company'];
        $hr_id = $_SESSION['user_id']; 

        if (createJobPost($title, $description, $requirements, $company, $hr_id, $conn)) {
            header("Location: hr_dashboard.php");
            exit();
        } else {
            $error_message = "Failed to create job post.";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Find Hire - Post Job</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Find Hire - Post Job</h1>
    <?php if (isset($error_message)) : ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="title" placeholder="Job Title" required><br>
        <textarea name="description" placeholder="Job Description" required></textarea><br>
        <textarea name="requirements" placeholder="Job Requirements" required></textarea><br>
        <input type="text" name="company" placeholder="Company Name" required><br>
        <button type="submit">Post Job</button>
    </form>
</body>
</html>

<?php
} elseif ($_SESSION['role'] === 'applicant') {
    $job_posts = getJobPosts($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Find Hire - Job Postings</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Find Hire - Job Postings</h1>
    <ul>
        <?php foreach ($job_posts as $post) : ?>
            <li>
                <h2><?php echo $post['title']; ?></h2>
                <p><?php echo $post['company']; ?></p>
                <a href="apply.php?job_id=<?php echo $post['id']; ?>">Apply</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>

<?php
} else {
    header("Location: login.php");
    exit();
}
?>