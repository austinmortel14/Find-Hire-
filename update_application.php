<?php
session_start();
require_once 'functions.php';

if ($_SESSION['role'] !== 'hr') {
    header("Location: index.php");
    exit();
}
if (isset($_GET['id']) && isset($_GET['status'])) {
    $application_id = $_GET['id'];
    $status = $_GET['status'];

    if (updateApplicationStatus($application_id, $status, $conn)) {
        $stmt = $conn->prepare("SELECT job_id FROM applications WHERE id = :application_id");
        $stmt->bindParam(':application_id', $application_id);
        $stmt->execute();
        $row = $stmt->fetch();
        $job_id = $row['job_id']; 
        header("Location: manageapplications.php?job_id=" . $job_id);
        exit(); 
    } else {
        echo "Error updating application status."; 
    }
} else {
    header("Location: hr_dashboard.php");
    exit();
}