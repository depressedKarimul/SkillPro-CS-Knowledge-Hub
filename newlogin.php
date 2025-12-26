<?php
session_start();
require_once "database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = trim($_POST["password"] ?? "");

    if ($email && $password !== "") {
        // Fetch user details
        $sql = "SELECT * FROM User WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            // Database error
            $error = "Something went wrong. Please try again later.";
        } else {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['password'])) {

                    // If instructor, check approval
                    if (
                        $user['role'] === 'instructor' &&
                        (is_null($user['is_approved']) || (int)$user['is_approved'] === 0)
                    ) {
                        $error = "Your account is not yet approved.";
                    } else {
                        // Store user details in session
                        $_SESSION['user_id']      = $user['user_id'];
                        $_SESSION['role']         = $user['role'];
                        $_SESSION['profile_pic']  = $user['profile_pic'] ?: 'default.png';

                        // Redirect based on role
                        switch ($user['role']) {
                            case 'instructor':
                                header("Location: instructor.php");
                                exit;
                            case 'student':
                                header("Location: student.php");
                                exit;
                            case 'admin':
                                header("Location: admin.php");
                                exit;
                            default:
                                $error = "Invalid role specified.";
                        }
                    }
                } else {
                    $error = "Incorrect password.";
                }
            } else {
                $error = "No user found with this email.";
            }

            $stmt->close();
        }
    } else {
        $error = "Please enter your email and password.";
    }
}

$conn->close();

// Optional: simple error display if you open newLogin.php directly
if ($error !== "") {
    echo htmlspecialchars($error);
}
?>
