<?php $pageTitle = 'Chat: ' . sanitize($room['name']); ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-hashtag" style="color: var(--primary); margin-right: 0.35rem;"></i> <?= sanitize($room['name']) ?>
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Virtual Office Room · <?= count($members ?? []) ?> active participants</p>
        </div>
        <a href="<?= eurl('/chat') ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> All Channels
        </a>
    </div>
</div>

<div class="card" style="height: 540px; display: flex; flex-direction: column;">
    <div id="messagesContainer" style="flex: 1; overflow-y: auto; padding: 1.25rem; background: var(--bg-card-subtle); border-radius: var(--radius-md); border: 1px solid var(--border); display: flex; flex-direction: column; gap: 1rem;">
        <?php if (empty($messages)): ?>
            <p style="color: var(--text-muted); text-align: center; margin: auto;">No messages in this channel yet. Start the conversation!</p>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
                <div>
                    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.25rem;">
                        <div style="width: 28px; height: 28px; border-radius: 50%; background: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 0.75rem; font-weight: 700;">
                            <?= strtoupper(substr($msg['first_name'], 0, 1)) ?>
                        </div>
                        <span style="font-weight: 700; color: var(--text-main); font-size: 0.88rem;"><?= sanitize($msg['first_name'] . ' ' . $msg['last_name']) ?></span>
                        <span style="font-size: 0.72rem; color: var(--text-muted); margin-left: auto;"><?= formatDateTime($msg['created_at']) ?></span>
                    </div>
                    <div style="margin-left: 2.25rem; padding: 0.65rem 0.95rem; background: var(--bg-card); border-radius: var(--radius-sm); color: var(--text-main); font-size: 0.9rem; border: 1px solid var(--border); width: fit-content; max-width: 80%;">
                        <?= nl2br(sanitize($msg['message'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <form method="POST" action="<?= eurl('/chat/room/' . $room['id'] . '/send') ?>" style="margin-top: 1rem;">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div style="display: flex; gap: 0.5rem;">
            <input type="text" name="message" class="form-control" placeholder="Type your message into #<?= sanitize($room['name']) ?>..." required style="flex: 1;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send</button>
        </div>
    </form>
</div>

<script>
    const el = document.getElementById('messagesContainer');
    if (el) el.scrollTop = el.scrollHeight;
</script>
