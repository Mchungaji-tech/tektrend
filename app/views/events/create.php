<?php $pageTitle = 'Schedule Event'; ?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Schedule New Event</h3>
            <p class="card-subtitle">Calendar entry for team sync, client demo, or deliverable deadline</p>
        </div>
        <a href="<?= eurl('/events') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> All Events</a>
    </div>

    <form method="POST" action="<?= eurl('/events') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Event Title</label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Apex Real Estate Demo Walkthrough" required>
            </div>
            <div class="form-group">
                <label>Event Type</label>
                <select name="type" class="form-control">
                    <option value="meeting">Meeting</option>
                    <option value="conference">Teleconference / Zoom</option>
                    <option value="deadline">Sprint Deadline</option>
                    <option value="holiday">Holiday / Office Closed</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Start Date & Time</label>
                <input type="datetime-local" name="start_datetime" class="form-control" value="<?= date('Y-m-d\TH:00', strtotime('+1 day')) ?>" required>
            </div>
            <div class="form-group">
                <label class="required">End Date & Time</label>
                <input type="datetime-local" name="end_datetime" class="form-control" value="<?= date('Y-m-d\TH:00', strtotime('+1 day +1 hour')) ?>" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Location / Room</label>
                <input type="text" name="location" class="form-control" placeholder="Virtual Office / Conference Room A">
            </div>
            <div class="form-group">
                <label>Department</label>
                <select name="department_id" class="form-control">
                    <option value="">All Departments / Global</option>
                    <?php foreach ($departments as $d): ?>
                        <option value="<?= $d['id'] ?>"><?= sanitize($d['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Event Description & Notes</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Meeting objectives, dial-in info, or milestone criteria..."></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/events') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-calendar-plus"></i> Save Event</button>
        </div>
    </form>
</div>
