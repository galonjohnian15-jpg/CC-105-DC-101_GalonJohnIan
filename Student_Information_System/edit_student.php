<?php 
include 'db.php';

// Function to sanitize output
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$success = false;
$error = '';
$student = null;

// Validate and sanitize ID from GET
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("Invalid student ID.");
}

$id = (int)$_GET['id'];

// Fetch student data using prepared statement
$stmt = $conn->prepare("SELECT student_id, first_name, last_name, email, course_id FROM students WHERE student_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Student not found.");
}

$student = $result->fetch_assoc();
$stmt->close();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $first = trim($_POST['first_name'] ?? '');
    $last = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $course = $_POST['course_id'] ?? '';
    
    // Validate inputs
    if (empty($first) || empty($last) || empty($email)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($first) > 50 || strlen($last) > 50) {
        $error = "Names must be less than 50 characters.";
    } else {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("UPDATE students SET first_name=?, last_name=?, email=?, course_id=? WHERE student_id=?");
        $stmt->bind_param("sssii", $first, $last, $email, $course, $id);
        
        try {
            if ($stmt->execute()) {
                $success = true;
                // Update the student array with new values
                $student['first_name'] = $first;
                $student['last_name'] = $last;
                $student['email'] = $email;
                $student['course_id'] = $course;
            } else {
                $error = "Error updating student.";
            }
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $error = "This email is already registered to another student.";
            } else {
                $error = "An error occurred. Please try again.";
            }
        }
        
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Edit Student</h1>
    
    <?php if ($success): ?>
        <div class="success-message">Student updated successfully!</div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="error-message"><?= h($error) ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <label>First Name: <span class="required">*</span></label>
        <input type="text" name="first_name" maxlength="50" required value="<?= h($student['first_name']) ?>">

        <label>Last Name: <span class="required">*</span></label>
        <input type="text" name="last_name" maxlength="50" required value="<?= h($student['last_name']) ?>">

        <label>Email: <span class="required">*</span></label>
        <input type="email" name="email" maxlength="100" required value="<?= h($student['email']) ?>">

        <label>Course: <span class="required">*</span></label>
        <select name="course_id" required>
            <?php
            $courses = $conn->query("SELECT course_id, course_name FROM courses ORDER BY course_name");
            if ($courses) {
                while ($c = $courses->fetch_assoc()) {
                    $selected = ($c['course_id'] == $student['course_id']) ? "selected" : "";
                    echo "<option value='" . h($c['course_id']) . "' {$selected}>" . h($c['course_name']) . "</option>";
                }
            }
            ?>
        </select>

        <input type="submit" name="update" value="Update Student" class="btn">
    </form>

    <a href="index.php">Back to Home</a>
</body>
</html>
<?php $conn->close(); ?>