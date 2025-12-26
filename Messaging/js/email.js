document.addEventListener("DOMContentLoaded", () => {
  // ELEMENTS
  const convList = document.getElementById("conversations");
  const chatBox = document.getElementById("chatBox");
  const messageList = document.getElementById("messageList");
  const chatHeaderName = document.getElementById("chatHeaderName");
  const chatHeaderPic = document.getElementById("chatHeaderPic");
  const headerActions = document.getElementById("headerActions");
  const currentReceiverIdInput = document.getElementById("currentReceiverId");
  const currentUserId = document.getElementById("currentUserId").value;
  const messageInput = document.getElementById("messageInput");
  const sendBtn = document.getElementById("sendBtn");
  const fileInput = document.getElementById("fileInput");
  const loadOlderBtn = document.getElementById("loadOlderBtn");
  const emptyChat = document.getElementById("emptyChat");
  const deleteChatBtn = document.getElementById("deleteChat");
  const closeChatBtn = document.getElementById("closeChat");
  const newChatBtn = document.getElementById("newChatBtn");
  const newChatModal = document.getElementById("newChatModal");
  const closeNewChatBtn = document.getElementById("closeNewChat");
  const newChatSearch = document.getElementById("newChatSearch");
  const newChatResults = document.getElementById("newChatResults");

  const messageRowClass = "message-row";

  let polling = null;
  let currentReceiver = null;
  let currentThread = null;
  let oldestMessageId = null;
  let hasMoreMessages = false;
  let isLoadingOlder = false;
  let activeConversationEl = null;
  let conversationCache = Array.isArray(window.initialConversations)
    ? window.initialConversations
    : [];
  let pendingRefresh = null;

  /* ============================
       FIX #1 — conversation click
       ============================ */
  if (convList) {
    convList.addEventListener("click", (e) => {
      const el = e.target.closest(".conversation");
      if (!el) return;

      const id = el.dataset.userId;
      const name = el.dataset.username;
      const pic = el.dataset.profilePic;

      openChat(id, name, pic, el);
    });
  }

  function openChat(id, name, pic, el) {
    currentReceiver = id;
    currentReceiverIdInput.value = id;
    chatHeaderName.textContent = name;
    chatHeaderPic.src = pic;

    hideEmptyState();
    headerActions.classList.remove("hidden");

    if (activeConversationEl) {
      activeConversationEl.classList.remove("bg-blue-50");
    }
    if (el) {
      el.classList.add("bg-blue-50");
      activeConversationEl = el;
    }

    loadMessages(id, { scrollToBottom: true, replace: true });

    if (polling) clearInterval(polling);
    polling = setInterval(() => loadMessages(id, { scrollToBottom: true, replace: true }), 2000);
  }

  /* ============================
       LOAD MESSAGES
       ============================ */
  async function loadMessages(receiverId, { scrollToBottom = true, replace = true, beforeId = null } = {}) {
    if (!receiverId) return;

    const params = new URLSearchParams({ receiver_id: receiverId });
    if (beforeId) params.append("before_id", beforeId);
    try {
      const res = await fetch(`api/get_messages.php?${params.toString()}`);
      const data = await res.json();

      if (data.status !== "success") return;

      currentThread = data.thread_id;
      oldestMessageId = data.oldest_id;
      hasMoreMessages = data.has_more;
      toggleLoadOlder(hasMoreMessages);

      if (replace) {
        clearMessages();
      }

      const previousScrollHeight = chatBox.scrollHeight;
      const previousScrollTop = chatBox.scrollTop;

      data.messages.forEach((msg) => appendMessageToUI(msg, replace ? "append" : "prepend"));

      if (replace && data.messages.length === 0) {
        showEmptyState();
      } else {
        hideEmptyState();
      }

      if (replace) {
        if (scrollToBottom) scrollToLatest();
      } else {
        const addedHeight = chatBox.scrollHeight - previousScrollHeight;
        chatBox.scrollTop = previousScrollTop + addedHeight;
      }

      refreshConversations();
    } catch (err) {
      console.error("Load messages error", err);
    } finally {
      isLoadingOlder = false;
    }
  }

  /* ============================
       APPEND MESSAGE TO UI
       ============================ */
  function appendMessageToUI(msg, position = "append") {
    const isMe = String(msg.sender_id) === String(currentUserId);
    const wrap = document.createElement("div");
    wrap.className = `${messageRowClass} ${isMe ? "flex justify-end" : "flex items-start gap-3"}`;

    const bubble = document.createElement("div");
    bubble.className = "bubble max-w-[60%] p-3 rounded-2xl shadow";

    if (isMe) {
      bubble.classList.add("bg-blue-500", "text-white");
    } else {
      bubble.classList.add("bg-white", "border");
    }

    if (msg.message_text) {
      const p = document.createElement("p");
      p.innerHTML = escapeHtml(msg.message_text);
      bubble.appendChild(p);
    }

    if (msg.file_path) {
      const ext = (msg.file_type || "").toLowerCase();
      if (["jpg", "jpeg", "png", "gif", "webp"].includes(ext)) {
        const img = document.createElement("img");
        img.src = msg.file_path;
        img.className = "rounded-md max-h-48 mt-2";
        bubble.appendChild(img);
      } else {
        const a = document.createElement("a");
        a.href = msg.file_path;
        a.target = "_blank";
        a.textContent = "📄 Download file";
        a.className = "block mt-2 underline";
        bubble.appendChild(a);
      }
    }

    const time = document.createElement("div");
    time.className = "text-[11px] mt-2 opacity-70";
    time.textContent = msg.formatted_time || "";
    bubble.appendChild(time);

    if (!isMe) {
      const img = document.createElement("img");
      img.src = msg.sender_pic;
      img.className = "w-8 h-8 rounded-full";
      wrap.appendChild(img);
    }

    wrap.appendChild(bubble);

    if (position === "prepend") {
      messageList.insertBefore(wrap, messageList.firstChild);
    } else {
      messageList.appendChild(wrap);
    }
  }

  function scrollToLatest() {
    chatBox.scrollTop = chatBox.scrollHeight + 200;
  }

  function clearMessages() {
    messageList.querySelectorAll(`.${messageRowClass}`).forEach((node) => node.remove());
  }

  function hideEmptyState() {
    if (emptyChat) emptyChat.classList.add("hidden");
  }

  function showEmptyState() {
    if (!emptyChat) return;
    emptyChat.classList.remove("hidden");
    if (!messageList.contains(emptyChat)) {
      messageList.appendChild(emptyChat);
    }
  }

  function toggleLoadOlder(show) {
    if (!loadOlderBtn) return;
    loadOlderBtn.classList.toggle("hidden", !show);
  }

  /* ============================
       FIX #2 — SEND BUTTON NOT WORKING
       ============================ */
  sendBtn.addEventListener("click", sendMessage);

  messageInput.addEventListener("keypress", (e) => {
    if (e.key === "Enter") {
      e.preventDefault(); // FIX #3 – prevents page reload
      sendMessage();
    }
  });

  /* ============================
       SEND MESSAGE
       ============================ */
  async function sendMessage() {
    if (!currentReceiver) {
      alert("Select a conversation first.");
      return;
    }

    const text = messageInput.value.trim();
    const file = fileInput.files[0];

    if (!text && !file) return;

    const fd = new FormData();
    fd.append("receiver_id", currentReceiver);
    fd.append("message_text", text);
    if (file) fd.append("file", file);

    try {
      // Optimistic UI update (optional, but good for UX)
      // For now, we wait for server response to be safe

      const res = await fetch("api/send_message.php", {
        method: "POST",
        body: fd,
      });

      const data = await res.json();

      /* FIX #4 — prevent UI freeze if PHP returns malformed JSON */
      if (!data || data.status !== "success") {
        alert("Message not sent. Check PHP.");
        return;
      }

      messageInput.value = "";
      fileInput.value = "";

      loadMessages(currentReceiver, { scrollToBottom: true, replace: true });
    } catch (err) {
      console.error("Send error:", err);
    }
  }

  /* ============================
       REFRESH CONVERSATIONS
       ============================ */
  async function refreshConversations() {
    if (pendingRefresh) return pendingRefresh;

    pendingRefresh = (async () => {
      try {
        const res = await fetch("api/get_conversations.php");
        const data = await res.json();

        if (data.status !== "success") return [];

        conversationCache = data.conversations;
        convList.innerHTML = "";

        conversationCache.forEach((c) => {
          const div = document.createElement("div");
          div.className =
            "conversation p-4 hover:bg-gray-50 border-b flex items-center gap-3 cursor-pointer";
          div.dataset.userId = c.user_id;
          div.dataset.username = c.username;
          div.dataset.profilePic = c.profile_pic;

          div.innerHTML = `
                      <img src="${c.profile_pic}" class="w-12 h-12 rounded-full">
                      <div class="flex-1">
                          <div class="flex justify-between">
                              <div class="font-semibold">${escapeHtml(
            c.username
          )}</div>
                              <div class="text-xs text-gray-400">${c.last_time || ""
            }</div>
                          </div>
                          <div class="flex justify-between">
                              <div class="text-sm text-gray-600">${escapeHtml(
              c.last_message || ""
            )}</div>
                              ${c.unread_count > 0
              ? `<span class="bg-red-600 text-white text-xs px-2 py-0.5 rounded-full">${c.unread_count}</span>`
              : ""
            }
                          </div>
                      </div>
                  `;

          if (String(c.user_id) === String(currentReceiver)) {
            div.classList.add("bg-blue-50");
            activeConversationEl = div;
          }

          convList.appendChild(div);
        });

        return conversationCache;
      } catch (err) {
        console.error("Refresh conv error:", err);
        return [];
      } finally {
        pendingRefresh = null;
      }
    })();

    return pendingRefresh;
  }

  setInterval(() => refreshConversations(), 5000);
  refreshConversations();

  if (newChatBtn && newChatModal) {
    newChatBtn.addEventListener("click", async () => {
      if (!conversationCache.length) {
        await refreshConversations();
      }
      showNewChatModal();
    });
  }

  if (closeNewChatBtn) {
    closeNewChatBtn.addEventListener("click", hideNewChatModal);
  }

  if (newChatModal) {
    newChatModal.addEventListener("click", (e) => {
      if (e.target === newChatModal) hideNewChatModal();
    });
  }

  if (newChatSearch) {
    newChatSearch.addEventListener("input", () => {
      const term = newChatSearch.value.trim().toLowerCase();
      const filtered = !term
        ? conversationCache
        : conversationCache.filter((c) => {
          const haystack = `${c.username || ""} ${(c.email || "")}`.toLowerCase();
          return haystack.includes(term);
        });
      renderNewChatResults(filtered);
    });
  }

  closeChatBtn.addEventListener("click", resetChat);

  if (deleteChatBtn) {
    deleteChatBtn.addEventListener("click", async () => {
      if (!currentThread) return;
      const confirmed = confirm("Delete the entire chat history? This cannot be undone.");
      if (!confirmed) return;

      const fd = new FormData();
      fd.append("thread_id", currentThread);
      try {
        const res = await fetch("api/delete_thread.php", { method: "POST", body: fd });
        const data = await res.json();
        if (data.status !== "success") {
          alert(data.message || "Could not delete chat");
          return;
        }
        resetChat();
        refreshConversations();
      } catch (err) {
        console.error("Delete chat error", err);
      }
    });
  }

  if (loadOlderBtn) {
    loadOlderBtn.addEventListener("click", () => {
      if (!currentReceiver || !oldestMessageId || !hasMoreMessages || isLoadingOlder) return;
      isLoadingOlder = true;
      loadMessages(currentReceiver, { scrollToBottom: false, replace: false, beforeId: oldestMessageId });
    });
  }

  function resetChat() {
    currentReceiver = null;
    currentThread = null;
    oldestMessageId = null;
    hasMoreMessages = false;
    toggleLoadOlder(false);
    clearMessages();
    showEmptyState();
    headerActions.classList.add("hidden");
    chatHeaderName.textContent = "Select a conversation";
    chatHeaderPic.src = "https://i.pravatar.cc/150";
    currentReceiverIdInput.value = "";
    if (activeConversationEl) {
      activeConversationEl.classList.remove("bg-blue-50");
      activeConversationEl = null;
    }
    if (polling) clearInterval(polling);
  }

  function showNewChatModal() {
    if (!newChatModal) return;
    renderNewChatResults(conversationCache);
    if (newChatSearch) {
      newChatSearch.value = "";
    }
    newChatModal.classList.remove("hidden");
    newChatModal.classList.add("flex");
    if (newChatSearch) {
      setTimeout(() => newChatSearch.focus(), 50);
    }
  }

  function hideNewChatModal() {
    if (!newChatModal) return;
    newChatModal.classList.add("hidden");
    newChatModal.classList.remove("flex");
  }

  function renderNewChatResults(list) {
    if (!newChatResults) return;
    newChatResults.innerHTML = "";
    if (!list || !list.length) {
      newChatResults.innerHTML =
        '<div class="py-6 text-center text-gray-400">No users found</div>';
      return;
    }

    list.forEach((user) => {
      const item = document.createElement("div");
      item.className =
        "flex items-center gap-3 py-3 cursor-pointer px-2 hover:bg-gray-50";
      item.innerHTML = `
        <img src="${user.profile_pic}" class="w-10 h-10 rounded-full object-cover">
        <div class="flex-1">
          <div class="font-medium">${escapeHtml(user.username || "Unnamed User")}</div>
          <div class="text-xs text-gray-500">${escapeHtml(user.email || "")}</div>
        </div>
      `;
      item.addEventListener("click", () => {
        hideNewChatModal();
        openChat(user.user_id, user.username, user.profile_pic);
      });
      newChatResults.appendChild(item);
    });
  }

  document.getElementById("downloadChat").addEventListener("click", () => {
    if (!currentReceiver) return;

    let txt = "";
    document.querySelectorAll(`#${messageList.id} .bubble`).forEach((b) => {
      txt += b.innerText + "\n";
    });

    const blob = new Blob([txt], { type: "text/plain" });
    const a = document.createElement("a");
    a.href = URL.createObjectURL(blob);
    a.download = `chat_${currentReceiver}.txt`;
    a.click();
  });

  function escapeHtml(str) {
    if (!str) return "";
    return str
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }
});
