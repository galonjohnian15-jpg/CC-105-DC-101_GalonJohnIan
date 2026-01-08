<?php
include 'db.php';

// Validate and sanitize ID
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("Invalid student ID.");
}

$id = (int)$_GET['id'];

// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
$stmt->bind_param("i", $id);

try {
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            // Successfully deleted
            header("Location: index.php?deleted=success");
        } else {
            // No rows affected - student doesn't exist
            header("Location: index.php?deleted=notfound");
        }
    } else {
        // Error during deletion
        header("Location: index.php?deleted=error");
    }
} catch (Exception $e) {
    // Handle any exceptions
    header("Location: index.php?deleted=error");
}

$stmt->close();
$conn->close();
exit;
?>