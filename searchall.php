<?php
session_start();
require_once "database.php";

$search_query = $_GET['q'] ?? '';
$courses = [];
$instructors = [];

if (!empty($search_query)) {
    $search_term = "%" . $search_query . "%";

    // 1. Search Instructors
    $stmt_instr = $conn->prepare("
        SELECT i.instructor_id, u.firstName, u.lastName, u.profile_pic, u.bio, i.expertise
        FROM Instructor i
        JOIN User u ON i.user_id = u.user_id
        WHERE (u.firstName LIKE ? OR u.lastName LIKE ? OR CONCAT(u.firstName, ' ', u.lastName) LIKE ? OR i.expertise LIKE ?)
        AND u.role = 'instructor'
    ");
    $stmt_instr->bind_param("ssss", $search_term, $search_term, $search_term, $search_term);
    $stmt_instr->execute();
    $result_instr = $stmt_instr->get_result();
    while ($row = $result_instr->fetch_assoc()) {
        $instructors[] = $row;
    }

    // 2. Search Courses
    $stmt_course = $conn->prepare("
        SELECT c.course_id, c.title, c.description, c.category, c.price,
               u.firstName, u.lastName, u.profile_pic, fp.post_date
        FROM Course c
        JOIN Instructor i ON c.instructor_id = i.instructor_id
        JOIN User u ON i.user_id = u.user_id
        LEFT JOIN Forum_Post fp ON c.course_id = fp.course_id
        WHERE (c.title LIKE ? OR c.description LIKE ? OR c.category LIKE ?)
        AND c.status = 'active'
    ");
    $stmt_course->bind_param("sss", $search_term, $search_term, $search_term);
    $stmt_course->execute();
    $result_course = $stmt_course->get_result();
    while ($row = $result_course->fetch_assoc()) {
        $courses[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Results | SkillPro</title>

  <link rel="stylesheet" href="Styles/styles.css">
  <link rel="stylesheet" href="Styles/Nav.css" />
  
  <!-- tailwind and daisy UI -->
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.13/dist/full.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- font awesome cdn -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

  <!-- tailwind custom color -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            clifford: "#da373d",
          },
        },
      },
    };
  </script>
</head>

<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Results | SkillPro</title>

  <link rel="stylesheet" href="Styles/styles.css">
  <link rel="stylesheet" href="Styles/Nav.css" />
  
  <!-- tailwind and daisy UI -->
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.13/dist/full.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- font awesome cdn -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

  <!-- tailwind custom color -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            clifford: "#da373d",
          },
        },
      },
    };
  </script>
</head>

<body class="bg-base-100 min-h-screen flex flex-col">

  <!-- Toast container (DaisyUI style) -->
  <div id="toast-container" class="toast toast-top toast-end z-[9999]"></div>

  <!-- Sign-in modal -->
  <dialog id="my_modal_1" class="modal">
    <div class="modal-box max-w-md">
      <div class="font-[sans-serif]">
        <div class="py-4 px-2">
          <a href="javascript:void(0)">
            <img
              src="logo.jpg"
              alt="logo"
              class="w-32 sm:w-40 mb-6 mx-auto block rounded-full" />
          </a>

          <div
            class="p-6 sm:p-8 rounded-2xl bg-base-100 shadow border border-base-300 transition-colors duration-300">
            <h2
              class="text-base-content text-center text-2xl font-bold">
              Sign in
            </h2>

            <!-- IMPORTANT: form now posts to newLogin.php -->
            <form
              class="mt-6 space-y-4"
              method="POST"
              action="newlogin.php">
              <div>
                <label
                  class="text-base-content text-sm mb-2 block"
                  for="login-email">Email</label>
                <div class="relative flex items-center">
                  <input
                    id="login-email"
                    name="email"
                    type="email"
                    required
                    class="w-full text-sm border border-base-300 px-4 py-3 rounded-md outline-primary bg-base-100 text-base-content"
                    placeholder="Enter your email" />
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    class="w-4 h-4 absolute right-4 opacity-60"
                    viewBox="0 0 24 24">
                    <circle cx="10" cy="7" r="6"></circle>
                    <path
                      d="M14 15H6a5 5 0 0 0-5 5 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 5 5 0 0 0-5-5zm8-4h-2.59l.3-.29a1 1 0 0 0-1.42-1.42l-2 2a1 1 0 0 0 0 1.42l2 2a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42l-.3-.29H22a1 1 0 0 0 0-2z"></path>
                  </svg>
                </div>
              </div>

              <div>
                <label
                  class="text-base-content text-sm mb-2 block"
                  for="login-password">Password</label>
                <div class="relative flex items-center">
                  <input
                    id="login-password"
                    name="password"
                    type="password"
                    required
                    class="w-full text-sm border border-base-300 px-4 py-3 rounded-md outline-primary bg-base-100 text-base-content"
                    placeholder="Enter password" />
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    class="w-4 h-4 absolute right-4 cursor-pointer opacity-60"
                    viewBox="0 0 128 128">
                    <path
                      d="M64 104C22.127 104 1.367 67.496.504 65.943a4 4 0 0 1 0-3.887C1.367 60.504 22.127 24 64 24s62.633 36.504 63.496 38.057a4 4 0 0 1 0 3.887C126.633 67.496 105.873 104 64 104zM8.707 63.994C13.465 71.205 32.146 96 64 96c31.955 0 50.553-24.775 55.293-31.994C114.535 56.795 95.854 32 64 32 32.045 32 13.447 56.775 8.707 63.994zM64 88c-13.234 0-24-10.766-24-24s10.766-24 24-24 24 10.766 24 24-10.766 24-24 24zm0-40c-8.822 0-16 7.178-16 16s7.178 16 16 16 16-7.178 16-16-7.178-16-16-16z"></path>
                  </svg>
                </div>
              </div>

              <div
                class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center">
                  <input
                    id="remember-me"
                    name="remember-me"
                    type="checkbox"
                    class="h-4 w-4 shrink-0 text-primary focus:ring-primary border-base-300 rounded" />
                  <label
                    for="remember-me"
                    class="ml-2 text-sm text-base-content">
                    Remember me
                  </label>
                </div>
                <div class="text-sm">
                  <a
                    href="javascript:void(0);"
                    class="text-primary hover:underline font-semibold">
                    Forgot your password?
                  </a>
                </div>
              </div>

              <div class="mt-6">
                <!-- IMPORTANT: type="submit" to send form -->
                <button
                  type="submit"
                  class="w-full py-3 px-4 text-sm tracking-wide rounded-lg text-base-100 bg-primary hover:bg-primary/80 focus:outline-none">
                  Sign in
                </button>
              </div>

              <p
                class="text-base-content text-sm mt-6 text-center">
                Don't have an account?
                <a
                  href="Register.php"
                  class="text-primary hover:underline ml-1 whitespace-nowrap font-semibold">Register here</a>
              </p>
            </form>
          </div>
        </div>
      </div>
      <div class="modal-action">
        <form method="dialog">
          <button class="btn">Close</button>
        </form>
      </div>
    </div>
  </dialog>

  <!-- Navigation -->
  <header class="">
    <!-- Navigation -->
    <nav id="navigation class=" bg-[rgba(255,255,255,0.7)] backdrop-blur-md fixed w-full z-50">
      <div class="navbar">
        <div class="navbar-start">
          <!-- Dropdown menu -->
          <div class="dropdown">
            <div tabindex="0" role="button" class="hidden btn btn-ghost btn-circle">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h7" />
              </svg>
            </div>
            <ul tabindex="0"
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
          <div class="relative">
            <button id="search-btn" class="btn btn-ghost btn-circle">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </button>
            <form action="searchall.php" method="GET" class="inline">
              <input id="search-input" type="text" name="q" placeholder="Search..."
                class="hidden absolute right-0 bg-black rounded-md p-2 mt-2"
                style="width: 150px" value="<?= htmlspecialchars($search_query); ?>" />
            </form>
          </div>

          <!-- Notification button -->
          <button class="btn btn-ghost btn-circle">
            <div class="indicator">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
              <span class="badge badge-xs badge-primary indicator-item"></span>
            </div>
          </button>
        </div>
      </div>

      <script>
        document.getElementById("search-btn").addEventListener("click", function() {
          const searchInput = document.getElementById("search-input");
          searchInput.classList.toggle("hidden");
          searchInput.focus();
        });
      </script>

      <div class="navbar relative z-50">
        <div class="navbar-start">
          <!-- Dropdown menu and brand -->
          <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h8m-8 6h16" />
              </svg>
            </div>
            <ul tabindex="0"
              class="menu menu-sm dropdown-content rounded-box z-[1] mt-3 w-52 p-2 shadow bg-black text-white">
              <li><a href="index.php">Home</a></li>
              <li><a href="index.php#development">Development</a></li>
              <li><a href="index.php#Software"> IT & Software</a></li>
              <li><a href="index.php#Design"> Design</a></li>
              <li><a href="allinstructor.php">Instructors</a></li>
              <li><a href="FreeCourses.php">Free Courses</a></li>
            </ul>
          </div>

          <!-- THEME TOGGLE -->
          <label class="flex cursor-pointer gap-2">
            <svg xmlns="http://www.w3.org/2000/svg"
              width="20" height="20" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="5" />
              <path d="M12 1v2M12 21v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M1 12h2M21 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4" />
            </svg>

            <input
              id="theme-toggle"
              type="checkbox"
              class="toggle theme-controller" />

            <svg xmlns="http://www.w3.org/2000/svg"
              width="20" height="20" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
          </label>
        </div>

        <div class="navbar-center hidden lg:flex">
          <ul class="menu menu-horizontal px-1">
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php#development">Development</a></li>
            <li><a href="index.php#Software"> IT & Software</a></li>
            <li><a href="index.php#Design"> Design</a></li>
            <li><a href="allinstructor.php">Instructors</a></li>
            <li><a href="FreeCourses.php">Free Courses</a></li>
          </ul>
        </div>

        <div class="navbar-end">
          <?php if (isset($_SESSION['user_id'])): ?>
             <a href="logout.php" class="btn button">Logout</a>
          <?php else: ?>
             <a href="login.php" class="btn button">Login</a>
          <?php endif; ?>
        </div>
      </div>
    </nav>
  </header>


  <main class="flex-grow py-24 px-6 max-w-7xl mx-auto w-full">
    
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-bold mb-4">Search Results</h1>
        <?php if (!empty($search_query)): ?>
            <p class="text-xl opacity-70">Showing results for "<span class="text-primary font-semibold"><?= htmlspecialchars($search_query); ?></span>"</p>
        <?php endif; ?>
    </div>

    <?php if (empty($courses) && empty($instructors)): ?>
        <div class="flex flex-col items-center justify-center py-20 text-center opacity-60">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <h2 class="text-2xl font-bold">No results found</h2>
            <p class="mt-2">Try adjusting your search terms or look for something else.</p>
        </div>
    <?php endif; ?>


    <!-- Instructors Section -->
    <?php if (count($instructors) > 0): ?>
        <div class="mb-16">
            <h2 class="text-3xl font-bold mb-8 border-b border-base-300 pb-2">Instructors</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php foreach ($instructors as $instructor): ?>
                    <div class="card bg-base-200 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 group border border-base-300">
                        <figure class="px-6 pt-10">
                            <div class="avatar">
                                <div class="w-32 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2 transition-transform duration-500 group-hover:scale-110">
                                    <img src="<?= htmlspecialchars(!empty($instructor['profile_pic']) ? $instructor['profile_pic'] : 'default_profile.png'); ?>" alt="Instructor" class="object-cover" />
                                </div>
                            </div>
                        </figure>
                        <div class="card-body items-center text-center">
                            <h2 class="card-title text-xl font-bold">
                                <?= htmlspecialchars($instructor['firstName'] . ' ' . $instructor['lastName']); ?>
                            </h2>
                            <div class="badge badge-secondary badge-outline mb-2">
                                <?= htmlspecialchars(!empty($instructor['expertise']) ? $instructor['expertise'] : 'Instructor'); ?>
                            </div>
                            <p class="text-sm opacity-70 line-clamp-3">
                                <?= htmlspecialchars(!empty($instructor['bio']) ? $instructor['bio'] : 'Passionate educator committed to student success.'); ?>
                            </p>
                            <div class="card-actions mt-4">
                                <button class="btn btn-primary btn-sm btn-outline gap-2">
                                    View Profile
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>


    <!-- Courses Section -->
    <?php if (count($courses) > 0): ?>
        <div class="mb-16">
            <h2 class="text-3xl font-bold mb-8 border-b border-base-300 pb-2">Courses</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($courses as $course): ?>
                    <?php
                    // Fetch preview video
                    $qv = $conn->prepare("SELECT file_url FROM Course_Content WHERE course_id = ? AND type = 'video' LIMIT 1");
                    $qv->bind_param("i", $course['course_id']);
                    $qv->execute();
                    $video = $qv->get_result()->fetch_assoc();
                    ?>
                    <div class="course-card group bg-base-200 border border-base-300 rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 ease-out hover:-translate-y-2 hover:scale-[1.01]">
                        <div class="relative h-52 bg-base-300 overflow-hidden">
                            <?php if (!empty($video['file_url'])): ?>
                                <video class="preview-video w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" preload="metadata" muted playsinline data-locked="1">
                                    <source src="<?= $video['file_url']; ?>" type="video/mp4">
                                </video>
                            <?php else: ?>
                                <div class="flex items-center justify-center w-full h-full text-base-content/50">No Preview Video</div>
                            <?php endif; ?>
                            <div class="absolute top-3 left-3">
                                <span class="badge badge-neutral badge-sm"><?= htmlspecialchars($course['category']); ?></span>
                            </div>
                        </div>
                        <div class="p-5 flex flex-col h-full">
                            <h3 class="text-xl font-semibold text-base-content tracking-tight"><?= htmlspecialchars($course['title']); ?></h3>
                            <p class="text-base-content/70 mt-2 text-sm leading-relaxed line-clamp-3"><?= htmlspecialchars($course['description']); ?></p>
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-sm font-semibold text-base-content">Price: $<?= number_format($course['price'], 2); ?></span>
                            </div>
                            <div class="divider my-4"></div>
                            <div class="flex items-center justify-between mt-auto">
                                <div class="flex items-center gap-3">
                                    <img src="<?= htmlspecialchars($course['profile_pic']); ?>" class="h-10 w-10 rounded-full object-cover ring-2 ring-base-300" alt="Instructor">
                                    <div class="text-sm">
                                        <p class="font-semibold text-base-content"><?= htmlspecialchars($course['firstName'] . " " . $course['lastName']); ?></p>
                                        <p class="text-xs text-base-content/60">Instructor</p>
                                    </div>
                                </div>
                                <button type="button" onclick="requireLogin(event)" class="btn btn-primary btn-sm normal-case">Buy Now</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

  </main>

  <?php include('footer.php'); ?>

  <!-- Theme Toggle Script -->
  <script>
    const html = document.documentElement;
    const themeToggle = document.getElementById("theme-toggle");

    // Check local storage or default
    const savedTheme = localStorage.getItem("theme") || "dark";
    html.setAttribute("data-theme", savedTheme);
    themeToggle.checked = savedTheme === "dark";

    themeToggle.addEventListener("change", function() {
      const newTheme = this.checked ? "dark" : "light";
      html.setAttribute("data-theme", newTheme);
      localStorage.setItem("theme", newTheme);
    });

    // Guest "Buy Now" handler
    function requireLogin(e) {
      if (e) e.preventDefault();

      showToast("Please log in first to buy this course.");

      const modal = document.getElementById("my_modal_1");
      if (modal && typeof modal.showModal === "function") {
        modal.showModal();
      }
    }

    // Simple toast using DaisyUI container
    function showToast(message) {
      const container = document.getElementById("toast-container");
      if (!container) {
        alert(message);
        return;
      }

      const toast = document.createElement("div");
      toast.className = "alert alert-warning shadow-lg";
      toast.innerHTML = "<span>" + message + "</span>";

      container.appendChild(toast);

      // fade out then remove
      setTimeout(() => {
        toast.style.opacity = "0";
        toast.style.transform = "translateY(6px)";
        toast.style.transition = "all 0.25s ease";
      }, 2000);

      setTimeout(() => toast.remove(), 2300);
    }
  </script>

</body>

</html>