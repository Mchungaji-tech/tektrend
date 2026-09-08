<?php $pageTitle = 'Edit Event'; ?>
<div class="card" style="max-width: 750px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Edit Event: <?= sanitize($event['title']) ?></h3>
            <p class="card-subtitle">Update schedule and details</p>
        </div>
        <a href="<?= eurl('/events/' . $event['id']) ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> View Event</a>
    </div>

    <form method="POST" action="<?= eurl('/events/' . $event['id']) ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div class="form-group">
            <label class="required">Event Title</label>
            <input type="text" name="title" class="form-control" value="<?= sanitize($event['title']) ?>" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3"><?= sanitize($event['description'] ?? '') ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Start Date & Time</label>
                <input type="datetime-local" name="start_datetime" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($event['start_datetime'] ?? ($event['start_date'] ?? 'now'))) ?>" required>
            </div>
            <div class="form-group">
                <label class="required">End Date & Time</label>
                <input type="datetime-local" name="end_datetime" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($event['end_datetime'] ?? ($event['end_date'] ?? '+1 hour'))) ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label>Location / Room</label>
            <input type="text" name="location" class="form-control" value="<?= sanitize($event['location'] ?? '') ?>">
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/events/' . $event['id']) ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        </div>
    </form>
</div>
