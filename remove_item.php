<?php
// This file handles the POST request to delete a Student, Instructor, or Course

// Define the safe default redirection target page
$redirect_page = "admin.php"; 

// Include the database connection
include("database.php"); 

// Function to safely sanitize and display output (defined in database.php, but kept here for clarity if needed)
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Ensure the request method is POST and necessary parameters are provided
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['item_type']) || !isset($_POST['item_id'])) {
    header("Location: $redirect_page?error=Invalid removal request. Access denied.");
    exit();
}

$item_type = strtolower(trim($_POST['item_type']));
$item_id = $_POST['item_id'];
$redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : $redirect_page;

// --- Basic Validation ---
if (!filter_var($item_id, FILTER_VALIDATE_INT)) {
    header("Location: $redirect_to?error=" . urlencode("Invalid ID format provided for deletion."));
    exit();
}

$success_message = "";
$error_message = "";

// --- Deletion Logic based on item_type ---
switch ($item_type) {
    case 'student':
        // Delete student from User table (requires role check)
        // NOTE: Database should handle cascade deletes (Enrollment, Payments, etc.)
        $sql = "DELETE FROM User WHERE user_id = ? AND role = 'student'";
        $success_message = "Student (ID $item_id) successfully removed. Related data deleted.";
        $error_message = "Could not find or delete student (ID $item_id). User may not exist or may not be a student.";
        break;
        
    case 'instructor':
        // Delete instructor from User table (requires role check)
        // NOTE: Database should handle cascade deletes (Courses created by them, etc.)
        $sql = "DELETE FROM User WHERE user_id = ? AND role = 'instructor'";
        $success_message = "Instructor (ID $item_id) successfully removed. Related data deleted.";
        $error_message = "Could not find or delete instructor (ID $item_id). User may not exist or may not be an instructor.";
        break;

    case 'course':
        // Delete course from Course table
        // NOTE: Database should handle cascade deletes (Enrollments, Modules, etc.)
        $sql = "DELETE FROM Course WHERE course_id = ?";
        $success_message = "Course (ID $item_id) successfully removed. Related data deleted.";
        $error_message = "Could not find or delete course (ID $item_id).";
        break;
        
    default:
        // Invalid item type provided
        header("Location: $redirect_to?error=" . urlencode("Unknown item type for deletion: " . h($item_type)));
        exit();
}

// --- Execute Deletion ---
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    error_log("SQL Prepare Error: " . $conn->error);
    header("Location: $redirect_to?error=" . urlencode("Database error during preparation for " . h($item_type) . "."));
    exit();
}

// Bind the ID parameter as an integer ('i')
$stmt->bind_param("i", $item_id); 

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        // Success: Redirect back to the specified page with success message
        header("Location: $redirect_to?success=" . urlencode($success_message));
        exit();
    } else {
        // Failure: No rows affected
        header("Location: $redirect_to?error=" . urlencode($error_message));
        exit();
    }
} else {
    // Execution error
    error_log("SQL Execute Error: " . $stmt->error);
    header("Location: $redirect_to?error=" . urlencode("Failed to execute deletion: " . h($stmt->error)));
    exit();
}

// Clean up
$stmt->close();
$conn->close();
?>