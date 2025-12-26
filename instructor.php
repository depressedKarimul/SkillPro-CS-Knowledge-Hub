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
  <?php
  include('head_content.php')
  ?>



</head>

<body>

  <header>
    <nav id="navigation">
      <!-- TOP NAV -->
      <div class="navbar">
        <div class="navbar-start">
          <div class="dropdown hidden">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h7" />
              </svg>
            </div>
            <ul tabindex="0"
              class="menu menu-sm dropdown-content bg-black rounded-box z-[1] mt-3 w-52 p-2 shadow left-0">
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
          <!-- Search -->
          <div class="relative">
            <button id="search-btn" class="btn btn-ghost btn-circle">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </button>
            <input id="search-input" type="text" placeholder="Search..."
              class="hidden absolute right-0 bg-black rounded-md p-2 mt-2 w-[150px]" />
          </div>

          <!-- Notification -->
          <button class="btn btn-ghost btn-circle">
            <div class="indicator">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
              <span class="badge badge-xs badge-primary indicator-item"></span>
            </div>
          </button>
        </div>
      </div>

      <!-- MAIN NAV -->
      <div class="navbar relative z-50">
        <div class="navbar-start">
          <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h8m-8 6h16" />
              </svg>
            </div>

            <ul tabindex="0"
              class="menu menu-sm dropdown-content rounded-box mt-3 w-52 p-2 shadow bg-black text-white">
              <li><a href="instructor.php">Home</a></li>
              <li><a>Development</a></li>
              <li><a>IT & Software</a></li>
              <li><a>Design</a></li>
              <li><a>Instructors</a></li>
              <li><a href="FreeCourses.html">Free Courses</a></li>
            </ul>
          </div>
        </div>

        <div class="navbar-center hidden lg:flex">
          <ul class="menu menu-horizontal px-1">
            <li class="mark"><a>Home</a></li>
            <li><a>Development</a></li>
            <li><a>IT & Software</a></li>
            <li><a>Design</a></li>
            <li><a>Instructors</a></li>
            <li><a href="FreeCourses.html">Free Courses</a></li>
          </ul>
        </div>

        <!-- PROFILE DROPDOWN (FIXED VERSION) -->
        <div class="navbar-end relative">

          <!-- Profile Image -->
          <button id="profile-btn" class="rounded-full w-10 h-10 mr-10 overflow-hidden cursor-pointer">
            <img src="<?php echo htmlspecialchars($profilePic); ?>" class="w-full h-full object-cover" />
          </button>

          <!-- Dropdown -->
          <div id="dropdown-menu"
            class="hidden absolute right-0 top-12 w-44 bg-[#021e3b] rounded-md shadow-lg z-[999]">
            <ul class="py-2 text-sm text-gray-100">
              <li><a href="instructor_profile.php" class="block px-4 py-2 hover:bg-[#01797a]">Profile</a></li>
              <li><a href="Upload_Course.php" class="block px-4 py-2 hover:bg-[#01797a]">Upload Course</a></li>
              <li><a href="edit_course.php" class="block px-4 py-2 hover:bg-[#01797a]">Edit Course</a></li>
              <li><a href="quiz_upload.php" class="block px-4 py-2 hover:bg-[#01797a]">Quiz Upload</a></li>
              <li><a href="instructor_settings.php" class="block px-4 py-2 hover:bg-[#01797a]">Settings</a></li>
              <li><a href="http://127.0.0.1:8000/?user_id=<?= $user_id ?>" target="_blank"
                  class="block px-4 py-2 hover:bg-[#01797a]">Chatbot</a></li>
              <li><a href="Messaging/sendEmail.php?user_id=<?= $user_id ?>" target="_blank"
                  class="block px-4 py-2 hover:bg-[#01797a]">Email</a></li>
              <li><a href="logout.php" class="block px-4 py-2 text-red-500 hover:bg-[#01797a]">Log Out</a></li>
            </ul>
          </div>

        </div>
      </div>
    </nav>
  </header>

  <!-- JS -->
  <script>
    // search input toggle
    document.getElementById("search-btn").addEventListener("click", function() {
      const searchInput = document.getElementById("search-input");
      searchInput.classList.toggle("hidden");
      searchInput.focus();
    });

    // profile dropdown toggle
    const profileBtn = document.getElementById("profile-btn");
    const dropdown = document.getElementById("dropdown-menu");

    profileBtn.addEventListener("click", function(e) {
      e.stopPropagation();
      dropdown.classList.toggle("hidden");
    });

    // close on outside click
    document.addEventListener("click", function() {
      dropdown.classList.add("hidden");
    });
  </script>



  <main>

    <!-- hero  -->

    <section>
      <div
        class="hero min-h-screen"
        style="background-image: url(https://img.daisyui.com/images/stock/photo-1507358522600-9f71e620c44e.webp);">
        <div class="hero-overlay"></div>
        <div class="hero-content text-neutral-content text-center">
          <div class="max-w-md">
            <h1 class="mb-5 text-5xl font-bold">Hello there</h1>
            <p class="mb-5">
              Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda excepturi exercitationem
              quasi. In deleniti eaque aut repudiandae et a id nisi.
            </p>
            <button class="btn btn-primary"><a href="Upload_course.php">Get Started</a></button>
          </div>
        </div>
      </div>
    </section>






    <!-- All Courses -->

    <section class="text-center m-10">
      <h2 class="text-5xl font-extrabold mb-4">Your All Courses</h2>

    </section>




    <!-- Courses Carousel Section -->
    <section>
      <?php
      // Ensure the user is logged in
      if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
      }

      include('database.php');
      $user_id = $_SESSION['user_id'];

      // Fetch instructor_id
      $query_instructor = "SELECT instructor_id FROM Instructor WHERE user_id = ?";
      $stmt_instructor = $conn->prepare($query_instructor);
      $stmt_instructor->bind_param("i", $user_id);
      $stmt_instructor->execute();
      $result_instructor = $stmt_instructor->get_result();
      $instructor = $result_instructor->fetch_assoc();

      if (!$instructor) {
        echo "<p class='text-center text-red-500 mt-5'>No instructor profile found. Please contact the admin.</p>";
        exit();
      }

      $instructor_id = $instructor['instructor_id'];

      // Fetch courses
      $sql = "
  SELECT 
      Course.course_id,
      Course.title AS course_title, 
      Course.description, 
      Course.category, 
      Course.price, 
      Course_Content.file_url 
  FROM 
      Course 
  LEFT JOIN 
      Course_Content ON Course.course_id = Course_Content.course_id 
  WHERE 
      Course_Content.type = 'video' 
      AND Course.instructor_id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $instructor_id);
      $stmt->execute();
      $result = $stmt->get_result();
      ?>

      <div class="carousel w-full  py-16">
        <?php
        if ($result && $result->num_rows > 0) {
          $courses = $result->fetch_all(MYSQLI_ASSOC);
          $chunks = array_chunk($courses, 3); // 3 cards per slide
          $slideCount = count($chunks);
          $i = 1;

          foreach ($chunks as $index => $chunk) {
        ?>
            <div id="slide<?php echo $i; ?>" class="carousel-item relative w-full">
              <!-- Cards Group -->
              <div class="lg:flex gap-10 justify-center items-center lg:ml-32">
                <?php foreach ($chunk as $row) { ?>
                  <div class="flex-auto">
                    <div class="card bg-base-100 w-96 shadow-xl">
                      <!-- Course Video -->
                      <video class="h-full w-full rounded-lg" controls>
                        <source src="<?php echo htmlspecialchars($row['file_url']); ?>" type="video/mp4" />
                        Your browser does not support the video tag.
                      </video>
                      <!-- Course Info -->
                      <div class="card-body">
                        <h2 class="card-title"><?php echo htmlspecialchars($row['course_title']); ?></h2>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <p class="text-sm text-gray-600"><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
                        <p class="text-sm text-gray-600"><strong>Price:</strong> $<?php echo htmlspecialchars($row['price']); ?></p>
                        <p class="text-sm text-gray-600"><strong>Course ID:</strong> <?php echo htmlspecialchars($row['course_id']); ?></p>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div>

              <!-- Carousel Controls -->
              <div class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
                <a href="#slide<?php echo ($i == 1) ? $slideCount : $i - 1; ?>" class="btn btn-circle">❮</a>
                <a href="#slide<?php echo ($i == $slideCount) ? 1 : $i + 1; ?>" class="btn btn-circle">❯</a>
              </div>
            </div>
        <?php
            $i++;
          }
        } else {
          echo '<p class="text-center text-white text-lg">No courses available. Start creating courses now!</p>';
        }
        ?>
      </div>
    </section>


    <!-- Students who have purchased -->
    <section>
      <h2 class="text-center text-4xl  p-5 font-extrabold">Students who have purchased courses</h2>
      <?php
      // Ensure the user is logged in
      if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
      }

      // Fetch the logged-in user ID
      $user_id = $_SESSION['user_id'];

      // Database connection
      include('database.php');

      // Query
      $sql = "
    SELECT 
        User.user_id AS student_id,
        User.firstName, 
        User.lastName, 
        User.email, 
        User.profile_pic, 
        User.bio, 
        Enrollment.enrollment_date,
        Course.course_id,
        Course.title
    FROM 
        Enrollment 
    JOIN 
        Course ON Enrollment.course_id = Course.course_id 
    JOIN 
        Instructor ON Course.instructor_id = Instructor.instructor_id 
    JOIN 
        User ON Enrollment.user_id = User.user_id 
    WHERE 
        Instructor.user_id = ?
  ";

      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();
      ?>

      <div class="flex flex-wrap justify-center">
        <?php
        $col_count = 0;

        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $profile_pic = !empty($row['profile_pic']) ? htmlspecialchars($row['profile_pic']) : 'default_profile.jpg';
            $full_name = htmlspecialchars($row['firstName']) . ' ' . htmlspecialchars($row['lastName']);
            $bio = htmlspecialchars($row['bio']);
            $enrollment_date = htmlspecialchars($row['enrollment_date']);
            $course_title = htmlspecialchars($row['title']);
            $student_id = $row['student_id']; // student ID for deletion
            $course_id = $row['course_id']; // course ID for deletion

            // Start a new row after every 3 cards
            if ($col_count % 3 === 0 && $col_count !== 0) {
              echo '</div><div class="flex flex-wrap justify-center">';
            }
        ?>
            <div class="rounded-lg border  px-4 pt-8 pb-10 shadow-lg m-4 w-96">
              <div class="relative mx-auto w-36 rounded-full">
                <img class="mx-auto h-auto w-full rounded-full" src="<?php echo $profile_pic; ?>" alt="<?php echo $full_name; ?>" />
              </div>
              <h1 class="my-1 text-center text-xl font-bold leading-8 "><?php echo $full_name; ?></h1>
              <h3 class="font-lg text-semibold text-center leading-6 "><?php echo $bio ?: 'No bio available'; ?></h3>
              <p class="text-center text-sm leading-6  ">Course: <?php echo $course_title ?: 'None'; ?></p>
              <ul class="mt-3 divide-y rounded  py-2 px-3  shadow-sm  hover:shadow">
                <li class="flex items-center py-3 text-sm">
                  <span>Email</span>
                  <span class="ml-auto"><?php echo htmlspecialchars($row['email']); ?></span>
                </li>
                <li class="flex items-center py-3 text-sm">
                  <span>Joined On</span>
                  <span class="ml-auto"><?php echo date("M d, Y", strtotime($enrollment_date)); ?></span>
                </li>
              </ul>

              <!-- Delete button form -->
              <form method="POST" action="delete_student.php" onsubmit="return confirm('Are you sure you want to remove this student?');">
                <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                <button type="submit" class="mt-4 bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-all duration-300">
                  Remove from Course
                </button>
              </form>
            </div>
        <?php
            $col_count++;
          }
        } else {
          echo '<p>No students have purchased your courses yet.</p>';
        }
        ?>
      </div>
    </section>





    <!-- All comment -->

    <section>
      <h2 class="text-center text-4xl  p-5 font-extrabold">All Your Reviews</h2>

      <?php
      // Get the logged-in user's ID
      $user_id = $_SESSION['user_id']; // Assuming the logged-in user's ID is stored in session

      // Check if the user is an instructor by their role
      $query = "SELECT * FROM User WHERE user_id = $user_id AND role = 'instructor'";
      $result = mysqli_query($conn, $query);

      // Initialize reviews_result to avoid undefined variable errors
      $reviews_result = null;

      // If the user is an instructor, proceed
      if (mysqli_num_rows($result) > 0) {
        // Get the instructor's courses by joining Course and Instructor tables
        $courses_query = "
            SELECT c.course_id, c.title, c.category
            FROM Course c
            JOIN Instructor i ON c.instructor_id = i.instructor_id
            WHERE i.user_id = $user_id";

        $courses_result = mysqli_query($conn, $courses_query);

        // Initialize an empty array to hold course IDs
        $course_ids = [];
        while ($row = mysqli_fetch_assoc($courses_result)) {
          $course_ids[] = $row['course_id'];  // Add course_id to the array
        }

        // If courses exist, fetch reviews for those courses
        if (!empty($course_ids)) {
          // Convert the array of course IDs to a comma-separated list
          $course_ids_str = implode(',', $course_ids);

          // Query to fetch reviews for the courses taught by the instructor
          $reviews_query = "
                SELECT 
                    r.review_id, r.comment, r.rating, r.review_date, u.firstName, u.lastName, u.email, u.profile_pic, 
                    c.title AS course_title, c.category AS course_category
                FROM 
                    course_reviews r
                JOIN 
                    User u ON r.user_id = u.user_id
                JOIN 
                    Course c ON r.course_id = c.course_id
                WHERE 
                    r.course_id IN ($course_ids_str)
                ORDER BY 
                    r.review_date DESC";

          $reviews_result = mysqli_query($conn, $reviews_query);
        }
      } else {
        echo "You are not authorized to view this page.";
      }
      ?>

      <!-- Display the comments and ratings -->
      <div class="comment-section p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <?php
          // Ensure $reviews_result is valid before accessing it
          if ($reviews_result && mysqli_num_rows($reviews_result) > 0) {
            while ($row = mysqli_fetch_assoc($reviews_result)) {
              $fullName = $row['firstName'] . " " . $row['lastName'];
              $profilePic = $row['profile_pic'] ? $row['profile_pic'] : 'https://loremflickr.com/320/320/girl'; // Default pic
              $comment = $row['comment'];
              $rating = $row['rating'];
              $courseTitle = $row['course_title'];
              $courseCategory = $row['course_category'];
          ?>

              <div class="p-5 border rounded shadow-md ">
                <div class="flex items-center">
                  <img class="w-16 h-16 rounded-full mr-3" src="<?= $profilePic ?>" alt="<?= $fullName ?>">
                  <div class="text-sm">
                    <a href="#" class="font-medium leading-none  hover:text-indigo-600 transition duration-500 ease-in-out">
                      <?= $fullName ?>
                    </a>
                    <p class=""><?= $row['email'] ?></p>
                  </div>
                </div>

                <!-- Course Name and Category -->
                <p class="mt-2 text-sm "><strong>Course:</strong> <?= $courseTitle ?></p>
                <p class="text-sm "><strong>Category:</strong> <?= $courseCategory ?></p>

                <p class="mt-2 text-sm "><?= $comment ?></p>

                <div class="flex mt-4">
                  <?php
                  // Display stars based on rating
                  for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $rating) {
                      echo '<svg class="fill-current text-yellow-500" width="24" height="24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path></svg>';
                    } else {
                      echo '<svg class="fill-current text-gray-300" width="24" height="24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path></svg>';
                    }
                  }
                  ?>
                </div>

                <!-- Delete Button -->
                <form method="POST" action="delete_comment.php" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                  <input type="hidden" name="review_id" value="<?= $row['review_id'] ?>">
                  <button type="submit" class="mt-4 text-red-500 hover:text-red-700">Delete Comment</button>
                </form>
              </div>
          <?php
            }
          } else {
            echo "<div class='col-span-full text-center  text-xl p-10'>No reviews yet.</div>";
          }
          ?>
        </div>
      </div>

    </section>





    <section class="py-10">

      <!-- Section Title -->
      <h2 class="text-center text-4xl p-5 font-extrabold">
        Certified Students
      </h2>

      <?php
      // Ensure the user is logged in
      if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
      }

      // Logged-in user ID
      $user_id = $_SESSION['user_id'];

      // DB connection
      include('database.php');

      // Fetch certified students for instructor courses
      $sql = "
        SELECT 
            User.firstName,
            User.lastName,
            User.email,
            User.profile_pic,
            User.bio,
            Certificate.issue_date,
            Course.title
        FROM Certificate
        JOIN Course ON Certificate.course_id = Course.course_id
        JOIN Instructor ON Course.instructor_id = Instructor.instructor_id
        JOIN User ON Certificate.user_id = User.user_id
        WHERE Instructor.user_id = ?
    ";

      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();
      ?>

      <!-- Wrapper -->
      <div class="flex flex-wrap justify-center mt-8">

        <?php
        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {

            $profile_pic = !empty($row['profile_pic'])
              ? htmlspecialchars($row['profile_pic'])
              : 'default_profile.jpg';

            $full_name = htmlspecialchars($row['firstName']) . " " . htmlspecialchars($row['lastName']);
            $bio = htmlspecialchars($row['bio']);
            $email = htmlspecialchars($row['email']);
            $issue_date = htmlspecialchars($row['issue_date']);
            $course_title = htmlspecialchars($row['title']);
        ?>

            <!-- Student Card -->
            <div class="rounded-lg border  px-4 pt-8 pb-10 shadow-lg m-4 w-80">

              <!-- Profile Image -->
              <div class="mx-auto w-32 h-32 rounded-full overflow-hidden">
                <img src="<?php echo $profile_pic; ?>"
                  class="h-full w-full object-cover"
                  alt="<?php echo $full_name; ?>">
              </div>

              <!-- Name -->
              <h1 class="mt-4 text-center text-xl font-bold ">
                <?php echo $full_name; ?>
              </h1>

              <!-- Bio -->
              <p class="text-center  text-sm">
                <?php echo $bio ?: 'No bio available'; ?>
              </p>

              <!-- Course Title -->
              <p class="text-center text-sm  mt-2">
                Course: <?php echo $course_title; ?>
              </p>

              <!-- Details -->
              <ul class="mt-4  rounded-lg py-3 px-4  shadow">

                <li class="flex justify-between py-2 text-sm">
                  <span>Email</span>
                  <span><?php echo $email; ?></span>
                </li>

                <li class="flex justify-between py-2 text-sm">
                  <span>Completion Date</span>
                  <span><?php echo date("M d, Y", strtotime($issue_date)); ?></span>
                </li>

              </ul>

            </div>
            <!-- END Student Card -->

        <?php
          }
        } else {
          echo '<p class="text-center  text-xl mt-6">
                    No students have received certificates for your courses yet.
                  </p>';
        }
        ?>

      </div>
      <!-- END Wrapper -->

    </section>








    <script src="js/script.js"></script>



</body>

</html>