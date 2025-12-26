<?php
session_start();
include("database.php");
$errors = [];
$successMessage = "";

// Check if the user is logged in (optional, depending on your system)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}

$userId = $_SESSION['user_id']; // Get the logged-in user's ID from session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $currentPassword = $_POST["currentPassword"];
    $confirmPassword = $_POST["confirmPassword"];

    // Validate input
    if (empty($email)) {
        $errors[] = "Email is required.";
    }
    if (empty($currentPassword)) {
        $errors[] = "Current password is required.";
    }
    if (empty($confirmPassword)) {
        $errors[] = "Confirm password is required.";
    }
    if ($currentPassword !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Check if there are no errors before proceeding
    if (empty($errors)) {
        // Get user information from the database
        $query = "SELECT email, password FROM User WHERE user_id = '$userId'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            // Verify if the email matches and the password is correct
            if ($email === $user['email'] && password_verify($currentPassword, $user['password'])) {
                // Delete the account (only for the logged-in user)
                $deleteQuery = "DELETE FROM User WHERE user_id = '$userId'";
                if (mysqli_query($conn, $deleteQuery)) {
                    // Log the user out
                    session_destroy();
                    header("Location: login.php"); // Redirect after deletion
                    exit();
                } else {
                    $errors[] = "Error deleting account. Please try again.";
                }
            } else {
                $errors[] = "Email or password is incorrect.";
            }
        } else {
            $errors[] = "User not found.";
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
    <title>Delete Account</title>
   <?php include('head_content.php') ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-image: url('https://img.freepik.com/free-vector/gradient-hexagonal-background_23-2148962038.jpg?t=st=1732254017~exp=1732257617~hmac=8b3eef7f28c93fe59cd257cef8d39bbfa00e6720d7357160568a15b2e303c2e7&w=996');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#1a0000] via-[#660000] to-[#300000] p-6 font-sans">
  <div class="w-full max-w-3xl bg-[#1a0000]/90 backdrop-blur-md border border-red-500/40 rounded-2xl shadow-2xl p-10 text-center">
    
    <!-- Header -->
    <div class="flex flex-col items-center mb-8">
      <div class="w-16 h-16 flex items-center justify-center rounded-full bg-red-600/20 border border-red-500/50 mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 9v2m0 4h.01m-6.938 4h13.856C19.403 19 20 18.403 20 17.694V6.306C20 5.597 19.403 5 18.694 5H5.306C4.597 5 4 5.597 4 6.306v11.388C4 18.403 4.597 19 5.306 19z" />
        </svg>
      </div>
      <h2 class="text-4xl font-semibold text-red-400 mb-2">Delete Your SkillPro Account</h2>
      <p class="text-gray-300 text-sm max-w-lg">
        This action <span class="text-red-400 font-semibold">cannot be undone</span>. All your data, courses, and records will be permanently removed.
      </p>
    </div>

    <!-- Form -->
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="space-y-6 text-left">
      <div>
        <label for="email" class="block text-gray-300 mb-2 font-semibold">Email</label>
        <input type="email" name="email" id="email"
          class="w-full px-4 py-3 rounded-md bg-white text-gray-900 focus:ring-4 focus:ring-red-400 outline-none transition"
          placeholder="Enter your email">
      </div>

      <div>
        <label for="currentPassword" class="block text-gray-300 mb-2 font-semibold">Current Password</label>
        <input type="password" name="currentPassword" id="currentPassword"
          class="w-full px-4 py-3 rounded-md bg-white text-gray-900 focus:ring-4 focus:ring-red-400 outline-none transition"
          placeholder="Enter your current password">
      </div>

      <div>
        <label for="confirmPassword" class="block text-gray-300 mb-2 font-semibold">Confirm Password</label>
        <input type="password" name="confirmPassword" id="confirmPassword"
          class="w-full px-4 py-3 rounded-md bg-white text-gray-900 focus:ring-4 focus:ring-red-400 outline-none transition"
          placeholder="Confirm your password">
      </div>

      <!-- Submit Button -->
      <div class="pt-4">
        <button type="submit" name="submit"
          class="w-full py-3 bg-gradient-to-r from-red-600 to-red-500 text-white text-lg font-semibold rounded-md shadow-md hover:from-red-700 hover:to-red-600 transition-all duration-300 focus:ring-4 focus:ring-red-400">
          Permanently Delete My Account
        </button>
      </div>

      <!-- Messages -->
      <?php if (!empty($successMessage)): ?>
        <div class="mt-4 text-green-400 text-center">
          <p><?= $successMessage ?></p>
        </div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="mt-4 text-red-400 text-center">
          <?php foreach ($errors as $error): ?>
            <p><?= $error ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </form>
  </div>
</body>

</html>
