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

$sql = "
SELECT u.user_id,
       CONCAT(u.firstName, ' ', u.lastName) as username,
       u.email,
       u.profile_pic,
       (SELECT m.message_text
         FROM messages m
         JOIN message_threads t ON m.thread_id = t.thread_id
         WHERE ((t.user1_id = ? AND t.user2_id = u.user_id) OR (t.user1_id = u.user_id AND t.user2_id = ?))
         ORDER BY m.message_time DESC LIMIT 1) AS last_message,
       (SELECT DATE_FORMAT(m.message_time, '%h:%i %p • %d %b') 
         FROM messages m
         JOIN message_threads t ON m.thread_id = t.thread_id
         WHERE ((t.user1_id = ? AND t.user2_id = u.user_id) OR (t.user1_id = u.user_id AND t.user2_id = ?))
         ORDER BY m.message_time DESC LIMIT 1) AS last_time,
       (SELECT COUNT(*) FROM messages m
         JOIN message_threads t ON m.thread_id = t.thread_id
         WHERE ((t.user1_id = ? AND t.user2_id = u.user_id) OR (t.user1_id = u.user_id AND t.user2_id = ?))
           AND m.is_read = 0 AND m.sender_id != ?) AS unread_count
FROM user u
WHERE u.user_id != ?
ORDER BY last_time IS NULL, last_time DESC, username ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiiiiiii", $me, $me, $me, $me, $me, $me, $me, $me, $me);
$stmt->execute();
$res = $stmt->get_result();
$rows = $res->fetch_all(MYSQLI_ASSOC);
foreach ($rows as &$r) {
  $r['profile_pic'] = format_profile_pic_path($r['profile_pic']);
}
echo json_encode(["status" => "success", "conversations" => $rows]);
