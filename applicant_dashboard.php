<?php
require_once 'dbconfig.php'; 
session_start();

if ($_SESSION['user_role'] !== 'applicant') {
    header("Location: index.php"); // Redirect if not an applicant user
    exit();
}

try {

    $stmt = $conn->prepare("SELECT m.*, u.username AS sender_username 
                            FROM messages m
                            JOIN users u ON m.sender_id = u.id
                            WHERE m.receiver_id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();

    $received_messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn->prepare("SELECT m.*, u.username AS receiver_username 
                            FROM messages m
                            JOIN users u ON m.receiver_id = u.id
                            WHERE m.sender_id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $sent_messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    $error_message = "Error fetching messages: " . $e->getMessage();
    error_log("Error fetching messages: " . $e->getMessage(), 3, "error.log"); 
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Find Hire - Applicant Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Applicant Dashboard</h1>
        <?php if (isset($error_message)) : ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <div class="messages">
            <h3>Received Messages</h3>
            <ul>
                <?php foreach ($received_messages as $message): ?>
                    <li>
                        <strong>From:</strong> <?php echo $message['sender_username']; ?>
                        <p><?php echo $message['message']; ?></p>
                        <p><?php echo $message['timestamp']; ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>

            <h3>Sent Messages</h3>
            <ul>
                <?php foreach ($sent_messages as $message): ?>
                    <li>
                        <strong>To:</strong> <?php echo $message['receiver_username']; ?>
                        <p><?php echo $message['message']; ?></p>
                        <p><?php echo $message['timestamp']; ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>