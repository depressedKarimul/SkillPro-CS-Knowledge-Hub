<?php
// Include database connection
// NOTE: Ensure 'database.php' contains your PDO or MySQLi connection logic ($conn variable)
include("database.php");

// Function to safely sanitize and display output
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Get search query
$search_query = isset($_GET['query']) ? trim($_GET['query']) : '';
$wildcard_query = "%" . $search_query . "%";

// Check if database connection is successful
if ($conn->connect_error) {
    die('<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Database Connection Error: ' . h($conn->connect_error) . '</div>');
}

// --- Display Status Messages from Redirect ---
$status_messages = '';
if (isset($_GET['success'])) {
    $status_messages .= '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert"><strong class="font-bold">Success!</strong><span class="block sm:inline"> ' . h($_GET['success']) . '</span></div>';
}
if (isset($_GET['error'])) {
    $status_messages .= '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert"><strong class="font-bold">Error!</strong><span class="block sm:inline"> ' . h($_GET['error']) . '</span></div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Search Results</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f7f7;
        }
        .bg-card {
            background-color: #283747;
            color: white;
        }
        .highlight:hover {
            box-shadow: 0 4px 18px rgba(0,0,0,0.4);
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="p-4 sm:p-8">

<?php echo $status_messages; ?>

<div class="flex flex-col sm:flex-row justify-between items-center mb-6 p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-xl sm:text-3xl font-extrabold text-[#283747] mb-4 sm:mb-0">
        Search Results for: <span class="text-red-600">"<?php echo h($search_query); ?>"</span>
    </h2>
    <a href="admin.php" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 shadow-lg">
        &larr; Back to Dashboard
    </a>
</div>

<main class="max-w-7xl mx-auto">
<?php 
if (empty($search_query)) {
    echo '<p class="text-center text-gray-500 text-lg p-10 bg-white rounded-lg shadow-md">Please enter a search query.</p>';
    $conn->close();
    exit();
}

$results_found = 0;

// ==========================================================
// 1. Search Students
// ==========================================================
$student_sql = "SELECT user_id, firstName, lastName, email, profile_pic, bio FROM User WHERE role = 'student' AND (firstName LIKE ? OR lastName LIKE ? OR email LIKE ?)";
$student_stmt = $conn->prepare($student_sql);
if ($student_stmt) {
    $student_stmt->bind_param("sss", $wildcard_query, $wildcard_query, $wildcard_query);
    $student_stmt->execute();
    $student_result = $student_stmt->get_result();
    $students = $student_result->fetch_all(MYSQLI_ASSOC);
    $student_stmt->close();
    
    echo '<h3 id="students" class="text-center text-3xl text-white bg-[#283747] p-4 font-extrabold rounded-t-lg mt-8">Students (' . count($students) . ' Found)</h3>';
    echo '<div class="flex flex-wrap justify-center gap-6 p-6 bg-white shadow-xl rounded-b-lg">';
    
    if (count($students) > 0) {
        $results_found += count($students);
        foreach ($students as $row) {
            $student_user_id = $row['user_id']; 

            // Fetch courses for the student
            $course_sql = "SELECT Course.title 
                           FROM Enrollment
                           JOIN Course ON Enrollment.course_id = Course.course_id
                           WHERE Enrollment.user_id = ?";
            $course_stmt = $conn->prepare($course_sql);
            if ($course_stmt) {
                $course_stmt->bind_param("i", $student_user_id);
                $course_stmt->execute();
                $courses_result = $course_stmt->get_result();
                $courses = [];
                while ($course = $courses_result->fetch_assoc()) {
                    $courses[] = $course['title'];
                }
                $course_stmt->close();
            } else {
                $courses[] = "Error fetching courses: " . $conn->error;
            }

            // Display the student card
            echo '<div class="bg-[#283747] p-6 shadow-xl w-full max-w-sm rounded-2xl font-[sans-serif] overflow-hidden highlight">';
            echo '<div class="flex flex-col items-center">';
            echo '<div class="min-h-[110px]">';
            echo '<img src="' . h($row["profile_pic"] ?: "https://placehold.co/112x112/37474F/ffffff?text=STUDENT") . '" class="w-28 h-28 rounded-full border-4 border-white object-cover" onerror="this.onerror=null; this.src=\'https://placehold.co/112x112/37474F/ffffff?text=STUDENT\';" />';
            echo '</div>';
            echo '<div class="mt-4 text-center">';
            echo '<p class="text-lg text-white font-bold">' . h($row["firstName"] . " " . $row["lastName"]) . '</p>';
            echo '<p class="text-sm text-gray-300 mt-1">' . h($row["email"]) . '</p>';
            echo '<p class="text-sm text-gray-400 mt-2 line-clamp-2 h-10">' . h($row["bio"] ?: "No bio provided.") . '</p>';
            
            if (!empty($courses)) {
                echo '<p class="text-sm text-white mt-3 font-semibold border-t border-gray-600 pt-2">Enrolled Courses:</p>';
                echo '<ul class="text-xs text-gray-300 list-disc list-inside h-12 overflow-y-auto">';
                foreach ($courses as $course) {
                    echo '<li>' . h($course) . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p class="text-sm text-gray-400 mt-3">No courses enrolled.</p>';
            }
            echo '</div>';

            // Remove Button Form 
            // NOTE: Assuming you have a file named remove_item.php to handle the deletion logic.
            echo '<form method="POST" action="remove_item.php" onsubmit="return confirm(\'Permanently delete ' . h($row["firstName"] . " " . $row["lastName"]) . ' (Student)?\');" class="mt-4 w-full">';
            echo '<input type="hidden" name="item_type" value="student">';
            echo '<input type="hidden" name="item_id" value="' . h($student_user_id) . '">';
            echo '<input type="hidden" name="redirect_to" value="adminSearch.php?query=' . urlencode($search_query) . '">'; 
            echo '<button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 shadow-md">';
            echo 'Remove Student';
            echo '</button>';
            echo '</form>';
            
            echo '</div>'; // Close student card
        }
    } else {
        echo '<p class="text-gray-500 italic p-4 w-full text-center">No Students found matching the query.</p>';
    }
    echo '</div>'; // Close student wrapper

} else {
    echo '<p class="text-red-500 mt-4">Error preparing student search: ' . h($conn->error) . '</p>';
}

// ==========================================================
// 2. Search Instructors
// ==========================================================
$instructor_sql = "SELECT user_id, firstName, lastName, email, profile_pic, bio FROM User WHERE role = 'instructor' AND (firstName LIKE ? OR lastName LIKE ? OR email LIKE ?)";
$instructor_stmt = $conn->prepare($instructor_sql);
if ($instructor_stmt) {
    $instructor_stmt->bind_param("sss", $wildcard_query, $wildcard_query, $wildcard_query);
    $instructor_stmt->execute();
    $instructor_result = $instructor_stmt->get_result();
    $instructors = $instructor_result->fetch_all(MYSQLI_ASSOC);
    $instructor_stmt->close();
    
    echo '<h3 id="instructors" class="text-center text-3xl text-white bg-[#283747] p-4 font-extrabold rounded-t-lg mt-8">Instructors (' . count($instructors) . ' Found)</h3>';
    echo '<div class="flex flex-wrap justify-center gap-6 p-6 bg-white shadow-xl rounded-b-lg">';

    if (count($instructors) > 0) {
        $results_found += count($instructors);
        foreach ($instructors as $row) {
            $user_id = $row['user_id'];

            // Fetch uploaded courses for the instructor
            $course_sql = "
                SELECT c.title 
                FROM Course c
                JOIN Instructor i ON c.instructor_id = i.instructor_id
                WHERE i.user_id = ?
            ";
            $course_stmt = $conn->prepare($course_sql);
            if ($course_stmt) {
                $course_stmt->bind_param("i", $user_id);
                $course_stmt->execute();
                $course_result = $course_stmt->get_result();
                $courses = [];
                while ($course = $course_result->fetch_assoc()) {
                    $courses[] = $course['title'];
                }
                $course_stmt->close();
            } else {
                $courses[] = "Error fetching courses: " . $conn->error;
            }

            // Display the instructor card
            echo '<div class="bg-[#283747] p-6 shadow-xl w-full max-w-sm rounded-2xl font-sans overflow-hidden highlight">';
            echo '<div class="flex flex-col items-center">';
            echo '<div class="min-h-[110px]">';
            echo '<img src="' . h($row["profile_pic"] ?: "https://placehold.co/112x112/37474F/ffffff?text=INSTRUCTOR") . '" class="w-28 h-28 rounded-full border-4 border-white object-cover" onerror="this.onerror=null; this.src=\'https://placehold.co/112x112/37474F/ffffff?text=INSTRUCTOR\';" />';
            echo '</div>';
            echo '<div class="mt-4 text-center">';
            echo '<p class="text-lg text-white font-bold">' . h($row["firstName"] . " " . $row["lastName"]) . '</p>';
            echo '<p class="text-sm text-gray-300 mt-1">' . h($row["email"]) . '</p>';
            echo '<p class="text-sm text-gray-400 mt-2 line-clamp-2 h-10">' . h($row["bio"] ?: "No bio provided.") . '</p>';
            
            // Display the list of courses if available
            if (!empty($courses)) {
                echo '<p class="text-sm text-white mt-3 font-semibold border-t border-gray-600 pt-2">Courses Taught:</p>';
                echo '<ul class="text-xs text-gray-300 list-disc list-inside h-12 overflow-y-auto">';
                foreach ($courses as $course) {
                    echo '<li>' . h($course) . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p class="text-sm text-gray-400 mt-3">No courses uploaded.</p>';
            }

            // Remove Button Form
            echo '<form method="POST" action="remove_item.php" onsubmit="return confirm(\'Permanently delete ' . h($row["firstName"] . " " . $row["lastName"]) . ' (Instructor)?\');" class="mt-4 w-full">';
            echo '<input type="hidden" name="item_type" value="instructor">';
            echo '<input type="hidden" name="item_id" value="' . h($user_id) . '">';
            echo '<input type="hidden" name="redirect_to" value="adminSearch.php?query=' . urlencode($search_query) . '">'; 
            echo '<button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 shadow-md">';
            echo 'Remove Instructor';
            echo '</button>';
            echo '</form>';

            echo '</div>'; // Close the instructor card div
        }
    } else {
        echo '<p class="text-gray-500 italic p-4 w-full text-center">No Instructors found matching the query.</p>';
    }
    echo '</div>'; // Close instructor wrapper

} else {
    echo '<p class="text-red-500 mt-4">Error preparing instructor search: ' . h($conn->error) . '</p>';
}

// ==========================================================
// 3. Search Courses
// ==========================================================
$course_sql = "SELECT c.course_id, c.title, c.description, c.category, c.price, 
               i.user_id AS instructor_user_id, u.firstName, u.lastName, u.profile_pic,
               fp.post_date
               FROM Course c
               JOIN Instructor i ON c.instructor_id = i.instructor_id
               JOIN User u ON i.user_id = u.user_id
               LEFT JOIN Forum_Post fp ON c.course_id = fp.course_id
               WHERE c.title LIKE ? OR c.description LIKE ?"; 

$course_stmt = $conn->prepare($course_sql);
if ($course_stmt) {
    $course_stmt->bind_param("ss", $wildcard_query, $wildcard_query);
    $course_stmt->execute();
    $course_result = $course_stmt->get_result();
    $courses = $course_result->fetch_all(MYSQLI_ASSOC);
    $course_stmt->close();
    
    echo '<h3 id="courses" class="text-center text-3xl text-white bg-[#283747] p-4 font-extrabold rounded-t-lg mt-8">Courses (' . count($courses) . ' Found)</h3>';
    echo '<div class="flex flex-wrap justify-center gap-6 p-6 bg-white shadow-xl rounded-b-lg">';

    if (count($courses) > 0) {
        $results_found += count($courses);
        foreach ($courses as $course) {
            
            // Query to get video content for the course (using the first video found)
            $query_video = "SELECT file_url FROM Course_Content WHERE course_id = ? AND type = 'video' LIMIT 1";
            $stmt_video = $conn->prepare($query_video);
            $video_url = 'https://placehold.co/320x224/37474F/ffffff?text=No+Video'; // Default placeholder
            if ($stmt_video) {
                $stmt_video->bind_param("i", $course['course_id']);
                $stmt_video->execute();
                $result_video = $stmt_video->get_result();
                $video_content = $result_video->fetch_assoc();
                if ($video_content && !empty($video_content['file_url'])) {
                    $video_url = $video_content['file_url'];
                }
                $stmt_video->close();
            } else {
                 error_log("Error fetching video: " . $conn->error);
            }

            // Course card HTML
            echo '<div class="relative flex flex-col my-6 text-white bg-[#283747] shadow-lg border border-slate-200 rounded-lg w-80 highlight">';
            echo '<div class="relative h-56 m-2.5 overflow-hidden text-white rounded-md">';
            
            // Check if it's a video URL or a placeholder image URL
            if (strpos($video_url, 'placehold.co') !== false) {
                // It's a placeholder image
                echo '<img src="' . h($video_url) . '" alt="No video available" class="h-full w-full rounded-lg object-cover" />';
            } else {
                // It's a video URL
                echo '<video class="h-full w-full rounded-lg bg-black" controls poster="https://placehold.co/320x224/000000/ffffff?text=Course+Preview">';
                echo '<source src="' . h($video_url) . '" type="video/mp4" />';
                echo 'Your browser does not support the video tag.';
                echo '</video>';
            }

            echo '</div>';
            echo '<div class="p-4">';
            echo '<h6 class="mb-2 text-white text-xl font-semibold line-clamp-2 h-14">';
            echo h($course['title']);
            echo '</h6>';
            echo '<p class="text-white text-sm leading-normal font-light line-clamp-3 h-14">';
            echo h($course['description']);
            echo '</p>';
            echo '<div class="flex justify-between items-center mt-3 border-t border-gray-600 pt-3">';
            echo '<p class="text-md font-bold text-red-400">Price: $' . number_format($course['price'], 2) . '</p>';
            echo '<p class="text-sm text-gray-400">Category: ' . h($course['category']) . '</p>';
            echo '</div>';
            echo '</div>';
            
            // Instructor and Date section
            echo '<div class="flex items-center justify-between p-4 border-t border-gray-600">';
            echo '<div class="flex items-center">';
            echo '<img alt="' . h($course['firstName'] . ' ' . $course['lastName']) . '"
                  src="' . h($course['profile_pic'] ?: "https://placehold.co/32x32/37474F/ffffff?text=I") . '"
                  class="relative inline-block h-8 w-8 rounded-full object-cover" 
                  onerror="this.onerror=null; this.src=\'https://placehold.co/32x32/37474F/ffffff?text=I\';" />';
            echo '<div class="flex flex-col ml-3 text-xs">';
            echo '<span class="text-white font-semibold">' . h($course['firstName'] . ' ' . $course['lastName']) . '</span>';
            $post_date = $course['post_date'] ? date('M j, Y', strtotime($course['post_date'])) : 'Date N/A';
            echo '<span class="text-gray-400">' . h($post_date) . '</span>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            // Delete Course Button
            echo '<form method="POST" action="remove_item.php" onsubmit="return confirm(\'Permanently delete ' . h($course['title']) . ' (Course)?\');" class="p-4 pt-0">';
            echo '<input type="hidden" name="item_type" value="course">';
            echo '<input type="hidden" name="item_id" value="' . h($course['course_id']) . '">';
            echo '<input type="hidden" name="redirect_to" value="adminSearch.php?query=' . urlencode($search_query) . '">'; 
            echo '<button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 shadow-md">';
            echo 'Remove Course';
            echo '</button>';
            echo '</form>';
            
            echo '</div>'; // Close course card
        }
    } else {
        echo '<p class="text-gray-500 italic p-4 w-full text-center">No Courses found matching the query.</p>';
    }
    echo '</div>'; // Close course wrapper

} else {
    echo '<p class="text-red-500 mt-4">Error preparing course search: ' . h($conn->error) . '</p>';
}


// --- Final message if nothing was found ---
if ($results_found === 0) {
    echo '<p class="text-center text-xl text-red-600 p-10 bg-white rounded-lg shadow-md mt-8">No results found for "' . h($search_query) . '" in Students, Instructors, or Courses.</p>';
}

$conn->close();
?>

</main>
</body>
</html>