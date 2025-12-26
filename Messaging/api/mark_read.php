<?php
session_start();
require_once dirname(__DIR__, 2) . '/database.php';
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) { echo json_encode(["status"=>"error"]); exit(); }
$me = intval($_SESSION['user_id']);
$thread = isset($_POST['thread_id']) ? intval($_POST['thread_id']) : 0;
if (!$thread) { echo json_encode(["status"=>"error"]); exit(); }
$mark = $conn->prepare("UPDATE messages SET is_read = 1 WHERE thread_id = ? AND sender_id != ?");
$mark->bind_param("ii", $thread, $me);
$mark->execute();
echo json_encode(["status"=>"success"]);
