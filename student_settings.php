<?php
session_start();
include("database.php");

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the current user's data from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM User WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission (update data)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $password = $_POST["password"];
    $bio = $_POST["bio"];
    $profilePic = $user['profile_pic']; // Default to current profile picture

    // Handle file upload for profile picture
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $fileName = $_FILES['profile_pic']['name'];
        $fileTmp = $_FILES['profile_pic']['tmp_name'];
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = uniqid('profile_', true) . '.' . $fileExt;
        $uploadDir = 'uploads/profile_pics/';

        // Check file size (limit to 2MB)
        if ($_FILES['profile_pic']['size'] > 2097152) {
            $error = "File size exceeds the 2MB limit.";
        } else {
            // Ensure the directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true); // Create the directory if it doesn't exist
            }

            // Move the uploaded file to the target directory
            if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
                $profilePic = $uploadDir . $newFileName;
            } else {
                $error = "Failed to upload the file.";
            }
        }
    }

    // If the password is not empty, hash it
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
    } else {
        // If no new password is provided, keep the old password
        $password = $user['password'];
    }

    // Update user information in the database
    $sql = "UPDATE User SET firstName = ?, lastName = ?, password = ?, profile_pic = ?, bio = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $firstName, $lastName, $password, $profilePic, $bio, $user_id);

    if ($stmt->execute()) {
        // Redirect after successful update
        header("Location: student.php");
        exit();
    } else {
        $error = "Failed to update information.";
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('head_content.php'); ?>
</head>

<style>
    body {
        background-image: url('https://i.postimg.cc/Tw9nySpP/5578502.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }
</style>



<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#0f172a] via-[#1e3a8a] to-[#172554] p-6 font-sans">
  <div class="w-full max-w-3xl bg-[#0f172a]/90 backdrop-blur-md border border-white/20 rounded-2xl shadow-2xl p-10">
    <h2 class="text-4xl font-semibold text-center text-[#6dff3a] mb-10">Edit Your Profile</h2>

    <?php if (isset($error)) { ?>
      <div class="text-red-400 text-center mb-4 font-medium"><?php echo $error; ?></div>
    <?php } ?>

    <form action="" method="post" enctype="multipart/form-data" class="space-y-8">

      <!-- Profile Picture -->
      <div class="flex items-center gap-6">
        <img src="<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile"
          class="w-24 h-24 object-cover rounded-full border-4 border-[#6dff3a] shadow-lg">
        <div>
          <label for="profile_pic" class="block text-gray-200 font-semibold mb-2">Change Profile Picture</label>
          <input type="file" name="profile_pic" id="profile_pic"
            class="block w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#6dff3a] file:text-black hover:file:bg-[#5bfa2e] cursor-pointer">
        </div>
      </div>

      <!-- Name Fields -->
      <div class="grid md:grid-cols-2 gap-6">
        <div>
          <label for="firstName" class="block text-gray-300 mb-2 font-semibold">First Name</label>
          <input type="text" name="firstName" id="firstName"
            value="<?php echo htmlspecialchars($user['firstName']); ?>"
            class="w-full px-4 py-3 rounded-md bg-white text-gray-800 focus:ring-4 focus:ring-[#6dff3a] outline-none transition">
        </div>

        <div>
          <label for="lastName" class="block text-gray-300 mb-2 font-semibold">Last Name</label>
          <input type="text" name="lastName" id="lastName"
            value="<?php echo htmlspecialchars($user['lastName']); ?>"
            class="w-full px-4 py-3 rounded-md bg-white text-gray-800 focus:ring-4 focus:ring-[#6dff3a] outline-none transition">
        </div>
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-gray-300 mb-2 font-semibold">Password</label>
        <input type="password" name="password" id="password"
          class="w-full px-4 py-3 rounded-md bg-white text-gray-800 focus:ring-4 focus:ring-[#6dff3a] outline-none transition"
          placeholder="Enter a new password">
      </div>

      <!-- Bio -->
      <div>
        <label for="bio" class="block text-gray-300 mb-2 font-semibold">Bio</label>
        <textarea name="bio" id="bio" rows="4"
          class="w-full px-4 py-3 rounded-md bg-white text-gray-800 focus:ring-4 focus:ring-[#6dff3a] outline-none transition resize-none"><?php echo htmlspecialchars($user['bio']); ?></textarea>
      </div>

      <!-- Buttons -->
      <div class="flex flex-col md:flex-row items-center justify-between gap-4 mt-6">
        <button type="submit" name="submit"
          class="w-full md:w-auto px-8 py-3 bg-[#6dff3a] text-black font-semibold rounded-md shadow-md hover:bg-[#54e22d] transition-all focus:ring-4 focus:ring-[#6dff3a]">
          Save Changes
        </button>

        <a href="delete_account.php"
          class="w-full md:w-auto px-8 py-3 bg-red-600 text-white font-semibold rounded-md shadow-md hover:bg-red-700 transition-all focus:ring-4 focus:ring-red-400">
          Delete Account
        </a>
      </div>

    </form>
  </div>
</body>




</html>
