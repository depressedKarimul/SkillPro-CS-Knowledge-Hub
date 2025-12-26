<?php
include("database.php"); // Include your database connection file

// Check if the request method is POST and the user_id is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    // Get the user_id to delete
    $user_id = $_POST['user_id'];

    // Validate the user_id to ensure it's an integer (basic security)
    if (!filter_var($user_id, FILTER_VALIDATE_INT)) {
        // Redirect back with an error if the ID is invalid
        header("Location: students_page.php?error=Invalid User ID");
        exit();
    }

    // Prepare the DELETE statement for the User table
    // Note: The role='student' condition is a safety measure to prevent accidental admin/instructor deletion.
    $sql = "DELETE FROM User WHERE user_id = ? AND role = 'student'";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        error_log("SQL Prepare Error: " . $conn->error);
        header("Location: students_page.php?error=Database error");
        exit();
    }

    // Bind the user_id parameter
    $stmt->bind_param("i", $user_id); // 'i' for integer

    // Execute the statement
    if ($stmt->execute()) {
        // Check if a row was actually deleted
        if ($stmt->affected_rows > 0) {
            // Success: Redirect back to the students page with a success message
            header("Location: admin.php?success=Student successfully removed.");
            exit();
        } else {
            // Failure: Student not found or was not a student
            header("Location: students_page.php?error=Student not found or is not a student role.");
            exit();
        }
    } else {
        // Execution error: Redirect back with an error
        error_log("SQL Execute Error: " . $stmt->error);
        header("Location: students_page.php?error=Failed to remove student.");
        exit();
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

} else {
    // If accessed directly or without a user_id, redirect
    header("Location: admin.php");
    exit();
}
?>