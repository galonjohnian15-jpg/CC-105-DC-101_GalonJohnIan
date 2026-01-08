<?php 
include 'db.php';

// Function to sanitize output
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Student Information System</h1>
    <a href="add_student.php" class="btn">Add New Student</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Course</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Use prepared statement to prevent SQL injection
            $sql = "SELECT students.student_id, first_name, last_name, email, course_name 
                    FROM students 
                    LEFT JOIN courses ON students.course_id = courses.course_id
                    ORDER BY students.student_id DESC";
            
            $result = $conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = h($row['student_id']);
                    $fullName = h($row['first_name']) . ' ' . h($row['last_name']);
                    $email = h($row['email']);
                    $course = h($row['course_name'] ?? 'N/A');
                    
                    echo "<tr>
                            <td>{$id}</td>
                            <td>{$fullName}</td>
                            <td>{$email}</td>
                            <td>{$course}</td>
                            <td>
                                <a href='edit_student.php?id={$id}'>Edit</a> | 
                                <a href='delete_student.php?id={$id}' onclick='return confirm(\"Are you sure you want to delete this student?\")'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No students found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
<?php $conn->close(); ?>