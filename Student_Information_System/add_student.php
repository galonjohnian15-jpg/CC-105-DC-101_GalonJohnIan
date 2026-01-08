<?php 
include 'db.php';

// Function to sanitize output
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$success = false;
$error = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Server-side validation
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
        $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, email, course_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $first, $last, $email, $course);
        
        try {
            if ($stmt->execute()) {
                $success = true;
                // Clear form values
                $first = $last = $email = $course = '';
            } else {
                $error = "Error adding student. Email might already exist.";
            }
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $error = "This email is already registered.";
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
    <title>Add Student</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
    <h1>Add New Student</h1>
    
    <?php if ($success): ?>
        <div class="success-message">Student added successfully!</div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="error-message"><?= h($error) ?></div>
    <?php endif; ?>
    
    <form name="studentForm" method="POST" onsubmit="return validateForm()">
        <label>First Name: <span class="required">*</span></label>
        <input type="text" name="first_name" maxlength="50" required value="<?= h($first ?? '') ?>">

        <label>Last Name: <span class="required">*</span></label>
        <input type="text" name="last_name" maxlength="50" required value="<?= h($last ?? '') ?>">

        <label>Email: <span class="required">*</span></label>
        <input type="email" name="email" maxlength="100" required value="<?= h($email ?? '') ?>">

        <label>Course: <span class="required">*</span></label>
        <select name="course_id" required>
            <option value="">-- Select Course --</option>
            <?php
            $result = $conn->query("SELECT course_id, course_name FROM courses ORDER BY course_name");
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $selected = (isset($course) && $course == $row['course_id']) ? 'selected' : '';
                    echo "<option value='" . h($row['course_id']) . "' {$selected}>" . h($row['course_name']) . "</option>";
                }
            }
            ?>
        </select>

        <input type="submit" name="submit" value="Add Student" class="btn">
    </form>

    <a href="index.php">Back to Home</a>
</body>
</html>
<?php $conn->close(); ?>