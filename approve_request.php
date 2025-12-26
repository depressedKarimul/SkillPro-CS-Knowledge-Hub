<?php
// ======================================================
// approve_request.php
// Handles Instructor + Course Approval in ONE FILE
// ======================================================

session_start();

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include_once 'database.php';

$admin_id = $_SESSION['user_id'];

// ======================================================
// ========== 1. INSTRUCTOR APPROVAL LOGIC ===============
// ======================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['instructor_action'])) {

    $action = $_POST['instructor_action']; // approve or reject
    $user_id = intval($_POST['user_id']);
    $date = date('Y-m-d');

    // Insert log into Instructor_Approval table
    $status = ($action === 'approve') ? 'approved' : 'rejected';

    $stmt = $conn->prepare("
        INSERT INTO Instructor_Approval (admin_id, user_id, approval_status, approval_date)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("iiss", $admin_id, $user_id, $status, $date);
    $stmt->execute();
    $stmt->close();

    if ($status === "approved") {

        // 1. Insert into Instructor table
        $inst = $conn->prepare("INSERT INTO Instructor (user_id) VALUES (?)");
        $inst->bind_param("i", $user_id);
        $inst->execute();
        $inst->close();

        // 2. Update user table
        $user_up = $conn->prepare("UPDATE User SET is_approved = 1 WHERE user_id = ?");
        $user_up->bind_param("i", $user_id);
        $user_up->execute();
        $user_up->close();

        $_SESSION['message'] = "Instructor ID $user_id has been APPROVED!";
    } else {
        // Delete rejected instructor
        $del = $conn->prepare("DELETE FROM User WHERE user_id = ?");
        $del->bind_param("i", $user_id);
        $del->execute();
        $del->close();

        $_SESSION['message'] = "Instructor ID $user_id has been REJECTED!";
    }

    header("Location: approve_request.php");
    exit;
}

// ======================================================
// ========== 2. COURSE APPROVAL LOGIC ===================
// ======================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_action'])) {

    $course_id = intval($_POST['course_id']);
    $action = $_POST['course_action']; // approve or reject
    $date = date('Y-m-d');

    $status = ($action === 'approve') ? 'active' : 'rejected';

    // Update course
    $stmt = $conn->prepare("UPDATE Course SET status = ? WHERE course_id = ?");
    $stmt->bind_param("si", $status, $course_id);
    $stmt->execute();
    $stmt->close();

    // Insert approval record
    $insert = $conn->prepare("
        INSERT INTO Course_Approval (admin_id, course_id, approval_status, approval_date)
        VALUES (?, ?, ?, ?)
    ");
    $insert->bind_param("iiss", $admin_id, $course_id, $status, $date);
    $insert->execute();
    $insert->close();

    $_SESSION['message'] = "Course ID $course_id has been $status!";
    header("Location: approve_request.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('head_content.php'); ?>
</head>

<body class="bg-[#1a202c] text-white">

<!-- SUCCESS / ERROR MESSAGE -->
<div class="container mx-auto mt-5">
<?php if (isset($_SESSION['message'])): ?>
    <div class="bg-green-200 text-green-900 p-4 rounded mb-4 font-semibold">
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
    </div>
<?php endif; ?>
</div>

<!-- ====================================================== -->
<!-- ============ INSTRUCTOR APPROVAL SECTION ============== -->
<!-- ====================================================== -->

<h2 class="text-center text-4xl bg-[#283747] p-5 font-extrabold mt-5 rounded-md">
    Pending Instructor Requests
</h2>

<div class="container mx-auto mt-6">

<?php
$q = "SELECT * FROM User 
      WHERE role='instructor' 
      AND is_approved = 0 
      AND NOT EXISTS (SELECT 1 FROM Instructor WHERE Instructor.user_id = User.user_id)";
$res = $conn->query($q);

if ($res->num_rows === 0): ?>
    <p class="text-center text-lg bg-gray-700 p-4 rounded-md">No pending instructors.</p>
<?php else: ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

<?php while ($row = $res->fetch_assoc()): ?>
<div class="bg-[#283747] p-5 rounded-lg shadow-lg">
    <div class="flex space-x-4">
        <img src="<?= $row['profile_pic'] ?>" class="w-20 h-20 rounded-full border-2 border-blue-500">
        <div>
            <h3 class="text-xl font-bold"><?= $row['firstName'] . " " . $row['lastName'] ?></h3>
            <p>Email: <?= $row['email'] ?></p>
            <p>Bio: <?= $row['bio'] ?></p>
        </div>
    </div>

    <div class="mt-4 flex gap-3">
        <form method="POST">
            <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
            <button name="instructor_action" value="approve"
                class="bg-green-600 px-4 py-2 rounded-md">Approve</button>
        </form>

        <form method="POST">
            <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
            <button name="instructor_action" value="reject"
                class="bg-red-600 px-4 py-2 rounded-md">Reject</button>
        </form>
    </div>
</div>
<?php endwhile; ?>

</div>
<?php endif; ?>
</div>



<!-- ====================================================== -->
<!-- ================= COURSE APPROVAL ===================== -->
<!-- ====================================================== -->

<h2 class="mt-10 text-center text-4xl bg-[#283747] p-5 font-extrabold rounded-md">
    Pending Courses
</h2>

<div class="container mx-auto mt-10">

<?php
$course_q = "
SELECT c.course_id, c.title, c.description, c.category, c.price,
       i.instructor_id, u.user_id AS uid, u.firstName, u.lastName, u.profile_pic
FROM Course c
JOIN Instructor i ON c.instructor_id = i.instructor_id
JOIN User u ON i.user_id = u.user_id
WHERE c.status = 'pending'
";
$courses = $conn->query($course_q);

if ($courses->num_rows === 0): ?>

<p class="text-center text-lg bg-gray-700 p-4 rounded-md">
    No pending courses.
</p>

<?php else: ?>

<div class="flex flex-wrap justify-center gap-6">

<?php while ($c = $courses->fetch_assoc()): ?>

<?php
// Fetch first video
$v = $conn->prepare("SELECT file_url FROM Course_Content WHERE course_id=? AND type='video' LIMIT 1");
$v->bind_param("i", $c['course_id']);
$v->execute();
$v_res = $v->get_result();
$video = $v_res->fetch_assoc();
?>

<div class="bg-[#283747] border rounded-lg w-80 shadow-lg">

    <div class="h-56 m-3 overflow-hidden rounded-lg">
        <video class="w-full h-full" controls>
            <source src="<?= $video['file_url'] ?? '' ?>" type="video/mp4">
        </video>
    </div>

    <div class="p-4">
        <h3 class="text-2xl font-semibold"><?= $c['title'] ?></h3>
        <p class="mt-2"><?= $c['description'] ?></p>

        <h4 class="mt-2 font-bold">Category: <?= $c['category'] ?></h4>
        <h4 class="font-bold">Price: $<?= number_format($c['price'], 2) ?></h4>
    </div>

    <div class="flex items-center px-4 py-2">
        <img src="<?= $c['profile_pic'] ?>" class="w-10 h-10 rounded-full">
        <div class="ml-3">
            <p class="font-bold"><?= $c['firstName'] . " " . $c['lastName'] ?></p>
        </div>
    </div>

    <div class="flex justify-between p-4">
        <!-- Approve -->
        <form method="POST">
            <input type="hidden" name="course_id" value="<?= $c['course_id'] ?>">
            <button name="course_action" value="approve"
                class="bg-green-600 px-4 py-2 rounded-md">Approve</button>
        </form>

        <!-- Reject -->
        <form method="POST">
            <input type="hidden" name="course_id" value="<?= $c['course_id'] ?>">
            <button name="course_action" value="reject"
                class="bg-red-600 px-4 py-2 rounded-md">Reject</button>
        </form>
    </div>

</div>

<?php endwhile; ?>

</div>
<?php endif; ?>

</div>

</body>
</html>
