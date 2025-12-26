<?php
session_start();
include(__DIR__ . '/smtp/PHPMailerAutoload.php'); // ✅ Correct PHPMailer path

// ✅ Database connection
$conn = new mysqli("localhost", "root", "", "skillprodb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['email'])) {
    $email = trim($_POST['email']);

    // ✅ Check if email exists in database
    $stmt = $conn->prepare("SELECT email FROM User WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // ✅ Email exists, generate and send OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        $subject = "Email Verification";
        $emailbody = "Your 6 Digit OTP Code: $otp";

        if (smtp_mailer($email, $subject, $emailbody)) {
            echo "<script>alert('OTP has been sent to your email.'); window.location.href='verify_otp.php';</script>";
            exit();
        } else {
            echo "<script>alert('Failed to send OTP. Please try again later.');</script>";
        }
    } else {
        // ❌ Email not found
        echo "<script>alert('Email not found in our database.');</script>";
    }
    $stmt->close();
}

$conn->close();

// ✅ Email sending function
function smtp_mailer($to, $subject, $msg)
{
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 587;
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';

    // Set your Gmail credentials
    $mail->Username = "hassanshuvo17.11.98@gmail.com"; 
    $mail->Password = "yzsu ebfu ywts cjze";            
    $mail->SetFrom("hassanshuvo17.11.98@gmail.com");    

    $mail->Subject = $subject;
    $mail->Body = $msg;
    $mail->AddAddress($to);

    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    return $mail->Send();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>OTP Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: url('https://i.postimg.cc/Tw9nySpP/5578502.jpg') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>

<body class="flex items-center justify-center h-screen bg-white">
    <div
        class="bg-white bg-opacity-70 backdrop-blur-md p-8 rounded-2xl shadow-xl w-96 border border-gray-300 transform transition duration-500 hover:-translate-y-2 hover:shadow-2xl">
        <h2 class="text-black text-2xl font-semibold text-center mb-4">
            Enter your email to receive OTP
        </h2>
        <form method="post" class="flex flex-col space-y-4">
            <input type="email" name="email" placeholder="Enter Email" required
                class="px-4 py-2 rounded-lg bg-white bg-opacity-60 text-black placeholder-gray-600 border border-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-300 focus:outline-none transition duration-300" />
            <button type="submit"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-semibold transition duration-300 transform hover:scale-105">
                Send OTP
            </button>
        </form>
    </div>
</body>

</html>
