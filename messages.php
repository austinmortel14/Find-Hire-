<?php
session_start();
require_once 'functions.php';

if ($_SESSION['role'] !== 'hr') {
    header("Location: index.php");
    exit();
}

if (isset($_POST['message'])) {
    $receiver_id = $_POST['receiver_id'];
    $message = $_POST['message'];

    if (sendMessage($sender_id, $receiver_id, $message, $conn)) {
   
        header("Location: messages.php"); 
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
    <title>Find Hire - Messages</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Find Hire - Messages</h1>
    <?php if (count($messages) > 0) : ?>
        <h2>Received Messages</h2>
        <ul>
            <?php foreach ($messages as $message) : ?>
                <li><?php echo $message['message']; ?></li> 
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>No messages received.</p>
    <?php endif; ?>

    <h2>Send Message</h2>
    <form method="post">
        <input type="hidden" name="receiver_id" value="<?php // Get applicant ID ?>"> 
        <textarea name="message" placeholder="Enter your message"></textarea><br>
        <button type="submit">Send</button>
    </form>
</body>
</html>