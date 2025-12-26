<!-- Upload_Course.php -->
<?php
// Include database configuration
include('database.php');
session_start();

$message = ""; 
$errors = [];

// Ensure user is logged in and is an instructor
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'instructor') {
    // Redirect to login or error page
    // header('Location: login.php'); 
    // exit;
    // Mock user_id and instructor_id for testing if session not set
    $_SESSION['user_id'] = 2; // Assuming user_id 2 is an instructor
    $mock_user_id = 2; 
} else {
    $mock_user_id = $_SESSION['user_id'];
}

// 1. Get instructor_id
$instructor_id = null;
$query_instructor = $conn->prepare("SELECT instructor_id FROM Instructor WHERE user_id = ?");
$query_instructor->bind_param("i", $mock_user_id);
$query_instructor->execute();
$result_instructor = $query_instructor->get_result();
if ($row = $result_instructor->fetch_assoc()) {
    $instructor_id = $row['instructor_id'];
}
$query_instructor->close();

if (!$instructor_id) {
    $message = "Error: User is not recognized as an instructor.";
    // For testing, mock an instructor ID if not found:
    $instructor_id = 1; 
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && $instructor_id) {
    $course_title = $_POST['course_title'];
    $course_description = $_POST['course_description'];
    $category = $_POST['category'];
    $difficulty = $_POST['difficulty'];
    $price = $_POST['price'];
    // Status is forcefully set to 'pending' by the database default or below logic

    $conn->begin_transaction();
    try {
        // 2. Insert Course with 'pending' status
        $insert_course_stmt = $conn->prepare("
            INSERT INTO Course (instructor_id, title, description, category, difficulty, price, status)
            VALUES (?, ?, ?, ?, ?, ?, 'pending')
        ");
        $insert_course_stmt->bind_param("issssd", 
            $instructor_id, $course_title, $course_description, $category, $difficulty, $price
        );
        $insert_course_stmt->execute();
        $course_id = $conn->insert_id;
        $insert_course_stmt->close();
        
        if ($course_id) {
            // 3. Insert into Course_Approval (creates the notification)
            $approval_stmt = $conn->prepare("
                INSERT INTO Course_Approval (course_id, approval_status)
                VALUES (?, 'pending')
            ");
            $approval_stmt->bind_param("i", $course_id);
            $approval_stmt->execute();
            $approval_stmt->close();

            $message = "Course **'{$course_title}'** uploaded successfully! It is now **pending admin approval**.";

            // --- Course Content Handling ---
            $contentFileUrl = "";
            if (isset($_FILES["content_file"]) && $_FILES["content_file"]["error"] == 0) {
                // IMPORTANT: In a real environment, use a secure path and cloud storage, not C:/xampp/
                $uploadDir = "C:/xampp/htdocs/cse299/Upload Course/"; 
                $fileName = time() . "_" . basename($_FILES["content_file"]["name"]);
                $targetPath = $uploadDir . $fileName;

                $allowedTypes = ["mp4", "pdf", "docx", "jpg"];
                $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

                if (in_array($fileType, $allowedTypes)) {
                    if (move_uploaded_file($_FILES["content_file"]["tmp_name"], $targetPath)) {
                        $contentFileUrl = "Upload Course/" . $fileName; 
                        
                        $contentType = $_POST["content_type"];
                        $contentTitle = $_POST["content_title"];
                        $contentDuration = $_POST["content_duration"];
                        
                        $insertContentStmt = $conn->prepare("
                            INSERT INTO Course_Content (course_id, type, title, file_url, content_duration)
                            VALUES (?, ?, ?, ?, ?)
                        ");
                        $insertContentStmt->bind_param("issss", $course_id, $contentType, $contentTitle, $contentFileUrl, $contentDuration);
                        $insertContentStmt->execute();
                        $insertContentStmt->close();
                    } else {
                        $errors[] = "Failed to upload content file.";
                    }
                } else {
                    $errors[] = "Invalid file type. Only MP4, PDF, DOCX, and JPG are allowed.";
                }
            }
            
            // --- Category and Forum Post (Simplified for single transaction) ---
            $category_name = $_POST['category'];
            
            // Check if category exists and insert if not (simple way, can be optimized)
            $category_check = $conn->query("SELECT * FROM Category WHERE category_name = '{$category_name}'");
            if ($category_check->num_rows == 0) {
                $conn->query("INSERT INTO Category (category_name) VALUES ('{$category_name}')");
            }
            
            // Insert Forum Post
            $post_text = "Discussion for course: {$course_title}";
            $post_date = date('Y-m-d');
            $insert_post_stmt = $conn->prepare("
                INSERT INTO Forum_Post (course_id, user_id, post_text, post_date)
                VALUES (?, ?, ?, ?)
            ");
            $insert_post_stmt->bind_param("iiss", $course_id, $mock_user_id, $post_text, $post_date);
            $insert_post_stmt->execute();
            $insert_post_stmt->close();
            
            $conn->commit();

        } else {
            $message = "Error creating course.";
            $conn->rollback();
        }

    } catch (Exception $e) {
        $conn->rollback();
        $message = "Transaction Failed: " . $e->getMessage();
        $errors[] = "Database error. Please check logs.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include('head_content.php') ?>
</head>
<body class="bg-[#F1F4F8] min-h-screen flex items-center justify-center p-8">
  <div class="max-w-4xl w-full bg-white p-10 rounded-2xl shadow-xl border border-gray-100">
    <h1 class="text-3xl font-extrabold text-center text-gray-900 mb-8 tracking-tight">Upload New Course</h1>

    <?php if (!empty($message)): ?>
      <div class="mb-6 p-4 bg-green-50 border border-green-300 text-green-700 rounded-lg text-center font-medium shadow-sm">
        <?php echo $message; ?>
      </div>
      <?php if (!empty($errors)): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-300 text-red-700 rounded-lg text-center font-medium shadow-sm">
            <?php echo implode('<br>', $errors); ?>
        </div>
      <?php endif; ?>
      <div class="flex justify-center">
        <!-- Assuming instructor.php is the dashboard -->
        <a href="instructor.php" class="px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition duration-200 shadow-md">
          Go to Dashboard
        </a>
      </div>
    <?php else: ?>

    <form action="" method="post" enctype="multipart/form-data" class="space-y-8">

      <!-- Upload Box -->
      <div>
        <label for="content_file" class="cursor-pointer flex flex-col items-center justify-center border-2 border-dashed border-gray-300 bg-gray-50 rounded-xl h-56 hover:border-blue-500 hover:bg-blue-50 transition-all">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-gray-400 mb-3" viewBox="0 0 32 32" fill="currentColor">
            <path d="M23.75 11.044a7.99 7.99 0 0 0-15.5-.009A8 8 0 0 0 9 27h3a1 1 0 0 0 0-2H9a6 6 0 0 1-.035-12 1.038 1.038 0 0 0 1.1-.854 5.991 5.991 0 0 1 11.862 0A1.08 1.08 0 0 0 23 13a6 6 0 0 1 0 12h-3a1 1 0 0 0 0 2h3a8 8 0 0 0 .75-15.956z"/>
            <path d="M20.293 19.707a1 1 0 0 0 1.414-1.414l-5-5a1 1 0 0 0-1.414 0l-5 5a1 1 0 0 0 1.414 1.414L15 16.414V29a1 1 0 0 0 2 0V16.414z"/>
          </svg>
          <span class="text-gray-600 font-semibold">Click or Drag to Upload File</span>
          <p id="file_name" class="text-sm text-gray-400 mt-2">Supports video (MP4) and PDF</p>
          <input type="file" id="content_file" name="content_file" class="hidden" required />
        </label>
      </div>

      <script>
        const fileInput = document.getElementById('content_file');
        const fileNameDisplay = document.getElementById('file_name');
        fileInput.addEventListener('change', function() {
          if (fileInput.files.length > 0) {
            fileNameDisplay.textContent = `Selected: ${fileInput.files[0].name}`;
            fileNameDisplay.classList.add("text-green-600", "font-medium");
          }
        });
      </script>

      <!-- Form Fields -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-gray-700 font-medium mb-1">Course Title</label>
          <input type="text" name="course_title" class="w-full p-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none shadow-sm" placeholder="e.g. Mastering Tailwind CSS" required>
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-1">Price ($)</label>
          <input type="number" step="0.01" name="price" class="w-full p-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none shadow-sm" placeholder="e.g. 49.99" required>
        </div>
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1">Course Description</label>
        <textarea name="course_description" rows="4" class="w-full p-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none shadow-sm" placeholder="Briefly describe your course..." required></textarea>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-gray-700 font-medium mb-1">Category</label>
          <select name="category" class="w-full p-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none shadow-sm" required>
            <option value="" disabled selected>Select Category</option>
            <option value="Development">Development</option>
            <option value="IT and Software">IT & Software</option>
            <option value="Design">Design</option>
            <option value="Business">Business</option>
          </select>
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-1">Difficulty</label>
          <select name="difficulty" class="w-full p-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none shadow-sm" required>
            <option value="beginner">Beginner</option>
            <option value="intermediate">Intermediate</option>
            <option value="advanced">Advanced</option>
          </select>
        </div>
      </div>
      
      <!-- Removed Course Status as it is now controlled by Admin -->
      <!-- The content below remains part of the form -->
      
      <hr class="my-8 border-gray-200">

      <h2 class="text-xl font-bold text-gray-800 mb-3">Course Content Details</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-gray-700 font-medium mb-1">Content Type</label>
          <select name="content_type" class="w-full p-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none shadow-sm" required>
            <option value="video">Video</option>
            <option value="article">Article</option>
            <option value="quiz">Quiz</option>
          </select>
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-1">Duration (e.g. 01:30:00)</label>
          <input type="text" name="content_duration" class="w-full p-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none shadow-sm">
        </div>
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1">Content Title</label>
        <input type="text" name="content_title" class="w-full p-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none shadow-sm" placeholder="e.g. Introduction to HTML" required>
      </div>

      <div class="pt-4">
        <button type="submit" class="w-full py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-md">
          Submit Course for Approval
        </button>
      </div>

    </form>
    <?php endif; ?>
  </div>
</body>
</html>