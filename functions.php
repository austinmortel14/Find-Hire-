<?php
require_once 'dbconfig.php';

function getUserRole($username, $conn) {
    $stmt = $conn->prepare("SELECT role FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['role'] ?? false; 
}

function registerUser($username, $password, $email, $role, $conn) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); 
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (:username, :password, :email, :role)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    return $stmt->execute();
}

function loginUser($username, $password, $conn) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    } else {
        return false;
    }
}
function createJobPost($title, $description, $requirements, $company, $hr_id, $conn) {
    $stmt = $conn->prepare("INSERT INTO job_posts (title, description, requirements, company, hr_id) 
                            VALUES (:title, :description, :requirements, :company, :hr_id)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':requirements', $requirements);
    $stmt->bindParam(':company', $company);
    $stmt->bindParam(':hr_id', $hr_id);
    return $stmt->execute();
}

function getJobPosts($conn) {
    $stmt = $conn->query("SELECT * FROM job_posts");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getJobPostById($id, $conn) {
    $stmt = $conn->prepare("SELECT * FROM job_posts WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function applyForJob($job_id, $applicant_id, $cover_letter, $resume_path, $conn) {
    $stmt = $conn->prepare("INSERT INTO applications (job_id, applicant_id, cover_letter, resume_path, status) 
                            VALUES (:job_id, :applicant_id, :cover_letter, :resume_path, 'Pending')");
    $stmt->bindParam(':job_id', $job_id);
    $stmt->bindParam(':applicant_id', $applicant_id);
    $stmt->bindParam(':cover_letter', $cover_letter);
    $stmt->bindParam(':resume_path', $resume_path);
    return $stmt->execute();
}

function getApplicationsByJobId($job_id, $conn) {
    $stmt = $conn->prepare("SELECT * FROM applications WHERE job_id = :job_id");
    $stmt->bindParam(':job_id', $job_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateApplicationStatus($application_id, $status, $conn) {
    $stmt = $conn->prepare("UPDATE applications SET status = :status WHERE id = :application_id");
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':application_id', $application_id);
    return $stmt->execute();
}

function sendMessage($sender_id, $receiver_id, $message, $conn) {
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) 
                            VALUES (:sender_id, :receiver_id, :message)");
    $stmt->bindParam(':sender_id', $sender_id);
    $stmt->bindParam(':receiver_id', $receiver_id);
    $stmt->bindParam(':message', $message);
    return $stmt->execute();
}

function getMessagesByUser($user_id, $conn) {
    $stmt = $conn->prepare("SELECT * FROM messages WHERE receiver_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}