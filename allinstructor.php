<?php
session_start();
require_once "database.php";

// Fetch all instructors
$query = "
    SELECT i.instructor_id, u.firstName, u.lastName, u.email, u.profile_pic, u.bio, i.expertise 
    FROM Instructor i 
    JOIN User u ON i.user_id = u.user_id 
    WHERE u.role = 'instructor'
";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$instructors = [];
while ($row = $result->fetch_assoc()) {
    $instructors[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Our Instructors | SkillPro</title>

  <link rel="stylesheet" href="Styles/styles.css">
  <link rel="stylesheet" href="Styles/Nav.css" />
  
  <!-- Tailwind and DaisyUI -->
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.13/dist/full.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

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

  <!-- Navigation (Same as index.php) -->
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
                style="width: 150px" />
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
              <li class="mark"><a>Home</a></li>
              <li><a href="">Development</a></li>
              <li><a href=""> IT & Software</a></li>
              <li><a href=""> Design</a></li>
              <li><a>Instructors</a></li>
              <li><a href="FreeCourses.php">Free Courses</a></li>
            </ul>
          </div>

          <!-- THEME TOGGLE (sun left, moon right) -->
          <label class="flex cursor-pointer gap-2">
            <!-- sun icon (for light) -->
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

            <!-- moon icon (for dark) -->
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
              <li><a href="index.php#Software">IT & Software</a></li>
              <li><a href="index.php#Design">Design</a></li>
              <li><a href="allinstructor.php" class="active">Instructors</a></li>
              <li><a href="FreeCourses.php">Free Courses</a></li>
          </ul>
        </div>

        <div class="navbar-end">
          <a href="login.php" class="btn button">Login</a>
        </div>
      </div>
    </nav>
  </header>

  <!-- Main Content -->
  <main class="flex-grow py-12 px-6">
    <div class="max-w-7xl mx-auto">
      
      <!-- Header -->
      <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4">Meet Our Instructors</h1>
        <p class="text-lg opacity-70 max-w-2xl mx-auto">
          Learn from industry experts passionate about sharing their knowledge and helping you succeed.
        </p>
      </div>

      <!-- Instructors Grid -->
      <?php if (count($instructors) > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <?php foreach ($instructors as $instructor): ?>
            <div class="card bg-base-200 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 group border border-base-300">
              <figure class="px-6 pt-10">
                <div class="avatar">
                  <div class="w-32 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2 transition-transform duration-500 group-hover:scale-110">
                    <img src="<?= htmlspecialchars(!empty($instructor['profile_pic']) ? $instructor['profile_pic'] : 'default_profile.png'); ?>" alt="Instructor" />
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
                
                <p class="text-sm opacity-70 line-clamp-3 mb-4">
                    <?= htmlspecialchars(!empty($instructor['bio']) ? $instructor['bio'] : 'Passionate educator committed to student success.'); ?>
                </p>

                <div class="card-actions">
                  <button class="btn btn-primary btn-sm btn-outline gap-2">
                    View Profile
                    <i class="fa-solid fa-arrow-right"></i>
                  </button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="text-center py-20">
          <div class="text-6xl mb-4">👩‍🏫</div>
          <h3 class="text-2xl font-bold opacity-50">No instructors found at the moment.</h3>
        </div>
      <?php endif; ?>

    </div>
  </main>

 <?php
  include('footer.php')


  ?>


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
  </script>

</body>
</html>
