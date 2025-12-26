<?php

// Include database configuration
include('database.php');
session_start();

$message = ""; // For success/error messages

// Assuming the user is logged in, and the user_id is stored in the session
$user_id = $_SESSION['user_id'];

// Fetch instructor_id for the logged-in user
$query = "SELECT instructor_id FROM Instructor WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$instructor_id = $row['instructor_id'];

$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : null; // Get course_id from URL

// Fetch current course data for editing
$course = null;
if ($course_id) {
    // Now, use the instructor_id instead of user_id
    $course_query = "SELECT * FROM Course WHERE course_id = '$course_id' AND instructor_id = '$instructor_id'";
    $course_result = mysqli_query($conn, $course_query);
    if ($course_result && mysqli_num_rows($course_result) > 0) {
        $course = mysqli_fetch_assoc($course_result);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $course_id) {
    // Course details
    $course_title = $_POST['course_title'];
    $course_description = $_POST['course_description'];
    $category = $_POST['category'];
    $difficulty = $_POST['difficulty'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    // Update course details
    $update_course_query = "
        UPDATE Course SET title = '$course_title', description = '$course_description', category = '$category', difficulty = '$difficulty', price = '$price', status = '$status'
        WHERE course_id = '$course_id' AND instructor_id = '$instructor_id'
    ";
    
    if (mysqli_query($conn, $update_course_query)) {
        include_once 'utils/ActivityLogger.php';
        logActivity($conn, "Instructor (ID: $user_id) updated Course '$course_title' (ID: $course_id).");
        $message = "Course details updated successfully.";

        // Handle content file upload
        if (isset($_FILES["content_file"]) && $_FILES["content_file"]["error"] == 0) {
            $uploadDir = "C:/xampp/htdocs/cse311/Upload Course/"; // Ensure this folder exists
            $fileName = basename($_FILES["content_file"]["name"]);
            $targetPath = $uploadDir . $fileName;

            // Check file type
            $allowedTypes = ["mp4", "pdf", "docx", "jpg"];
            $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES["content_file"]["tmp_name"], $targetPath)) {
                    $contentFileUrl = "Upload Course/" . $fileName; // Save relative path

                    // Update content data in the database
                    $contentType = $_POST["content_type"];
                    $contentTitle = $_POST["content_title"];
                    $contentDuration = $_POST["content_duration"];
                    $updateContentQuery = "
                        UPDATE Course_Content SET type = '$contentType', title = '$contentTitle', file_url = '$contentFileUrl', content_duration = '$contentDuration'
                        WHERE course_id = '$course_id'
                    ";

                    if (mysqli_query($conn, $updateContentQuery)) {
                        $message = "Course and content updated successfully!";
                      
                        
                    } else {
                        $errors[] = "Error updating content: " . mysqli_error($conn);
                    }
                } else {
                    $errors[] = "Failed to upload content file.";
                }
            } else {
                $errors[] = "Invalid file type. Only MP4, PDF, DOCX, and JPG are allowed.";
            }
        }
    } else {
        $message = "Error updating course: " . mysqli_error($conn);
    }
}
?>
<!-- Professional Edit Course Page (Will redirect to instructor.php after save) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('head_content.php') ?>
</head>
<body class="bg-[#F8F9FA] min-h-screen flex items-center justify-center p-8">

<div class="max-w-4xl w-full bg-white p-10 rounded-2xl shadow-lg border border-gray-200">
    <h1 class="text-3xl font-extrabold text-center text-gray-800 mb-8 tracking-tight">
        Edit Course Content
    </h1>

    <?php if ($course): ?>
    <form action="" method="post" enctype="multipart/form-data" class="space-y-8">

        <!-- File Upload Box -->
        <div>
            <label for="content_file" class="cursor-pointer flex flex-col items-center justify-center border-2 border-dashed border-gray-300 bg-gray-50 rounded-xl h-56 hover:border-blue-500 hover:bg-blue-50 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-gray-400 mb-3" viewBox="0 0 32 32" fill="currentColor">
                    <path d="M23.75 11.044a7.99 7.99 0 0 0-15.5-.009A8 8 0 0 0 9 27h3a1 1 0 0 0 0-2H9a6 6 0 0 1-.035-12 1.038 1.038 0 0 0 1.1-.854 5.991 5.991 0 0 1 11.862 0A1.08 1.08 0 0 0 23 13a6 6 0 0 1 0 12h-3a1 1 0 0 0 0 2h3a8 8 0 0 0 .75-15.956z"/>
                    <path d="M20.293 19.707a1 1 0 0 0 1.414-1.414l-5-5a1 1 0 0 0-1.414 0l-5 5a1 1 0 0 0 1.414 1.414L15 16.414V29a1 1 0 0 0 2 0V16.414z"/>
                </svg>
                <span class="text-gray-600 font-semibold">Click or Drag to Upload File</span>
                <p id="file_name" class="text-sm text-gray-400 mt-2">Supports video (MP4) and PDF</p>
                <input type="file" id="content_file" name="content_file" class="hidden" />
            </label>
        </div>

        <!-- Course Details Inputs -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Course Title</label>
                <input type="text" name="course_title" value="<?php echo $course['title']; ?>" class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none" required>
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Price ($)</label>
                <input type="number" step="0.01" name="price" value="<?php echo $course['price']; ?>" class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none" required>
            </div>
        </div>

        <div>
            <label class="block text-gray-700 font-semibold mb-1">Course Description</label>
            <textarea name="course_description" rows="4" class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none" required><?php echo $course['description']; ?></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Category</label>
                <select name="category" class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none" required>
                    <option value="Development" <?php echo $course['category']=='Development'?'selected':''; ?>>Development</option>
                    <option value="IT and Software" <?php echo $course['category']=='IT and Software'?'selected':''; ?>>IT & Software</option>
                    <option value="Design" <?php echo $course['category']=='Design'?'selected':''; ?>>Design</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Difficulty</label>
                <select name="difficulty" class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none" required>
                    <option value="beginner" <?php echo $course['difficulty']=='beginner'?'selected':''; ?>>Beginner</option>
                    <option value="intermediate" <?php echo $course['difficulty']=='intermediate'?'selected':''; ?>>Intermediate</option>
                    <option value="advanced" <?php echo $course['difficulty']=='advanced'?'selected':''; ?>>Advanced</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-gray-700 font-semibold mb-1">Status</label>
            <select name="status" class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none" required>
                <option value="active" <?php echo $course['status']=='active'?'selected':''; ?>>Active</option>
                <option value="inactive" <?php echo $course['status']=='inactive'?'selected':''; ?>>Inactive</option>
            </select>
        </div>

        <!-- Content Fields -->
        <hr class="my-8 border-gray-300">
        <h2 class="text-xl font-bold text-gray-800 mb-3">Content Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <select name="content_type" class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none" required>
                <option value="video">Video</option>
                <option value="article">Article</option>
                <option value="quiz">Quiz</option>
            </select>
            <input type="text" name="content_duration" placeholder="Duration (hh:mm:ss)" class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none">
        </div>

        <input type="text" name="content_title" placeholder="Content Title" class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none">

        <!-- Submit -->
        <button type="submit" class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all duration-200">
            Save Changes
        </button>

        <?php if ($message): ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const modal = document.createElement("div");
        modal.innerHTML = `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-8 rounded-lg shadow-xl text-center max-w-sm w-full">
                <h2 class="text-2xl font-semibold text-green-600 mb-3">✅ Update Successful!</h2>
                <p class="text-gray-700 mb-6">Your course has been updated successfully.</p>
                <button id="goDashboard" class="w-full py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Go to Dashboard</button>
            </div>
        </div>`;
        document.body.appendChild(modal);
        document.getElementById("goDashboard").onclick = () => window.location.href = "instructor.php";
    });
</script>
<?php endif; ?>

    </form>

    <div class="text-center mt-4">
        <a href="instructor.php" class="text-blue-600 font-medium hover:underline">Back to Dashboard</a>
    </div>

    <?php else: ?>
    <p class="text-center text-red-600">Course not found or unauthorized.</p>
    <?php endif; ?>
</div>

</body>
</html>
