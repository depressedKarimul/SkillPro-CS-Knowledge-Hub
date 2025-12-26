<?php
// Include database configuration
include('database.php');
session_start();

$message = ""; // For success/error messages

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form inputs
    $course_id = $_POST['course_id'];
    $total_questions = $_POST['total_questions'];
    $passing_marks = $_POST['passing_marks'];

    // Check if course ID exists in the database
    $check_course_query = "SELECT * FROM Course WHERE course_id = ?";
    $stmt_check_course = $conn->prepare($check_course_query);
    $stmt_check_course->bind_param("i", $course_id);
    $stmt_check_course->execute();
    $course_result = $stmt_check_course->get_result();

    if ($course_result->num_rows == 0) {
        $message = "Course ID does not exist in the database.";
    } else {
        // Insert data into Quiz table
        $insert_quiz_query = "INSERT INTO Quiz (course_id, total_questions, passing_marks) VALUES (?, ?, ?)";
        $stmt_quiz = $conn->prepare($insert_quiz_query);
        $stmt_quiz->bind_param("iii", $course_id, $total_questions, $passing_marks);

        if ($stmt_quiz->execute()) {
            $quiz_id = $stmt_quiz->insert_id; // Get the ID of the newly inserted quiz

            // Insert questions into the Question table
            $questions = $_POST['questions']; // Expecting an array of questions from the form
            $errors = [];

            foreach ($questions as $question) {
                $question_text = $question['text'];
                $question_type = $question['type'];
                $answer = $question['answer'];

                // For multiple choice, get options
                $option1 = $option2 = $option3 = $option4 = null;
                if ($question_type === 'multiple_choice') {
                    $option1 = $question['option1'];
                    $option2 = $question['option2'];
                    $option3 = $question['option3'];
                    $option4 = $question['option4'];
                }

                $insert_question_query = "
                    INSERT INTO Question (quiz_id, question_text, question_type, option1, option2, option3, option4, answer) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ";
                $stmt_question = $conn->prepare($insert_question_query);
                $stmt_question->bind_param(
                    "isssssss",
                    $quiz_id,
                    $question_text,
                    $question_type,
                    $option1,
                    $option2,
                    $option3,
                    $option4,
                    $answer
                );

                if (!$stmt_question->execute()) {
                    $errors[] = "Error adding question: " . $stmt_question->error;
                }
            }

            if (empty($errors)) {
                $message = "Quiz and questions uploaded successfully!";
            } else {
                $message = "Quiz uploaded, but some questions failed: " . implode(", ", $errors);
            }
        } else {
            $message = "Error creating quiz: " . $stmt_quiz->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <?php include('head_content.php') ?>
</head>
<body class="min-h-screen bg-cover bg-center" 
      style="background-image: url('https://i.postimg.cc/C5mf7kSb/4847051.jpg');">

    <div class="min-h-screen flex items-center justify-center bg-black/20 backdrop-blur-sm">

        <div class="max-w-4xl w-full bg-white/70 shadow-xl rounded-2xl p-10 backdrop-blur-md">

            <h1 class="text-4xl font-bold text-center text-gray-800 mb-8">
                Upload Quiz
            </h1>

            <form method="POST" class="space-y-8" onsubmit="return validateForm()">
                
                <!-- Course ID -->
                <div>
                    <label class="block text-lg font-medium text-gray-800">Course ID</label>
                    <input 
                        id="course_id"
                        name="course_id"
                        class="w-full mt-2 px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-800 shadow-sm focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <!-- Total Questions -->
                <div>
                    <label class="block text-lg font-medium text-gray-800">Total Questions</label>
                    <input 
                        id="total_questions"
                        name="total_questions"
                        type="number"
                        min="1"
                        oninput="generateQuestionFields()"
                        class="w-full mt-2 px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-800 shadow-sm focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <!-- Passing Marks -->
                <div>
                    <label class="block text-lg font-medium text-gray-800">Passing Marks</label>
                    <input 
                        id="passing_marks"
                        name="passing_marks"
                        class="w-full mt-2 px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-800 shadow-sm focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <h2 class="text-2xl font-semibold text-gray-900">Questions</h2>
                <div id="questions-container" class="space-y-6"></div>

                <!-- Submit -->
                <button 
                    type="submit"
                    class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg shadow hover:bg-green-700 transition"
                >
                    Upload Quiz
                </button>

            </form>

            <!-- Message -->
            <p class="text-center text-green-600 font-semibold text-lg mt-6">
                <?php echo htmlspecialchars($message); ?>
            </p>

            <!-- Go to Home Button -->
            <?php if (!empty($message) && $message === "Quiz and questions uploaded successfully!"): ?>
                <div class="text-center mt-4">
                    <a href="instructor.php" 
                       class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold shadow hover:bg-blue-700 transition">
                       Go to Home
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>

<script>
function generateQuestionFields() {
    let total = document.getElementById("total_questions").value;
    const container = document.getElementById("questions-container");
    container.innerHTML = "";

    if (total < 1) return;

    for (let i = 0; i < total; i++) {
        container.innerHTML += `
            <div class="bg-white/60 p-6 rounded-xl border shadow-sm backdrop-blur-sm">

                <h3 class="font-semibold text-lg text-gray-800 mb-4">Question ${i + 1}</h3>

                <label class="block text-gray-800 font-medium">Question Text</label>
                <input 
                    type="text"
                    name="questions[${i}][text]"
                    class="w-full mt-2 mb-4 px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-blue-500"
                >

                <label class="block text-gray-800 font-medium">Question Type</label>
                <select 
                    name="questions[${i}][type]"
                    class="w-full mt-2 mb-4 px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-blue-500"
                    onchange="toggleOptions(this, ${i})"
                >
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="true_false">True/False</option>
                    <option value="short_answer">Short Answer</option>
                </select>

                <div id="options-container-${i}" class="space-y-2 mt-2">
                    <!-- Options for multiple choice will appear here -->
                    <label class="block text-gray-800 font-medium">Option 1</label>
                    <input type="text" name="questions[${i}][option1]" class="w-full px-4 py-2 border rounded-lg">
                    <label class="block text-gray-800 font-medium">Option 2</label>
                    <input type="text" name="questions[${i}][option2]" class="w-full px-4 py-2 border rounded-lg">
                    <label class="block text-gray-800 font-medium">Option 3</label>
                    <input type="text" name="questions[${i}][option3]" class="w-full px-4 py-2 border rounded-lg">
                    <label class="block text-gray-800 font-medium">Option 4</label>
                    <input type="text" name="questions[${i}][option4]" class="w-full px-4 py-2 border rounded-lg">
                </div>

                <label class="block text-gray-800 font-medium mt-4">Answer</label>
                <input 
                    type="text"
                    name="questions[${i}][answer]"
                    class="w-full mt-2 px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-blue-500"
                >
            </div>
        `;
    }
}

// Hide options if not multiple choice
function toggleOptions(select, index) {
    const optionsDiv = document.getElementById(`options-container-${index}`);
    if (select.value === 'multiple_choice') {
        optionsDiv.style.display = 'block';
    } else {
        optionsDiv.style.display = 'none';
    }
}
</script>

</body>
</html>
