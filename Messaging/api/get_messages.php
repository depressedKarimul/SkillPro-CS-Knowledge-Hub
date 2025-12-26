<?php
session_start();
require_once dirname(__DIR__, 2) . '/database.php';
require_once dirname(__DIR__) . '/helpers/media.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit();
}

$me = intval($_SESSION['user_id']);
$receiver = isset($_GET['receiver_id']) ? intval($_GET['receiver_id']) : 0;
if (!$receiver) {
    echo json_encode(["status" => "error", "message" => "No receiver"]);
    exit();
}

$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 50;
if ($limit < 10) $limit = 10;
if ($limit > 100) $limit = 100;
$beforeId = isset($_GET['before_id']) ? intval($_GET['before_id']) : 0;

// find or create thread
$thread_sql = "SELECT thread_id FROM message_threads WHERE (user1_id=? AND user2_id=?) OR (user1_id=? AND user2_id=?) LIMIT 1";
$stmt = $conn->prepare($thread_sql);
$stmt->bind_param("iiii", $me, $receiver, $receiver, $me);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $thread = $res->fetch_assoc();
    $thread_id = $thread['thread_id'];
} else {
    $create = $conn->prepare("INSERT INTO message_threads (user1_id, user2_id) VALUES (?, ?)");
    $create->bind_param("ii", $me, $receiver);
    $create->execute();
    $thread_id = $create->insert_id;
}

// fetch messages with optional pagination (older than before_id)
$msg_sql = "
SELECT m.*, u.profile_pic AS sender_pic,
       DATE_FORMAT(m.message_time, '%h:%i %p • %d %b') AS formatted_time
FROM messages m
JOIN user u ON m.sender_id = u.user_id
WHERE m.thread_id = ?";
$types = "i";
$params = [$thread_id];

if ($beforeId > 0) {
    $msg_sql .= " AND m.message_id < ?";
    $types .= "i";
    $params[] = $beforeId;
}

$msg_sql .= " ORDER BY m.message_id DESC LIMIT ?";
$types .= "i";
$params[] = $limit;

$stmt = $conn->prepare($msg_sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$messages_desc = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$has_more = count($messages_desc) === $limit;
$messages = array_reverse($messages_desc);
$oldest_id = $messages ? $messages[0]['message_id'] : null;
$newest_id = $messages ? end($messages)['message_id'] : null;

// mark messages from other as read
$mark = $conn->prepare("UPDATE messages m JOIN message_threads t ON m.thread_id=t.thread_id
    SET m.is_read = 1 WHERE m.thread_id = ? AND m.sender_id != ?");
$mark->bind_param("ii", $thread_id, $me);
$mark->execute();

foreach ($messages as &$m) {
    $m['sender_pic'] = format_profile_pic_path($m['sender_pic']);
}

echo json_encode([
    "status" => "success",
    "messages" => $messages,
    "thread_id" => $thread_id,
    "oldest_id" => $oldest_id,
    "newest_id" => $newest_id,
    "has_more" => $has_more
]);
