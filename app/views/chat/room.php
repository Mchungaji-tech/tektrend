<?php $pageTitle = 'Chat Room'; ?>
<div class="topbar"><div class="greeting"><h1><?= sanitize($room['name']) ?></h1><p>Chat room</p></div>
    <a href="/chat" style="color: #6b7280; background: rgba(107,114,128,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;"><i class="fas fa-arrow-left"></i> Back to Rooms</a>
</div>
<div class="chart-card reveal" style="height: 500px; display: flex; flex-direction: column;">
    <div class="header"><h3>Messages</h3><span class="period"><?= count($members) ?> members</span></div>
    <div id="messagesContainer" style="flex: 1; overflow-y: auto; padding: 1rem; margin-top: 1rem; background: rgba(0,0,0,0.2); border-radius: 12px;">
        <?php if (empty($messages)): ?>
            <p style="color: rgba(245,240,235,0.3); text-align: center;">No messages yet. Start the conversation!</p>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
                <div style="margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.3rem;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(184,148,60,0.06); display: flex; align-items: center; justify-content: center; color: #b8943c; font-size: 0.7rem;"><i class="fas fa-user"></i></div>
                        <span style="font-weight: 500; color: #f5f0eb;"><?= sanitize($msg['first_name'] . ' ' . $msg['last_name']) ?></span>
                        <span style="font-size: 0.7rem; color: rgba(245,240,235,0.3); margin-left: auto;"><?= formatDateTime($msg['created_at']) ?></span>
                    </div>
                    <div style="margin-left: 2.5rem; padding: 0.6rem 0.8rem; background: rgba(245,240,235,0.02); border-radius: 8px; color: rgba(245,240,235,0.5); font-size: 0.85rem;"><?= sanitize($msg['message']) ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <form method="POST" action="/chat/room/<?= $room['id'] ?>/send" style="margin-top: 1rem;">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div style="display: flex; gap: 0.5rem;">
            <input type="text" name="message" class="form-control" placeholder="Type a message..." required style="flex: 1;">
            <button type="submit" class="btn btn-primary" style="padding: 0.8rem 1.5rem;"><i class="fas fa-paper-plane"></i> Send</button>
        </div>
    </form>
</div>
<style>.chart-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .chart-card .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .chart-card .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } .chart-card .header .period { font-size: 0.7rem; color: rgba(245,240,235,0.15); background: rgba(245,240,235,0.02); padding: 0.2rem 1rem; border-radius: 40px; border: 1px solid rgba(245,240,235,0.02); } .form-control { width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; color: #f5f0eb; font-family: 'Inter', sans-serif; }</style>
<script>
document.getElementById('messagesContainer').scrollTop = document.getElementById('messagesContainer').scrollHeight;
</script>
