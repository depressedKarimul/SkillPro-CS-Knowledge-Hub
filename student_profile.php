<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Fetch the profile picture from session
$profilePic = isset($_SESSION['profile_pic']) ? $_SESSION['profile_pic'] : 'default_profile.jpg'; // Set a default image if not available
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
  <?php include('head_content.php') ?>
</head>

<body>







  <header>
    <!-- Navigation -->
    <nav id="navigation">
      <div class="navbar">
        <div class="navbar-start">
          <!-- Dropdown menu -->
          <div class="dropdown">
            <div tabindex="0" role="button" class="hidden btn btn-ghost btn-circle">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h7" />
              </svg>
            </div>
            <ul
              tabindex="0"
              class="hidden menu menu-sm dropdown-content bg-black rounded-box z-[1] mt-3 w-52 p-2 shadow">
              <li><a>Homepage</a></li>
              <li><a>Portfolio</a></li>
              <li><a>About</a></li>
            </ul>
          </div>
        </div>

        <div class="navbar-center">
          <a class="btn btn-ghost text-xl">SkillPro</a>
        </div>

        <div class="navbar-end">
          <!-- Search form -->
          <form action="search.php" method="GET" class="relative">
            <button id="search-btn" type="button" class="btn btn-ghost btn-circle">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </button>
            <input
              id="search-input"
              type="text"
              name="query"
              placeholder="Search..."
              class="hidden absolute right-0 bg-black text-white rounded-md p-2 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 ease-in-out"
              style="width: 150px" />
            <input
              type="hidden"
              name="sort"
              value="<?php echo isset($_GET['sort']) ? htmlspecialchars($_GET['sort']) : 'asc'; ?>" />
          </form>

          <!-- Notification button -->
          <button class="btn btn-ghost btn-circle">
            <div class="indicator">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
              <span class="badge badge-xs badge-primary indicator-item"></span>
            </div>
          </button>
        </div>
      </div>

      <div class="navbar relative z-50">
        <div class="navbar-start">
          <!-- Dropdown menu and brand -->
          <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h8m-8 6h16" />
              </svg>
            </div>
            <ul
              tabindex="0"
              class="menu menu-sm dropdown-content rounded-box z-[1] mt-3 w-52 p-2 shadow bg-black text-white">
              <li><a href="student.php">Home</a></li>
              <li><a>Development</a></li>
              <li><a>IT & Software</a></li>
              <li><a>Design</a></li>
              <li><a>Instructors</a></li>
              <li><a href="FreeCourses.html">Free Courses</a></li>
            </ul>
          </div>

          <!-- Theme toggle -->
          <label class="flex cursor-pointer gap-2">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round">
              <circle cx="12" cy="12" r="5" />
              <path
                d="M12 1v2M12 21v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M1 12h2M21 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4" />
            </svg>
            <input type="checkbox" value="synthwave" class="toggle theme-controller" />
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round">
              <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
          </label>
        </div>

        <div class="navbar-center hidden lg:flex">
          <ul class="menu menu-horizontal px-1">
            <li class="mark"><a href="student.php">Home</a></li>
            <li><a href="">Development</a></li>
            <li><a href="">IT & Software</a></li>
            <li><a href="">Design</a></li>
            <li><a>Instructors</a></li>
            <li><a href="FreeCourses.html">Free Courses</a></li>
          </ul>
        </div>

        <!-- Profile Image Button -->
        <div class="navbar-end">
          <button
            id="profile-btn"
            class="rounded-full w-10 h-10 mr-10 overflow-hidden focus:outline-none focus:ring-2 focus:ring-blue-500">
            <img
              src="<?php echo htmlspecialchars($profilePic); ?>"
              alt="Profile"
              class="w-full h-full object-cover" />
          </button>

          <!-- Dropdown Menu -->
          <div
            id="dropdown-menu"
            class="hidden absolute right-0 mt-2 w-40 bg-[#021e3b] rounded-md shadow-lg z-10">
            <ul class="py-2 text-sm text-gray-100">
              <li>
                <a href="student_profile.php" class="block px-4 py-2 hover:bg-[#01797a]">Profile</a>
              </li>
              <li>
                <a href="student_settings.php" class="block px-4 py-2 hover:bg-[#01797a]">Settings</a>
              </li>
              <li>
                <a href="logout.php" class="block px-4 py-2 text-red-500 hover:bg-[#01797a]">Log Out</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>

    <!-- Search input toggle script -->
    <script>
      document.getElementById("search-btn").addEventListener("click", function() {
        const searchInput = document.getElementById("search-input");
        searchInput.classList.toggle("hidden");
        searchInput.focus();
      });

      document.getElementById("search-input").addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
          event.preventDefault();
          const form = document.querySelector("form");
          if (!form.querySelector('input[name="sort"]')) {
            const sortInput = document.createElement("input");
            sortInput.type = "hidden";
            sortInput.name = "sort";
            sortInput.value = "asc";
            form.appendChild(sortInput);
          }
          form.submit();
        }
      });
    </script>
  </header>

 <?php
include("database.php");


// Assuming the user is logged in and the user_id is stored in the session
$user_id = $_SESSION['user_id'];
$profilePic = isset($_SESSION['profile_pic']) ? $_SESSION['profile_pic'] : 'default_profile.jpg'; // Set a default image if not available

// Query to fetch student data and their enrolled courses
$query = "
    SELECT u.firstName, u.lastName, u.email, u.profile_pic, u.bio, e.enrollment_date, c.title AS course_title
    FROM User u
    LEFT JOIN Enrollment e ON u.user_id = e.user_id
    LEFT JOIN Course c ON e.course_id = c.course_id
    WHERE u.user_id = $user_id
";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    // Fetch the student data
    $row = mysqli_fetch_assoc($result);
    $firstName = $row['firstName'];
    $lastName = $row['lastName'];
    $email = $row['email'];
    $bio = $row['bio'];
    $profile_pic = $row['profile_pic'];
    $enrollment_date = $row['enrollment_date'];
    $course_title = $row['course_title']; // Fetching course titles the student is enrolled in
} else {
    echo "No data found for this user.";
    exit();
}
?>

  <main class="min-h-screen py-16 flex items-center justify-center font-sans transition-colors duration-300">
    <div class="max-w-5xl w-full rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col lg:flex-row bg-white dark:bg-gray-800 transition-colors duration-300">

      <!-- Profile Image -->
      <div class="lg:w-1/3 w-full bg-gradient-to-br from-[#6dff3a]/20 to-[#00b894]/10 flex items-center justify-center p-8">
        <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile"
          class="w-56 h-56 rounded-full object-cover shadow-lg border-4 border-[#6dff3a]" />
      </div>

      <!-- Profile Info -->
      <div class="lg:w-2/3 w-full p-10 text-gray-900 dark:text-white transition-colors duration-300">
        <h1 class="text-3xl font-bold mb-2">
          <?php echo htmlspecialchars($firstName . ' ' . $lastName); ?>
        </h1>
        <p class="text-sm mb-6 text-gray-600 dark:text-gray-300">
          User ID: <span class="font-medium text-gray-800 dark:text-gray-200"><?php echo htmlspecialchars($user_id); ?></span>
        </p>

        <!-- Bio -->
        <div class="mb-6">
          <h2 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Bio</h2>
          <p class="leading-relaxed text-gray-700 dark:text-gray-300">
            <?php echo $bio ? htmlspecialchars($bio) : "No bio available."; ?>
          </p>
        </div>

        <!-- Enrolled Info -->
        <div class="mb-6">
          <h2 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Account Info</h2>

          <!-- Enroll Date (Perfectly formatted) -->
          <p class="flex items-center text-gray-700 dark:text-gray-300 mb-1">
            <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path d="M2 5a2 2 0 012-2h3l1-1h4l1 1h3a2 2 0 012 2v2H2V5z" />
              <path fill-rule="evenodd" d="M2 9v6a2 2 0 002 2h12a2 2 0 002-2V9H2zm5 3a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd" />
            </svg>
            Enrolled on:
            <span class="ml-1 font-medium text-gray-800 dark:text-gray-200">
              <?php
              if (!empty($enrollment_date)) {
                echo date('F j, Y \a\t g:i A', strtotime($enrollment_date));
              } else {
                echo "Not enrolled yet";
              }
              ?>
            </span>
          </p>

          <!-- Email -->
          <p class="flex items-center text-gray-700 dark:text-gray-300">
            <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
              <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
            </svg>
            <a href="mailto:<?php echo $email; ?>" class="text-[#6dff3a] hover:underline font-medium">
              <?php echo htmlspecialchars($email); ?>
            </a>
          </p>
        </div>

        <!-- Enrolled Courses -->
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-5 bg-gray-50 dark:bg-gray-700 transition-colors duration-300">
          <h2 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">Enrolled Courses</h2>
          <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-1">
            <?php
            $coursesQuery = "SELECT c.title FROM Course c JOIN Enrollment e ON c.course_id = e.course_id WHERE e.user_id = $user_id";
            $coursesResult = mysqli_query($conn, $coursesQuery);
            if ($coursesResult && mysqli_num_rows($coursesResult) > 0) {
              while ($course = mysqli_fetch_assoc($coursesResult)) {
                echo "<li>" . htmlspecialchars($course['title']) . "</li>";
              }
            } else {
              echo "<li>No courses enrolled yet.</li>";
            }
            ?>
          </ul>
        </div>

        <!-- Buttons -->
        <div class="mt-8 flex flex-wrap gap-4">
          <a href="student_settings.php"
            class="bg-[#6dff3a] text-gray-900 dark:text-gray-900 font-semibold px-6 py-3 rounded-md shadow hover:bg-[#5bfa2e] transition">
            Edit Profile
          </a>
          <a href="delete_account.php"
            class="bg-red-500 text-white font-semibold px-6 py-3 rounded-md shadow hover:bg-red-600 transition">
            Delete Account
          </a>
        </div>
      </div>
    </div>







    
  </main>



    <!-- My Enrolled Courses -->
<h2 class="mt-5 justify-center text-center text-4xl text-white bg-[#283747] p-5 font-extrabold">My Enrolled Courses</h2>

<div class="lg:ml-32">
   <?php
include('database.php');

// Start the session to retrieve user_id (assuming session_start() is done in your header or elsewhere)

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];  // Assuming session has the logged-in user's ID

// Check if the form is submitted to enroll in a course
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];

    // Check if the user is already enrolled in the course
    $check_enrollment = "SELECT * FROM Enrollment WHERE user_id = ? AND course_id = ?";
    $stmt_check = $conn->prepare($check_enrollment);
    $stmt_check->bind_param("ii", $user_id, $course_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows == 0) {
        // If not already enrolled, enroll the user in the course
        $enroll_course = "INSERT INTO Enrollment (user_id, course_id, enrollment_date) VALUES (?, ?, NOW())";
        $stmt_enroll = $conn->prepare($enroll_course);
        $stmt_enroll->bind_param("ii", $user_id, $course_id);
        $stmt_enroll->execute();
        echo "Successfully enrolled in the course!";
    } else {
        echo "You are already enrolled in this course.";
    }
}

// Query to get only the courses the logged-in user is enrolled in
$query = "SELECT c.course_id, c.title, c.description, c.category, c.price, 
                 i.user_id AS instructor_id, u.firstName, u.lastName, u.profile_pic,
                 fp.post_date, e.enrollment_date
          FROM Course c
          JOIN Instructor i ON c.instructor_id = i.instructor_id
          JOIN User u ON i.user_id = u.user_id
          LEFT JOIN Forum_Post fp ON c.course_id = fp.course_id
          JOIN Enrollment e ON c.course_id = e.course_id
          WHERE e.user_id = ? AND c.status = 'active'"; // Filter by user_id in the Enrollment table

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id); // Bind the user_id to the query for checking enrollment
$stmt->execute();
$result = $stmt->get_result();

// Initialize a counter to track the courses in each row
$counter = 0;

// Start a new row after every 3 cards
echo '<div class="flex flex-wrap">';

// Loop through all courses and display them
while ($course = $result->fetch_assoc()) {
    // Query to get video content for the course
    $query_video = "SELECT file_url FROM Course_Content WHERE course_id = ? AND type = 'video'";
    $stmt_video = $conn->prepare($query_video);
    $stmt_video->bind_param("i", $course['course_id']);
    $stmt_video->execute();
    $result_video = $stmt_video->get_result();
    $video_content = $result_video->fetch_assoc();
?>

  <!-- Course card HTML -->
  <div class="relative flex ml-5 flex-col my-6 text-white bg-[#283747] shadow-sm border border-slate-200 rounded-lg w-96">
    <div class="relative h-56 m-2.5 overflow-hidden text-white rounded-md">
      <video id="video_<?php echo $course['course_id']; ?>" class="h-full w-full rounded-lg" controls>
        <!-- Video fetched from Course_Content table -->
        <source src="<?php echo $video_content['file_url']; ?>" type="video/mp4" />
        Your browser does not support the video tag.
      </video>
    </div>
    <div class="p-4">
      <!-- Title and description fetched from Course table -->
      <h6 class="mb-2 text-white text-xl font-semibold">
        <?php echo htmlspecialchars($course['title']); ?>
      </h6>
      <p class="text-white leading-normal font-light">
        <?php echo htmlspecialchars($course['description']); ?>
      </p>
      <p class="text-white leading-normal font-light">
        <h4 class="font-bold">Category: <?php echo htmlspecialchars($course['category']); ?></h4> 
      </p>
      <p class="text-white leading-normal font-light">
        <h4 class="font-bold">Price: $<?php echo number_format($course['price'], 2); ?></h4> 
      </p>

      <!-- Show Enrolled date -->
      <?php if ($course['enrollment_date']) { ?>
        <p class="text-white leading-normal font-light">
          <h4 class="font-bold">Enrolled on: <?php echo date('F j, Y', strtotime($course['enrollment_date'])); ?></h4>
        </p>
      <?php } ?>
    </div>
    <div class="flex items-center justify-between p-4">
      <div class="flex items-center">
        <!-- Instructor profile picture -->
        <img alt="<?php echo htmlspecialchars($course['firstName'] . ' ' . $course['lastName']); ?>"
             src="<?php echo $course['profile_pic']; ?>"
             class="relative inline-block h-8 w-8 rounded-full" />
        <div class="flex flex-col ml-3 text-sm">
          <span class="text-white font-semibold">
            <?php echo htmlspecialchars($course['firstName'] . ' ' . $course['lastName']); ?>
          </span>

          <!-- Retrieve post date from Forum_Post table -->
          <span class="text-white">
            <?php echo date('F j, Y', strtotime($course['post_date'])); ?>
          </span>
        </div>
      </div>
    </div>

    <!-- Buttons hidden initially -->
    <div class="p-4 hidden" id="buttons_<?php echo $course['course_id']; ?>">
    <a href="review.php?course_id=<?php echo $course['course_id']; ?>" 
   class="bg-blue-500 text-white px-4 py-2 rounded">
   Review
</a>

<form action="quiz_page.php" method="GET" class="inline-block">
    <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Quiz</button>
</form>

    </div>
  </div>
  
<?php
    // Increment the counter
    $counter++;

    // Close the row div after every 3 cards
    if ($counter % 3 == 0) {
        echo '</div><div class="flex flex-wrap">'; // Close the current row and start a new one
    }
} // End of while loop

// Close the last row div if there are fewer than 3 cards
if ($counter % 3 != 0) {
    echo '</div>'; // Close the remaining flex row div
}
?>

<script>
  // JavaScript to handle the video completion
  document.querySelectorAll('video').forEach(function(videoElement) {
    videoElement.addEventListener('ended', function() {
      // Get the course ID from the video element's ID
      var courseId = videoElement.id.split('_')[1];

      // Show the "Review" and "Quiz" buttons when the video ends
      document.getElementById('buttons_' + courseId).classList.remove('hidden');
    });
  });
</script>














  </div>



  <script src="js/script.js"></script>

</body>

</html>