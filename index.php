<?php
session_start();
require_once "database.php";
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SkillPro</title>

  <link rel="stylesheet" href="Styles/styles.css">
  <link rel="stylesheet" href="Styles/Nav.css" />
  <link rel="stylesheet" href="Styles/allCourses.css">

  <!-- tailwind and daisy UI -->
  <link
    href="https://cdn.jsdelivr.net/npm/daisyui@4.12.13/dist/full.min.css"
    rel="stylesheet"
    type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- font awesome cdn -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- tailwind custom color -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            clifford: "#da373d",
          },
          animation: {
            'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
            'fade-in': 'fadeIn 1s ease-out forwards',
          },
          keyframes: {
            fadeInUp: {
              '0%': {
                opacity: '0',
                transform: 'translateY(20px)'
              },
              '100%': {
                opacity: '1',
                transform: 'translateY(0)'
              },
            },
            fadeIn: {
              '0%': {
                opacity: '0'
              },
              '100%': {
                opacity: '1'
              },
            },
          },
        },
      },
    };
  </script>
  <style>
    /* Custom utility if tailwind config fails or for extra specificity */
    .animate-fade-in-up {
      animation: fadeInUp 0.8s ease-out forwards;
      opacity: 0;
    }

    .animate-fade-in {
      animation: fadeIn 1s ease-out forwards;
      opacity: 0;
    }

    .delay-100 {
      animation-delay: 0.1s;
    }

    .delay-200 {
      animation-delay: 0.2s;
    }

    .delay-300 {
      animation-delay: 0.3s;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }
  </style>
</head>

<body>
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
            <li class="mark"><a>Home</a></li>
            <li><a href="#development">Development</a></li>
            <li><a href="#Software"> IT & Software</a></li>
            <li><a href="#Design"> Design</a></li>
            <li><a href="allinstructor.php""> Instructors</a></li>
            <li><a href=" FreeCourses.php">Free Courses</a></li>
          </ul>
        </div>

        <div class="navbar-end">
          <a href="login.php" class="btn button">Login</a>
        </div>
      </div>
    </nav>
  </header>


  <!-- Hero / Banner Section -->
  <section class="bg-base-100 transition-colors duration-300 border-b border-base-300 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0 opacity-20">
      <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-primary/30 rounded-full blur-[100px]"></div>
      <div class="absolute top-[20%] right-[10%] w-[20%] h-[20%] bg-secondary/30 rounded-full blur-[80px]"></div>
    </div>

    <div class="max-w-6xl mx-auto px-6 lg:px-10 py-12 lg:py-20 relative z-10">
      <div class="grid md:grid-cols-2 gap-8 lg:gap-12 items-stretch">
        <!-- Left: Hero content card -->
        <div
          class="bg-base-200/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-base-300 p-7 md:p-9 lg:p-10 flex flex-col justify-between h-full transition-all duration-300 hover:shadow-primary/10 hover:-translate-y-1">
          <div class="space-y-4">
            <p
              class="text-xs sm:text-sm font-semibold tracking-[0.25em] text-primary uppercase animate-fade-in-up">
              Learn without limits
            </p>

            <h1
              class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight text-base-content animate-fade-in-up delay-100">
              LEARN AT HOME
            </h1>

            <p class="text-sm sm:text-base font-semibold text-secondary animate-fade-in-up delay-200">
              Let's start with a free class
            </p>

            <p class="text-sm sm:text-base text-base-content/80 leading-relaxed animate-fade-in-up delay-300">
              SkillPro is a premier online learning platform designed to empower
              students with in-depth computer science knowledge. Featuring top
              educators from Bangladesh, SkillPro offers expertly crafted courses
              that make mastering computer science accessible and engaging for
              learners at every level.
            </p>
          </div>

          <!-- CTA + modal trigger -->
          <div class="pt-6 animate-fade-in-up delay-300">
            <button
              class="btn btn-primary w-full sm:w-auto btn-md md:btn-lg normal-case font-semibold shadow-lg hover:shadow-primary/50 transition-shadow duration-300"
              onclick="my_modal_1.showModal()">
              15% OFF IN THE FIRST TWO MONTHS
            </button>

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
          </div>
        </div>

        <!-- Right: Image card (same size as content card) -->
        <div
          class="bg-base-200/90 rounded-3xl shadow-2xl border border-base-300 overflow-hidden h-full flex items-stretch transition-transform duration-500 hover:scale-[1.02] animate-fade-in group">
          <img
            src="Images/learning from online.jpg"
            alt="E-learning"
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
        </div>
      </div>
    </div>
  </section>

  <!-- ===================== DEVELOPMENT COURSES ===================== -->
  <h2 id="development" class="mt-5 text-center text-4xl p-5 font-extrabold scroll-mt-24">
    All Development Courses
  </h2>

  <?php
  // Fetch Development Courses
  $queryDev = "
  SELECT c.course_id, c.title, c.description, c.category, c.price,
         u.firstName, u.lastName, u.profile_pic, fp.post_date
  FROM Course c
  JOIN Instructor i ON c.instructor_id = i.instructor_id
  JOIN User u ON i.user_id = u.user_id
  LEFT JOIN Forum_Post fp ON c.course_id = fp.course_id
  WHERE c.status = 'active' AND c.category = 'Development'
  ";

  $stmt = $conn->prepare($queryDev);
  $stmt->execute();
  $result = $stmt->get_result();

  $devCourses = [];
  while ($row = $result->fetch_assoc()) {
    $devCourses[] = $row;
  }
  ?>

  <!-- Toast container (DaisyUI style) -->
  <div id="toast-container" class="toast toast-top toast-end z-[9999]"></div>

  <!-- Cards Grid (Development) -->
  <div class="px-5 pb-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php foreach ($devCourses as $course): ?>
        <?php
        // Fetch preview video
        $qv = $conn->prepare("SELECT file_url FROM Course_Content WHERE course_id = ? AND type = 'video' LIMIT 1");
        $qv->bind_param("i", $course['course_id']);
        $qv->execute();
        $video = $qv->get_result()->fetch_assoc();
        ?>
        <!-- COURSE CARD -->
        <div class="course-card group bg-base-200 border border-base-300 rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 ease-out hover:-translate-y-2 hover:scale-[1.01]">
          <!-- Media -->
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
          <!-- Content -->
          <div class="p-5 flex flex-col h-full">
            <h3 class="text-xl font-semibold text-base-content tracking-tight"><?= htmlspecialchars($course['title']); ?></h3>
            <p class="text-base-content/70 mt-2 text-sm leading-relaxed line-clamp-3"><?= htmlspecialchars($course['description']); ?></p>
            <div class="mt-4 flex items-center justify-between">
              <span class="text-sm font-semibold text-base-content">Price: $<?= number_format($course['price'], 2); ?></span>
              <?php if (!empty($course['post_date'])): ?>
                <span class="text-xs text-base-content/50"><?= date('F j, Y', strtotime($course['post_date'])); ?></span>
              <?php endif; ?>
            </div>
            <div class="divider my-4"></div>
            <!-- Footer -->
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

  <!-- ===================== IT & SOFTWARE COURSES ===================== -->
  <h2 id="Software" class="mt-5 text-center text-4xl p-5 font-extrabold scroll-mt-24">
    IT & Software Courses
  </h2>

  <?php
  // Fetch IT & Software Courses
  $queryIT = "
  SELECT c.course_id, c.title, c.description, c.category, c.price,
         u.firstName, u.lastName, u.profile_pic, fp.post_date
  FROM Course c
  JOIN Instructor i ON c.instructor_id = i.instructor_id
  JOIN User u ON i.user_id = u.user_id
  LEFT JOIN Forum_Post fp ON c.course_id = fp.course_id
  WHERE c.status = 'active' AND c.category = 'IT and Software'
  ";

  $stmt = $conn->prepare($queryIT);
  $stmt->execute();
  $result = $stmt->get_result();

  $itCourses = [];
  while ($row = $result->fetch_assoc()) {
    $itCourses[] = $row;
  }
  ?>

  <!-- Cards Grid (IT & Software) -->
  <div class="px-5 pb-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php if (count($itCourses) > 0): ?>
        <?php foreach ($itCourses as $course): ?>
          <?php
          $qv = $conn->prepare("SELECT file_url FROM Course_Content WHERE course_id = ? AND type = 'video' LIMIT 1");
          $qv->bind_param("i", $course['course_id']);
          $qv->execute();
          $video = $qv->get_result()->fetch_assoc();
          ?>
          <!-- COURSE CARD -->
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
                <?php if (!empty($course['post_date'])): ?>
                  <span class="text-xs text-base-content/50"><?= date('F j, Y', strtotime($course['post_date'])); ?></span>
                <?php endif; ?>
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
      <?php else: ?>
        <p class="col-span-full text-center text-base-content/50 italic py-10">No courses available in this category.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- ===================== DESIGN COURSES ===================== -->
  <h2 id="Design" class="mt-5 text-center text-4xl p-5 font-extrabold scroll-mt-24">
    Design Courses
  </h2>

  <?php
  // Fetch Design Courses
  $queryDesign = "
  SELECT c.course_id, c.title, c.description, c.category, c.price,
         u.firstName, u.lastName, u.profile_pic, fp.post_date
  FROM Course c
  JOIN Instructor i ON c.instructor_id = i.instructor_id
  JOIN User u ON i.user_id = u.user_id
  LEFT JOIN Forum_Post fp ON c.course_id = fp.course_id
  WHERE c.status = 'active' AND c.category = 'Design'
  ";

  $stmt = $conn->prepare($queryDesign);
  $stmt->execute();
  $result = $stmt->get_result();

  $designCourses = [];
  while ($row = $result->fetch_assoc()) {
    $designCourses[] = $row;
  }
  ?>

  <!-- Cards Grid (Design) -->
  <div class="px-5 pb-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php if (count($designCourses) > 0): ?>
        <?php foreach ($designCourses as $course): ?>
          <?php
          $qv = $conn->prepare("SELECT file_url FROM Course_Content WHERE course_id = ? AND type = 'video' LIMIT 1");
          $qv->bind_param("i", $course['course_id']);
          $qv->execute();
          $video = $qv->get_result()->fetch_assoc();
          ?>
          <!-- COURSE CARD -->
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
                <?php if (!empty($course['post_date'])): ?>
                  <span class="text-xs text-base-content/50"><?= date('F j, Y', strtotime($course['post_date'])); ?></span>
                <?php endif; ?>
              </div>
              <div class="divider my-4"></div>
              <div class="flex items-center justify-between mt-auto">
                <div class="flex items-center gap-3">
                  <img src="<?= htmlspecialchars(!empty($course['profile_pic']) ? $course['profile_pic'] : 'default_profile.png'); ?>" class="h-10 w-10 rounded-full object-cover ring-2 ring-base-300" alt="Instructor">
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
      <?php else: ?>
        <p class="col-span-full text-center text-base-content/50 italic py-10">No courses available in this category.</p>
      <?php endif; ?>
    </div>
  </div>






  <!-- ===================== WATCH FREE WEBINAR SECTION ===================== -->
  <section class="py-20 px-6 bg-base-200">
    <div class="max-w-7xl mx-auto">

      <!-- Section Header -->
      <div class="text-center mb-16 space-y-4">
        <h2 class="text-4xl md:text-5xl font-extrabold text-base-content tracking-tight">
          Watch Free Webinars
        </h2>
        <p class="text-lg text-base-content/60 max-w-2xl mx-auto font-light">
          Discover everything you gain by joining our live sessions. Learn from industry experts for free.
        </p>
      </div>

      <!-- Webinar Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Card 1 -->
        <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 group">
          <figure class="h-48 overflow-hidden">
            <img src="https://marketplace.canva.com/EAE_V_x3FgA/1/0/1600w/canva-live-webinar-instagram-post-Iofdxu_-4ZQ.jpg"
              alt="UI/UX Design Webinar"
              class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
          </figure>
          <div class="card-body p-6">
            <h3 class="card-title text-lg font-bold">UI/UX Design Webinar</h3>
            <p class="text-sm text-base-content/70 mt-2 line-clamp-3">Join our free webinar to learn the basics of UI/UX design and how it impacts user experience.</p>
            <div class="card-actions justify-end mt-4">
              <button class="btn btn-primary btn-sm w-full">Watch Now</button>
            </div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 group">
          <figure class="h-48 overflow-hidden">
            <img src="https://marketplace.canva.com/EAF1cBj-8l8/1/0/1600w/canva-blue-modern-online-webinar-instagram-post-P3Ui26vGrZU.jpg"
              alt="Web Development"
              class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
          </figure>
          <div class="card-body p-6">
            <h3 class="card-title text-lg font-bold">Web Development</h3>
            <p class="text-sm text-base-content/70 mt-2 line-clamp-3">Learn the essentials of web development, from HTML to JavaScript, in this free webinar.</p>
            <div class="card-actions justify-end mt-4">
              <button class="btn btn-primary btn-sm w-full">Watch Now</button>
            </div>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 group">
          <figure class="h-48 overflow-hidden">
            <img src="https://i0.wp.com/orangevfx.com/wp-content/uploads/2023/03/March-Free-Webinar-Seminar-2023-Orange-VFX-3D-Game-Unreal-Engine-Seminar-Training-Online.jpg?fit=1551%2C1549&ssl=1"
              alt="Cybersecurity"
              class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
          </figure>
          <div class="card-body p-6">
            <h3 class="card-title text-lg font-bold">Intro to Cybersecurity</h3>
            <p class="text-sm text-base-content/70 mt-2 line-clamp-3">Explore the world of cybersecurity and learn key strategies for protecting digital assets.</p>
            <div class="card-actions justify-end mt-4">
              <button class="btn btn-primary btn-sm w-full">Watch Now</button>
            </div>
          </div>
        </div>

        <!-- Card 4 (Was 5 in user list) -->
        <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 group">
          <figure class="h-48 overflow-hidden">
            <img src="https://img.freepik.com/premium-psd/digital-marketing-webinar-social-media-post-template_539910-278.jpg"
              alt="Cloud Computing"
              class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
          </figure>
          <div class="card-body p-6">
            <h3 class="card-title text-lg font-bold">Cloud Essentials</h3>
            <p class="text-sm text-base-content/70 mt-2 line-clamp-3">Get started with cloud computing and understand the fundamentals of cloud infrastructure.</p>
            <div class="card-actions justify-end mt-4">
              <button class="btn btn-primary btn-sm w-full">Watch Now</button>
            </div>
          </div>
        </div>

        <!-- Card 5 (Was 6) -->
        <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 group">
          <figure class="h-48 overflow-hidden">
            <img src="https://i.pinimg.com/736x/55/41/b8/5541b8bb6ae4cf90fa33fb3388427842.jpg"
              alt="Responsive Design"
              class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
          </figure>
          <div class="card-body p-6">
            <h3 class="card-title text-lg font-bold">Responsive Design</h3>
            <p class="text-sm text-base-content/70 mt-2 line-clamp-3">Learn how to create websites that adapt seamlessly to different screen sizes.</p>
            <div class="card-actions justify-end mt-4">
              <button class="btn btn-primary btn-sm w-full">Watch Now</button>
            </div>
          </div>
        </div>

        <!-- Card 6 (Was 7) -->
        <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 group">
          <figure class="h-48 overflow-hidden">
            <img src="https://store.taproot.com/images/thumbs/0001404_free-webinar-most-used-least-effective-cas_550.png"
              alt="Python Intro"
              class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
          </figure>
          <div class="card-body p-6">
            <h3 class="card-title text-lg font-bold">Intro to Python</h3>
            <p class="text-sm text-base-content/70 mt-2 line-clamp-3">Join us to learn the basics of Python programming and how to build simple applications.</p>
            <div class="card-actions justify-end mt-4">
              <button class="btn btn-primary btn-sm w-full">Watch Now</button>
            </div>
          </div>
        </div>

        <!-- Card 7 (Was 8) -->
        <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 group">
          <figure class="h-48 overflow-hidden">
            <img src="https://digital.excelacademy.my/wp-content/uploads/2023/03/Webinar-AI-Powered-Social-Media-Marketing-24-March-web.png"
              alt="Scalable APIs"
              class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
          </figure>
          <div class="card-body p-6">
            <h3 class="card-title text-lg font-bold">Scalable APIs</h3>
            <p class="text-sm text-base-content/70 mt-2 line-clamp-3">Learn how to design and build scalable APIs with modern frameworks and technologies.</p>
            <div class="card-actions justify-end mt-4">
              <button class="btn btn-primary btn-sm w-full">Watch Now</button>
            </div>
          </div>
        </div>

        <!-- Card 8 (Was 9) -->
        <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 group">
          <figure class="h-48 overflow-hidden">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT7mNCg2N-3ZqEzb-2S6TpNOCgvsrDNTJHGsGhHd2U_bzVdjl6_IqFJ8A1U7qYN_iddQCY&usqp=CAU"
              alt="Mastering React.js"
              class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
          </figure>
          <div class="card-body p-6">
            <h3 class="card-title text-lg font-bold">Mastering React.js</h3>
            <p class="text-sm text-base-content/70 mt-2 line-clamp-3">Gain a deep understanding of React.js and build modern, interactive web applications.</p>
            <div class="card-actions justify-end mt-4">
              <button class="btn btn-primary btn-sm w-full">Watch Now</button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>










  <?php
  // Fetch course counts per category
  $categoryCounts = [
    'Development' => 0,
    'IT and Software' => 0,
    'Design' => 0
  ];

  $countQuery = "SELECT category, COUNT(*) as total FROM Course WHERE status = 'active' GROUP BY category";
  $countStmt = $conn->prepare($countQuery);
  $countStmt->execute();
  $countResult = $countStmt->get_result();

  while ($row = $countResult->fetch_assoc()) {
    $cat = $row['category'];
    if (isset($categoryCounts[$cat])) {
      $categoryCounts[$cat] = $row['total'];
    } elseif ($cat == 'IT and Software') {
      // Handle the exact string match from DB
      $categoryCounts['IT and Software'] = $row['total'];
    }
  }
  ?>

  <!-- Career Goal Section -->
  <section class="py-20 px-4 bg-base-100">
    <div class="max-w-6xl mx-auto">
      <!-- Header -->
      <div class="text-center mb-16 space-y-4">
        <h2 class="text-4xl md:text-5xl font-extrabold text-base-content tracking-tight">
          Set Your Career Goal
        </h2>
        <p class="text-lg text-base-content/60 max-w-2xl mx-auto font-light">
          Select your path and start your learning journey with our expert-led courses.
        </p>
      </div>

      <!-- Cards Container -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 justify-items-center">

        <!-- Development Card -->
        <a href="#development" class="group w-full max-w-sm cursor-pointer">
          <div class="card bg-base-200 border border-base-300 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 h-full">
            <figure class="pt-10 pb-4 bg-gradient-to-t from-transparent to-base-300/30">
              <img src="Icons/developer.png" alt="Development" class="w-24 h-24 object-contain drop-shadow-md group-hover:scale-110 transition-transform duration-300" />
            </figure>
            <div class="card-body text-center items-center">
              <h3 class="card-title text-2xl font-bold group-hover:text-primary transition-colors">Development</h3>
              <p class="text-sm text-base-content/70">Master coding and build the future.</p>

              <div class="w-full h-px bg-base-300 my-4"></div>

              <div class="flex justify-center gap-6 text-sm font-medium text-base-content/80">
                <div class="flex flex-col items-center">
                  <span class="text-xl font-bold text-primary"><?= $categoryCounts['Development'] ?? 0; ?></span>
                  <span class="text-xs opacity-70 uppercase tracking-wider">Courses</span>
                </div>
                <div class="w-px h-10 bg-base-content/10"></div>
                <div class="flex flex-col items-center">
                  <span class="text-xl font-bold text-secondary">5</span>
                  <span class="text-xs opacity-70 uppercase tracking-wider">Workshops</span>
                </div>
              </div>
            </div>
          </div>
        </a>

        <!-- IT & Software Card -->
        <a href="#Software" class="group w-full max-w-sm cursor-pointer">
          <div class="card bg-base-200 border border-base-300 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 h-full">
            <figure class="pt-10 pb-4 bg-gradient-to-t from-transparent to-base-300/30">
              <img src="Icons/software-development.png" alt="IT & Software" class="w-24 h-24 object-contain drop-shadow-md group-hover:scale-110 transition-transform duration-300" />
            </figure>
            <div class="card-body text-center items-center">
              <h3 class="card-title text-2xl font-bold group-hover:text-primary transition-colors">IT & Software</h3>
              <p class="text-sm text-base-content/70">Scale your skills in tech &amp; operations.</p>

              <div class="w-full h-px bg-base-300 my-4"></div>

              <div class="flex justify-center gap-6 text-sm font-medium text-base-content/80">
                <div class="flex flex-col items-center">
                  <span class="text-xl font-bold text-primary"><?= $categoryCounts['IT and Software'] ?? 0; ?></span>
                  <span class="text-xs opacity-70 uppercase tracking-wider">Courses</span>
                </div>
                <div class="w-px h-10 bg-base-content/10"></div>
                <div class="flex flex-col items-center">
                  <span class="text-xl font-bold text-secondary">4</span>
                  <span class="text-xs opacity-70 uppercase tracking-wider">Workshops</span>
                </div>
              </div>
            </div>
          </div>
        </a>

        <!-- Design Card -->
        <a href="#Design" class="group w-full max-w-sm cursor-pointer">
          <div class="card bg-base-200 border border-base-300 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 h-full">
            <figure class="pt-10 pb-4 bg-gradient-to-t from-transparent to-base-300/30">
              <img src="Icons/design-thinking.png" alt="Design" class="w-24 h-24 object-contain drop-shadow-md group-hover:scale-110 transition-transform duration-300" />
            </figure>
            <div class="card-body text-center items-center">
              <h3 class="card-title text-2xl font-bold group-hover:text-primary transition-colors">Design</h3>
              <p class="text-sm text-base-content/70">Create stunning visuals and experiences.</p>

              <div class="w-full h-px bg-base-300 my-4"></div>

              <div class="flex justify-center gap-6 text-sm font-medium text-base-content/80">
                <div class="flex flex-col items-center">
                  <span class="text-xl font-bold text-primary"><?= $categoryCounts['Design'] ?? 0; ?></span>
                  <span class="text-xs opacity-70 uppercase tracking-wider">Courses</span>
                </div>
                <div class="w-px h-10 bg-base-content/10"></div>
                <div class="flex flex-col items-center">
                  <span class="text-xl font-bold text-secondary">3</span>
                  <span class="text-xs opacity-70 uppercase tracking-wider">Workshops</span>
                </div>
              </div>
            </div>
          </div>
        </a>

      </div>
    </div>
  </section>





  <!-- Live Course Contents title and description (No explicit color classes) -->
  <section class="py-24 px-6 relative overflow-hidden">

    <div class="max-w-7xl mx-auto relative z-10">

      <!-- Section Header -->
      <div class="text-center mb-20 space-y-4">
        <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight">
          Live Course Benefits
        </h2>
        <p class="text-lg max-w-2xl mx-auto font-light leading-relaxed opacity-70">
          Unlock your potential with our comprehensive learning ecosystem designed for your success.
        </p>
      </div>

      <!-- Live Course Contents Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

        <!-- Item 1 -->
        <div
          class="group relative backdrop-blur-sm p-8 rounded-2xl border shadow-lg
               hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 overflow-hidden">
          <div class="relative z-10 flex flex-col items-center text-center h-full">
            <div
              class="w-20 h-20 rounded-2xl flex items-center justify-center mb-6
                   group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500
                   ring-1 shadow-inner">
              <img class="w-10 h-10 object-contain drop-shadow-lg" src="Icons/video-marketing.png" alt="Industry Focused">
            </div>
            <h3 class="text-xl font-bold mb-3">
              Industry Focused
            </h3>
            <p class="text-sm leading-relaxed opacity-70">
              Join our guideline-based and industry-focused live courses to kickstart your professional career journey with confidence.
            </p>
          </div>
        </div>

        <!-- Item 2 -->
        <div
          class="group relative backdrop-blur-sm p-8 rounded-2xl border shadow-lg
               hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 overflow-hidden">
          <div class="relative z-10 flex flex-col items-center text-center h-full">
            <div
              class="w-20 h-20 rounded-2xl flex items-center justify-center mb-6
                   group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500
                   ring-1 shadow-inner">
              <img class="w-10 h-10 object-contain drop-shadow-lg" src="Icons/live.png" alt="Live Classes">
            </div>
            <h3 class="text-xl font-bold mb-3">
              Interactive Live Classes
            </h3>
            <p class="text-sm leading-relaxed opacity-70">
              Experience skill development with industry experts through live, conceptual, and dedicated support classes.
            </p>
          </div>
        </div>

        <!-- Item 3 -->
        <div
          class="group relative backdrop-blur-sm p-8 rounded-2xl border shadow-lg
               hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 overflow-hidden">
          <div class="relative z-10 flex flex-col items-center text-center h-full">
            <div
              class="w-20 h-20 rounded-2xl flex items-center justify-center mb-6
                   group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500
                   ring-1 shadow-inner">
              <img class="w-10 h-10 object-contain drop-shadow-lg" src="Icons/cubes.png" alt="Module Based">
            </div>
            <h3 class="text-xl font-bold mb-3">
              Module Based Plan
            </h3>
            <p class="text-sm leading-relaxed opacity-70">
              Stay distinctively on track with a structured module-based study plan featuring quizzes, assignments, and live tests.
            </p>
          </div>
        </div>

      </div>
    </div>
  </section>




  <!-- Explore & Assess Section (No explicit colors) -->
  <section class="lg:mt-10">
    <div class="max-w-6xl mx-auto px-4 py-12 lg:py-20">

      <!-- Optional header -->
      <div class="mb-8 lg:mb-12 text-center">
        <h3 class="text-2xl lg:text-3xl font-extrabold">
          Explore, Assess, and Earn Certificates
        </h3>
        <p class="mt-2 text-sm lg:text-base opacity-70">
          Build confidence with guided practice and skill-based assessments.
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- card-1 -->
        <div class="card lg:card-side w-full border shadow-lg
                  hover:shadow-2xl transition-all duration-300 overflow-hidden group">

          <figure class="lg:w-1/2">
            <img
              class="w-full h-56 lg:h-full object-cover
                   group-hover:scale-[1.02] transition-transform duration-300"
              src="https://images.stockcake.com/public/2/5/2/2528afc3-9429-470b-8a32-edea706aafc9_large/focused-computer-programmer-stockcake.jpg"
              alt="Programmer working on code"
              loading="lazy" />
          </figure>

          <div class="card-body lg:w-1/2">
            <span class="text-xs uppercase tracking-widest font-semibold opacity-70">
              Skill Pro
            </span>

            <h2 class="card-title text-xl lg:text-2xl leading-snug">
              Explore your coding knowledge
            </h2>

            <p class="text-sm opacity-70">
              Practice curated challenges, track your progress, and identify the next skills to master.
            </p>

            <div class="card-actions mt-4">
              <a class="btn gap-2 font-semibold">
                CHECK
                <img class="w-5 h-5" src="Icons/RIGHT-ARROW.png" alt="" aria-hidden="true" />
              </a>
            </div>
          </div>
        </div>

        <!-- card-2 -->
        <div class="card lg:card-side w-full border shadow-lg
                  hover:shadow-2xl transition-all duration-300 overflow-hidden group">

          <figure class="lg:w-1/2">
            <img
              class="w-full h-56 lg:h-full object-cover
                   group-hover:scale-[1.02] transition-transform duration-300"
              src="https://t3.ftcdn.net/jpg/08/71/02/34/360_F_871023459_qw2fo1BlgBk45aKC0N9Ll558qexg1nSm.jpg"
              alt="Skill assessment concept"
              loading="lazy" />
          </figure>

          <div class="card-body lg:w-1/2">
            <span class="text-xs uppercase tracking-widest font-semibold opacity-70">
              Skillable
            </span>

            <h2 class="card-title text-xl lg:text-2xl leading-snug">
              Assess skills and get a free certificate
            </h2>

            <p class="text-sm opacity-70">
              Validate your expertise with structured assessments and add verified certificates to your profile.
            </p>

            <div class="card-actions mt-4">
              <a class="btn gap-2 font-semibold">
                CHECK
                <img class="w-5 h-5" src="Icons/RIGHT-ARROW.png" alt="" aria-hidden="true" />
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>


  <!-- SkillPro for business Section  -->
  <section class="p-10 mt-9">
    <div
      class="max-w-6xl mx-auto
           flex flex-col md:flex-row
           rounded-2xl p-6 md:p-10
           border shadow-lg
           hover:shadow-2xl transition-all duration-300">
      <!-- Left Content -->
      <div class="md:w-1/2 p-2 md:p-4 flex flex-col justify-center">
        <div class="space-y-4">
          <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">
            SkillPro for business
          </h2>

          <p class="leading-relaxed opacity-80">
            Join our Corporate Skills Training Program, get training from the country's best
            industry experts and reinvent your team.
          </p>

          <div>
            <button class="btn font-semibold gap-2">
              SEE DETAILS
              <span aria-hidden="true">&rarr;</span>
            </button>
          </div>
        </div>

        <div class="mt-10">
          <h3 class="text-lg md:text-xl font-semibold">
            Some of our clients
          </h3>

          <div class="flex flex-wrap items-center gap-5 mt-4">
            <img src="Images/logo/ibm.png" alt="IBM" class="h-8 w-auto opacity-90">
            <img src="Images/logo/penn.png" alt="Penn" class="h-8 w-auto opacity-90">
            <img src="Images/logo/Machigan.png" alt="Michigan" class="h-8 w-auto opacity-90">
            <img src="Images/logo/google.png" alt="Google" class="h-8 w-auto opacity-90">
          </div>
        </div>
      </div>

      <!-- Right Content (Image) -->
      <div class="md:w-1/2 mt-8 md:mt-0 flex justify-center items-center">
        <img
          src="Images/eng.png"
          alt="Training Session"
          class="rounded-2xl w-full md:w-11/12 lg:w-5/6
               shadow-sm hover:shadow-md transition-shadow duration-300"
          loading="lazy">
      </div>
    </div>
  </section>


  <!-- Success rate section (with icons, no explicit bg/text colors) -->
  <section class="lg:mt-10 lg:p-10 flex justify-center">
    <div
      class="stats stats-vertical lg:stats-horizontal
           border rounded-2xl shadow-lg
           hover:shadow-2xl transition-shadow duration-300">
      <div class="stat">
        <div class="stat-value text-3xl md:text-4xl flex items-center gap-2">
          <i class="fa-solid fa-briefcase text-2xl" aria-hidden="true"></i>
          <span>7000+</span>
        </div>
        <div class="stat-title opacity-70">Job Placement</div>
      </div>

      <div class="stat">
        <div class="stat-value text-3xl md:text-4xl flex items-center gap-2">
          <i class="fa-solid fa-users text-2xl" aria-hidden="true"></i>
          <span>15,000+</span>
        </div>
        <div class="stat-title opacity-70">Learner</div>
      </div>

      <div class="stat">
        <div class="stat-value text-3xl md:text-4xl flex items-center gap-2">
          <i class="fa-solid fa-circle-check text-2xl" aria-hidden="true"></i>
          <span>90%</span>
        </div>
        <div class="stat-title opacity-70">Course Completion Rate</div>
      </div>

      <div class="stat">
        <div class="stat-value text-3xl md:text-4xl flex items-center gap-2">
          <i class="fa-solid fa-video text-2xl" aria-hidden="true"></i>
          <span>46</span>
        </div>
        <div class="stat-title opacity-70">Live Course</div>
      </div>
    </div>
  </section>





  <?php
  // Assuming you already have: $conn = mysqli_connect(...);

  // Reviews query based on your schema
  $sql = "
SELECT
    cr.review_id,
    cr.rating,
    cr.comment,
    cr.review_date,

    c.course_id,
    c.title AS course_title,
    c.category,
    c.difficulty,
    c.instructor_id,

    i.user_id AS instructor_user_id,

    u_stu.firstName AS student_firstName,
    u_stu.lastName  AS student_lastName,
    u_stu.profile_pic AS student_pic,

    u_ins.firstName AS instructor_firstName,
    u_ins.lastName  AS instructor_lastName,
    u_ins.profile_pic AS instructor_pic

FROM course_reviews cr
JOIN course c ON cr.course_id = c.course_id
LEFT JOIN instructor i ON c.instructor_id = i.instructor_id
LEFT JOIN user u_ins ON i.user_id = u_ins.user_id
JOIN user u_stu ON cr.user_id = u_stu.user_id
WHERE c.status = 'active'
ORDER BY cr.review_date DESC, cr.review_id DESC
";

  $result = mysqli_query($conn, $sql);

  // Your images folder is: C:\xampp\htdocs\cse299\Images
  // DB stores paths like "images/adil.jpg" (case-insensitive on Windows).
  function resolveProfilePic($path)
  {
    if (empty($path)) return "Images/default-avatar.png";

    $path = str_replace("\\", "/", $path);

    // Normalize "images/xxx" => "Images/xxx"
    if (preg_match('#^images/#i', $path)) {
      $path = "Images/" . preg_replace('#^images/#i', '', $path);
    }

    // If only filename stored, prefix
    if (!preg_match('#^(https?://|/|Images/)#i', $path)) {
      $path = "Images/" . ltrim($path, "/");
    }

    return $path;
  }

  function renderStars($rating)
  {
    $rating = max(0, min(5, (int)$rating));
    $html = "";
    for ($i = 1; $i <= 5; $i++) {
      $html .= ($i <= $rating)
        ? '<i class="fa-solid fa-star"></i>'
        : '<i class="fa-regular fa-star"></i>';
    }
    return $html;
  }
  ?>

  <!-- ===================== REVIEWS SECTION ===================== -->
  <section class="py-16 px-6">
    <div class="max-w-7xl mx-auto">

      <!-- Header -->
      <div class="flex flex-col items-center justify-center text-center gap-4 mb-12">
        <div class="max-w-2xl">
          <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">
            Student Reviews
          </h2>
          <p class="mt-2 opacity-70 leading-relaxed">
            See how learners rate specific courses and the instructors behind them.
          </p>
        </div>

        <div>
          <a href="allReviews.php" class="btn btn-outline btn-sm">
            View all reviews
          </a>
        </div>
      </div>


      <?php if ($result && mysqli_num_rows($result) > 0): ?>

        <!-- Review Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">

          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <?php
            $studentName = trim(($row['student_firstName'] ?? '') . ' ' . ($row['student_lastName'] ?? ''));
            $instructorName = trim(($row['instructor_firstName'] ?? '') . ' ' . ($row['instructor_lastName'] ?? ''));

            $studentPic = resolveProfilePic($row['student_pic'] ?? '');
            $instructorPic = resolveProfilePic($row['instructor_pic'] ?? '');

            $courseTitle = $row['course_title'] ?? 'Untitled Course';
            $rating = (int)($row['rating'] ?? 0);

            $instructorProfileId = (int)($row['instructor_id'] ?? 0);
            $courseId = (int)($row['course_id'] ?? 0);

            $category = $row['category'] ?? '';
            $difficulty = $row['difficulty'] ?? '';
            $reviewDate = $row['review_date'] ?? '';
            $comment = $row['comment'] ?? '';
            ?>

            <!-- Review Card -->
            <article
              class="group border rounded-2xl p-6
                   shadow-sm hover:shadow-lg
                   transition-all duration-300
                   hover:-translate-y-1">
              <!-- Top row: student + rating -->
              <div class="flex items-start gap-4">

                <!-- Student avatar -->
                <img
                  src="<?= htmlspecialchars($studentPic) ?>"
                  alt="<?= htmlspecialchars($studentName ?: 'Student') ?>"
                  class="w-12 h-12 rounded-full object-cover border"
                  loading="lazy" />

                <div class="flex-1">
                  <div class="flex items-start justify-between gap-3">
                    <div>
                      <div class="font-semibold leading-tight">
                        <?= htmlspecialchars($studentName ?: 'Student') ?>
                      </div>
                      <div class="text-xs opacity-60 mt-1">
                        <?= htmlspecialchars($reviewDate) ?>
                      </div>
                    </div>

                    <!-- Stars -->
                    <div class="flex items-center gap-2">
                      <div class="flex items-center gap-1 text-base"
                        aria-label="<?= $rating ?> out of 5 stars">
                        <?= renderStars($rating) ?>
                      </div>
                      <span class="text-xs opacity-70">
                        <?= $rating ?>/5
                      </span>
                    </div>
                  </div>

                  <!-- Course Title (prominent) -->
                  <div class="mt-4">
                    <a
                      href="courseDetails.php?id=<?= $courseId ?>"
                      class="text-lg font-semibold leading-snug hover:underline">
                      <?= htmlspecialchars($courseTitle) ?>
                    </a>

                    <div class="mt-2 flex flex-wrap gap-2">
                      <?php if ($category): ?>
                        <span class="badge badge-outline">
                          <i class="fa-solid fa-layer-group mr-1" aria-hidden="true"></i>
                          <?= htmlspecialchars($category) ?>
                        </span>
                      <?php endif; ?>

                      <?php if ($difficulty): ?>
                        <span class="badge badge-outline">
                          <i class="fa-solid fa-gauge-high mr-1" aria-hidden="true"></i>
                          <?= htmlspecialchars($difficulty) ?>
                        </span>
                      <?php endif; ?>
                    </div>
                  </div>

                  <!-- Comment -->
                  <div class="mt-4">
                    <?php if (!empty($comment)): ?>
                      <p class="text-sm leading-relaxed opacity-80">
                        <?= nl2br(htmlspecialchars($comment)) ?>
                      </p>
                    <?php else: ?>
                      <p class="text-sm opacity-60">
                        No written comment.
                      </p>
                    <?php endif; ?>
                  </div>

                  <!-- Instructor row -->
                  <div class="mt-6 pt-4 border-t flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                      <img
                        src="<?= htmlspecialchars($instructorPic) ?>"
                        alt="<?= htmlspecialchars($instructorName ?: 'Instructor') ?>"
                        class="w-8 h-8 rounded-full object-cover border"
                        loading="lazy" />
                      <div class="leading-tight">
                        <div class="text-xs opacity-60">Instructor</div>
                        <a
                          href="instructorProfile.php?id=<?= $instructorProfileId ?>"
                          class="text-sm font-medium hover:underline">
                          <?= htmlspecialchars($instructorName ?: 'Instructor') ?>
                        </a>
                      </div>
                    </div>

                    <a
                      href="courseDetails.php?id=<?= $courseId ?>"
                      class="btn btn-ghost btn-xs">
                      View course
                      <i class="fa-solid fa-arrow-right ml-1" aria-hidden="true"></i>
                    </a>
                  </div>

                </div>
              </div>
            </article>

          <?php endwhile; ?>

        </div>

      <?php else: ?>

        <!-- Empty State -->
        <div class="border rounded-2xl p-12 text-center shadow-sm">
          <div class="inline-flex items-center justify-center w-12 h-12 rounded-full border mb-4">
            <i class="fa-regular fa-comment-dots text-xl" aria-hidden="true"></i>
          </div>
          <h3 class="text-xl font-semibold">No reviews yet</h3>
          <p class="mt-2 opacity-70">
            When students submit ratings for courses, they will appear here.
          </p>
        </div>

      <?php endif; ?>

    </div>
  </section>


  <!-- Free Courses title and button section (no text/bg color classes) -->
  <section class="text-center my-14 px-4">
    <h2 class="text-4xl md:text-5xl font-extrabold mb-4 tracking-tight">
      Free Courses
    </h2>
    <a href="FreeCourses.php" class="inline-block">
      <button class="btn btn-outline">
        SEE ALL
      </button>
    </a>
  </section>

  <!-- Free Courses videos section (3 cards, no slider, no text/bg color classes) -->
  <section class="py-10 px-4">
    <div class="max-w-7xl mx-auto">

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

        <!-- Card 1 -->
        <div class="card border rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden">
          <figure class="px-4 pt-4">
            <video class="w-full rounded-xl" controls preload="metadata">
              <source src="https://docs.material-tailwind.com/demo.mp4" type="video/mp4" />
              Your browser does not support the video tag.
            </video>
          </figure>
          <div class="card-body">
            <div class="flex items-center gap-2 text-sm opacity-70">
              <i class="fa-solid fa-code" aria-hidden="true"></i>
              <span>Beginner Friendly</span>
            </div>
            <h3 class="card-title text-xl font-bold">
              Web Development
            </h3>
            <p class="text-sm leading-relaxed opacity-80">
              Learn modern web fundamentals with MERN, PHP, Laravel, and Django basics in one structured path.
            </p>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="card border rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden">
          <figure class="px-4 pt-4">
            <video class="w-full rounded-xl" controls preload="metadata">
              <source src="https://docs.material-tailwind.com/demo.mp4" type="video/mp4" />
              Your browser does not support the video tag.
            </video>
          </figure>
          <div class="card-body">
            <div class="flex items-center gap-2 text-sm opacity-70">
              <i class="fa-solid fa-database" aria-hidden="true"></i>
              <span>Hands-on Practice</span>
            </div>
            <h3 class="card-title text-xl font-bold">
              Backend Essentials
            </h3>
            <p class="text-sm leading-relaxed opacity-80">
              Understand APIs, databases, authentication, and deployment concepts with practical examples.
            </p>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="card border rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden">
          <figure class="px-4 pt-4">
            <video class="w-full rounded-xl" controls preload="metadata">
              <source src="https://docs.material-tailwind.com/demo.mp4" type="video/mp4" />
              Your browser does not support the video tag.
            </video>
          </figure>
          <div class="card-body">
            <div class="flex items-center gap-2 text-sm opacity-70">
              <i class="fa-solid fa-laptop-code" aria-hidden="true"></i>
              <span>Career Starter</span>
            </div>
            <h3 class="card-title text-xl font-bold">
              Frontend UI Basics
            </h3>
            <p class="text-sm leading-relaxed opacity-80">
              Build clean layouts, responsive components, and modern UI patterns with a beginner-first approach.
            </p>
          </div>
        </div>

      </div>
    </div>
  </section>





  <?php
  include('footer.php')


  ?>















  <script>
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

    document.addEventListener("DOMContentLoaded", () => {

      // 1) Lock preview videos (no one can play)
      const videos = document.querySelectorAll("video.preview-video");

      videos.forEach((v) => {
        // Ensure no controls on listing page
        v.controls = false;
        v.setAttribute("controlsList", "nodownload noplaybackrate noremoteplayback");

        // Always force pause if any play is triggered
        const hardLock = () => {
          try {
            v.pause();
            v.currentTime = 0;
          } catch (err) {}
        };

        v.addEventListener("play", hardLock);
        v.addEventListener("playing", hardLock);
        v.addEventListener("click", (e) => {
          e.preventDefault();
          hardLock();
        });
        v.addEventListener("contextmenu", (e) => e.preventDefault());

        // Initial lock
        hardLock();
      });

      // 2) On-scroll reveal for cards
      const cards = document.querySelectorAll(".course-card");
      const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add("in-view");
            obs.unobserve(entry.target);
          }
        });
      }, {
        threshold: 0.12
      });

      cards.forEach(card => observer.observe(card));
    });
  </script>








  <!-- THEME TOGGLE SCRIPT: checked = dark (moon), unchecked = light (sun) -->
  <script>
    const html = document.documentElement;
    const themeToggle = document.getElementById("theme-toggle");

    // Default: dark, knob on moon side
    html.setAttribute("data-theme", "dark");
    themeToggle.checked = true;

    themeToggle.addEventListener("change", function() {
      if (this.checked) {
        // dark mode → moon side
        html.setAttribute("data-theme", "dark");
      } else {
        // light mode → sun side
        html.setAttribute("data-theme", "light");
      }
    });
  </script>
</body>

</html>