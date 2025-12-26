<?php
session_start();
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/helpers/media.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}

$me = intval($_SESSION['user_id']);

// Helper: get profile pic URL
function profile_url($pic)
{
  return format_profile_pic_path($pic);
}

// Fetch users + last message + unread count
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
       (SELECT COUNT(*) 
         FROM messages m
         JOIN message_threads t ON m.thread_id = t.thread_id
         WHERE ((t.user1_id = ? AND t.user2_id = u.user_id) OR (t.user1_id = u.user_id AND t.user2_id = ?))
           AND m.is_read = 0 AND m.sender_id != ?) AS unread_count
FROM user u
WHERE u.user_id != ?
ORDER BY last_time IS NULL, last_time DESC, username ASC
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
  "iiiiiiii",
  $me,
  $me,
  $me,
  $me,
  $me,
  $me,
  $me,
  $me
);

$stmt->execute();
$res = $stmt->get_result();
$users = $res->fetch_all(MYSQLI_ASSOC);

// Process profile pics for JS
foreach ($users as &$u) {
  $u['profile_pic'] = profile_url($u['profile_pic']);
}
unset($u);
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>SkillPro Messenger</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- FIXED SCROLL CSS -->
  <style>
    #chatBox {
      overflow-y: auto !important;
      height: calc(100vh - 180px);
      padding-bottom: 10px;
      scrollbar-width: thin;
    }

    #conversations {
      overflow-y: auto !important;
      height: calc(100vh - 140px);
    }

    #inputArea {
      position: sticky;
      bottom: 0;
      background: white;
      z-index: 50;
    }

    #messageInput {
      max-height: 120px;
      overflow-y: auto;
    }

    .bubble {
      transition: transform .06s;
    }

    .bubble:active {
      transform: scale(.995);
    }
  </style>
</head>

<body class="bg-gray-100 h-screen overflow-hidden flex items-center justify-center">
  <div class="w-full max-w-6xl h-[95vh] shadow rounded-lg overflow-hidden bg-white grid grid-cols-1 md:grid-cols-3">

    <!-- LEFT PANEL -->
    <div class="col-span-1 border-r bg-white">
      <div class="p-4 flex items-center justify-between border-b">
        <div class="flex items-center gap-3">
          <img src="<?php echo htmlspecialchars(profile_url($_SESSION['profile_pic'] ?? null)); ?>" class="w-12 h-12 rounded-full">
          <div>
            <div class="font-semibold">You</div>
            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></div>
          </div>
        </div>
        <button id="newChatBtn" class="px-3 py-1 bg-blue-500 text-white rounded">New</button>
      </div>

      <div id="conversations">
        <?php foreach ($users as $u): ?>
          <?php
          $pic = profile_url($u['profile_pic']);
          $preview = $u['last_message'] ? htmlspecialchars(substr($u['last_message'], 0, 80)) : 'Say hi!';
          $unread = intval($u['unread_count']);
          $time = $u['last_time'] ?? '';
          ?>
          <div class="conversation p-4 hover:bg-gray-50 border-b flex items-center gap-3 cursor-pointer"
            data-user-id="<?php echo $u['user_id']; ?>"
            data-username="<?php echo htmlspecialchars($u['username']); ?>"
            data-email="<?php echo htmlspecialchars($u['email']); ?>"
            data-profile-pic="<?php echo htmlspecialchars($pic); ?>">

            <img src="<?php echo $pic; ?>" class="w-12 h-12 rounded-full object-cover">

            <div class="flex-1">
              <div class="flex justify-between items-center">
                <div class="font-semibold"><?php echo htmlspecialchars($u['username']); ?></div>
                <div class="text-xs text-gray-400"><?php echo $time; ?></div>
              </div>

              <div class="flex justify-between items-center">
                <div class="text-sm text-gray-600"><?php echo $preview; ?></div>
                <?php if ($unread > 0): ?>
                  <div class="ml-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                    <?php echo $unread; ?>
                  </div>
                <?php endif; ?>
              </div>

            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- RIGHT CHAT PANEL -->
    <div class="col-span-2 flex flex-col">
      <div id="chatHeader" class="p-4 border-b flex items-center gap-3">
        <img id="chatHeaderPic" src="https://i.pravatar.cc/150" class="w-12 h-12 rounded-full">
        <div class="flex-1">
          <div id="chatHeaderName" class="font-semibold text-lg">Select a conversation</div>
          <div id="chatHeaderStatus" class="text-sm text-green-500 hidden">Online</div>
        </div>
        <div id="headerActions" class="hidden flex items-center gap-2">
          <button id="downloadChat" class="px-3 py-1 border rounded">Export</button>
          <button id="deleteChat" class="px-3 py-1 border rounded text-red-600 border-red-200">Delete</button>
          <button id="closeChat" class="px-3 py-1 border rounded">Close</button>
        </div>
      </div>

      <div id="chatBox" class="p-6 bg-[#f7f9fb]">
        <button id="loadOlderBtn" class="hidden mx-auto mb-4 text-sm text-blue-600 hover:underline">
          Load older messages
        </button>
        <div id="messageList" class="space-y-4">
          <div id="emptyChat" class="text-center text-gray-400 mt-8">
            Select a user from left to start chatting — looks like WhatsApp :)
          </div>
        </div>
      </div>

      <!-- FIXED BOTTOM INPUT -->
      <div id="inputArea" class="p-4 border-t bg-white flex items-center gap-3">
        <label class="cursor-pointer">
          <input type="file" id="fileInput" class="hidden">
          <div class="p-2 bg-gray-100 rounded-full">📎</div>
        </label>

        <input id="messageInput" type="text" placeholder="Type a message..."
          class="flex-1 border rounded-full px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">

        <button id="sendBtn" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600">
          Send
        </button>
      </div>
    </div>

  </div>

  <!-- NEW CHAT MODAL -->
  <div id="newChatModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 overflow-hidden flex flex-col max-h-[80vh]">
      <div class="p-4 border-b flex items-center justify-between">
        <h3 class="font-semibold text-lg">New Conversation</h3>
        <button id="closeNewChat" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
      </div>
      <div class="p-4 border-b">
        <input type="text" id="newChatSearch" placeholder="Search users..." class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      <div id="newChatResults" class="overflow-y-auto p-2 flex-1">
        <!-- Results injected by JS -->
      </div>
    </div>
  </div>

  <input type="hidden" id="currentReceiverId" value="">
  <input type="hidden" id="currentUserId" value="<?php echo $me; ?>">

  <script>
    window.initialConversations = <?php echo json_encode($users, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
  </script>
  <script src="js/email.js"></script>
</body>

</html>