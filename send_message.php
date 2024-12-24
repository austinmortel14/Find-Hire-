<?php
require_once 'dbconfig.php'; 
session_start();

if (isset($_POST['send'])) {
    $sender_id = $_SESSION['user_id']; 
    $receiver_id = $_POST['receiver_id']; 
    $message = $_POST['message'];

    try {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (:sender_id, :receiver_id, :message)");
        $stmt->bindParam(':sender_id', $sender_id);
        $stmt->bindParam(':receiver_id', $receiver_id);
        $stmt->bindParam(':message', $message);
        $stmt->execute();

        $success_message = "Message sent successfully!";

    } catch(PDOException $e) {
        $error_message = "Error sending message: " . $e->getMessage();
        error_log("Error sending message: " . $e->getMessage(), 3, "error.log"); 
    }
}

?>