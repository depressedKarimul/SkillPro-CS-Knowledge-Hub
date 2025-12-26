<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('head_content.php') ?>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Custom styles to ensure color consistency */
        .bg-base-100 {
            background-color: #1D232A !important;
            /* Dark background for navbar */
            color: #ffffff;
        }

        .btn-ghost {
            color: #ffffff;
        }

        .menu li>* {
            background-color: #283747 !important;
            color: #ffffff;
        }

        .menu li>*:hover {
            background-color: #37474F !important;
        }
    </style>

</head>




<body class="p-0">

    <header class="mb-5">
        <!-- Navigation -->
        <nav>
            <div class="navbar bg-base-100 shadow-lg px-8">
                <div class="navbar-start">
                    <div class="dropdown">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
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
                            class="menu menu-sm dropdown-content bg-[#283747] rounded-box z-[1] mt-3 w-52 p-2 shadow">
                            <li><a href="#students">All Students</a></li>
                            <li><a href="#instructors">All Instructors</a></li>
                            <li><a href="#courses">All Courses</a></li>
                            <li>
                                <a href="Messaging/sendEmail.php?user_id=<?= $user_id ?>"
                                    target="_blank">
                                    Email
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="navbar-center">
                    <a class="btn btn-ghost text-xl text-white">SkillPro Admin Panel</a>
                </div>
                <div class="navbar-end flex items-center space-x-2">

                    <!-- --- UPDATED SEARCH FORM --- -->
                    <form action="adminSearch.php" method="GET" class="flex items-center">
                        <input type="text" name="query" placeholder="Search User/Course Name"
                            class="input input-bordered w-full max-w-xs text-sm h-10 px-3 bg-[#37474F] text-white border-gray-600 focus:border-white rounded-l-lg" required>
                        <button type="submit" class="btn btn-ghost btn-circle bg-red-600 hover:bg-red-700 h-10 w-10 min-h-10 rounded-r-lg rounded-l-none">
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
                    </form>
                    <!-- --- END UPDATED SEARCH FORM --- -->

                    <a href="approve_request.php" class="btn btn-ghost btn-circle">
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
                    </a>
                </div>
            </div>
        </nav>
    </header>



    <main>

        <h2 id="students" class="text-center text-4xl text-white bg-[#283747] p-5 font-extrabold">All Students</h2>

        <div class="all-student mt-6 ">
            <?php
            include("database.php");

            // Fetch all students
            $sql = "SELECT 
                User.user_id, 
                User.firstName, 
                User.lastName, 
                User.email, 
                User.profile_pic, 
                User.bio
            FROM User
            WHERE User.role = 'student'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<div class="all-student flex flex-wrap justify-center gap-4">';

                while ($row = $result->fetch_assoc()) {
                    $student_user_id = $row['user_id']; // Store the user ID

                    // Fetch courses for the student
                    $course_sql = "SELECT Course.title 
                           FROM Enrollment
                           JOIN Course ON Enrollment.course_id = Course.course_id
                           WHERE Enrollment.user_id = ?";
                    $course_stmt = $conn->prepare($course_sql);
                    // Check if preparation was successful
                    if ($course_stmt === false) {
                        die('Prepare failed: ' . htmlspecialchars($conn->error));
                    }
                    $course_stmt->bind_param("i", $student_user_id);
                    $course_stmt->execute();
                    $courses_result = $course_stmt->get_result();
                    $courses = [];
                    while ($course = $courses_result->fetch_assoc()) {
                        $courses[] = $course['title'];
                    }
                    $course_stmt->close();

                    // Display the student card
                    echo '<div class="bg-[#283747] p-6 shadow-[0_4px_12px_-5px_rgba(0,0,0,0.4)] w-full max-w-sm rounded-2xl font-[sans-serif] overflow-hidden highlight">';
                    echo '<div class="flex flex-col items-center">';
                    echo '<div class="min-h-[110px]">';
                    echo '<img src="' . htmlspecialchars($row["profile_pic"] ?: "default-profile.png") . '" class="w-28 h-w-28 rounded-full" />';
                    echo '</div>';
                    echo '<div class="mt-4 text-center">';
                    echo '<p class="text-lg text-white font-bold">' . htmlspecialchars($row["firstName"] . " " . $row["lastName"]) . '</p>';
                    echo '<p class="text-sm text-white mt-1">' . htmlspecialchars($row["email"]) . '</p>';
                    echo '<p class="text-sm text-white mt-1">' . htmlspecialchars($row["bio"]) . '</p>';
                    if (!empty($courses)) {
                        echo '<p class="text-sm text-white mt-1">Courses:</p>';
                        echo '<ul class="text-sm text-white">';
                        foreach ($courses as $course) {
                            echo '<li>' . htmlspecialchars($course) . '</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '<p class="text-sm text-white mt-1">No courses enrolled</p>';
                    }
                    echo '</div>';

                    // --- START: DELETE BUTTON ADDITION ---
                    echo '<form method="POST" action="remove_student.php" onsubmit="return confirm(\'Are you sure you want to remove this student?\');" class="mt-4">';
                    echo '<input type="hidden" name="user_id" value="' . htmlspecialchars($student_user_id) . '">';
                    echo '<button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-300">';
                    echo 'Remove Student';
                    echo '</button>';
                    echo '</form>';
                    // --- END: DELETE BUTTON ADDITION ---

                    echo '</div>';
                    echo '</div>';
                }

                echo '</div>';
            } else {
                echo '<p class="text-center text-gray-500">No students found.</p>';
            }

            $conn->close();
            ?>
        </div>




        <section>
            <h2 id="instructors" class="mt-5 text-center text-4xl text-white bg-[#283747] p-5 font-extrabold">All Instructors</h2>

            <div class="all-instructor mt-6">

                <?php
                include("database.php");

                // Fetch all instructors
                $sql = "SELECT user_id, firstName, lastName, email, profile_pic, bio FROM User WHERE role = 'instructor'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo '<div class="all-instructor flex flex-wrap justify-center gap-6">';  // Adjusted gap between cards

                    while ($row = $result->fetch_assoc()) {
                        $user_id = $row['user_id'];

                        // Fetch uploaded courses for the instructor
                        $course_sql = "
            SELECT c.title 
            FROM Course c
            JOIN Instructor i ON c.instructor_id = i.instructor_id
            WHERE i.user_id = ?
        ";
                        $course_stmt = $conn->prepare($course_sql);
                        $course_stmt->bind_param("i", $user_id);
                        $course_stmt->execute();
                        $course_result = $course_stmt->get_result();
                        $courses = [];
                        while ($course = $course_result->fetch_assoc()) {
                            $courses[] = $course['title'];
                        }
                        $course_stmt->close();

                        // Display the instructor card
                        echo '<div class="bg-[#283747] p-6 shadow-xl w-full max-w-sm rounded-2xl font-sans overflow-hidden highlight">';
                        echo '<div class="flex flex-col items-center">';
                        echo '<div class="min-h-[110px]">';
                        echo '<img src="' . htmlspecialchars($row["profile_pic"] ?: "default-profile.png") . '" class="w-28 h-28 rounded-full border-4 border-white" />';
                        echo '</div>';
                        echo '<div class="mt-4 text-center">';
                        echo '<p class="text-lg text-white font-bold">' . htmlspecialchars($row["firstName"] . " " . $row["lastName"]) . '</p>';
                        echo '<p class="text-sm text-white mt-1">' . htmlspecialchars($row["email"]) . '</p>';
                        echo '<p class="text-sm text-white mt-1">' . htmlspecialchars($row["bio"]) . '</p>';
                        echo '</div>';

                        // Display the list of courses if available
                        if (!empty($courses)) {
                            echo '<div class="mt-4 text-white">';
                            echo '<p class="font-bold text-lg mb-2">Courses:</p>';
                            echo '<ul class="list-disc pl-5 space-y-2">';
                            foreach ($courses as $course) {
                                echo '<li class="text-sm">' . htmlspecialchars($course) . '</li>';
                            }
                            echo '</ul>';
                            echo '</div>';
                        } else {
                            echo '<p class="text-sm text-gray-400">No courses uploaded.</p>';
                        }

                        // Delete Instructor Button (Danger Button)
                        echo '<form method="POST" action="delete_instructor.php" onsubmit="return confirm(\'Are you sure you want to delete this instructor?\');">';
                        echo '<input type="hidden" name="user_id" value="' . $user_id . '">';
                        echo '<button type="submit" class="mt-4 bg-red-600 text-white hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-semibold py-2 px-4 rounded-md border border-red-600 transition duration-200 ease-in-out">';
                        echo 'Delete Instructor';
                        echo '</button>';
                        echo '</form>';

                        echo '</div>';  // Close the instructor card div
                        echo '</div>';  // Close the all-instructor div
                    }

                    echo '</div>';  // Close the flex wrapper
                } else {
                    echo '<p class="text-center text-gray-500">No Instructors Found.</p>';
                }

                $conn->close();
                ?>


            </div>

        </section>


        <!-- All Courses -->
        <h2 id="courses" class="mt-5 text-center text-4xl text-white bg-[#283747] p-5 font-extrabold">All Courses</h2>
        <section class="flex justify-center items-center min-h-screen">

            <div class="max-w-screen-xl w-full px-4 py-6">

                <div class="mt-6">
                    <?php
                    include('database.php');

                    // Query to get all courses with the forum post date
                    $query = "SELECT c.course_id, c.title, c.description, c.category, c.price, 
                             i.user_id AS instructor_id, u.firstName, u.lastName, u.profile_pic,
                             fp.post_date
                      FROM Course c
                      JOIN Instructor i ON c.instructor_id = i.instructor_id
                      JOIN User u ON i.user_id = u.user_id
                      LEFT JOIN Forum_Post fp ON c.course_id = fp.course_id
                      WHERE c.status = 'active'"; // Optional: filter by active courses
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Initialize a counter to track the courses in each row
                    $counter = 0;

                    // Start a new row after every 3 cards
                    echo '<div class="flex flex-wrap justify-center gap-6">';

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
                        <div class="relative flex flex-col my-6 text-white bg-[#283747] shadow-lg border border-slate-200 rounded-lg w-80 highlight">
                            <div class="relative h-56 m-2.5 overflow-hidden text-white rounded-md">
                                <video class="h-full w-full rounded-lg" controls>
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

                            <!-- Delete Course Button (Danger Button) -->
                            <form method="POST" action="delete_course.php" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
                                <button type="submit" class="mt-4 ml-5 mb-5 bg-red-600 text-white hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-semibold py-2 px-4 rounded-md border border-red-600 transition duration-200 ease-in-out">
                                    Delete Course
                                </button>
                            </form>

                        </div>

                    <?php
                        // Increment the counter
                        $counter++;

                        // Close the row div after every 3 cards
                        if ($counter % 3 == 0) {
                            echo '</div><div class="flex flex-wrap justify-center gap-6">'; // Close the current row and start a new one
                        }
                    } // End of while loop

                    // Close the last row div if there are fewer than 3 cards
                    if ($counter % 3 != 0) {
                        echo '</div>'; // Close the remaining flex row div
                    }
                    ?>

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
                            All Reviews
                        </h2>

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







    </main>


    <!-- chatbot -->


    <!-- Official chat widget styles -->
    <link href="https://cdn.jsdelivr.net/npm/@n8n/chat/style.css" rel="stylesheet" />

    <!-- Brand styling (CSS variables) -->
    <style>
        :root {
            /* Base */
            --chat--color-dark: #0b1220;
            --chat--color-white: #ffffff;

            /* “Light” tokens become dark surfaces in dark mode */
            --chat--color-light: #0b1220;
            /* main background */
            --chat--color-light-shade-50: #111827;
            /* panels / borders */
            --chat--color-light-shade-100: #1f2937;
            /* hover-ish surfaces */

            /* Brand (optional) */
            --chat--color-primary: #2563eb;
            --chat--color-primary-shade-50: #1d4ed8;
            --chat--color-primary-shade-100: #1e40af;

            /* Bubbles: keep both dark, text white */
            --chat--message--bot--background: #111827;
            --chat--message--bot--color: #f9fafb;

            --chat--message--user--background: #1f2937;
            --chat--message--user--color: #f9fafb;

            /* Toggle button */
            --chat--toggle--background: #2563eb;
            --chat--toggle--hover--background: #1d4ed8;
            --chat--toggle--active--background: #1e40af;
            --chat--toggle--color: #ffffff;

            /* Input */
            --chat--textarea--height: 52px;
            --chat--message--pre--background: rgba(255, 255, 255, 0.08);

            /* Shape/spacing (optional) */
            --chat--border-radius: 16px;
            --chat--spacing: 1rem;
        }

        /* Optional polish overrides (safe to remove) */
        .n8n-chat-widget-window,
        .n8n-chat-widget-body {
            background: #0b1220 !important;
            color: #f9fafb !important;
        }

        .n8n-chat-widget-header {
            background: #0b1220 !important;
            color: #f9fafb !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        }

        .n8n-chat-widget-message-input-field {
            background: #0f172a !important;
            color: #f9fafb !important;
            border: 1px solid rgba(255, 255, 255, 0.10) !important;
        }
        /* The actual input (textarea/input) */
#n8n-chat .n8n-chat-widget-message-input-field,
#n8n-chat textarea,
#n8n-chat input {
  background: #0b1220 !important;
  color: #ffffff !important;
  border: 1px solid rgba(255, 255, 255, 0.10) !important;
  box-shadow: none !important;
}

        .n8n-chat-widget-message-input-field::placeholder {
            color: rgba(249, 250, 251, 0.55) !important;
        }


        /* If you want the widget to sit above everything */
        #n8n-chat {
            position: relative;
            z-index: 9999;
        }
    </style>
    </head>

    <body>
        <!-- Target container (required) -->
        <div id="n8n-chat"></div>

        <script type="module">
            import {
                createChat
            } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/chat.bundle.es.js';

            createChat({
                webhookUrl: 'https://kari09.app.n8n.cloud/webhook/3acd9b82-a7a8-4901-b0c1-1d08822bc2dc/chat',
                webhookConfig: {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        // If using Basic Auth in n8n, uncomment:
                        // 'Authorization': 'Basic ' + btoa('USERNAME:PASSWORD'),
                    },
                },

                target: '#n8n-chat',
                mode: 'window', // 'window' | 'fullscreen' :contentReference[oaicite:5]{index=5}

                // UX copy
                showWelcomeScreen: false,
                defaultLanguage: 'en',
                initialMessages: [
                    'Hi there! 👋',
                    'How can I assist you today?'
                ],
                i18n: {
                    en: {
                        title: 'Support',
                        subtitle: "Start a chat. We're here to help you 24/7.",
                        footer: '',
                        getStarted: 'New Conversation',
                        inputPlaceholder: 'Type your question...',
                    },
                },

                // Optional (use if your n8n template expects these; otherwise you can remove)
                loadPreviousSession: true,
                metadata: {
                    pageUrl: location.href,
                },
            });
        </script>


    </body>

</html>