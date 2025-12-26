<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
include('database.php');

// Check if review_id is set
if (isset($_POST['review_id'])) {
    $review_id = $_POST['review_id'];

    // Fetch info for logging
    $logDetails = "Unknown Review";
    //$review_id is raw POST, let's sanitize for the select
    $safe_rid = mysqli_real_escape_string($conn, $review_id);
    $qLog = "SELECT r.comment, u.firstName, u.lastName, c.title 
             FROM course_reviews r 
             JOIN User u ON r.user_id = u.user_id 
             JOIN Course c ON r.course_id = c.course_id 
             WHERE r.review_id = '$safe_rid'";
    $resLog = mysqli_query($conn, $qLog);
    if ($resLog && $infoVals = mysqli_fetch_assoc($resLog)) {
         $logDetails = "Comment: '" . substr($infoVals['comment'], 0, 50) . "...' | Student: {$infoVals['firstName']} {$infoVals['lastName']} | Course: {$infoVals['title']}";
    }

    // Prepare the delete query to delete the comment based on review_id
    $sql = "DELETE FROM course_reviews WHERE review_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameter and execute query
        $stmt->bind_param("i", $review_id);
        $stmt->execute();

        // Check if the deletion was successful
        if ($stmt->affected_rows > 0) {
            include_once 'utils/ActivityLogger.php';
            if (isset($_SESSION['user_id'])) {
                logActivity($conn, "Instructor (ID: " . $_SESSION['user_id'] . ") deleted a student comment.", $logDetails);
            }
            echo "<script>alert('Comment deleted successfully.'); window.location.href = 'instructor.php';</script>";
        } else {
            echo "<script>alert('Error: Unable to delete the comment.'); window.location.href = 'instructor.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Database error.'); window.location.href = 'instructor.php';</script>";
    }

    $conn->close();
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'instructor.php';</script>";
}
?>
