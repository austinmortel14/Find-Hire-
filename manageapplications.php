<?php
session_start();
require_once 'functions.php';

if ($_SESSION['role'] !== 'hr') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];
    $applications = getApplicationsByJobId($job_id, $conn);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Find Hire - Manage Applications</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Find Hire - Manage Applications</h1>
    <h2>Job ID: <?php echo $job_id; ?></h2>
    <table>
        <thead>
            <tr>
                <th>Applicant</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applications as $application) : ?>
                <tr>
                    <td><?php ?></td>
                    <td><?php echo $application['status']; ?></td>
                    <td>
                        <?php if ($application['status'] === 'Pending') : ?>
                            <a href="update_application.php?id=<?php echo $application['id']; ?>&status=Accepted">Accept</a> | 
                            <a href="update_application.php?id=<?php echo $application['id']; ?>&status=Rejected">Reject</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

<?php
} else {
    header("Location: hr_dashboard.php");
    exit();
}