<?php
session_start();
require_once dirname(__DIR__, 2) . '/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit();
}

$me = intval($_SESSION['user_id']);
$thread_id = isset($_POST['thread_id']) ? intval($_POST['thread_id']) : 0;

if (!$thread_id) {
    echo json_encode(["status" => "error", "message" => "Missing thread_id"]);
    exit();
}

// ensure the current user participates in this thread
$check = $conn->prepare("SELECT thread_id FROM message_threads WHERE thread_id = ? AND (user1_id = ? OR user2_id = ?) LIMIT 1");
$check->bind_param("iii", $thread_id, $me, $me);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Thread not found"]);
    exit();
}

$delete = $conn->prepare("DELETE FROM message_threads WHERE thread_id = ?");
$delete->bind_param("i", $thread_id);
$delete->execute();

echo json_encode(["status" => "success"]);

