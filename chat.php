<?php

include 'db.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// إضافة رسالة جديدة مع PRG
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['msg'])) {
    $msg = trim($_POST['msg']);
    $uid = $_SESSION['user_id'];
    $stmt = $conn->prepare("INSERT INTO allmsgs (userid, msgs) VALUES (?, ?)");
    $stmt->bind_param("is", $uid, $msg);
    $stmt->execute();
    $stmt->close();
    header("Location: chat.php");
    exit;
}

// حذف الرسالة فقط إذا كانت للمستخدم الحالي
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $uid = $_SESSION['user_id'];
    $stmt = $conn->prepare("DELETE FROM allmsgs WHERE id=? AND userid=?");
    $stmt->bind_param("ii", $id, $uid);
    $stmt->execute();
    $stmt->close();
    header("Location: chat.php");
    exit;
}

// جلب الرسائل
$result = $conn->query("SELECT allmsgs.*, allusers.username 
    FROM allmsgs 
    JOIN allusers ON allmsgs.userid = allusers.id 
    ORDER BY allmsgs.created_at ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AnonnymChat - Chat Room</title>
<link rel="stylesheet" href="all.css">
<style>
@import url('https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;600;700&family=Orbitron:wght@400;500;600;700&display=swap');
.chat-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 20px;
    overflow-y: auto;
    max-height: calc(100vh - 160px);
    scroll-behavior: smooth; /* للتمرير السلس */
}

</style>
</head>
<body>

<!-- Background effects -->
<div class="bg-grid"></div>
<div class="bg-circles"></div>
<div class="bg-circles"></div>
<div class="character-element character-eye"></div>
<div class="character-element character-antenna"></div>

<!-- Header -->
<div class="header">
    <div class="header-left">
        <img src="https://public.youware.com/users-website-assets/prod/3d62d3ab-28b7-4564-b821-4ad27172d474/3c1a674b5cd74653806c1b81a50c48cc.jpg" alt="ANONN-BOT" class="bot-icon">
        <div class="header-title">
            <h1>AnonnymChat</h1>
            <p>Welcome, <span class="username">@<?php echo $_SESSION['username']; ?></span></p>
        </div>
    </div>
    <a href="logout.php" class="logout-link">Logout</a>
</div>

<!-- Chat container -->
<div class="chat-container">
<?php while ($row = $result->fetch_assoc()): ?>
    <div class="message <?php echo ($row['userid'] == $_SESSION['user_id']) ? 'my-message' : ''; ?>">
        <div class="message-header">
            <span class="message-sender">@<?php echo htmlspecialchars($row['username']); ?></span>
            <span class="message-time">(<?php echo $row['created_at']; ?>)</span>
        </div>
        <div class="message-content">
            <?php echo htmlspecialchars($row['msgs']); ?>
        </div>

        <?php if ($row['userid'] == $_SESSION['user_id']): ?>
        <div class="message-actions">
            <a class="delete-btn " href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this message?');">Delete</a>
        </div>
        <?php endif; ?>
    </div>
<?php endwhile; ?>
</div>

<!-- Input area -->
<div class="input-container">
    <form method="post" style="display: flex; width: 100%;">
        <input type="text" name="msg" placeholder="Write message..." class="chat-input" required>
        <button type="submit" class="send-btn">Send</button>
    </form>
</div>
<script>
    // التمرير إلى آخر رسالة تلقائيًا
    const chatBox = document.querySelector('.chat-container');
    chatBox.scrollTop = chatBox.scrollHeight;
    
</script>

</body>
</html>
