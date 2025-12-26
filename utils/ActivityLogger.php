<?php
// utils/ActivityLogger.php

function logActivity($conn, $actionDescription, $targetDetails = "") {
    // 1. Define Emails
    $senderEmail = 'skillpro@gmail.com'; // Corrected from slillpro
    $receiverEmail = 'admin@skillpro.com';

    // 2. Get Receiver ID (Admin)
    $receiverId = null;
    $QUERY_USER = "SELECT user_id FROM user WHERE email = ?";
    $stmt = $conn->prepare($QUERY_USER);
    $stmt->bind_param("s", $receiverEmail);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $receiverId = $row['user_id'];
    }

    if (!$receiverId) {
        // Fallback to ID 1 if admin email not found
        $receiverId = 1;
    }

    // 3. Get Sender ID (System/Bot)
    $senderId = null;
    $stmt->bind_param("s", $senderEmail);
    $stmt->execute();
    $resSender = $stmt->get_result();
    if ($row = $resSender->fetch_assoc()) {
        $senderId = $row['user_id'];
    }

    // 4. Create Sender if not exists
    if (!$senderId) {
        // Create the system user
        $fName = "System";
        $lName = "Notification";
        $role = "admin"; // Use admin role to be safe
        $is_approved = 1;
        $pwd = password_hash("system_auto_generated_" . time(), PASSWORD_DEFAULT);
        
        // Insert
        // Columns: firstName, lastName, email, password, role, is_approved
        $insertUser = "INSERT INTO user (firstName, lastName, email, password, role, is_approved) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($insertUser);
        $stmtInsert->bind_param("sssssi", $fName, $lName, $senderEmail, $pwd, $role, $is_approved);
        if ($stmtInsert->execute()) {
            $senderId = $stmtInsert->insert_id;
        }
    }

    // Should have senderId now. If create failed, we might still fallback, but let's hope it works.
    if ($senderId && $receiverId && $senderId != $receiverId) {
        // 5. Ensure Thread Exists
        $threadId = null;
        $queryThread = "SELECT thread_id FROM message_threads WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)";
        $stmtThread = $conn->prepare($queryThread);
        $stmtThread->bind_param("iiii", $senderId, $receiverId, $receiverId, $senderId);
        $stmtThread->execute();
        $resThread = $stmtThread->get_result();
        
        if ($row = $resThread->fetch_assoc()) {
            $threadId = $row['thread_id'];
        } else {
            // Create new thread
            $insertThread = "INSERT INTO message_threads (user1_id, user2_id) VALUES (?, ?)";
            $stmtInsertThread = $conn->prepare($insertThread);
            $stmtInsertThread->bind_param("ii", $senderId, $receiverId);
            if ($stmtInsertThread->execute()) {
                $threadId = $stmtInsertThread->insert_id;
            }
        }

        if ($threadId) {
            // 6. Insert Message
            $fullMessage = $actionDescription;
            if (!empty($targetDetails)) {
                $fullMessage .= "\n\nDetails: " . $targetDetails;
            }

            $insertMsg = "INSERT INTO messages (thread_id, sender_id, message_text, is_read) VALUES (?, ?, ?, 0)";
            $stmtMsg = $conn->prepare($insertMsg);
            $stmtMsg->bind_param("iis", $threadId, $senderId, $fullMessage);
            $stmtMsg->execute();
        }
    }
}
?>
