<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
$user_id = $_SESSION['user_id'];

// Fetch the profile picture from session
$profilePic = isset($_SESSION['profile_pic']) ? $_SESSION['profile_pic'] : 'default_profile.jpg';
?>


<?php
include('database.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['course_id'])) {

  $user_id = $_SESSION['user_id'];
  $course_id = $_POST['course_id'];

  // Check if already enrolled
  $check = $conn->prepare("SELECT * FROM Enrollment WHERE user_id=? AND course_id=?");
  $check->bind_param("ii", $user_id, $course_id);
  $check->execute();
  $result = $check->get_result();

  if ($result->num_rows > 0) {
    echo "<script>alert('You are already enrolled!');</script>";
  } else {
    $stmt = $conn->prepare("INSERT INTO Enrollment (user_id, course_id, enrollment_date) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $user_id, $course_id);

    if ($stmt->execute()) {
      echo "<script>alert('Enrollment successful!'); window.location='student_profile.php';</script>";
    } else {
      echo "<script>alert('Error enrolling!');</script>";
    }
  }
}
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
    <!-- Navigation -->
    <nav id="navigation">
      <div class="navbar ">
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
          <!-- Search button and input field -->

          <!-- Search form with button and input field -->
          <form action="search.php" method="GET" class="relative">
            <button id="search-btn" type="button" class="btn btn-ghost btn-circle">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </button>
            <input
              id="search-input"
              type="text"
              name="query"
              placeholder="Search..."
              class="hidden absolute right-0 bg-black text-white rounded-md p-2 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 ease-in-out"
              style="width: 150px" />
            <input type="hidden" name="sort" value="<?php echo isset($_GET['sort']) ? htmlspecialchars($_GET['sort']) : 'asc'; ?>" />
          </form>

        </div>

        <script>
          // Toggle visibility of the search input when the search button is clicked
          document.getElementById("search-btn").addEventListener("click", function() {
            const searchInput = document.getElementById("search-input");
            searchInput.classList.toggle("hidden");
            searchInput.focus();
          });

          document.getElementById("search-input").addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
              event.preventDefault();
              // Add or update the sort parameter if necessary
              const form = document.querySelector("form");
              if (!form.querySelector('input[name="sort"]')) {
                const sortInput = document.createElement("input");
                sortInput.type = "hidden";
                sortInput.name = "sort";
                sortInput.value = "asc"; // Default value, adjust as needed
                form.appendChild(sortInput);
              }
              form.submit();
            }
          });
        </script>


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
            <span
              class="badge badge-xs badge-primary indicator-item"></span>
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
              <li><a>Home</a></li>

              <li>
                <a>Development</a>

              </li>

              <li>
                <a>IT & Software</a>

              </li>

              <li>
                <a>Design</a>

              </li>

              <li><a href="all_instructor.php">Instructors</a></li>


            </ul>
          </div>

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
            <input
              type="checkbox"
              value="synthwave"
              class="toggle theme-controller" />
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
              <path
                d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
          </label>
        </div>

        <div class="navbar-center hidden lg:flex">
          <ul class="menu menu-horizontal px-1">
            <li class="mark"><a>Home</a></li>
            <li>
              <a href="">Development</a>
            </li>
            <li>
              <a href=""> IT & Software</a>
            </li>

            <li>
              <a href=""> Design</a>

            </li>

            <li><a href="all_instructor.php">Instructors</a></li>

          </ul>
        </div>


        <!-- Profile Image Button -->
        <div class="navbar-end">
          <!-- Profile Image Button -->
          <button id="profile-btn" class="rounded-full w-10 h-10 mr-10 overflow-hidden focus:outline-none focus:ring-2 focus:ring-blue-500">
            <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile" class="w-full h-full object-cover" />
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
                <a href="http://127.0.0.1:8000/?user_id=<?= $user_id ?>"
                  target="_blank"
                  class="block px-4 py-2 hover:bg-[#01797a]">
                  Chatbot
                </a>


              </li>
              <li>
                <a href="Messaging/sendEmail.php?user_id=<?= $user_id ?>"
                  target="_blank"
                  class="block px-4 py-2 hover:bg-[#01797a]">
                  Email
                </a>
              </li>

              <li>
                <a href="logout.php" class="block px-4 py-2 text-red-500 hover:bg-[#01797a]">Log Out</a>
              </li>




            </ul>
          </div>
        </div>



      </div>
    </nav>
  </header>




  <!-- All Development Courses -->

  <h2 class="mt-5 text-center text-4xl p-5 font-extrabold">
    All Development Courses
  </h2>

  <section class="lg:ml-32">
    <div class="carousel w-full overflow-hidden">

      <?php
      include('database.php');

      if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
      }

      $query = "SELECT c.course_id, c.title, c.description, c.category, c.price,
                         u.firstName, u.lastName, u.profile_pic, fp.post_date
                  FROM Course c
                  JOIN Instructor i ON c.instructor_id = i.instructor_id
                  JOIN User u ON i.user_id = u.user_id
                  LEFT JOIN Forum_Post fp ON c.course_id = fp.course_id
                  WHERE c.status = 'active'
                  AND c.category='Development'";

      $stmt = $conn->prepare($query);
      $stmt->execute();
      $result = $stmt->get_result();

      $allCourses = [];
      while ($row = $result->fetch_assoc()) {
        $allCourses[] = $row;
      }

      $total = count($allCourses);
      $itemsPerSlide = 3;
      $slideCount = ceil($total / $itemsPerSlide);
      $index = 0;

      for ($slide = 1; $slide <= $slideCount; $slide++) {
      ?>

        <div id="slide<?php echo $slide; ?>" class="carousel-item w-full shrink-0 px-5">

          <div class="flex justify-center gap-6">

            <?php
            for ($i = 0; $i < $itemsPerSlide; $i++) {

              if (!isset($allCourses[$index])) break;

              $course = $allCourses[$index];

              $qv = $conn->prepare("SELECT file_url FROM Course_Content WHERE course_id=? AND type='video'");
              $qv->bind_param("i", $course['course_id']);
              $qv->execute();
              $video = $qv->get_result()->fetch_assoc();
            ?>

              <!-- CARD -->
              <div class="shadow-xl rounded-xl w-96 flex flex-col">
                <div class="h-52 overflow-hidden rounded-md">
                  <video class="w-full h-full" controls>
                    <source src="<?php echo $video['file_url']; ?>" type="video/mp4">
                  </video>
                </div>

                <div class="p-4 flex flex-col flex-grow">
                  <h3 class="text-xl font-semibold"><?php echo $course['title']; ?></h3>

                  <p class="text-gray-600 mt-2"><?php echo $course['description']; ?></p>

                  <p class="mt-3 font-bold">
                    Category: <?php echo $course['category']; ?>
                  </p>

                  <p class="font-bold">
                    Price: $<?php echo number_format($course['price'], 2); ?>
                  </p>

                  <div class="flex items-center justify-between mt-auto pt-4">
                    <div class="flex items-center">
                      <img src="<?php echo $course['profile_pic']; ?>" class="h-10 w-10 rounded-full">
                      <div class="ml-2 text-sm">
                        <p class="font-semibold">
                          <?php echo $course['firstName'] . " " . $course['lastName']; ?>
                        </p>
                        <p class="text-xs text-gray-500">
                          <?php echo date('F j, Y', strtotime($course['post_date'])); ?>
                        </p>
                      </div>
                    </div>

                    <form method="POST">
                      <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
                      <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Buy Now
                      </button>
                    </form>
                  </div>
                </div>
              </div>

            <?php $index++;
            } ?>

          </div>
        </div>

      <?php } ?>

    </div>

    <!-- SLIDER BUTTONS -->
    <div class="flex justify-center gap-2 py-3">
      <?php for ($i = 1; $i <= $slideCount; $i++) { ?>
        <a href="#slide<?php echo $i; ?>" class="btn btn-xs"><?php echo $i; ?></a>
      <?php } ?>
    </div>
  </section>




  <!-- all design -->


  <h2 class="mt-5 text-center text-4xl text-white p-5 font-extrabold">All Design Courses</h2>

  <section class="lg:ml-32">

    <div class="carousel w-full">

      <?php
      include('database.php');

      if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
      }

      $query = "SELECT c.course_id, c.title, c.description, c.category, c.price,
              u.firstName, u.lastName, u.profile_pic, fp.post_date
              FROM Course c
              JOIN Instructor i ON c.instructor_id = i.instructor_id
              JOIN User u ON i.user_id = u.user_id
              LEFT JOIN Forum_Post fp ON c.course_id = fp.course_id
              WHERE c.status = 'active' AND c.category='Design'";
      $stmt = $conn->prepare($query);
      $stmt->execute();
      $result = $stmt->get_result();

      $allCourses = [];
      while ($row = $result->fetch_assoc()) {
        $allCourses[] = $row;
      }

      $total = count($allCourses);
      $itemsPerSlide = 3;
      $slideCount = ceil($total / $itemsPerSlide);
      $index = 0;

      for ($slide = 1; $slide <= $slideCount; $slide++) {
      ?>

        <div id="slide<?php echo $slide; ?>" class="carousel-item relative w-full px-5">

          <!-- CENTER ALL CARDS -->
          <div class="flex justify-center gap-6 flex-wrap">

            <?php
            for ($i = 0; $i < $itemsPerSlide; $i++) {

              if (!isset($allCourses[$index])) break;

              $course = $allCourses[$index];

              $qv = $conn->prepare("SELECT file_url FROM Course_Content WHERE course_id=? AND type='video'");
              $qv->bind_param("i", $course['course_id']);
              $qv->execute();
              $video = $qv->get_result()->fetch_assoc();
            ?>

              <!-- CARD -->
              <div class="shadow-xl rounded-xl w-96 flex flex-col course-card-enhanced bg-white">

                <!-- Video -->
                <div class="h-52 overflow-hidden rounded-md">
                  <video class="w-full h-full" controls>
                    <source src="<?php echo $video['file_url']; ?>" type="video/mp4">
                  </video>
                </div>

                <!-- Content -->
                <div class="p-4 flex flex-col flex-grow">
                  <h3 class="text-xl font-semibold"><?php echo $course['title']; ?></h3>

                  <p class="text-gray-600 mt-2">
                    <?php echo $course['description']; ?>
                  </p>

                  <p class="mt-3 font-bold">Category: <?php echo $course['category']; ?></p>
                  <p class="font-bold">Price: $<?php echo number_format($course['price'], 2); ?></p>

                  <!-- Instructor + Button -->
                  <div class="flex items-center justify-between mt-auto pt-4">

                    <div class="flex items-center">
                      <img src="<?php echo $course['profile_pic']; ?>" class="h-10 w-10 rounded-full">
                      <div class="ml-2 text-sm">
                        <p class="font-semibold">
                          <?php echo $course['firstName'] . " " . $course['lastName']; ?>
                        </p>
                        <p class="text-xs text-gray-500">
                          <?php echo date('F j, Y', strtotime($course['post_date'])); ?>
                        </p>
                      </div>
                    </div>

                    <form method="POST">
                      <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
                      <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Buy Now
                      </button>
                    </form>

                  </div>
                </div>

              </div>
              <!-- CARD END -->

            <?php $index++;
            } ?>

          </div>

          <!-- SLIDER ARROWS -->
          <div class="absolute left-5 right-5 top-1/2 -translate-y-1/2 flex justify-between">
            <a href="#slide<?php echo $slide == 1 ? $slideCount : $slide - 1; ?>" class="btn btn-circle">❮</a>
            <a href="#slide<?php echo $slide == $slideCount ? 1 : $slide + 1; ?>" class="btn btn-circle">❯</a>
          </div>

        </div>

      <?php } ?>

    </div>

    <!-- SLIDER BUTTONS -->
    <div class="flex justify-center gap-2 py-3">
      <?php for ($i = 1; $i <= $slideCount; $i++) { ?>
        <a href="#slide<?php echo $i; ?>" class="btn btn-xs"><?php echo $i; ?></a>
      <?php } ?>
    </div>

  </section>


  <!-- all IT and Software -->


  <h2 class="mt-5 text-center text-4xl p-5 font-extrabold">All IT and Software Courses</h2>
  <section class="lg:ml-32">
    <div class="carousel w-full">

      <?php
      include('database.php');

      if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
      }

      $query = "SELECT c.course_id, c.title, c.description, c.category, c.price,
                 u.firstName, u.lastName, u.profile_pic, fp.post_date
          FROM Course c
          JOIN Instructor i ON c.instructor_id = i.instructor_id
          JOIN User u ON i.user_id = u.user_id
          LEFT JOIN Forum_Post fp ON c.course_id = fp.course_id
          WHERE c.status = 'active' AND c.category='IT and Software'";
      $stmt = $conn->prepare($query);
      $stmt->execute();
      $result = $stmt->get_result();

      $allCourses = [];
      while ($row = $result->fetch_assoc()) {
        $allCourses[] = $row;
      }

      $total = count($allCourses);
      $itemsPerSlide = 3;
      $slideCount = ceil($total / $itemsPerSlide);

      $index = 0;

      for ($slide = 1; $slide <= $slideCount; $slide++) {
      ?>
        <div id="slide<?php echo $slide; ?>" class="carousel-item w-full px-5">

          <!-- CENTER ALL CARDS -->
          <div class="flex justify-center gap-6">

            <?php
            for ($i = 0; $i < $itemsPerSlide; $i++) {

              if (!isset($allCourses[$index])) break;

              $course = $allCourses[$index];

              $qv = $conn->prepare("SELECT file_url FROM Course_Content WHERE course_id=? AND type='video'");
              $qv->bind_param("i", $course['course_id']);
              $qv->execute();
              $video = $qv->get_result()->fetch_assoc();
            ?>

              <!-- CARD -->
              <div class=" shadow-xl rounded-xl w-96 flex flex-col course-card-enhanced">

                <!-- Video -->
                <div class="h-52 overflow-hidden rounded-md">
                  <video class="w-full h-full" controls>
                    <source src="<?php echo $video['file_url']; ?>" type="video/mp4">
                  </video>
                </div>

                <!-- Content -->
                <div class="p-4 flex flex-col flex-grow">
                  <h3 class="text-xl font-semibold"><?php echo $course['title']; ?></h3>

                  <p class="text-gray-600 mt-2">
                    <?php echo $course['description']; ?>
                  </p>

                  <p class="mt-3 font-bold">Category: <?php echo $course['category']; ?></p>
                  <p class="font-bold">Price: $<?php echo number_format($course['price'], 2); ?></p>

                  <!-- Instructor + Button -->
                  <div class="flex items-center justify-between mt-auto pt-4">

                    <div class="flex items-center">
                      <img src="<?php echo $course['profile_pic']; ?>" class="h-10 w-10 rounded-full">
                      <div class="ml-2 text-sm">
                        <p class="font-semibold">
                          <?php echo $course['firstName'] . " " . $course['lastName']; ?>
                        </p>
                        <p class="text-xs text-gray-500">
                          <?php echo date('F j, Y', strtotime($course['post_date'])); ?>
                        </p>
                      </div>
                    </div>

                    <form method="POST">
                      <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
                      <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Buy Now
                      </button>
                    </form>

                  </div>
                </div>

              </div>
              <!-- CARD END -->

            <?php $index++;
            } ?>

          </div>
        </div>

      <?php } ?>
    </div>

    <!-- SLIDER BUTTONS -->
    <div class="flex justify-center gap-2 py-3">
      <?php for ($i = 1; $i <= $slideCount; $i++) { ?>
        <a href="#slide<?php echo $i; ?>" class="btn btn-xs"><?php echo $i; ?></a>
      <?php } ?>
    </div>


  </section>



  <script>
    // JavaScript to handle video play, stop it, and show message
    document.addEventListener('DOMContentLoaded', function() {
      // Get all video elements on the page
      const videos = document.querySelectorAll('video');

      // Add event listeners to prevent video play, hide controls, and show the message
      videos.forEach(function(video) {
        // Disable the controls for the video
        video.controls = false;

        // Show the message overlay when the user tries to play
        video.addEventListener('play', function(event) {
          event.preventDefault(); // Prevent the video from playing

          // Show the message
          const courseId = video.id.split('-')[1];
          const messageDiv = document.getElementById('message-' + courseId);
          messageDiv.classList.remove('hidden');

          // Hide the message after 3 seconds
          setTimeout(function() {
            messageDiv.classList.add('hidden');
          }, 3000);
        });
      });
    });
  </script>
  </section>
  </main>

  <script src="js/script.js"></script>





  <script src="./js/script.js"></script>

  <!-- chatbot -->
  <link href="https://cdn.jsdelivr.net/npm/@n8n/chat/dist/style.css" rel="stylesheet" />
  <script type="module">
    import {
      createChat
    } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js';

    createChat({
      webhookUrl: '.....'
    });
  </script>



 <?php
  include('footer.php')


  ?>

</body>

</html>