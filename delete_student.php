<?php
// Include the database configuration
include('database.php');
session_start();

// Check if the required data is received
if (isset($_POST['student_id']) && isset($_POST['course_id'])) {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];

    // Prepare the delete query to remove the student from the course in the Enrollment table
    $delete_enrollment_query = "
        DELETE FROM Enrollment 
        WHERE user_id = '$student_id' AND course_id = '$course_id'
    ";

    if (mysqli_query($conn, $delete_enrollment_query)) {
        // Redirect or show a success message
        include_once 'utils/ActivityLogger.php';
        $log_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Unknown';
        
        // Fetch names for logging
        $sName = "Unknown Student";
        $cTitle = "Unknown Course";
        
        $resS = mysqli_query($conn, "SELECT firstName, lastName FROM User WHERE user_id = '$student_id'");
        if ($resS && $rS = mysqli_fetch_assoc($resS)) {
            $sName = $rS['firstName'] . " " . $rS['lastName'];
        }
        
        $resC = mysqli_query($conn, "SELECT title FROM Course WHERE course_id = '$course_id'");
        if ($resC && $rC = mysqli_fetch_assoc($resC)) {
            $cTitle = $rC['title'];
        }
        
        logActivity($conn, "Instructor (ID: $log_user_id) deleted Student '$sName' (ID: $student_id) from Course '$cTitle' (ID: $course_id).");

        $_SESSION['message'] = "Student removed from the course successfully.";
        header('Location: instructor.php'); // Change to the appropriate page
    } else {
        // Error message in case of failure
        $_SESSION['message'] = "Error removing student from course: " . mysqli_error($conn);
        header('Location: instructor.php'); // Change to the appropriate page
    }
} else {
    // If the required data is not received, redirect with an error message
    $_SESSION['message'] = "Invalid request.";
    header('Location: instructor.php'); // Change to the appropriate page
}
?>
