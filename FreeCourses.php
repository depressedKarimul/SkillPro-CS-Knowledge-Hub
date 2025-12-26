<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SkillPro - Free Courses</title>

  <link rel="stylesheet" href="Styles/styles.css">
  <link rel="stylesheet" href="Styles/Nav.css" />
  <link rel="stylesheet" href="Styles/allCourses.css">
  <!-- Keeping original stylesheet just in case, but loaded last -->
  <link rel="stylesheet" href="Styles/FreeCourses.css">

  <!-- tailwind and daisy UI -->
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.13/dist/full.min.css" rel="stylesheet" type="text/css" />
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
              <li><a href="index.php">Homepage</a></li>
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
              <li><a href="index.php">Home</a></li>
              <li><a href="index.php#development">Development</a></li>
              <li><a href="index.php#Software"> IT & Software</a></li>
              <li><a href="index.php#Design"> Design</a></li>
              <li><a href="allinstructor.php">Instructors</a></li>
              <li><a href="FreeCourses.php" class="active">Free Courses</a></li>
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
            <li><a href="index.php#Software"> IT & Software</a></li>
            <li><a href="index.php#Design"> Design</a></li>
            <li><a href="allinstructor.php"> Instructors</a></li>
            <li><a href="FreeCourses.php" class="active">Free Courses</a></li>
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




  <main >
    <section class="max-w-6xl mx-auto px-4 py-8">
  <h1 class="text-4xl font-semibold text-center mb-8">
    Free Courses
  </h1>

  <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

    <!-- Course 1 -->
    <article class="rounded-xl overflow-hidden shadow-md">
      <iframe
        class="w-full h-[215px]"
        src="https://www.youtube.com/embed/_uQrJ0TkZlc?si=9wMBJz2r366MHh19"
        title="YouTube video player"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen
      ></iframe>
    </article>

    <!-- Course 2 -->
    <article class="rounded-xl overflow-hidden shadow-md">
      <iframe
        class="w-full h-[215px]"
        src="https://www.youtube.com/embed/ix9cRaBkVe0?si=BsZYi1ujYVE6wUMK"
        title="YouTube video player"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen
      ></iframe>
    </article>

    <!-- Course 3 -->
    <article class="rounded-xl overflow-hidden shadow-md">
      <iframe
        class="w-full h-[215px]"
        src="https://www.youtube.com/embed/eWRfhZUzrAc?si=MXpgNh98NdWW5yMh"
        title="YouTube video player"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen
      ></iframe>
    </article>

    <!-- Course 4 -->
    <article class="rounded-xl overflow-hidden shadow-md">
      <iframe
        class="w-full h-[215px]"
        src="https://www.youtube.com/embed/_uQrJ0TkZlc?si=9wMBJz2r366MHh19"
        title="YouTube video player"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen
      ></iframe>
    </article>

    <!-- Course 5 -->
    <article class="rounded-xl overflow-hidden shadow-md">
      <iframe
        class="w-full h-[215px]"
        src="https://www.youtube.com/embed/ix9cRaBkVe0?si=BsZYi1ujYVE6wUMK"
        title="YouTube video player"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen
      ></iframe>
    </article>

    <!-- Course 6 -->
    <article class="rounded-xl overflow-hidden shadow-md">
      <iframe
        class="w-full h-[215px]"
        src="https://www.youtube.com/embed/eWRfhZUzrAc?si=MXpgNh98NdWW5yMh"
        title="YouTube video player"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen
      ></iframe>
    </article>

    <!-- Course 7 -->
    <article class="rounded-xl overflow-hidden shadow-md">
      <iframe
        class="w-full h-[215px]"
        src="https://www.youtube.com/embed/_uQrJ0TkZlc?si=9wMBJz2r366MHh19"
        title="YouTube video player"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen
      ></iframe>
    </article>

    <!-- Course 8 -->
    <article class="rounded-xl overflow-hidden shadow-md">
      <iframe
        class="w-full h-[215px]"
        src="https://www.youtube.com/embed/ix9cRaBkVe0?si=BsZYi1ujYVE6wUMK"
        title="YouTube video player"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen
      ></iframe>
    </article>

    <!-- Course 9 -->
    <article class="rounded-xl overflow-hidden shadow-md">
      <iframe
        class="w-full h-[215px]"
        src="https://www.youtube.com/embed/eWRfhZUzrAc?si=MXpgNh98NdWW5yMh"
        title="YouTube video player"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen
      ></iframe>
    </article>

    <!-- Course 10 -->
    <article class="rounded-xl overflow-hidden shadow-md">
      <iframe
        class="w-full h-[215px]"
        src="https://www.youtube.com/embed/BI1o2H9z9fo?si=FFII7uWsv7bKH60p"
        title="YouTube video player"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen
      ></iframe>
    </article>

    <!-- Course 11 -->
    <article class="rounded-xl overflow-hidden shadow-md">
      <iframe
        class="w-full h-[215px]"
        src="https://www.youtube.com/embed/PkZNo7MFNFg?si=qCeci99bbsO1HbKT"
        title="YouTube video player"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen
      ></iframe>
    </article>

    <!-- Course 12 -->
    <article class="rounded-xl overflow-hidden shadow-md">
      <iframe
        class="w-full h-[215px]"
        src="https://www.youtube.com/embed/lfmg-EJ8gm4?si=YGq3WTjWD1IqsmJm"
        title="YouTube video player"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen
      ></iframe>
    </article>

  </div>
</section>

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