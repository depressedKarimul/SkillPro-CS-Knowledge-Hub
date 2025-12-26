<?php
// Include database configuration
include('database.php');
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get logged-in user ID

// Check if course_id is provided
if (!isset($_GET['course_id'])) {
    echo "No course selected.";
    exit();
}

$course_id = intval($_GET['course_id']);

// Fetch quiz details
$query_quiz = "SELECT * FROM Quiz WHERE course_id = ?";
$stmt_quiz = $conn->prepare($query_quiz);
$stmt_quiz->bind_param("i", $course_id);
$stmt_quiz->execute();
$result_quiz = $stmt_quiz->get_result();

$quiz = $result_quiz->fetch_assoc();
$quiz_exists = $quiz ? true : false;
$quiz_id = $quiz_exists ? $quiz['quiz_id'] : null;
$total_questions = $quiz_exists ? $quiz['total_questions'] : 0;

$results = [];
$all_correct = true;

// Handle form submission if quiz exists
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $quiz_exists) {
    $answers = $_POST['answers'] ?? [];

    $query_questions = "SELECT * FROM Question WHERE quiz_id = ?";
    $stmt_questions = $conn->prepare($query_questions);
    $stmt_questions->bind_param("i", $quiz_id);
    $stmt_questions->execute();
    $result_questions = $stmt_questions->get_result();

    while ($question = $result_questions->fetch_assoc()) {
        $question_id = $question['question_id'];
        $correct_answer = $question['answer'];
        $user_answer = $answers[$question_id] ?? null;

        // For multiple choice, match option text
        $is_correct = false;
        if ($question['question_type'] == 'multiple_choice') {
            $options = [
                'A' => $question['option1'],
                'B' => $question['option2'],
                'C' => $question['option3'],
                'D' => $question['option4'],
            ];
            $selected_text = $options[$user_answer] ?? '';
            $is_correct = (strcasecmp($selected_text, $correct_answer) == 0);
        } else {
            $is_correct = (strcasecmp($user_answer, $correct_answer) == 0);
        }

        $results[$question_id] = $is_correct;

        if (!$is_correct) {
            $all_correct = false;
        }
    }

    if ($all_correct) {
        $query_certificate = "INSERT INTO Certificate (user_id, course_id, issue_date) VALUES (?, ?, NOW())";
        $stmt_certificate = $conn->prepare($query_certificate);
        $stmt_certificate->bind_param("ii", $user_id, $course_id);
        $stmt_certificate->execute();

        $success_message = "Congratulations! You are ready for certification. You will receive your certificate soon.";
    }
}

// Fetch questions again for display if quiz exists
if ($quiz_exists) {
    $query_questions = "SELECT * FROM Question WHERE quiz_id = ?";
    $stmt_questions = $conn->prepare($query_questions);
    $stmt_questions->bind_param("i", $quiz_id);
    $stmt_questions->execute();
    $result_questions = $stmt_questions->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('head_content.php'); ?>
</head>

<body class="bg-cover bg-center min-h-screen p-8"
      style="background-image: url('https://i.postimg.cc/C5mf7kSb/4847051.jpg');">

    <!-- CARD with Opacity -->
    <div class="max-w-4xl mx-auto bg-white/80 backdrop-blur-md 
                p-10 rounded-2xl shadow-xl border">

        <h1 class="text-3xl font-extrabold text-center text-gray-800">
            Quiz for Course #<?php echo $course_id; ?>
        </h1>

        <?php if (!$quiz_exists) { ?>

            <p class="mt-4 text-lg text-center text-red-600 font-semibold">
                No quiz available for this course.
            </p>

        <?php } else { ?>

            <p class="mt-4 text-lg font-medium text-gray-700">
                Total Questions: <?php echo $total_questions; ?>
            </p>

            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') { ?>

                <h2 class="mt-6 text-2xl font-bold text-gray-800">Results:</h2>

                <?php
                $question_number = 1;
                foreach ($results as $question_id => $is_correct) {
                    $status = $is_correct ?
                        "<span class='text-green-600 font-semibold'>Correct ✅</span>" :
                        "<span class='text-red-600 font-semibold'>Wrong ❌</span>";

                    echo "<p class='mt-2 text-lg text-gray-700'>Q{$question_number}: {$status}</p>";
                    $question_number++;
                }
                ?>

                <?php if ($all_correct && isset($success_message)) { ?>
                    <p class="mt-6 text-green-600 text-xl font-semibold">
                        <?php echo $success_message; ?>
                    </p>
                <?php } ?>

                <!-- HOME BUTTON -->
                <a href="student.php" 
                   class="mt-8 block text-center bg-blue-600 text-white py-3 rounded-lg 
                          font-semibold hover:bg-blue-700 transition">
                    Go to Home
                </a>

            <?php } else { ?>

                <form action="quiz_page.php?course_id=<?php echo $course_id; ?>" 
                      method="POST" class="mt-6 space-y-8">

                    <?php
                    $question_number = 1;
                    while ($question = $result_questions->fetch_assoc()) {
                        $question_id = $question['question_id'];
                        $question_text = htmlspecialchars($question['question_text']);
                        $question_type = $question['question_type'];
                        $options = [
                            'A' => $question['option1'],
                            'B' => $question['option2'],
                            'C' => $question['option3'],
                            'D' => $question['option4'],
                        ];
                    ?>

                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">
                                Q<?php echo $question_number++; ?>. <?php echo $question_text; ?>
                            </h2>

                            <?php if ($question_type == 'multiple_choice') { ?>
                                <?php foreach ($options as $key => $text) { 
                                    if ($text != null) { ?>
                                        <label class="block mt-2 text-gray-700">
                                            <input type="radio" name="answers[<?php echo $question_id; ?>]" value="<?php echo $key; ?>" required> <?php echo htmlspecialchars($text); ?>
                                        </label>
                                <?php } } ?>

                            <?php } elseif ($question_type == 'true_false') { ?>
                                <label class="block mt-2 text-gray-700">
                                    <input type="radio" name="answers[<?php echo $question_id; ?>]" value="true" required> True
                                </label>
                                <label class="block text-gray-700">
                                    <input type="radio" name="answers[<?php echo $question_id; ?>]" value="false"> False
                                </label>

                            <?php } elseif ($question_type == 'short_answer') { ?>
                                <input type="text"
                                       name="answers[<?php echo $question_id; ?>]"
                                       class="w-full mt-2 px-4 py-2 border border-gray-300 rounded-lg 
                                              bg-white/90 focus:bg-white focus:outline-none 
                                              focus:ring-2 focus:ring-blue-500"
                                       required>
                            <?php } ?>

                        </div>

                    <?php } ?>

                    <button type="submit"
                            class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg 
                                   hover:bg-green-700 transition">
                        Submit Quiz
                    </button>

                </form>

            <?php } ?>
        <?php } ?>

    </div>

</body>
</html>
