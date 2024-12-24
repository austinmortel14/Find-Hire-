<?php
require_once 'functions.php';

if (isset($_POST['submit'])) {
    $job_id = $_GET['job_id']; 
    $applicant_id = $_SESSION['user_id']; 

    try {
        $stmt = $conn->prepare("INSERT INTO applications (job_id, applicant_id) VALUES (:job_id, :applicant_id)");
        $stmt->bindParam(':job_id', $job_id);
        $stmt->bindParam(':applicant_id', $applicant_id);
        $stmt->execute();

        $success_message = "Application submitted successfully!";

    } catch(PDOException $e) {
        $error_message = "Error applying for job: " . $e->getMessage();
        error_log("Error applying for job: " . $e->getMessage(), 3, "error.log"); 
    }
}

if (isset($_GET['job_id']) && !empty($_GET['job_id'])) {
    $job_id = $_GET['job_id']; 

    try {
        $stmt = $conn->prepare("SELECT * FROM jobs WHERE id = :job_id");
        $stmt->bindParam(':job_id', $job_id);
        $stmt->execute();

    } catch(PDOException $e) {
        $error_message = "Error fetching job details: " . $e->getMessage();
        error_log("Error fetching job details: " . $e->getMessage(), 3, "error.log"); 
    }

} else {
    $error_message = "Invalid job ID.";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Find Hire - Apply for Job</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Find Hire - Apply for Job</h1>
        <?php if (isset($success_message)) : ?>
            <p style="color: green;"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <?php if (isset($error_message)) : ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <?php if (isset($job) && !empty($job)) : ?> 
            <h2><?php echo $job['title']; ?></h2>
            <p><strong>Company:</strong> <?php echo $job['company']; ?></p>
            <p><strong>Location:</strong> <?php echo $job['location']; ?></p>
            <p><strong>Description:</strong></p>
            <p><?php echo $job['description']; ?></p>

            <form method="post">
                <button type="submit" class="btn btn-primary">Apply Now</button>
            </form>
        <?php else: ?>
            <p>Job not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>