<?php
session_start();
require_once 'functions.php';

if ($_SESSION['role'] !== 'applicant') {
    header("Location: index.php");
    exit();
}

if (isset($_POST['message'])) {
    $sender_id = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("SELECT job_id FROM applications WHERE applicant_id = :applicant_id ORDER BY id DESC LIMIT 1"); 
    $stmt->bindParam(':applicant_id', $sender_id);
    $stmt->execute();
    $row = $stmt->fetch(); 
    $job_id = $row['job_id'];

    $stmt = $conn->prepare("SELECT hr_id FROM job_posts WHERE id = :job_id");
    $stmt->bindParam(':job_id', $job_id);
    $stmt->execute();
    $row = $stmt->fetch();
    $receiver_id = $row['hr_id'];

    if (sendMessage($sender_id, $receiver_id, $_POST['message'], $conn)) {
      
        header("Location: message_hr.php"); 
        exit();
    } else {
     
        echo "Error sending message.";
    }
}

$messages = getMessagesByUser($_SESSION['user_id'], $conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Find Hire - Message HR</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Find Hire - Message HR</h1>
    <?php if (count($messages) > 0) : ?>
        <h2>Messages from HR</h2>
        <ul>
            <?php foreach ($messages as $message) : ?>
                <li><?php echo $message['message']; ?></li> 
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>No messages from HR.</p>
    <?php endif; ?>

    <h2>Send Message to HR</h2>
    <form method="post">
        <textarea name="message" placeholder="Enter your message"></textarea><br>
        <button type="submit">Send</button>
    </form>
</body>
</html>

<div class="navbar">
    <span class="navbar-brand">Find Hire</span> 
    <ul class="navbar-links">
        <li><a href="logout.php">Logout</a></li> 
    </ul>
</div>