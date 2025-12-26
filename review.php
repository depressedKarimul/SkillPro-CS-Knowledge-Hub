<?php

session_start();
include('database.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user_id'];
  $course_id = $_POST['course_id'];
  $rating = $_POST['rating'];
  $comment = $_POST['comment'];
  $review_date = date('Y-m-d');

  // Insert review into the database, including user_id
  $query = "INSERT INTO course_reviews (course_id, user_id, rating, comment, review_date) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("iiiss", $course_id, $user_id, $rating, $comment, $review_date);
  if ($stmt->execute()) {
    $message = "Review submitted successfully!";
  } else {
    $message = "Failed to submit review.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include('head_content.php')
  ?>
</head>

<body class="bg-white min-h-screen flex items-center justify-center p-4">

  <div class="bg-white shadow-xl border border-gray-200 rounded-2xl p-8 w-full max-w-md">

    <h1 class="text-3xl font-extrabold text-center mb-6 text-gray-800">
      Submit Your Review
    </h1>

    <form method="POST" action="">
      <input type="hidden" name="course_id" value="<?php echo $_GET['course_id']; ?>">

      <!-- Rating -->
      <label class="block text-gray-700 font-semibold mb-2">Rating:</label>
      <div class="star-rating flex justify-center space-x-2 mb-4 text-3xl cursor-pointer">
        <input type="radio" name="rating" value="1" id="star1" class="hidden" required>
        <label for="star1" class="text-gray-300">&#9733;</label>

        <input type="radio" name="rating" value="2" id="star2" class="hidden">
        <label for="star2" class="text-gray-300">&#9733;</label>

        <input type="radio" name="rating" value="3" id="star3" class="hidden">
        <label for="star3" class="text-gray-300">&#9733;</label>

        <input type="radio" name="rating" value="4" id="star4" class="hidden">
        <label for="star4" class="text-gray-300">&#9733;</label>

        <input type="radio" name="rating" value="5" id="star5" class="hidden">
        <label for="star5" class="text-gray-300">&#9733;</label>
      </div>

      <!-- Comment -->
      <label for="comment" class="block text-gray-700 font-semibold mb-2">Comment:</label>
      <textarea name="comment" id="comment" rows="4"
        class="w-full border bg-white border-gray-300 rounded-lg p-3 mb-4 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        required></textarea>

      <!-- Submit -->
      <button type="submit"
        class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
        Submit Review
      </button>
    </form>

    <!-- Message + Go to Profile -->
    <div class="mt-4 text-center">
      <?php if (isset($message) && !empty($message)) { ?>
        <p class="text-green-600 font-semibold mb-3"><?php echo $message; ?></p>

        <!-- Go to Profile Button -->
        <a href="student_profile.php"
          class="inline-block mt-2 px-5 py-2 bg-gray-800 text-white rounded-lg shadow hover:bg-black transition">
          Go to Profile
        </a>
      <?php } ?>
    </div>

  </div>

  <script>
    // Star rating logic
    const stars = document.querySelectorAll('.star-rating label');

    stars.forEach(star => {
      star.addEventListener('click', (e) => {
        const rating = e.target.getAttribute('for').replace('star', '');
        document.querySelector(`input[name="rating"][value="${rating}"]`).checked = true;
        updateStarRating(rating);
      });
    });

    function updateStarRating(rating) {
      stars.forEach((star, index) => {
        if (index < rating) {
          star.classList.add('text-yellow-400');
          star.classList.remove('text-gray-300');
        } else {
          star.classList.add('text-gray-300');
          star.classList.remove('text-yellow-400');
        }
      });
    }
  </script>

</body>

</html>