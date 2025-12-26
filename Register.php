<!-- Register.php -->
<?php
include("database.php");
$errors = [];
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $firstName = filter_input(INPUT_POST, "firstName", FILTER_SANITIZE_SPECIAL_CHARS);
    $lastName = filter_input(INPUT_POST, "lastName", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password securely
    $bio = filter_input(INPUT_POST, "bio", FILTER_SANITIZE_SPECIAL_CHARS);
    $role = filter_input(INPUT_POST, "role", FILTER_SANITIZE_SPECIAL_CHARS);

    // Handle file upload
    $profilePic = "";
    if (isset($_FILES["profile_pic"]) && $_FILES["profile_pic"]["error"] == 0) {
        $uploadDir = "C:/xampp/htdocs/cse299/images/"; // Ensure this folder exists
        $fileName = basename($_FILES["profile_pic"]["name"]);
        $targetPath = $uploadDir . $fileName;

        // Check file type
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetPath)) {
                $profilePic = "images/" . $fileName; // Save relative path
            } else {
                $errors[] = "Failed to upload profile picture.";
            }
        } else {
            $errors[] = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    }

    // Validate input fields
    if (empty($firstName)) $errors[] = "First Name is required.";
    if (empty($lastName)) $errors[] = "Last Name is required.";
    if (empty($email)) {
        $errors[] = "Email is required.";
    } else {
        // Check if email already exists
        $query = "SELECT email FROM User WHERE email = '$email'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $errors[] = "This email is already registered.";
        }
    }
    if (empty($password)) $errors[] = "Password is required.";
    if (empty($role)) $errors[] = "Role is required.";

    // If no errors, proceed with the database insertion
    if (empty($errors)) {
        $sql = "INSERT INTO User (firstName, lastName, email, password, role, profile_pic, bio) 
                VALUES ('$firstName','$lastName','$email','$hashedPassword', '$role', '$profilePic', '$bio')";

        try {
            // Begin transaction
            mysqli_begin_transaction($conn);

            // Execute user insertion
            if (mysqli_query($conn, $sql)) {
                // Get the inserted user's ID
                $user_id = mysqli_insert_id($conn);

                // If the role is student, insert into the Student table
                if ($role === 'student') {
                    $student_sql = "INSERT INTO Student (user_id) VALUES ($user_id)";
                    if (!mysqli_query($conn, $student_sql)) {
                        throw new Exception("Error inserting student record.");
                    }
                }

                // Commit transaction
                mysqli_commit($conn);

                $successMessage = "You are now registered!";
                header("Location: login.php");
                exit();
            } else {
                throw new Exception("Error inserting user record.");
            }
        } catch (Exception $e) {
            // Rollback transaction on error
            mysqli_rollback($conn);
            $errors[] = "Error: " . $e->getMessage();
        }
    }

    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-image: url('https://i.postimg.cc/Tw9nySpP/5578502.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-100 flex items-center justify-center">

  <div class="w-full max-w-6xl bg-white shadow-2xl rounded-3xl overflow-hidden flex flex-col md:flex-row">

    <!-- Left Section: Branding -->
    <div class="md:w-1/2 bg-gradient-to-br from-blue-700 via-indigo-700 to-purple-700 text-white flex flex-col justify-center items-center p-12 relative">
      <div class="absolute inset-0 bg-black/10 backdrop-blur-sm"></div>

      <div class="relative z-10 text-center">
        <h1 class="text-4xl font-extrabold mb-4 tracking-tight">Welcome to <span class="text-blue-200">skillPro</span></h1>
        <p class="text-blue-100 text-lg leading-relaxed max-w-md mx-auto">
          Join thousands of learners and instructors on our platform.  
          Build your profile, share your knowledge, and grow your skills.
        </p>
      </div>

      <div class="relative z-10 mt-10">
        <img src="./logo.jpg"
             alt="Learning Illustration" class="w-64 drop-shadow-lg">
      </div>
    </div>

    <!-- Right Section: Registration -->
    <div class="md:w-1/2 bg-white p-10">
      <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Create Your Account</h2>

      <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="firstName" class="block text-gray-700 font-medium mb-1">First Name</label>
            <input type="text" name="firstName" id="firstName"
              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none placeholder-gray-400"
              placeholder="John">
          </div>
          <div>
            <label for="lastName" class="block text-gray-700 font-medium mb-1">Last Name</label>
            <input type="text" name="lastName" id="lastName"
              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none placeholder-gray-400"
              placeholder="Doe">
          </div>
        </div>

        <div>
          <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
          <input type="email" name="email" id="email"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none placeholder-gray-400"
            placeholder="you@example.com">
        </div>

        <div>
          <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
          <input type="password" name="password" id="password"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none placeholder-gray-400"
            placeholder="••••••••">
        </div>

        <div>
          <label for="role" class="block text-gray-700 font-medium mb-1">Role</label>
          <select name="role" id="role"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            <option value="">Select Role</option>
            <option value="student">Student</option>
            <option value="instructor">Instructor</option>
          </select>
        </div>

        <div>
          <label for="profile_pic" class="block text-gray-700 font-medium mb-1">Profile Picture</label>
          <input type="file" name="profile_pic" id="profile_pic"
            class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm cursor-pointer focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <div>
          <label for="bio" class="block text-gray-700 font-medium mb-1">Bio</label>
          <textarea name="bio" id="bio" rows="3"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none placeholder-gray-400"
            placeholder="Tell us about yourself..."></textarea>
        </div>

        <button type="submit" name="submit"
          class="w-full py-3 text-lg font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-md hover:shadow-lg transition-all duration-300">
          Sign Up
        </button>

        <!-- PHP Messages -->
        <?php if (!empty($successMessage)): ?>
          <div class="mt-4 text-green-600 text-center font-medium">
            <p><?= $successMessage ?></p>
          </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
          <div class="mt-4 text-red-600 text-center font-medium space-y-1">
            <?php foreach ($errors as $error): ?>
              <p><?= $error ?></p>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </form>
    </div>
  </div>
</body>



</html>