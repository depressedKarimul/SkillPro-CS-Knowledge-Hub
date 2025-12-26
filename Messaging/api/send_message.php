<?php
session_start();
require_once dirname(__DIR__, 2) . '/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status"=>"error","message"=>"Unauthorized"]);
    exit();
}
$sender = intval($_SESSION['user_id']);
$receiver = isset($_POST['receiver_id']) ? intval($_POST['receiver_id']) : 0;
$message_text = isset($_POST['message_text']) ? trim($_POST['message_text']) : '';

if (!$receiver && !$message_text && empty($_FILES['file']['name'])) {
    echo json_encode(["status"=>"error","message"=>"Nothing to send"]);
    exit();
}

// find or create thread
$thread_sql = "SELECT thread_id FROM message_threads WHERE (user1_id=? AND user2_id=?) OR (user1_id=? AND user2_id=?) LIMIT 1";
$stmt = $conn->prepare($thread_sql);
$stmt->bind_param("iiii", $sender, $receiver, $receiver, $sender);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $thread_id = $row['thread_id'];
} else {
    $create = $conn->prepare("INSERT INTO message_threads (user1_id, user2_id) VALUES (?, ?)");
    $create->bind_param("ii", $sender, $receiver);
    $create->execute();
    $thread_id = $create->insert_id;
}

// file handling
$file_path = null;
$file_type = null;
$file_size = null;
if (!empty($_FILES['file']['name'])) {
    $uploadDir = __DIR__ . '/../uploads/';
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
    $fname = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/','_', $_FILES['file']['name']);
    $target = $uploadDir . $fname;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
        $file_path = 'uploads/' . $fname;
        $file_type = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
        $file_size = intval($_FILES['file']['size']);
    }
}

// insert message
$sql = "INSERT INTO messages (thread_id, sender_id, message_text, file_path, file_type, file_size) VALUES (?,?,?,?,?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iisssi", $thread_id, $sender, $message_text, $file_path, $file_type, $file_size);
$stmt->execute();

echo json_encode(["status"=>"success"]);
